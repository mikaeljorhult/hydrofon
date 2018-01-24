<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupObjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_object', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->index();
            $table->integer('object_id')->unsigned()->index();

            // Delete relationship if group is deleted.
            $table->foreign('group_id')->references('id')->on('groups')
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
        Schema::dropIfExists('group_object');
    }
}
