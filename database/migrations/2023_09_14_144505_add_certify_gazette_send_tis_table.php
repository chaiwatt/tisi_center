<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCertifyGazetteSendTisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->enum('send_tis', ['0', '1'])->default('0')->comment('นำส่งข้อมูลให้กับ ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร (1.นำส่ง, 0.รอส่ง)')->after('state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_gazette', function (Blueprint $table) {
            $table->dropColumn(['send_tis']); 
        });
    }
}
