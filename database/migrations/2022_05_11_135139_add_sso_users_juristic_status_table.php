<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSsoUsersJuristicStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->integer('juristic_status')->nullable()->comment('สถานะนิติบุคคล 1.ยังดำเนินกิจการอยู่ , 2.ฟื้นฟู , 3. เลิกกิจการ ')->after('applicanttype_id');
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
            $table->dropColumn(['juristic_status']);
        });
    }
}
