<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIconColumnInUsersTable extends Migration
{
    public function up()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->string('icon')->nullable()->change(); // VARCHARに変更
        });
    }

    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->binary('icon')->nullable()->change(); // 元に戻す場合
        });
    }
}
