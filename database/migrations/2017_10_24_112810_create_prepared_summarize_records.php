<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreparedSummarizeRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('prepared_summarize_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prepared_summarize_id')->unsigned()->index();
            $table->foreign('prepared_summarize_id')->references('id')->on('prepared_summarize');
            $table->integer('total_price');
            $table->string('description');
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
        //
    }
}
