<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardDraftPlanHistorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_draft_plan_historys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('draft_plan_id')->nullable()->comment('id ตาราง tisi_estandard_draft_plan');
            $table->string('data_field')->nullable()->comment('ชื่อฟิลด์');
            $table->text('data_old')->nullable()->comment('ข้อมูลเดิม');
            $table->text('data_new')->nullable()->comment('ข้อมูลใหม่');
            $table->integer('state')->nullable()->comment('ลำดับการแก้ไข');
            $table->integer('created_by')->nullable()->comment('id ตาราง user_register');
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
        Schema::dropIfExists('tisi_estandard_draft_plan_historys');
    }
}
