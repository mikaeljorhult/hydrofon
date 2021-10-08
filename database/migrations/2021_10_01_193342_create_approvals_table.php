<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');

            $table->bigInteger('booking_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->nullable()->index();

            // Delete approval if booking is deleted.
            $table->foreign('booking_id')
                  ->references('id')->on('bookings')
                  ->onDelete('cascade');

            // Set approver to null if user is deleted.
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('approvals');
    }
}
