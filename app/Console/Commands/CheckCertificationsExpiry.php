<?php

namespace App\Console\Commands;

use App\Models\Certification;
use App\Models\Lead;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckCertificationsExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certifications:check-expiry {--days=30 : The number of days before expiry to flag}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks for expiring certifications and creates renewal leads for expired ones.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $dateThreshold = Carbon::now()->addDays($days);
        $today = Carbon::now();

        $this->info("Checking certifications expiring before: {$dateThreshold->toDateString()}");

        // 1. Flag expiring soon
        $expiringCount = Certification::where('status', 'active')
            ->whereDate('expiry_date', '<=', $dateThreshold)
            ->whereDate('expiry_date', '>=', $today)
            ->update(['status' => 'expiring_soon']);
            
        $this->info("Flagged {$expiringCount} certifications as expiring soon.");

        // 2. Process expired certs and create renewal leads
        $expiredCerts = Certification::with('client.lead')
            ->whereIn('status', ['active', 'expiring_soon'])
            ->whereDate('expiry_date', '<', $today)
            ->get();

        $renewalsCreated = 0;

        foreach ($expiredCerts as $cert) {
            DB::beginTransaction();
            try {
                $cert->update(['status' => 'renewal_triggered']);
                
                $originalLead = $cert->client->lead;

                Lead::create([
                    'contact_person' => $originalLead->contact_person,
                    'company_name' => $originalLead->company_name,
                    'mobile' => $originalLead->mobile,
                    'alternate_mobile' => $originalLead->alternate_mobile,
                    'email' => $originalLead->email,
                    'city' => $originalLead->city,
                    'source' => $originalLead->source,
                    'services' => [$cert->certificate_type],
                    'status' => 'renewal',
                    'is_active' => true,
                    'renewed_from_certification_id' => $cert->id,
                    'created_by' => null, // System created
                ]);

                $renewalsCreated++;
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->error("Failed to process renewal for cert ID {$cert->id}: " . $e->getMessage());
            }
        }

        $this->info("Created {$renewalsCreated} renewal leads.");
    }
}
