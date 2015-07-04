<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnboardingToUsersOrganizations extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users_organizations', function(Blueprint $table)
        {
            $table->boolean('onboarding_complete')->default(false);
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
        Schema::table('users_organizations', function(Blueprint $table)
        {
            $table->dropColumn('onboardin_complete');
        });
    }

}
