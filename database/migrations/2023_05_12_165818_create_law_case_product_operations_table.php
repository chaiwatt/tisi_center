<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseProductOperationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_product_operations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('law_cases_product_results_id')->nullable()->comment('ID ตาราง law_cases_product_results');
            $table->date('operation_date')->nullable()->comment('วันที่ดำเนินการ');
            $table->date('due_date')->nullable()->comment('วันที่ครบกำหนด');
            $table->integer('status_job_track_id')->nullable()->comment('law_basic_status_operate');
            $table->text('detail')->nullable()->comment('ข้อมูลจาก sub_department');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_case_product_operations');
    }
}
