<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('netbox_sync_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type');          // site_groups | sites | locations | devices
            $table->unsignedBigInteger('netbox_id')->nullable();
            $table->string('entity_name')->nullable();
            $table->enum('status', ['ok', 'skipped', 'error']);
            $table->text('message')->nullable();     // error message atau info
            $table->json('payload')->nullable();     // raw data dari NetBox (debugging)
            $table->timestamp('synced_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('netbox_sync_logs');
    }
};
