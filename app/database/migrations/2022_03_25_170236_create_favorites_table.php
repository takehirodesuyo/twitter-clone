<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->increments('id');
            // 誰がいいねしたか、どの投稿にいいねしたか型の指定
            $table->unsignedInteger('user_id')->comment('ユーザID');
            $table->unsignedInteger('tweet_id')->comment('ツイートID');

            $table->index('id');
            $table->index('user_id');
            $table->index('tweet_id');

            $table->unique([
                'user_id',
                'tweet_id'
            ]);
            // usersテーブル
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            // tweetsテーブル
            $table->foreign('tweet_id')
                ->references('id')
                ->on('tweets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};
