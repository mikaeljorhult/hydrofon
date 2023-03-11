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
    public function up(): void
    {
        Schema::create('approver_group', function (Blueprint $table) {
            $table->integer('group_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();

            // Delete relation if group is deleted.
            $table->foreign('group_id')
                  ->references('id')->on('groups')
                  ->onDelete('cascade');

            // Delete relation if user is deleted.
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('approver_group');
    }
};
