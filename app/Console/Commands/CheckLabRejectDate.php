<?php

namespace App\Console\Commands;
use HP;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\CertificateHistory;
use App\Models\Bcertify\LabRequestRejectTracking;

class CheckLabRejectDate extends Command
{
    protected $signature = 'check:lab-reject-date';
    protected $description = 'ตรวจสอบ Lab Request ที่เกิน 30 วัน';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);

        // ค้นหารายการที่เกิน 30 วัน
        $expiredEntries = LabRequestRejectTracking::where('date', '<=', $thresholdDate)->get();

        foreach ($expiredEntries as $entry) {
            $app_certi_lab = CertiLab::find($entry->app_certi_lab_id);
 
            HP::CancelCertiLab($app_certi_lab,'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีการแก้ใขภายใน 30 วัน');
        }

        $this->info('ตรวจสอบ Lab Request ที่เกิน 30 วัน เสร็จสิ้น');
    }
}
