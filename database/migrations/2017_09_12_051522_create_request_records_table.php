<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('code')->unique();
            $table->string('description');
            $table->smallInteger('count');
            $table->string('unit_count');
            $table->integer('price');
            $table->integer('rate');
            $table->string('why_not')->nullable()->comment ="دلیل رد شدن" ;
            $table->smallInteger('step')->comment ="مرحله ای که درخواست توشه" ;
            $table->smallInteger('accept')->default(0);
            $table->integer('refuse_user_id')->unsigned()->index()->comment = "گسی که درخواستو رد کرده";
            $table->foreign('refuse_user_id')->references('id')->on('users'); //کسی که درخواست رو رد کرده
            $table->integer('receiver_id')->unsigned()->index()->comment = "تحویل گیرنده";
            $table->foreign('receiver_id')->references('id')->on('users'); //گیرنده نتیجه درخواست از کارپرداز
            $table->boolean('active')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('request_records');
    }
}
