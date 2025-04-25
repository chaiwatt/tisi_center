<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToSection5InspectorsAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_inspectors_agreements', function (Blueprint $table) {
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->integer('file_created_by')->nullable()->comment('ผู้บันทึกไฟล์');
            $table->integer('file_updated_by')->nullable()->comment('ผู้แก้ไขไฟล์');
            $table->timestamp('file_created_at')->nullable()->comment('วันที่ไฟล์');
            $table->timestamp('file_updated_at')->nullable()->comment('วันที่แก้ไขไฟล์');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_inspectors_agreements', function (Blueprint $table) {
            $table->dropColumn(['description', 'file_created_by', 'file_updated_by', 'file_created_at', 'file_updated_at']);
        });
    }
}
