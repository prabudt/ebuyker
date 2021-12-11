<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users');
            $table->integer('vehicle_type_id')->unsigned()->foreign('vehicle_type_id')->references('id')->on('vehicle_type');
            $table->integer('vehicle_id')->unsigned()->foreign('vehicle_id')->references('id')->on('vehicles');
            $table->string('load_location');
            $table->string('unload_location');
            $table->timestamp('pickup_date')->nullable();
            $table->string('material_type');
            $table->string('material_weight');
            $table->string('material_length');
            $table->string('material_width');
            $table->string('material_height');
            $table->unsignedTinyInteger('approval_flag')->default(1)->comment('0=>Not Approved, 1=>Approved');
            $table->double('amount', 15, 2);
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
        Schema::dropIfExists('loads');
    }
}
