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
        Schema::create('comments', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->comment('ユーザID');
            $table->unsignedInteger('tweet_id')->comment('ツイートID');

            $table->increments('id');
            $table->string('text')->comment('本文');
            $table->timestamps();
            $table->index('id');
            $table->index('user_id');
            $table->index('tweet_id');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('tweet_id')->references('id')->on('tweets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
