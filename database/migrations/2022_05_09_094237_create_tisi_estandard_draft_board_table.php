<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTisiEstandardDraftBoardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tisi_estandard_draft_board', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('draft_id')->comment('TB : tisi_estandard_draft . id');
            $table->unsignedInteger('committee_id')->comment('คณะกรรมการ TB : committee_in_departments . id');
            $table->integer('ordering')->nullable()->comment('การเรียงลำดับ');
            $table->integer('created_by')->nullable()->comment('เจ้าหน้าที่ดำเนินการ');
            $table->integer('updated_by')->nullable()->comment('ผู้แกไข');
            $table->timestamps();

  
            $table->foreign('draft_id')
                  ->references('id')
                  ->on('tisi_estandard_draft')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');  

            $table->foreign('committee_id')
                  ->references('id')
                  ->on('committee_in_departments')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');       
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tisi_estandard_draft_board');
    }
}
