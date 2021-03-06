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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('api_token');
            $table->string('image')->nullable();
            $table->unsignedBigInteger('batch_id');
            $table->string('name');
            $table->string('enroll_no')->unique();
            $table->string('dob_password');
            $table->string('dob');
            $table->tinyInteger('active')->default(1);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
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
