<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah type menjadi VARCHAR jika belum
        DB::statement("ALTER TABLE locations MODIFY COLUMN type VARCHAR(50) NULL");

        Schema::table('locations', function (Blueprint $table) {
            // Hapus kolom lama yang tidak sesuai hierarki baru
            $toDrop = ['region', 'site_group', 'site_group_id'];
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('locations', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Tambah site_id FK ke tabel sites
            if (! Schema::hasColumn('locations', 'site_id')) {
                $table->unsignedBigInteger('site_id')->nullable()->after('id');
            }
            if (! Schema::hasColumn('locations', 'site_name')) {
                $table->string('site_name')->nullable()->after('site_id');
            }
            if (! Schema::hasColumn('locations', 'tenant')) {
                $table->string('tenant')->nullable()->after('description');
            }
            if (! Schema::hasColumn('locations', 'device_count')) {
                $table->unsignedInteger('device_count')->default(0)->after('tenant');
            }
        });

        // Tambah FK setelah kolom ada
        Schema::table('locations', function (Blueprint $table) {
            // Cek apakah foreign key sudah ada
            try {
                $table->foreign('site_id')
                      ->references('id')
                      ->on('sites')
                      ->nullOnDelete();
            } catch (\Exception $e) {
                // FK sudah ada, skip
            }
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            try { $table->dropForeign(['site_id']); } catch (\Exception $e) {}

            $toDrop = ['site_id', 'site_name', 'tenant', 'device_count'];
            foreach ($toDrop as $col) {
                if (Schema::hasColumn('locations', $col)) {
                    $table->dropColumn($col);
                }
            }

            // Kembalikan kolom yang dihapus
            $table->string('region')->nullable();
            $table->string('site_group')->nullable();
            $table->unsignedBigInteger('site_group_id')->nullable();
        });
    }
};
