<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CustomerApprovalFlagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_based_load_book', function (Blueprint $table) {
            $table->unsignedTinyInteger('customer_approval_flag')->default(0)->comment('0=>Not Approved, 1=>Approved');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_based_load_book', function (Blueprint $table) {
            //
        });
    }
}
