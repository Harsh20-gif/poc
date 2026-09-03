<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Project;
use App\Models\Staff;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────
        $admin = User::firstOrCreate(['email' => 'admin@omega.com'], [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $alice = User::firstOrCreate(['email' => 'alice@omega.com'], [
            'name' => 'Alice Smith',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $bob = User::firstOrCreate(['email' => 'bob@omega.com'], [
            'name' => 'Bob Jones',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // ── Staff ─────────────────────────────────────────────────────
        $s1 = Staff::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@certco.in',
            'phone' => '9876543210',
            'designation' => 'Senior Consultant',
            'department' => 'Technical',
            'status' => 'active',
            'joining_date' => '2022-03-15',
        ]);

        $s2 = Staff::create([
            'name' => 'Priya Verma',
            'email' => 'priya@certco.in',
            'phone' => '9812345678',
            'designation' => 'Sales Manager',
            'department' => 'Sales',
            'status' => 'active',
            'joining_date' => '2021-06-01',
        ]);

        $s3 = Staff::create([
            'name' => 'Amit Patel',
            'email' => 'amit@certco.in',
            'phone' => '9900112233',
            'designation' => 'Certification Auditor',
            'department' => 'Operations',
            'status' => 'active',
            'joining_date' => '2023-01-10',
        ]);

        $s4 = Staff::create([
            'name' => 'Neha Singh',
            'email' => 'neha@certco.in',
            'phone' => '9765432100',
            'designation' => 'Business Development Executive',
            'department' => 'Sales',
            'status' => 'active',
            'joining_date' => '2023-07-20',
        ]);

        // ── Leads ─────────────────────────────────────────────────────
        $leads = [
            [
                'company_name' => 'Sunrise Pharma Pvt Ltd',
                'contact_person' => 'Manish Gupta',
                'email' => 'manish@sunrisepharma.com',
                'phone' => '9811223344',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'service_type' => 'ISO 9001',
                'status' => 'proposal_sent',
                'source' => 'referral',
                'expected_value' => 85000,
                'notes' => 'Large pharma company. Very interested. Follow-up after board meeting.',
                'follow_up_date' => now()->addDays(3)->toDateString(),
                'assigned_to' => $s2->id,
            ],
            [
                'company_name' => 'GreenTech Solutions',
                'contact_person' => 'Kiran Nair',
                'email' => 'kiran@greentech.co',
                'phone' => '9922334455',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'service_type' => 'ISO 14001',
                'status' => 'in_progress',
                'source' => 'website',
                'expected_value' => 120000,
                'notes' => 'Environmental management cert. Documentation stage ongoing.',
                'follow_up_date' => now()->addDays(7)->toDateString(),
                'assigned_to' => $s1->id,
            ],
            [
                'company_name' => 'Apex Manufacturing Co.',
                'contact_person' => 'Suresh Mehta',
                'email' => 'suresh@apexmfg.com',
                'phone' => '9833445566',
                'city' => 'Surat',
                'state' => 'Gujarat',
                'service_type' => 'ISO 45001',
                'status' => 'new',
                'source' => 'cold_call',
                'expected_value' => 65000,
                'notes' => 'Manufacturing unit with 200+ workers. Safety cert required.',
                'follow_up_date' => now()->addDays(1)->toDateString(),
                'assigned_to' => $s4->id,
            ],
            [
                'company_name' => 'DataSafe Technologies',
                'contact_person' => 'Asha Reddy',
                'email' => 'asha@datasafe.io',
                'phone' => '9700123456',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'service_type' => 'ISO 27001',
                'status' => 'negotiation',
                'source' => 'website',
                'expected_value' => 250000,
                'notes' => 'IT company. Needs ISMS certification for US client contracts.',
                'follow_up_date' => now()->addDays(2)->toDateString(),
                'assigned_to' => $s1->id,
            ],
            [
                'company_name' => 'FreshBite Foods Ltd',
                'contact_person' => 'Ravi Tiwari',
                'email' => 'ravi@freshbite.in',
                'phone' => '9855667788',
                'city' => 'Delhi',
                'state' => 'Delhi',
                'service_type' => 'FSSAI',
                'status' => 'completed',
                'source' => 'referral',
                'expected_value' => 45000,
                'notes' => 'Food company. Successfully completed FSSAI certification.',
                'follow_up_date' => null,
                'assigned_to' => $s3->id,
            ],
            [
                'company_name' => 'SwiftLogistics Pvt Ltd',
                'contact_person' => 'Deepak Joshi',
                'email' => null,
                'phone' => '9988776655',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'service_type' => 'ISO 9001',
                'status' => 'contacted',
                'source' => 'cold_call',
                'expected_value' => 70000,
                'notes' => 'Logistics firm. Initial call done. Sending proposal soon.',
                'follow_up_date' => now()->addDays(5)->toDateString(),
                'assigned_to' => $s2->id,
            ],
            [
                'company_name' => 'BuildRight Constructions',
                'contact_person' => 'Farhan Sheikh',
                'email' => 'farhan@buildright.com',
                'phone' => '9312456789',
                'city' => 'Ahmedabad',
                'state' => 'Gujarat',
                'service_type' => 'CE Marking',
                'status' => 'new',
                'source' => 'walk_in',
                'expected_value' => 150000,
                'notes' => 'Construction equipment exported to Europe. Needs CE marking.',
                'follow_up_date' => now()->addDays(4)->toDateString(),
                'assigned_to' => $s4->id,
            ],
            [
                'company_name' => 'MedCure Diagnostics',
                'contact_person' => 'Dr. Pooja Agarwal',
                'email' => 'pooja@medcure.in',
                'phone' => '9645321000',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'service_type' => 'GMP',
                'status' => 'lost',
                'source' => 'email_campaign',
                'expected_value' => 90000,
                'notes' => 'Went with competitor. Budget constraint reason given.',
                'follow_up_date' => null,
                'assigned_to' => $s3->id,
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }

        // ── Sample Project & Tasks (from original POC) ─────────────────
        $project = Project::create([
            'name' => 'ISO 9001 - Sunrise Pharma',
            'description' => 'Full ISO 9001 certification project for Sunrise Pharma Pvt Ltd.',
            'status' => 'active',
            'user_id' => $alice->id,
        ]);

        $tasks = [
            ['title' => 'Initial gap analysis', 'status' => 'done', 'priority' => 'high'],
            ['title' => 'Document QMS procedures', 'status' => 'in_progress', 'priority' => 'high'],
            ['title' => 'Internal audit scheduling', 'status' => 'todo', 'priority' => 'medium'],
            ['title' => 'Management review meeting', 'status' => 'todo', 'priority' => 'medium'],
            ['title' => 'Certification body audit', 'status' => 'todo', 'priority' => 'high'],
        ];

        foreach ($tasks as $t) {
            Task::create([
                ...$t,
                'project_id' => $project->id,
                'created_by' => $alice->id,
                'assigned_to' => $alice->id,
            ]);
        }
    }
}
