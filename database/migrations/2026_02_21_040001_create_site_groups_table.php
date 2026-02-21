<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('netbox_id')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable(); // self-referencing
            $table->string('parent_name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('depth')->default(0);
            $table->unsignedInteger('site_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_groups');
    }
};
