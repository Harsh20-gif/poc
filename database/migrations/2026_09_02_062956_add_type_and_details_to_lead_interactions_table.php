<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lead_interactions', function (Blueprint $table) {
            $table->string('type')->default('remark')->after('user_id');
            $table->json('details')->nullable()->after('remark');
            $table->text('remark')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lead_interactions', function (Blueprint $table) {
            $table->dropColumn(['type', 'details']);
            $table->text('remark')->nullable(false)->change();
        });
    }
};
