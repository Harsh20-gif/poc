<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_documents', function (Blueprint $table) {
            $table->foreignId('certification_id')->nullable()->constrained('certifications')->cascadeOnDelete()->after('client_id');
            $table->string('file_path')->nullable()->change();
            $table->string('original_filename')->nullable()->change();
        });
        
        // Also update the ENUM to include 'missing' if it's MySQL enum
        // Since Laravel ENUM updates can be tricky, let's just use pending for empty files or we can use a raw statement.
        // Actually, it's safer to just rely on `file_path IS NULL` to mean missing, and keep status as 'pending'.
    }

    public function down(): void
    {
        Schema::table('client_documents', function (Blueprint $table) {
            $table->dropForeign(['certification_id']);
            $table->dropColumn('certification_id');
            $table->string('file_path')->nullable(false)->change();
            $table->string('original_filename')->nullable(false)->change();
        });
    }
};
