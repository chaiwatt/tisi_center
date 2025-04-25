<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->text('group')->nullable()->comment('id : setting_systems');
            $table->text('description')->nullable()->comment('คำอธิบาย');
            $table->boolean('state')->nullable()->comment('สถานะ 1 = ใช้งาน, 2 = ไม่ใช้งาน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn(['group','description','state']);  
            
        });
    }
}
