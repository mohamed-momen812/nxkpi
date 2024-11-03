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
            $table->dropForeign(['kpi_id']);
            $table->dropForeign(['kpi_id2']);
            $table->dropColumn('kpi_id');
            $table->dropColumn('kpi_id2');
            $table->dropColumn('from_date');
            $table->dropColumn('from_date2');
            $table->dropColumn('to_date');        
            $table->dropColumn('to_date2');

            $table->json('kpis_ids')->nullable();
            $table->json('from_dates')->nullable();
            $table->json('to_dates')->nullable();
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
