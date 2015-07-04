<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReflectionsDiscussionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reflections_discussions', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('reflection_id')->unsigned();
            $table->foreign('reflection_id')->references('id')->on('reflections');
            $table->integer('discussion_id')->unsigned();
            $table->foreign('discussion_id')->references('id')->on('discussions');
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
        Schema::drop('reflections_discussions');
    }

}
