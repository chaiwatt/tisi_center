<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdjustmentToLawBasicSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->integer('adjustment_type')->nullable()->default('3')->comment('1 ไม่เกิน (ทีมพัฒนา), 2 ช่วงอัตราต่ำสุด / สูงสุด, 3 ไม่มี ');
            $table->decimal('adjustment',30,2)->nullable()->comment('อัตราค่าปรับ');
            $table->decimal('adjustment_max',30,2)->nullable()->comment('อัตราค่าปรับสูงสุด');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_basic_section', function (Blueprint $table) {
            $table->dropColumn(['adjustment_type','adjustment', 'adjustment_max']);
        });
    }
}
