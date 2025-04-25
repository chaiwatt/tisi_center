<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBsection5WorkgroupIbBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bsection5_workgroup_ib_branch', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('workgroup_id')->nullable()->comment('id เจ้าหน้าที่ผู้รับผิดชอบ (เชื่อมกับตาราง bsection5_workgroup_ib)');
            $table->integer('branch_id')->nullable()->comment('id ตาราง basic_branches');
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
        Schema::dropIfExists('bsection5_workgroup_ib_branch');
    }
}
