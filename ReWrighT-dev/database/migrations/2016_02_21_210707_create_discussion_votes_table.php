<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussion_votes', function (Blueprint $table) {
            $table->integer('discussion_comment_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('vote');
            $table->timestamps('update_ts');
            $table->foreign('discussion_comment_id')
                ->references('id')
                ->on('discussion_comments')
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
        
    }
}
