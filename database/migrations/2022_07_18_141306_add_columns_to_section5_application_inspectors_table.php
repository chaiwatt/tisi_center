<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToSection5ApplicationInspectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->text('remarks_delete')->nullable()->comment('ระบุเหตุผล');
            $table->integer('delete_by')->nullable()->comment('ผู้ลบข้อมูล');
            $table->timestamp('delete_at')->nullable()->comment('วันที่ลบ');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('section5_application_inspectors', function (Blueprint $table) {
            $table->dropColumn(['remarks_delete', 'delete_by', 'delete_at']);
        });
    }
}
