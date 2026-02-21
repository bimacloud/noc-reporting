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
        Schema::table('devices', function (Blueprint $table) {
            $table->foreignId('device_type_id')->nullable()->after('name')->constrained('device_types')->nullOnDelete();
            $table->dropColumn('type');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->foreignId('service_type_id')->nullable()->after('name')->constrained('service_types')->nullOnDelete();
            $table->dropColumn('service_type');
        });

        Schema::table('resellers', function (Blueprint $table) {
            $table->text('address')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropForeign(['device_type_id']);
            $table->dropColumn('device_type_id');
            $table->string('type')->nullable();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['service_type_id']);
            $table->dropColumn('service_type_id');
            $table->string('service_type')->nullable();
        });

        Schema::table('resellers', function (Blueprint $table) {
            $table->dropColumn('address');
        });
    }
};
