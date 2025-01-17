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
        Schema::create('user', function (Blueprint $table) { //会員情報テーブル
            $table->bigIncrements('user_id'); // 主キー、AI
            $table->string('password', 20);
            $table->string('nickname', 20);
            $table->string('name', 10);
            $table->string('kana', 10);
            $table->date('birth');
            $table->string('sex', 3);
            $table->string('tel', 11);
            $table->string('email', 100)->unique(); // UNIQUE制約
            $table->bigInteger('address_id')->unsigned(); // 外部キー
            $table->string('intro', 500)->nullable(); // NULL可能
            $table->binary('icon')->nullable(); // NULL可能
            $table->timestamps(); // created_at, updated_at
            //外部キー制約
            $table->foreign('address_id')->references('address_id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): voi
    {
        Schema::dropIfExists('users');
    }
};
