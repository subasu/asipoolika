<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->integer('receiver_id')->unsigned()->index();;
            $table->foreign('receiver_id')->references('id')->on('users');
            $table->integer('request_id')->unsigned()->index();;
            $table->foreign('request_id')->references('id')->on('requests');
            $table->integer('certificate_type_id')->unsigned()->index();;
            $table->foreign('certificate_type_id')->references('id')->on('certificate_types');
            $table->string('shop_comp');
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
        Schema::dropIfExists('certificates');
    }
}
