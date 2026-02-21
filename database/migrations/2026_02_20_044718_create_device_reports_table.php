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
        Schema::create('device_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->date('month')->index(); // Index for monthly queries
            $table->string('physical_status');
            $table->string('psu_status');
            $table->string('fan_status');
            $table->string('layer2_status');
            $table->string('layer3_status');
            $table->string('cpu_status');
            $table->string('throughput_in');
            $table->string('throughput_out');
            $table->integer('duration_downtime')->default(0); // in minutes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_reports');
    }
};
