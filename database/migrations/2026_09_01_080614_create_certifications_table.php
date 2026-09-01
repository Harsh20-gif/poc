<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('certificate_name');
            $table->string('certificate_type');
            $table->date('issue_date');
            $table->date('expiry_date');
            $table->string('certificate_pdf_path')->nullable();
            $table->enum('status', ['active', 'expiring_soon', 'expired', 'renewal_triggered'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certifications');
    }
};
