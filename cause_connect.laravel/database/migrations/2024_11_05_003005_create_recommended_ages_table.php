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
        Schema::create('recommended_age', function (Blueprint $table) { //推奨年齢マスタ
            $table->bigIncrements('rec_age_id'); // 主キー
            $table->string('rec_age', 10); // 非NULL
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recommended_ages');
    }
};
