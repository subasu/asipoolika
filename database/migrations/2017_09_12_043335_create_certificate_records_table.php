<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('info');
            $table->smallInteger('count');
            $table->integer('price');
            $table->integer('certificate_id')->unsigned()->index();;
            $table->foreign('certificate_id')->references('id')->on('certificates');
            $table->boolean('active');
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
        Schema::dropIfExists('certificate_records');
    }
}
