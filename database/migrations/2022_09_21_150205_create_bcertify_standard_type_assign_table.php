<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcertifyStandardTypeAssignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bcertify_standard_type_assign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('bc_standard_type_id')->nullable()->comment('TB : bcertify_standard_type .ID');
            $table->integer('ordering')->nullable()->comment('1. ผอ. เห็นคำขอก่อน , 2. ผอ. มอบหมายให้ ผก. , 3. ผก. มอบหมายเจ้าหน้าที่ ภายในกลุ่ม ได้');
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
        Schema::dropIfExists('bcertify_standard_type_assign');
    }
}
