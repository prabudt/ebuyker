<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('truck', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->index();
            $table->string('truck_name');
            $table->string('truck_number')->unique();
            $table->string('location');
            $table->integer('vehicle_type_id')->unsigned()->foreign('vehicle_type_id')->references('id')->on('vehicle_type');
            $table->integer('vehicle_id')->unsigned()->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('truck_image')->nullable();
            $table->string('licene_no');
            $table->string('licene_front')->nullable();
            $table->string('licene_back')->nullable();
            $table->string('rc_book_number')->nullable();
            $table->string('rc_image')->nullable();
            $table->unsignedTinyInteger('approval_flag')->default(1)->comment('0=>Not Approved, 1=>Approved');
            $table->unsignedTinyInteger('active_flag')->default(1)->comment('0=>Inactive, 1=>Active');
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
        Schema::dropIfExists('truck');
    }
}
