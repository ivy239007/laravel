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
        Schema::create('favorite', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned(); // 外部キー
            $table->bigInteger('case_id')->unsigned(); // 外部キー
            $table->boolean('favo_flg')->default(false); // フラグ、デフォルト値false

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
        Schema::dropIfExists('favorites');
    }
};
