<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunAllSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:all-schedules';
    protected $description = 'รันทุกคำสั่ง Command ที่กำหนดไว้';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    // การรัน php artisan run:all-schedules
    public function handle()
    {
        $this->call('check:lab-reject-date'); //ยกเลิกคำ LAB ขอถ้าไม่แก้ภายใน 30 วัน
        $this->call('check:lab-payin-one'); //อัพเดทสถานการจ่ายเงิน payin1 lab ผ่าน api
        $this->call('command:generate-payin-lab'); //สร้าง payin1 (lab) ใหม่ 
        $this->call('check:lab-payin-two'); ////อัพเดทสถานการจ่ายเงิน payin2 lab ผ่าน api
        $this->call('command:generate-payin-two-lab'); //สร้าง payin2 (lab) ใหม่ 
        $this->call('check:lab-notice'); //ยกเลิกคำขอ LAB ถ้าไม่แก้ข้อบกพร่องทุก transaction ภายใน 90 วัน
        
        $this->call('check:tracking-lab-payin-one'); //อัพเดทสถานการจ่ายเงิน payin1 ตรวจติดตาม lab ผ่าน api


        $this->call('check:cb-reject-date'); //ยกเลิกคำ CB ขอถ้าไม่แก้ภายใน 30 วัน
        $this->call('check:lab-payin-one-cb'); //อัพเดทสถานการจ่ายเงิน payin1 cb ผ่าน api
        $this->call('check:cb-payin-two'); //อัพเดทสถานการจ่ายเงิน payin2 cb ผ่าน api
        $this->call('check:ib-payin-one-ib'); //อัพเดทสถานการจ่ายเงิน payin1 ib ผ่าน api
        $this->call('check:ib-payin-two'); 
        
        
        
        
    }
}
