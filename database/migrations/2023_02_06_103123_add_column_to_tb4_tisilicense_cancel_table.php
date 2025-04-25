<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToTb4TisilicenseCancelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tb4_tisilicense_cancel', function (Blueprint $table) {
            $table->text('input_data')->nullable()->comment('ข้อมูลมาจากระบบงาน - law');
            $table->text('case_number')->nullable()->comment('เลขคดี');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb4_tisilicense_cancel', function (Blueprint $table) {
            $table->dropColumn([
                'input_data','case_number'
            ]);
        });
    }
}
