<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawBasicStatusJobTrackTableToLawBasicStatusOperateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('law_basic_status_job_tracks', 'law_basic_status_operate');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::rename('law_basic_status_operate', 'law_basic_status_job_tracks');
    }
}
