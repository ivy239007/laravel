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
        Schema::create('request_report', function (Blueprint $table) { //依頼報告テーブル
            $table->bigInteger('case_id')->unsigned()->primary(); // 主キー
            $table->string('comment1', 200); // 非NULL
            $table->string('comment2', 200); // 非NULL
            $table->string('comment3', 200); // 非NULL
            $table->string('comment4', 200); // 非NULL
            $table->timestamps(); // 作成日・更新日

            // 外部キー制約
            $table->foreign('case_id')->references('case_id')->on('case')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_reports');
    }
};
