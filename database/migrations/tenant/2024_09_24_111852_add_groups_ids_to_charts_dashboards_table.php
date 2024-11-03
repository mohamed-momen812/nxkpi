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
            $table->json('groups_ids')->nullable();
            $table->dropColumn('roles_filter');
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
