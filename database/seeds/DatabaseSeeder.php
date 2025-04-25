<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);
        $this->call(ConfigsTableSeeder::class);
        $this->call(FakeDataSeeder::class);
        $this->call(CertificateAlertTableSeeder::class);
        $this->call(ConfigTermsTableSeeder::class);
        /* ยกมาจาก SSO*/
        $this->call(BasicPrefixTableSeeder::class);
        $this->call(BasicZipcodeTableSeeder::class);
        $this->call(SettingSystemsTableSeeder::class);
        // $this->call(SsoUserSeeder::class);
        $this->call(TrackingAuditorsStepSeeder::class);
        $this->call(TrackingStatusSeeder::class);
        $this->call(SettingFeeSeeder::class);
        $this->call(ApplicationLabStatusTableSeeder::class);
        $this->call(ApplicationIBCBStatusTableSeeder::class);
         /*ข้อมูลพื้นฐานกฏหมาย*/
        $this->call(LawArrestSeeder::class);
        $this->call(LawDivisionTypeSeeder::class);
        $this->call(LawProcessTypeSeeder::class);
        $this->call(LawRewardGroupSeeder::class);
        $this->call(LawTypeFileSeeder::class);
        $this->call(LawSystemCategorySeeder::class);
        /*เพิ่มเติมโดย NPC*/

        // $this->call(SiteTypeTableSeeder::class);
        // $this->call(CalibrationBranchInstrumentGroupSeeder::class);
        // $this->call(CalibrationBranchInstrumentSeeder::class);
        // $this->call(CalibrationBranchParam1TableSeeder::class);
        // $this->call(CalibrationBranchParam2TableSeeder::class);
        // $this->call(IbBranchesTableSeeder::class);

    }
}
