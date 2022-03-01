<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBasedLoadBookChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_based_load_book_chat', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->index();
            $table->integer('users_based_load_book_id')->unsigned()->foreign('users_based_load_book_id')->references('id')->on('users_based_load_book')->index();
            $table->unsignedTinyInteger('chat_count')->default(0);
            $table->double('amount', 40, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_based_load_book_chat');
    }
}
