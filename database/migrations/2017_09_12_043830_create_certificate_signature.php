<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateSignature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_signature', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('certificate_id')->unsigned()->index();
            $table->foreign('certificate_id')->references('id')->on('certificates');
            $table->integer('signature_id')->unsigned()->index();
            $table->foreign('signature_id')->references('id')->on('signatures');
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
        Schema::dropIfExists('certificate_signature');
    }
}
