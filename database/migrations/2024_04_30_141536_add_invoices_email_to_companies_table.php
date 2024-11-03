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
        Schema::table('companies', function (Blueprint $table) {
            $table->json('invoices_email')->nullable()->after('site_url');
            $table->string('invoice_address')->nullable()->after('invoices_email');
            $table->foreignId('default_frequency_id')->nullable()->constrained('frequencies')->after('invoice_address');
            $table->enum('start_finantial_year', ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'])->nullable();
            $table->enum('start_of_week', ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'])->nullable()->after('start_finantial_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign(['default_frequency_id']);
            $table->dropColumn([
                'invoices_email', 
                'invoice_address', 
                'default_frequency_id', 
                'start_finantial_year', 
                'start_of_week'
            ]);
        });
    }
};
