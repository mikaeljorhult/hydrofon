<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_resource', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();

            // Delete relationship if group is deleted.
            $table->foreign('group_id')->references('id')->on('groups')
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
        Schema::dropIfExists('group_resource');
    }
}
