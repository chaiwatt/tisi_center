<?php

namespace App\Console\Commands;
use HP;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Bcertify\CbRequestRejectTracking;

class CheckCbRejectDate extends Command
{
    protected $signature = 'check:cb-reject-date';
    protected $description = 'ตรวจสอบ Cb Request ที่เกิน 30 วัน';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        $thresholdDate = Carbon::now()->subDays(30);

        // ค้นหารายการที่เกิน 30 วัน
        $expiredEntries = CbRequestRejectTracking::where('date', '<=', $thresholdDate)->get();

        foreach ($expiredEntries as $entry) {
            $app_certi_cb = CertiCb::find($entry->app_certi_cb_id);  
            HP::CancelCertiCb($app_certi_cb,'ยกเลิกคำขออัตโนมัติ เนื่องจากไม่มีการแก้ใขภายใน 30 วัน');
        }

        $this->info('ตรวจสอบ Cb Request ที่เกิน 30 วัน เสร็จสิ้น');
    }
}
