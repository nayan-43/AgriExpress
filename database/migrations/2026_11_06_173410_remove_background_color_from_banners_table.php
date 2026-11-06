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
        Schema::table('banners', function (Blueprint $table) {
            if (Schema::hasColumn('banners', 'background_color')) {
                Schema::table('banners', function (Blueprint $table) {
                    $table->dropColumn('background_color');
                });
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            Schema::table('banners', function (Blueprint $table) {
                $table->string('background_color')->default('#fff1f2');
            });
        });
    }
};
