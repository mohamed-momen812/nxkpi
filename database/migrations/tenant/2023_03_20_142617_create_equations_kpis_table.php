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
        Schema::create('equations_kpis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('equation_id');
            $table->foreign('equation_id')->references('id')->on('equations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('kpi_id');
            $table->foreign('kpi_id')->references('id')->on('kpis')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('equations_kpis');
    }
};
