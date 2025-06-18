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
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('province_name')->nullable()->change();
            $table->string('district_name')->nullable()->change();
            $table->string('ward_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('province_name')->nullable(false)->change();
            $table->string('district_name')->nullable(false)->change();
            $table->string('ward_name')->nullable(false)->change();
        });
    }
};
