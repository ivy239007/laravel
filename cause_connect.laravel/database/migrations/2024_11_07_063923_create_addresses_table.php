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
        Schema::create('address', function (Blueprint $table) { // 住所テーブル
            $table->bigIncrements('address_id'); // 主キー、AI
            $table->bigInteger('pref_id')->unsigned(); // 外部キー対象
            $table->string('address1', 20); // 非NULL
            $table->string('address2', 100)->nullable(); // NULL可能
            $table->string('post_code', 10)->nullable(); // NULL可能

            // 外部キー制約
            $table->foreign('pref_id')->references('pref_id')->on('prefectures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
