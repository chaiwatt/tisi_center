<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeInCertificateExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            DB::statement("ALTER TABLE `certificate_exports` CHANGE `certificate_date_start` `certificate_date_start` DATE NULL DEFAULT NULL;");
            DB::statement("ALTER TABLE `certificate_exports` CHANGE `certificate_date_end` `certificate_date_end` DATE NULL DEFAULT NULL;");
            DB::statement("ALTER TABLE `certificate_exports` CHANGE `certificate_date_first` `certificate_date_first` DATE NULL DEFAULT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('certificate_exports', function (Blueprint $table) {
            $table->string('certificate_date_start')->nullable()->change();
            $table->string('certificate_date_end')->nullable()->change();
            $table->string('certificate_date_first')->nullable()->change();
        });
    }
}
