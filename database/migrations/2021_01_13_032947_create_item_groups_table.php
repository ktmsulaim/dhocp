<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CreateItemGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name')->default(Str::random(5));
            $table->integer('is_valid')->default(1); // [0 => Not valid, 1 => Pending, 2 => Valid]
            $table->text('valid_message')->nullable();
            $table->timestamp('admin_updated')->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
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
        Schema::dropIfExists('item_groups');
    }
}
