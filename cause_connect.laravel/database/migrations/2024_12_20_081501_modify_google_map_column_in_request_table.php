<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('case', function (Blueprint $table) {
            $table->string('google_map', 500)->change(); // カラムを VARCHAR(500) に変更
        });
    }

    public function down(): void
    {
        Schema::table('case', function (Blueprint $table) {
            $table->string('google_map', 300)->change(); // 元に戻す
        });
    }
};
