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
        Schema::create('feature', function (Blueprint $table) { //特徴マスタ
            $table->string('feature_id', 20)->primary(); // 特徴ID (主キー)
            $table->string('feature', 20)->nullable(false); // 特徴 (非NULL)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
