<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchgroupstisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_branch_groups_tis', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('branch_groups_id')->nullable()->comment('id หมวดหมู่อุตสาหกรรม/สาขา (ตาราง basic_branch_groups)');
            $table->integer('tis_id')->nullable()->comment('ID มอก.');
            $table->string('tis_tisno')->nullable()->comment('เลขที่ มอก.');
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
        Schema::dropIfExists('basic_branch_groups_tis');
    }
}
