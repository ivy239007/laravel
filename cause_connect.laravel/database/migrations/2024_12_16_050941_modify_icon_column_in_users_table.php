<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyIconColumnInUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // `users` テーブルの `icon` カラムを MEDIUMBLOB に変更
        DB::statement('ALTER TABLE user MODIFY icon MEDIUMBLOB');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 元に戻す場合の処理（例：BLOB に戻す）
        DB::statement('ALTER TABLE user MODIFY icon BLOB');
    }
}
