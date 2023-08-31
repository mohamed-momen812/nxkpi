<?php

use App\Models\Kpi;
use App\Enums\FormatEnum;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('user_target')->nullable();
            $table->integer('sort_order')->nullable();
            $table->string('format')->default(FormatEnum::INTEGER->value);
            $table->string('direction')->default('up');
            $table->string('aggregated')->default(Kpi::AGGREGATED_SUM_TOTAL);
            $table->string('equation')->nullable();
            $table->boolean('target_calculated')->default(false);
            $table->string('icon')->nullable();
            $table->json('thresholds')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('frequency_id');
            $table->foreign('frequency_id')->references('id')->on('frequencies')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('kpis');
    }
};
