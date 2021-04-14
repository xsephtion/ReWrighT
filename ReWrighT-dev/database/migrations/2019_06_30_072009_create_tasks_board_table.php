<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::drop('tasks_board');
        Schema::create('tasks_board', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();  // patient group id
            $table->integer('user_id')->unsigned();     // physician user_id
            $table->increments('id');
            $table->string('title');
            $table->mediumText('text')->nullable();
            $table->string('exer_data')->nullable();     //can remove (do in model)
            $table->integer('frequency')->nullable(); //points to exer_data table too
            $table->string('image')->nullable();
            $table->boolean('active');
            $table->timestamps('update_ts');
            
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
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
