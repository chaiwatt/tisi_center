<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStdForceToCertifyStandardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->string('std_force',5)->nullable()->comment('สถานะมาตรฐาน ท = ทั่วไป, บ = บังคับ');
            $table->integer('standard_id')->nullable()->comment('id ตาราง certify_standards ใช้ในกรณีทบทวน');
            $table->string('ref_document', 255)->nullable()->comment('เอกสารอ้างอิง');
            $table->text('reason')->nullable()->comment('เหตุผลเเละความจำเป็น');
            $table->string('confirm_time', 255)->nullable()->comment('คณะกรรมการประชุมครั้งที่');
            $table->decimal('std_price',10,2)->nullable()->comment('ราคา');
            $table->integer('industry_target')->nullable()->comment('id ตาราง basic_industry_targets');
            $table->text('remark')->nullable()->comment('หมายเหตุ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certify_standards', function (Blueprint $table) {
            $table->dropColumn(['std_force','ref_document','reason','confirm_time','industry_target','remark']);
        });
    }
}
