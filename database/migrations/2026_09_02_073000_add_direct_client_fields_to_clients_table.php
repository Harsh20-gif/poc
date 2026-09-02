<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('owner')->nullable()->after('company_name');
            $table->string('address')->nullable()->after('owner');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('zip')->nullable()->after('state');
            $table->string('country')->nullable()->after('zip');
            $table->string('phone')->nullable()->after('country');
            $table->string('website')->nullable()->after('phone');
            $table->string('vat_number')->nullable()->after('website');
            $table->string('client_group')->nullable()->after('vat_number');
            $table->string('currency')->default('INR')->after('client_group');
            $table->string('currency_symbol')->default('₹')->after('currency');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'owner',
                'address',
                'city',
                'state',
                'zip',
                'country',
                'phone',
                'website',
                'vat_number',
                'client_group',
                'currency',
                'currency_symbol',
            ]);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('lead_id')->nullable(false)->change();
        });
    }
};
