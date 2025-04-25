<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnSignToAppCertiCbExportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->integer('sign_id')->nullable()->comment('ตารางผู้ลงนาม')->after('attachs');
            $table->string('sign_name')->nullable()->comment('ชื่อผู้ลงนาม')->after('sign_id');
            $table->string('sign_position')->nullable()->comment('ตำแหน่งผู้ลงนาม')->after('sign_name');
            $table->enum('sign_instead', array('0','1'))->default('0')->comment('ปฏิบัติราชการแทนเลขาธิการฯ (0-ไม่ใช่, 1-ใช่)')->after('sign_position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_certi_cb_export', function (Blueprint $table) {
            $table->dropColumn([ 
                'sign_id',
                'sign_name',
                'sign_position',
                'sign_instead'
                
            ]);
        });
    }
}
