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
        Schema::create('review', function (Blueprint $table) { //評価テーブル
            $table->bigInteger('user_id')->unsigned(); // 主キー
            $table->integer('good')->default(0); // 初期値0
            $table->integer('bad')->default(0);  // 初期値0

            // 主キー設定
            $table->primary('user_id');

            // 外部キー制約
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
