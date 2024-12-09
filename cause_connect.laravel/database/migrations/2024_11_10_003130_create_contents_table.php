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
        Schema::create('content', function (Blueprint $table) {
            $table->bigInteger('case_id')->unsigned(); // 複合キー対象
            $table->integer('picture_type')->unsigned(); // 複合キー対象
            $table->binary('picture'); // 非NULL (BLOB)

            // 複合主キー
            $table->primary(['case_id', 'picture_type']);

            // 外部キー制約
            $table->foreign('case_id')->references('case_id')->on('case')->onDelete('cascade');
            $table->foreign('picture_type')->references('picture_type')->on('picture')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
};
