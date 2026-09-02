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
        // Update old enum values that aren't in the new expected list to 'Other'
        \Illuminate\Support\Facades\DB::table('leads')
            ->whereNotIn('source', ['Facebook', 'Instagram', 'Google', 'WhatsApp', 'Other'])
            ->update(['source' => 'Other']);
            
        // Note: The column was already migrated to a VARCHAR(255) string column in a previous migration,
        // so we don't need to change the column type to an ENUM. Keeping it as a VARCHAR allows us to
        // store the custom "Other" text directly in this column as requested.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
