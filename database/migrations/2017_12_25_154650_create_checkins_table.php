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
        Schema::create('checkins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('booking_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            // Delete checkin if booking is deleted.
            $table->foreign('booking_id')->references('id')->on('bookings')
                  ->onDelete('cascade');

            // Set creator to NULL if creating user is deleted.
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('checkins');
    }
};
