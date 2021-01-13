<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->text('value')->nullable();
            $table->integer('is_valid')->default(1); // [0 => Not valid, 1 => Pending, 2 => Valid]
            $table->text('valid_message')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_users');
    }
}
