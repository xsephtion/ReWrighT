<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->integer('physician_id')->unsigned();  // physician user_id (maker)
            $table->integer('patient_id')->unsigned();     // patient user_id (tagged patient user)
            $table->integer('task_exer_data_id')->unsigned()->nullable();     // tagged task_exer_data
            $table->increments('id');
            $table->string('title')->nullabe();
            $table->mediumText('text')->nullable();
            $table->string('image')->nullable();
            $table->boolean('active');
            $table->timestamps('update_ts');
            
            $table->foreign('physician_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');          
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
