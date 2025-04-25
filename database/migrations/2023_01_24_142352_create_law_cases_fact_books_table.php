<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesFactBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_cases_fact_books', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();

            $table->string('fact_book_numbers',255)->nullable()->comment('เอกสารหมายเลข');
            $table->text('fact_book_date')->nullable()->comment('วันที่จัดทำ');
            $table->text('fact_offend_name')->nullable()->comment('ผู้กระทำความผิด');
            $table->date('fact_detection_date')->nullable()->comment('วันที่ตรวจพบ');
            $table->text('fact_locale')->nullable()->comment('สถานที่เกิดเหตุ');
            $table->text('fact_maker_by')->nullable()->comment('ผู้จัดทำหนังสือ');
            $table->text('fact_lawyer_by')->nullable()->comment('นิติกรเจ้าของสำนวน');
            
            $table->unsignedInteger('law_cases_id')->nullable()->comment('ID ตาราง law_cases');
            $table->foreign('law_cases_id')
                    ->references('id')
                    ->on('law_cases')
                    ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('law_cases_fact_books');
    }
}
