<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskExerData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_exer_data', function (Blueprint $table) {
            $table->integer('task_assignment_id')->unsigned();     // task board id
            $table->integer('exer_data_id')->unsigned();     // exer data id
            $table->integer('patient_exer_data_id')->unsigned()->nullable(); //points to exer_data table too
            $table->increments('id');
            $table->integer('freq_order');
            $table->timestamp('created')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('active');
            $table->timestamps('update_ts');
            
            $table->foreign('task_assignment_id')
                ->references('id')
                ->on('task_assignment')
                ->onDelete('cascade');

            $table->foreign('exer_data_id')
                ->references('id')
                ->on('exer_data')
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
