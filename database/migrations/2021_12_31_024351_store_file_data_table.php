<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StoreFileDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_file_data', function (Blueprint $table) {
            $table->id();
            $table->integer('truck_id')->unsigned()->foreign('truck_id')->references('id')->on('truck')->index();
            $table->string('truck_type');
            $table->string('file');
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
        Schema::dropIfExists('store_file_data');
    }
}
