<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteeListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_committee_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('committee_special_id')->nullable()->comment('ID : bcertify_committee_specials');
            $table->integer('expert_id')->nullable()->comment('ID : register_experts');
            $table->string('expert_name',255)->nullable()->comment('ชื่อคณะกรรมการ');
            $table->string('department_name',255)->nullable()->comment('หน่วยงาน');
            $table->string('committee_qualified',255)->nullable()->comment('ประเภทคณะกรรมการ : 1=ผู้ทรงคุณวุฒิ 2=ผู้แทนหลัก 3=ผู้แทนสำรอง 4=ฝ่ายเลขานุการ');
            $table->string('committee_position',255)->nullable()->comment('ประธานกรรมการ : 1=ประธาน 2=กรรมการ 3=กรรมการและเลขานุการ 4=กรรมการและผู้ช่วยเลขานุการ');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก (จนท.) TB : user_register');
            $table->integer('updated_by')->nullable()->comment('ผู้แก้ไข');
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
        Schema::dropIfExists('bcertify_committee_lists');
    }
}
