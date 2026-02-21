<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('netbox_id')->nullable()->unique();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('status')->nullable()->default('active');
            $table->string('region')->nullable();
            $table->unsignedBigInteger('site_group_id')->nullable(); // FK ke site_groups
            $table->string('site_group_name')->nullable();
            $table->text('address')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('device_count')->default(0);
            $table->timestamps();

            $table->foreign('site_group_id')
                  ->references('id')
                  ->on('site_groups')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
