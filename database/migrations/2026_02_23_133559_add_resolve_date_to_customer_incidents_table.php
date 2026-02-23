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
        Schema::table('customer_incidents', function (Blueprint $table) {
            $table->dateTime('resolve_date')->nullable()->after('incident_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_incidents', function (Blueprint $table) {
            $table->dropColumn('resolve_date');
        });
    }
};
