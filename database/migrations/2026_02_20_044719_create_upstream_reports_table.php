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
        Schema::create('upstream_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upstream_id')->constrained()->onDelete('cascade');
            $table->date('month')->index(); // Index for monthly queries
            $table->string('status_l1');
            $table->string('status_l2');
            $table->string('status_l3');
            $table->string('advertise');
            $table->integer('duration')->default(0); // in minutes
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upstream_reports');
    }
};
