<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardDraftPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_draft_plan', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('draft_id')->comment('TB : tisi_estandard_draft . id');
            $table->unsignedInteger('offer_id')->comment('ความคิดเห็น TB : tisi_estandard_offers . id');
            $table->unsignedInteger('std_type')->comment('ประเภทมาตรฐาน TB : bcertify_standard_type . id');
            $table->string('tis_number',255)->nullable()->comment('เลขที่มาตรฐาน');
            $table->string('tis_book',255)->nullable()->comment('เล่มมาตรฐาน');
            $table->string('tis_year',255)->nullable()->comment('ปีมาตรฐาน');
            $table->string('tis_name',255)->nullable()->comment('ชื่อมาตรฐาน');
            $table->string('tis_name_eng',255)->nullable()->comment('ชื่อมาตรฐาน eng');
            $table->integer('method_id')->nullable()->comment('วิธีการ TB : basic_methods . id');
            $table->string('confirm_time',255)->nullable()->comment('คณะกรรมการเห็นชอบในการประชุมครั้งที');
            $table->integer('industry_target')->nullable()->comment('อุตสาหกรรมเป้าหมาย/บริการแห่งอนาคต  TB :  basic_industry_targets . id');
            $table->integer('assign_id')->nullable()->comment('เจ้าหน้าที่ที่รับมอบหมาย');
            $table->datetime('assign_date')->nullable()->comment('วันที่มอบหมาย');
            $table->integer('status_id')->nullable()->comment('สถานะการท าแผน 1. ร่างแผน 2.จัดท าแผน 3. อนุมัติแผน 4.ไม่อนุมัติแผน 5.แจ้งแก้ไขแผน');
            $table->date('plan_startdate')->nullable()->comment('วันที่เริ่มกำหนดแผน');
            $table->date('plan_enddate')->nullable()->comment('วันที่สิ้นสุดกำหนดแผน');
            $table->integer('confirm_by')->nullable()->comment('ผู้ยืนยันแผน');
            $table->datetime('confirm_at')->nullable()->comment('วันที่ยืนยันแผน');
            $table->integer('created_by')->nullable()->comment('เจ้าหน้าที่ดำเนินการ');
            $table->integer('updated_by')->nullable()->comment('ผู้แกไข');
            $table->timestamps();
            
              
            $table->foreign('draft_id')
                  ->references('id')
                  ->on('tisi_estandard_draft')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');  

           $table->foreign('offer_id')
                  ->references('id')
                  ->on('tisi_estandard_offers')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');         

            $table->foreign('std_type')
                  ->references('id')
                  ->on('bcertify_standard_type')
                  ->onDelete('cascade')
                  ->onUpdate('cascade'); 
 
                  
 
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tisi_estandard_draft_plan');
    }
}
