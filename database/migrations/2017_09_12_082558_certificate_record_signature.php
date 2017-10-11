<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CertificateRecordSignature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_record_signature', function (Blueprint $table) {
            $table->integer('certificate_record_id')->unsigned()->index();;
            $table->foreign('certificate_record_id')->references('id')->on('certificate_records')->onDelete('cascade');
            $table->integer('signature_id')->unsigned()->index();;
            $table->foreign('signature_id')->references('id')->on('signatures')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
