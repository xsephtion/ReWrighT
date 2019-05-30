<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('text');
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
        Schema::drop('discussion_notifs');
        Schema::drop('discussion_votes');
        Schema::drop('discussion_comments');
        Schema::drop('discussions_board');
        Schema::drop('developers');
        Schema::drop('projects');
    }
}
