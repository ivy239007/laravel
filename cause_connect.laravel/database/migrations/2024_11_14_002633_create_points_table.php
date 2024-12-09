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
        Schema::create('point', function (Blueprint $table) { //ポイントテーブル
            $table->bigInteger('user_id')->unsigned(); // 外部キー対象
            $table->timestamp('timestamp'); // 複合キー対象
            $table->bigInteger('points'); // 非NULL
            $table->string('description', 100); // 非NULL

            // 複合主キー
            $table->primary(['user_id', 'timestamp']);

            // 外部キー制約
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
