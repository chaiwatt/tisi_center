<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Sso\User AS SSO_User;

class CertiCBLogMail extends Model
{
    protected $table = 'app_certi_cb_log_mail';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id', //TB: app_certi_cb
                            'system',
                            'table_name',
                            'ref_id',
                            'name',
                            'details_one',
                            'details_two',
                            'details_three',
                            'details_four',
                            'link_url',
                            'attachs',
                            'files',
                            'contact',
                            'officer',
                            'operator',
                            'token'
                            ];

    public function EsurvTraderTo()
    {
        return $this->belongsTo(SSO_User::class, 'operator');
    }

    public function getEsurvTraderTitleAttribute() {
        return @$this->EsurvTraderTo->name ?? '-';
    }

    public function UserTo()
    {
        return $this->belongsTo(User::class,'officer','runrecno');
    }
    public function CertiCbCostTo()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }

    public function getDataSystemAttribute() {
        $details = ['1'=>'ยื่นคำขอรับใบรับรอง',
                    '2'=>'มอบหมายตรวจสอบ',
                    '3'=> 'ขอเอกสารเพิ่มเติม',
                    '4'=> 'ยกเลิกคำขอ',
                    '5'=> 'ไม่ผ่านการตรวจสอบ',
                    '6'=>'รับคำขอ',
                    '7'=>'การประมาณค่าใช้จ่าย',
                    '8'=>'แต่งตั้งคณะผู้ตรวจประเมิน'
                  ];
        return  array_key_exists($this->system,$details) ?  $details[$this->system] : '-';
    }

}
