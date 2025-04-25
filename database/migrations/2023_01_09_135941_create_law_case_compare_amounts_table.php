<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCaseCompareAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_compare_amounts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('law_case_compare_id')->nullable()->comment('ID : law_case_compare');
            $table->text('detail_amounts')->nullable()->comment('รายละเอียดผลเปรียบเทียบปรับ');
            $table->decimal('amount',12,2)->nullable()->comment('จำนวนเงิน');
            $table->integer('created_by')->nullable()->comment('ผู้บันทึก ID : user_register');
            $table->foreign('law_case_compare_id')
                    ->references('id')
                    ->on('law_case_compare')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('law_case_compare_amounts');
    }
}
