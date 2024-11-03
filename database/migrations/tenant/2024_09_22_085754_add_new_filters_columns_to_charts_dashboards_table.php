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
    public function up()
    {
        Schema::table('charts_dashboards', function (Blueprint $table) {
            //
            $table->enum('stack_by', ['not', 'value', 'percent'])->default('not')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('charts_dashboards', function (Blueprint $table) {
            //
        });
    }
};
