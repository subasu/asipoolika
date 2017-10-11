<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestReqcordSignature extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_record_signature', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_record_id')->unsigned()->index();
            $table->foreign('request_record_id')->references('id')->on('request_records')->onDelete('cascade');
            $table->integer('signatures_id')->unsigned()->index();
            $table->foreign('signatures_id')->references('id')->on('signatures')->onDelete('cascade');;
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
        //
    }
}
