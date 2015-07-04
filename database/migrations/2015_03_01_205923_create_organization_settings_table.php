<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationSettingsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_settings', function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('scheduled_reflection_group_size')->default(5);
            $table->integer('scheduled_reflection_day_of_week')->default(\App\Models\Reflection::DAY_OF_WEEK_FRIDAY);
            $table->time('scheduled_reflection_start_time')->nullable()->default(null);
            $table->time('scheduled_reflection_end_time')->nullable()->default(null);
            $table->string('scheduled_reflection_timezone')->nullable()->default(null);
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
        Schema::drop('organizations');
    }

}
