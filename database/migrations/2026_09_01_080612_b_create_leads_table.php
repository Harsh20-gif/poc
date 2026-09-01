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
            $table->string('contact_person');
            $table->string('company_name')->nullable();
            $table->string('mobile');
            $table->string('alternate_mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->enum('source', ['Website', 'LinkedIn', 'Instagram', 'Cold Call', 'Direct Visit', 'Other'])->default('Other');
            $table->json('services')->nullable();
            $table->enum('status', ['pending', 'in_conversation', 'deactivated', 'converted', 'renewal'])->default('pending');
            $table->boolean('is_active')->default(true);
            $table->string('deactivation_reason')->nullable();
            
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
