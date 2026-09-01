<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->unique()->constrained('leads')->cascadeOnDelete();
            $table->string('client_name');
            $table->string('company_name')->nullable();
            $table->decimal('deal_amount', 10, 2)->default(0);
            $table->json('finalized_services')->nullable();
            $table->date('conversion_date');
            $table->enum('verification_status', ['pending', 'scheduled', 'completed'])->default('pending');
            $table->date('survey_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
