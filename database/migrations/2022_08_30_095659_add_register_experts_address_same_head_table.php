<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegisterExpertsAddressSameHeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->integer('address_same_head')->nullable()->comment('1 ใช้ที่อยู่ตามทะเบียนบ้าน')->after('head_zipcode');
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_experts', function (Blueprint $table) {
            $table->dropColumn(['address_same_head']);
        });
    }
}
