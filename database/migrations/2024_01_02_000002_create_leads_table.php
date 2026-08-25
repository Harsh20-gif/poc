<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');          // Lead company name
            $table->string('contact_person');        // Person to contact
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            // Certification type they need
            $table->enum('service_type', [
                'ISO 9001',
                'ISO 14001',
                'ISO 45001',
                'ISO 27001',
                'CE Marking',
                'BIS Certification',
                'FSSAI',
                'GMP',
                'Other',
            ])->default('ISO 9001');

            // Lead pipeline status
            $table->enum('status', [
                'new',
                'contacted',
                'proposal_sent',
                'negotiation',
                'in_progress',
                'completed',
                'lost',
            ])->default('new');

            // Where did the lead come from
            $table->enum('source', [
                'website',
                'referral',
                'cold_call',
                'email_campaign',
                'walk_in',
                'other',
            ])->default('cold_call');

            $table->decimal('expected_value', 10, 2)->nullable();  // Expected project value (₹)
            $table->text('notes')->nullable();
            $table->date('follow_up_date')->nullable();

            // Assigned staff member
            $table->foreignId('assigned_to')->nullable()->constrained('staff')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
