<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_type', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('is_admin');
            $table->string('active_flag')->default(1)->comment('0=>Inactive, 1=>Active');;;
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('mobile_no');
            $table->unsignedTinyInteger('is_admin')->default(0)->comment('0=>No, 1=>Yes');;
            $table->integer('otp')->nullable();
            $table->integer('user_type_id')->unsigned()->foreign('user_type_id')->references('id')->on('user_type');
            $table->text('address')->nullable();
            $table->unsignedTinyInteger('approval_flag')->default(0)->comment('0=>Pending, 1=>Approved');
            $table->unsignedTinyInteger('active_flag')->default(1)->comment('0=>Inactive, 1=>Active');
            $table->index(['user_type_id']);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
