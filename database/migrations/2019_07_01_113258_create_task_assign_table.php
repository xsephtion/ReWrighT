<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_assignment', function (Blueprint $table) {
            $table->integer('task_id')->unsigned();  // patient group id
            $table->integer('user_id')->unsigned();     // patient user_id
            $table->increments('id');
            $table->boolean('status');
            $table->string('training_data')->nullable();
            $table->timestamp('start')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('end')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('active');
            $table->timestamps('update_ts');
            
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks_board')
                ->onDelete('cascade');
            $table->foreign('user_id')
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
