<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfigsEvidenceSystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configs_evidence_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title')->nullable()->comment('ชื่อระบบ');
            $table->text('title_en')->nullable()->comment('ชื่อระบบ');
            $table->string('code')->nullable()->comment('รหัส');
            $table->integer('ordering')->nullable();
            $table->integer('state')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('configs_evidence_systems');
    }
}
