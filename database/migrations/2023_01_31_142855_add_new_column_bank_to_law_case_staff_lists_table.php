<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnBankToLawCaseStaffListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('law_case_staff_lists', function(Blueprint $table)
        {
            $table->renameColumn('law_case_impound_id', 'law_cases_id');

        });

        Schema::table('law_case_staff_lists', function (Blueprint $table) {

            $table->string('taxid',50)->nullable()->comment('TAXID / Gen จากระบบ');
            $table->string('mobile',50)->nullable()->comment('เบอร์มือถือ');
            $table->string('email',50)->nullable()->comment('อีเมล');
            $table->text('address')->nullable()->comment('ที่อยู่')->change();

            $table->integer('basic_bank_id')->nullable()->comment('ID : basic_bank');
            $table->string('bank_account_name',255)->nullable()->comment('ชื่อบัญชี');
            $table->string('bank_account_number',255)->nullable()->comment('เลขบัญชี'); 

            $table->string('sub_department_id',255)->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department')->change();

            $table->unsignedInteger('law_cases_id')->nullable()->comment('ID ตาราง law_cases')->change();
            $table->foreign('law_cases_id')
                    ->references('id')
                    ->on('law_cases')
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

        Schema::table('law_case_staff_lists', function(Blueprint $table)
        {
            $table->renameColumn('law_cases_id', 'law_case_impound_id');

        });

        Schema::table('law_case_staff_lists', function (Blueprint $table) {
            $table->dropColumn(['basic_bank_id','bank_account_name', 'bank_account_number','taxid']);
            $table->integer('sub_department_id')->nullable()->comment('เจ้าของคดี : กอง/กลุ่ม (กรณีภายใน) ตารางอ้างอิง sub_department')->change();
            $table->integer('law_case_impound_id')->comment('ID : ตาราง law_case')->change();
            $table->string('address',255)->nullable()->comment('ที่อยู่')->change();
        });
    }
}
