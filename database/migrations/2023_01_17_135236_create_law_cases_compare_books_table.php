<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawCasesCompareBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_case_compare_book', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->text('book_number')->nullable()->comment('เลขที่หนังสือ');
            $table->text('book_date')->nullable()->comment('ลงวันที่');

            $table->text('title')->nullable()->comment('เรื่อง');
            $table->text('send_to')->nullable()->comment('เรียน');
            $table->text('refer')->nullable()->comment('อ้างถึง (Json)');

            $table->text('offend_name')->nullable()->comment('ผู้กระทำความผิด : ชื่อ-สกุล');
            $table->text('offend_address')->nullable()->comment('ผู้กระทำความผิด: ที่ตั้งสำนักงานใหญ่');

            $table->text('detail')->nullable()->comment('รายละเอียดก่อนปรับ');
            $table->decimal('amount', 30, 2)->nullable()->comment('รวมเงินค่าปรับ');
            
            $table->integer('created_by')->unsigned();
            $table->integer('updated_by')->nullable();
            $table->unsignedInteger('law_case_compare_id')->nullable()->comment('ID ตาราง law_case_compare');

            $table->foreign('law_case_compare_id')
                    ->references('id')
                    ->on('law_case_compare')
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
        Schema::dropIfExists('law_case_compare_book');
    }
}
