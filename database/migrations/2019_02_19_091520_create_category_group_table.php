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
        Schema::create('category_group', function (Blueprint $table) {
            $table->integer('category_id')->unsigned()->index();
            $table->integer('group_id')->unsigned()->index();

            // Delete relationship if group is deleted.
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade');

            // Delete relationship if resource is deleted.
            $table->foreign('category_id')->references('id')->on('categories')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_group');
    }
};
