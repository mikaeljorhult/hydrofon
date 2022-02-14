<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNestedSetColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('categories', '_lft'))  {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn(['_lft', '_rgt']);
            });
        }
    }
}
