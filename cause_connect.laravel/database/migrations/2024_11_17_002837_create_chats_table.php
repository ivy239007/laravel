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
        Schema::create('chat', function (Blueprint $table) { //チャットテーブル
            $table->timestamp('created'); // 複合キーの一部
            $table->bigInteger('case_id')->unsigned(); // 外部キー対象
            $table->bigInteger('user_id')->unsigned(); // 外部キー対象
            $table->string('message', 100); // 本文

            // 複合主キー設定
            $table->primary(['created', 'case_id', 'user_id']);

            // 外部キー制約
            $table->foreign('case_id')->references('case_id')->on('case')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('user')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
