<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Certify\Applicant\Notice;
use App\Models\Certify\Applicant\CertiLab;
use HP;

class CheckLabNotice extends Command
{
    protected $signature = 'check:lab-notice';
    protected $description = 'ตรวจสอบวันข้อบกพร่อง';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiredNotices = Notice::whereNotNull('notice_confirm_date')
                    ->where('report_status', 1)
                    ->where('degree', '!=', 7)
                    ->where('submit_type', "confirm")
                    ->whereHas('applicant', function ($query) {
                        $query->where('status', '!=', 4);
                    })
                    ->whereDoesntHave('applicant.notices', function ($subQuery) {
                        $subQuery->whereRaw('DATE_ADD(notice_confirm_date, INTERVAL notice_duration DAY) > ?', [now()]);
                    })
                    ->get();

        // Pluck และ Unique
        $uniqueAppCertiLabIds = $expiredNotices->pluck('app_certi_lab_id')->unique();
        $certiLabs = CertiLab::whereIn('id',$uniqueAppCertiLabIds)->get();
        if($certiLabs->count() != 0)
        {
            foreach($certiLabs as $certiLab)
            {
                HP::CancelCertiLab($certiLab,'ยกเลิกคำขออัตโนมัติ เนื่องจากแก้ไขข้อบกพร่องเกิน 90 วัน');
                $this->info($certiLab->lab_name. ' ยกเลิกคำขออัตโนมัติ เนื่องจากแก้ไขข้อบกพร่องเกิน 90 วัน');
            }
            
        }
        $this->info("ตรวจสอบแกไขข้อบกพร่อง 90 วัน");
    }
}
