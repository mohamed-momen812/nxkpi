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
            $table->foreignId('kpi_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('kpi_id2')->nullable()->constrained()->cascadeOnDelete();
            $table->date('from_date')->nullable();
            $table->date('from_date2')->nullable();
            $table->date('to_date')->nullable();
            $table->date('to_date2')->nullable();
            $table->json('roles_filter')->nullable(); // array of roles
            $table->json('users_filter')->nullable(); // array of users
            $table->enum('view_by', ['date','groups_users'])->default('date');
            $table->enum('view_days', ['Days','Weeks', 'Months', 'Quarters', 'Years'])->default('Days');
            $table->foreignId(column: 'group_id')->nullable()->constrained()->cascadeOnDelete();
            $table->enum('display_options', ['area','line','column','bar'])->default('column');
            $table->string('y_min')->nullable();
            $table->string('y_max')->nullable();
            $table->json('options')->nullable();
            $table->enum('size', ['s','m','l','xl'])->default('l');
            $table->json('colors')->nullable();
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
