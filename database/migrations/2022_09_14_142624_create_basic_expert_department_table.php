<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBasicExpertDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_expert_department', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expert_id')->nullable()->comment('idตาราง : basic_expert_groups');
            $table->integer('department_id')->nullable()->comment('idตาราง : basic_departments');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก');
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
        Schema::dropIfExists('basic_expert_department');
    }
}
