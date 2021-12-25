<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_load', function (Blueprint $table) {
            $table->id();
            $table->integer('load_id')->unsigned()->foreign('load_id')->references('id')->on('loads')->index();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->index();
            $table->double('booking_amount', 15, 2);
            $table->unsignedTinyInteger('approval_flag')->default(1)->comment('0=>Not Approved, 1=>Approved');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_load');
    }
}
