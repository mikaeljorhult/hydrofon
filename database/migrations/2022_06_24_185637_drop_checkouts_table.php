<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('checkouts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('booking_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();

            // Delete checkout if booking is deleted.
            $table->foreign('booking_id')->references('id')->on('bookings')
                  ->onDelete('cascade');

            // Set creator to NULL if creating user is deleted.
            $table->foreign('user_id')->references('id')->on('users')
                  ->onDelete('set null');
        });
    }
};
