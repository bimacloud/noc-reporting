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
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('month')->unique(); // Format YYYY-MM
            $table->integer('total_incident')->default(0);
            $table->integer('total_downtime')->default(0); // in minutes
            $table->decimal('sla_percentage', 5, 2)->default(100.00);
            $table->integer('total_activation')->default(0);
            $table->integer('total_upgrade')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_summaries');
    }
};
