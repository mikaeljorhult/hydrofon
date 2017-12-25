<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_object', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->integer('object_id')->unsigned()->index();

            // Delete relationship if category is deleted.
            $table->foreign('category_id')->references('id')->on('categories')
                  ->onDelete('cascade');

            // Delete relationship if object is deleted.
            $table->foreign('object_id')->references('id')->on('objects')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_object');
    }
}
