<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_resource', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();

            // Delete relationship if category is deleted.
            $table->foreign('category_id')->references('id')->on('categories')
                  ->onDelete('cascade');

            // Delete relationship if resource is deleted.
            $table->foreign('resource_id')->references('id')->on('resources')
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
        Schema::dropIfExists('category_resource');
    }
};
