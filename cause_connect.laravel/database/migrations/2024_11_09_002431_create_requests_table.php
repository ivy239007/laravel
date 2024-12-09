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
        Schema::create('case', function (Blueprint $table) { //依頼情報テーブル
            $table->bigIncrements('case_id'); // 主キー、AI
            $table->bigInteger('client_id')->unsigned(); // 外部キー
            $table->string('case_name', 10)->nullable(false); // 非NULL
            $table->integer('lower_limit')->nullable(false); // 非NULL
            $table->integer('upper_limit')->nullable(false); // 非NULL
            $table->integer('num_people')->nullable(false); // 非NULL
            $table->date('case_date')->nullable(false); // 非NULL
            $table->timestamp('exec_date')->nullable(false); // 非NULL
            $table->integer('start_activty')->nullable(false); // 非NULL
            $table->integer('end_activty')->nullable(false); // 非NULL
            $table->bigInteger('address_id')->unsigned(); // 外部キー
            $table->string('equipment', 10)->nullable(false); // 非NULL
            $table->bigInteger('area_id')->unsigned(); // 外部キー
            $table->bigInteger('theme_id')->unsigned(); // 外部キー
            $table->bigInteger('rec_age_id')->unsigned(); // 外部キー
            $table->bigInteger('feature_id')->unsigned(); // 外部キー
            $table->string('achieve', 200)->nullable(false); // 非NULL
            $table->string('area_detail', 200)->nullable(false); // 非NULL
            $table->text('content')->nullable(false); // 非NULL
            $table->text('contents')->nullable(false); // 非NULL
            $table->bigInteger('state_id')->unsigned(); // 外部キー
            $table->string('google_map', 300)->nullable(); // NULL可
            $table->timestamps(); // created_at, updated_at

            // 外部キー制約
            $table->foreign('client_id')->references('user_id')->on('user')->onDelete('cascade');
            $table->foreign('address_id')->references('address_id')->on('address')->onDelete('cascade');
            $table->foreign('area_id')->references('area_id')->on('place')->onDelete('cascade');
            $table->foreign('theme_id')->references('theme_id')->on('activity_theme')->onDelete('cascade');
            $table->foreign('rec_age_id')->references('rec_age_id')->on('recommended_age')->onDelete('cascade');
            $table->foreign('feature_id')->references('feature_id')->on('feature')->onDelete('cascade');
            $table->foreign('state_id')->references('state_id')->on('state')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
