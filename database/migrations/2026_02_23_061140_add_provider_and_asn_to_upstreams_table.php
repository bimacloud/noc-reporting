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
        Schema::table('upstreams', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('peer_name');
            $table->string('asn')->nullable()->after('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('upstreams', function (Blueprint $table) {
            $table->dropColumn(['provider', 'asn']);
        });
    }
};
