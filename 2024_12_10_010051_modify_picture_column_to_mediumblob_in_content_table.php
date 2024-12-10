<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyPictureColumnToMediumblobInContentTable extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE `content` MODIFY `picture` MEDIUMBLOB');
    }

    public function down()
    {
        // 変更前の状態に戻す場合 (元に戻すデータ型を指定)
        DB::statement('ALTER TABLE `content` MODIFY `picture` BLOB');
    }
}
