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
        Schema::create('sup', function (Blueprint $table) { //出資ポイントテーブル
            $table->bigInteger('user_id')->unsigned(); // 外部キー対象
            $table->bigInteger('case_id')->unsigned(); // 外部キー対象
            $table->integer('sup_point'); // 非NULL

            // 複合主キー
            $table->primary(['user_id', 'case_id']);

            // 外部キー制約
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('case_id')->references('case_id')->on('case')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sups');
    }
};
