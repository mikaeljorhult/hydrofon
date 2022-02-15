<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();
            $table->integer('created_by_id')->unsigned()->nullable();
            $table->dateTime('start_time')->index();
            $table->dateTime('end_time')->index();
            $table->timestamps();

            // Delete booking if user is deleted.
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('cascade');

            // Delete booking if resource is deleted.
            $table->foreign('resource_id')->references('id')->on('resources')
                  ->onDelete('cascade');

            // Set creator to NULL if creating user is deleted.
            $table->foreign('created_by_id')->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
