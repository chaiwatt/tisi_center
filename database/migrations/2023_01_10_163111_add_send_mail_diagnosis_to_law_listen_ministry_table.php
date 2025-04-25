<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendMailDiagnosisToLawListenMinistryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->integer('mail_status_diagnosis')->nullable()->comment('1=ส่งอีเมล, 2=ไม่ส่งเมล')->after('status_diagnosis');
            $table->text('mail_list_diagnosis')->nullable()->comment('เมลผู้แสดงความคิดเห็น')->after('mail_status_diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_listen_ministry', function (Blueprint $table) {
            $table->dropColumn(['mail_status_diagnosis','mail_list_diagnosis']);
        });
    }
}
