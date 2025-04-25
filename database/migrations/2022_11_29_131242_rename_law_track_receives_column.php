<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameLawTrackReceivesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->renameColumn('book_number', 'book_no');
            $table->renameColumn('receive_number', 'receive_no');
            $table->renameColumn('subject', 'title');

            $table->renameColumn('status', 'status_job_track_id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('law_track_receives', function (Blueprint $table) {
            $table->renameColumn('book_no', 'book_number');
            $table->renameColumn('receive_no', 'receive_number');
            $table->renameColumn('title', 'subject');
            $table->renameColumn('status', 'status_job_track_id');
        });
    }
}
