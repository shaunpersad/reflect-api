<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('organizations', function(Blueprint $table)
		{
			$table->increments('id')->unsigned();
            $table->string('name'); // doesn't have to be unique.
            $table->string('registration_email');
            $table->string('app_subdomain')->unique()->nullable()->default(null);
            $table->integer('organization_settings_id')->unsigned();
            $table->foreign('organization_settings_id')->references('id')->on('organization_settings');
            $table->string('verification_code')->unique()->nullable()->default(null);
            $table->boolean('registration_complete')->default(false);
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
