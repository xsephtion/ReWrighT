<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::drop('tag_info');
        Schema::create('tag_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('desc');
            $table->boolean('active');
            $table->timestamps('update_ts');
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
