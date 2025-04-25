<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAppSsoUsersNameEnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->string('name_en',255)->nullable()->after('name')->comment('En ชื่อ/บริษัท');
            $table->string('address_en',255)->nullable()->comment('En เลขที่ อาคาร ชั้น ห้อง ชื่อหมู่บ้าน');
            $table->string('moo_en',80)->nullable()->comment('En หมู่');
            $table->string('soi_en',80)->nullable()->comment('En ตรอก/ซอย');
            $table->string('street_en',80)->nullable()->comment('En ถนน');
            $table->string('subdistrict_en',255)->nullable()->comment('En  แขวง/ตำบล');
            $table->string('district_en',255)->nullable()->comment('En  เขต/อำเภอ');
            $table->string('province_en',255)->nullable()->comment('En จังหวัด');
            $table->string('zipcode_en',5)->nullable()->comment('En รหัสไปรษณีย');
            $table->string('contact_address_en',255)->nullable()->comment('En เลขที่ อาคาร ชั้น ห้อง ชื่อหมู่บ้าน(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_moo_en',80)->nullable()->comment('En หมู่(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_soi_en',80)->nullable()->comment('En ตรอก/ซอย(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_street_en',80)->nullable()->comment('En ถนน(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_subdistrict_en',255)->nullable()->comment('En  แขวง/ตำบล(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_district_en',255)->nullable()->comment('En  เขต/อำเภอ(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_province_en',255)->nullable()->comment('En จังหวัด(ที่อยู่ที่ติดต่อได้)');
            $table->string('contact_zipcode_en',5)->nullable()->comment('En รหัสไปรษณีย(ที่อยู่ที่ติดต่อได้)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sso_users', function (Blueprint $table) {
            $table->dropColumn(['name_en','address_en','moo_en','soi_en','street_en','subdistrict_en','district_en','province_en','zipcode_en',
            'contact_address_en','contact_moo_en','contact_soi_en','contact_street_en','contact_subdistrict_en','contact_district_en','contact_province_en','contact_zipcode_en']);
        });
    }
}
