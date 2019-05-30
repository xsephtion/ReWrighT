<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->string('profile')->nullable();
            $table->string('banner')->nullable();
            $table->string('student_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('suffix_name')->nullable();
            $table->string('sex')->nullable();  
            $table->text('perm_address')->nullable();
            $table->text('tempo_address')->nullable();
            $table->text('office_address')->nullable();
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
        Schema::drop('users_info');
    }
}
