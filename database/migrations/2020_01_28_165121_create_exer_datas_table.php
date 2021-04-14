<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExerDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('exer_data', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();     // physician/patient user_id
            $table->increments('id');
            $table->string('desc');
            $table->string('file')->nullable();
            $table->timestamp('created')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('active');
            $table->timestamps('update_ts');
            
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
