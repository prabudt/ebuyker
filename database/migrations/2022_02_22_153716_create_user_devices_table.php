<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->foreign('user_id')->references('id')->on('users')->index();
            $table->string('device_id');
            $table->text('push_token')->nullable();
            $table->string('platform')->nullable();
            $table->string('model_name')->nullable();
            $table->string('model_version')->nullable();
            $table->string('ip_address')->nullable();
            $table->unsignedTinyInteger('active_flag')->default(1)->comment('0=>Inactive, 1=>Active');
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
        Schema::dropIfExists('user_devices');
    }
}
