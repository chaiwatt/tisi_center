<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnToLawBookManageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_book_manage', function (Blueprint $table) {
            $table->text('owner')->nullable()->comment('เจ้าของเรื่อง');
            $table->text('lawyer')->nullable()->comment('นิติกร');
            $table->date('operation_date')->nullable()->comment('วันที่ดำเนินการ');
            $table->integer('ordering')->nullable()->comment('ลำดับ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_book_manage', function (Blueprint $table) {
            $table->dropColumn([ 'owner', 'lawyer', 'operation_date', 'ordering' ]);
        });
    }
}
