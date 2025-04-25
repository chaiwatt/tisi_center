<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLawSystemCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('law_system_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->nullable()->comment('ขื่อระบบ');
            $table->string('color',255)->nullable()->comment('สีระบบงาน');
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
        Schema::dropIfExists('law_system_categories');
    }
}
