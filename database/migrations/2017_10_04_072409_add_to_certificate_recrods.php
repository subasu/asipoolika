<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToCertificateRecrods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_records', function (Blueprint $table) {
            $table->integer('request_record_id')->unsigned()->index();
            $table->foreign('request_record_id')->references('id')->on('request_records');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_records', function (Blueprint $table) {
            //
        });
    }
}
