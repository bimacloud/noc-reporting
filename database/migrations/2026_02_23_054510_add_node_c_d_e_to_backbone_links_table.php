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
        Schema::table('backbone_links', function (Blueprint $table) {
            $table->string('node_c')->nullable()->after('node_b');
            $table->string('node_d')->nullable()->after('node_c');
            $table->string('node_e')->nullable()->after('node_d');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('backbone_links', function (Blueprint $table) {
            $table->dropColumn(['node_c', 'node_d', 'node_e']);
        });
    }
};
