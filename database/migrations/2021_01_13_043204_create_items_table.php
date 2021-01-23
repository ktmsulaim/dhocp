<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_id')->index();
            $table->string('key');
            $table->string('label');
            $table->text('description');
            $table->tinyInteger('required')->default(1);
            $table->string('type'); // [number, text, textarea, dropdown, date, file, checkbox]
            $table->string('placeholder')->nullable();
            $table->text('additional')->nullable();
            $table->string('size')->nullable(); // [1 => Full, 2 => Half, 3 => One third, 4 => Quarter]
            $table->integer('order')->nullable();
            $table->timestamps();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
