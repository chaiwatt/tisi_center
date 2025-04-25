<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBcertifyCommitteeSpecialsProductGroupIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bcertify_committee_specials', function (Blueprint $table) {
            $table->string('faculty',255)->nullable()->after('committee_group')->comment('ชื่อคณะกรรมการ');
            $table->string('faculty_no',255)->nullable()->after('faculty')->comment('คณะที่');
            $table->integer('product_group_id')->nullable()->after('faculty_no')->comment('กลุ่มผลิตภัณฑ์/สาข TB : basic_product_groups');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bcertify_committee_specials', function (Blueprint $table) {
            $table->dropColumn(['faculty','faculty_no','product_group_id']);
        });
    }
}
