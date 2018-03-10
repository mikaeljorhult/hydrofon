<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBucketResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bucket_resource', function (Blueprint $table) {
            $table->integer('bucket_id')->unsigned()->index();
            $table->integer('resource_id')->unsigned()->index();

            // Delete relationship if bucket is deleted.
            $table->foreign('bucket_id')->references('id')->on('buckets')
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
        Schema::dropIfExists('bucket_resource');
    }
}
