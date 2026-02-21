<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---- LOCATIONS ----
        // Ubah ENUM type → VARCHAR agar fleksibel terima data dari NetBox
        DB::statement("ALTER TABLE locations MODIFY COLUMN type VARCHAR(50) NULL");

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
            if (! Schema::hasColumn('locations', 'region')) {
                $table->string('region')->nullable()->after('description');
            }
            if (! Schema::hasColumn('locations', 'site_group')) {
                $table->string('site_group')->nullable()->after('region');
            }
            if (! Schema::hasColumn('locations', 'site_group_id')) {
                $table->unsignedBigInteger('site_group_id')->nullable()->after('site_group');
            }
            if (! Schema::hasColumn('locations', 'status')) {
                $table->string('status')->nullable()->default('active')->after('site_group_id');
            }
        });

        // ---- DEVICES ----
        Schema::table('devices', function (Blueprint $table) {
            if (! Schema::hasColumn('devices', 'netbox_id')) {
                $table->unsignedBigInteger('netbox_id')->nullable()->unique()->after('id');
            }
            if (! Schema::hasColumn('devices', 'ip_address')) {
                $table->string('ip_address')->nullable()->after('name');
            }
            if (! Schema::hasColumn('devices', 'role')) {
                $table->string('role')->nullable()->after('ip_address');
            }
            if (! Schema::hasColumn('devices', 'manufacturer')) {
                $table->string('manufacturer')->nullable()->after('brand');
            }
            if (! Schema::hasColumn('devices', 'platform')) {
                $table->string('platform')->nullable()->after('manufacturer');
            }
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $cols = ['netbox_id', 'slug', 'description', 'region', 'site_group', 'site_group_id', 'status'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('locations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
        DB::statement("ALTER TABLE locations MODIFY COLUMN type ENUM('POP','DC','BTS') NOT NULL");

        Schema::table('devices', function (Blueprint $table) {
            $cols = ['netbox_id', 'ip_address', 'role', 'manufacturer', 'platform'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('devices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
