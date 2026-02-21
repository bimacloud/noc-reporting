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
        Schema::create('backbone_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backbone_link_id')->constrained()->onDelete('cascade');
            $table->dateTime('incident_date')->index();
            $table->string('latency')->nullable();
            $table->boolean('down_status')->default(false);
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
        Schema::dropIfExists('backbone_incidents');
    }
};
