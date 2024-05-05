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
        Schema::table('users', function (Blueprint $table) {
            $table->string('primary_color')->nullable()->after('type');
            $table->string('secondry_color')->nullable()->after('primary_color');
            $table->string('text_color')->nullable()->after('secondry_color');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('primary_color');
            $table->dropColumn('secondry_color');
            // $table->dropColumn('text_color');
        });
    }
};
