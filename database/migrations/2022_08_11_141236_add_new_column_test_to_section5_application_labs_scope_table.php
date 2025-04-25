<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnTestToSection5ApplicationLabsScopeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->text('test_duration')->nullable()->comment('ระยะเวลาทดสอบ (วัน)');
            $table->text('test_price')->nullable()->comment('ราคาทดสอบ/ชุดทดสอบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_labs_scope', function (Blueprint $table) {
            $table->dropColumn(['test_duration','test_price']);
        });
    }
}
