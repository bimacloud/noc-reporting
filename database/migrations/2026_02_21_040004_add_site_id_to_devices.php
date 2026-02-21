<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            if (! Schema::hasColumn('devices', 'site_id')) {
                $table->unsignedBigInteger('site_id')->nullable()->after('location_id');
            }
            if (! Schema::hasColumn('devices', 'site_name')) {
                $table->string('site_name')->nullable()->after('site_id');
            }
        });

        Schema::table('devices', function (Blueprint $table) {
            try {
                $table->foreign('site_id')
                      ->references('id')
                      ->on('sites')
                      ->nullOnDelete();
            } catch (\Exception $e) {}
        });
    }

    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            try { $table->dropForeign(['site_id']); } catch (\Exception $e) {}
            foreach (['site_id', 'site_name'] as $col) {
                if (Schema::hasColumn('devices', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
