<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoUsersContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->renameColumn('head_street', 'contact_street');
            $table->renameColumn('head_address_no', 'contact_address_no');
            $table->renameColumn('head_moo', 'contact_moo');
            $table->renameColumn('head_soi', 'contact_soi');
            $table->renameColumn('head_subdistrict', 'contact_subdistrict');
            $table->renameColumn('head_district', 'contact_district');
            $table->renameColumn('head_province', 'contact_province');
            $table->renameColumn('head_zipcode', 'contact_zipcode');
            $table->renameColumn('head_building', 'contact_building');
            $table->renameColumn('head_country_code', 'contact_country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->renameColumn('contact_street', 'head_street');
            $table->renameColumn('contact_address_no', 'head_address_no');
            $table->renameColumn('contact_moo', 'head_moo');
            $table->renameColumn('contact_soi', 'head_soi');
            $table->renameColumn('contact_subdistrict', 'head_subdistrict');
            $table->renameColumn('contact_district', 'head_district');
            $table->renameColumn('contact_province', 'head_province');
            $table->renameColumn('contact_zipcode', 'head_zipcode');
            $table->renameColumn('contact_building', 'head_building');
            $table->renameColumn('contact_country_code', 'head_country_code');
        });
    }
}
