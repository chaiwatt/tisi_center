<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDropColumnToSection5LabsScopesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->dropColumn([

                'test_tools_id',
                'test_tools_no',
                'capacity',
                'range',
                'true_value',
                'fault_value',
                'state',
                'start_date',
                'end_date'
                
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_labs_scopes', function (Blueprint $table) {
            $table->integer('test_tools_id')->nullable()->comment('ไอดีเครื่องมือทดสอบ');
            $table->string('test_tools_no')->nullable()->comment('รหัส/หมายเลขเครื่องมือทดสอบ');
            $table->text('capacity')->nullable()->comment('ขีดความสามารถ');
            $table->text('range')->nullable()->comment('ช่วงการใช้งาน');
            $table->text('true_value')->nullable()->comment('ความละเอียดที่อ่านได้');
            $table->text('fault_value')->nullable()->comment('ความคลาดเคลื่อนที่ยอมรับ');
            $table->date('start_date')->nullable()->comment('วันที่เริ่มต้น');
            $table->date('end_date')->nullable()->comment('วันที่สิ้นสุด');
            $table->tinyInteger('state')->nullable()->comment('สถานะ 1 = Active, 2 = Not Active');
        });
    }
}
