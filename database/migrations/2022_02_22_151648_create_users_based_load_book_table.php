<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersBasedLoadBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_based_load_book', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_id')->unsigned()->foreign('booking_id')->references('id')->on('booking_load')->index();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->index();
            $table->unsignedTinyInteger('approval_flag')->default(0)->comment('0=>Not Approved, 1=>Approved');
            $table->unsignedTinyInteger('limit_count')->default(0);
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
        Schema::dropIfExists('users_based_load_book');
    }
}
