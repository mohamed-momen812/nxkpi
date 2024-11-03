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
        Schema::create('reports_filters', function (Blueprint $table) {
            $table->id();
            // Reports name
            $table->string('name');
            // choose kpis
            $table->json('kpis_ids')->nullable();
            // Date from, to
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            // Date from, to (compare)
            $table->date('from_date2')->nullable();
            $table->date('to_date2')->nullable();
            // Filter by All Users
            $table->json('users_filter')->nullable(); 
            // Filter by All Groups
            $table->json('groups_filter')->nullable(); 
            // View By (date, groups_users)
            $table->enum('view_by', ['date','groups_users'])->default('date')->nullable();
            $table->enum('view_days', ['Days','Weeks', 'Months', 'Quarters', 'Years'])->default('Days')->nullable();
            $table->foreignId('group_id')->nullable()->constrained()->cascadeOnDelete();
            // Filter by groups or users
            $table->enum('group', ['groups', 'users'])->default('groups')->nullable();
            // Filter by one User
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            // Sort By
            $table->enum('sort_by', ['Actual (ascending)', 'Actual (descending)', 'Target % (ascending)', 'Target % (descending)', '% Change (ascending)', '% Change (descending)', 'A-Z', 'Z-A'])->nullable();
            // Options
            $table->json('options')->nullable();
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
        Schema::dropIfExists('reports_filters');
    }
};
