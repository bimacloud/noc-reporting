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
        Schema::create('backbone_links', function (Blueprint $table) {
            $table->id();
            $table->string('node_a');
            $table->string('node_b');
            $table->string('provider')->nullable();
            $table->string('media')->nullable(); // e.g., FO, Radio
            $table->string('capacity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backbone_links');
    }
};
