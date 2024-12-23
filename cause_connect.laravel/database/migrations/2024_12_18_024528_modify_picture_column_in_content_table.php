<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPictureColumnInContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content', function (Blueprint $table) {
            // カラムが存在する場合、削除
            if (Schema::hasColumn('content', 'picture')) {
                $table->dropColumn('picture');
            }

            // 新しいVARCHAR(255)カラムを追加
            $table->string('picture', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content', function (Blueprint $table) {
            // 追加したVARCHARカラムを削除
            if (Schema::hasColumn('content', 'picture')) {
                $table->dropColumn('picture');
            }

            // 元のBLOBカラムを復元
            $table->binary('picture')->nullable();
        });
    }
}
