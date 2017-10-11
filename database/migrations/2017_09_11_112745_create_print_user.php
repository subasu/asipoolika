<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_form', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('form_id')->unsigned()->index();
            $table->foreign('form_id')->references('id')->on('users'); //شماره فرم
            $table->integer('printed_by')->unsigned()->index();
            $table->foreign('printed_by')->references('id')->on('users');// کسی که پرینت گرفته
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
        Schema::dropIfExists('print_form');
    }
}
