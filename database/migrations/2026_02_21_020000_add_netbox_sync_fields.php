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
        // Add netbox fields to locations table
        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'netbox_id')) {
                $table->unsignedBigInteger('netbox_id')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('locations', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (! Schema::hasColumn('locations', 'description')) {
                $table->text('description')->nullable()->after('address');
            }
        });

        // Add netbox fields to devices table
        Schema::table('devices', function (Blueprint $table) {
            if (! Schema::hasColumn('devices', 'netbox_id')) {
                $table->unsignedBigInteger('netbox_id')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('devices', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['netbox_id', 'slug', 'description']);
        });

        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['netbox_id', 'ip_address']);
        });
    }
};
