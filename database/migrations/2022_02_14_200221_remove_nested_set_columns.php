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
        if (Schema::hasColumn('categories', '_lft')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn(['_lft', '_rgt']);
            });
        }
    }
};
