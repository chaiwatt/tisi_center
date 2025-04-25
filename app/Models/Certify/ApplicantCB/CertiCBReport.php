<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;

class CertiCBReport extends Model
{
    protected $table = 'app_certi_cb_report';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id', //TB: app_certi_cb
                            'report_date',
                            'report_status',
                            'status_confirm',
                            'start_date',
                            'end_date',
                            'issue_date',
                            'status_alert',
                            'details',
                            'created_by',
                            'updated_by',
                            'review_approve',
                            'ability_confirm'
                            ];
    public function CertiCBCostTo()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }


     //รายงาน  ขอบข่ายที่ได้รับการเห็นชอบ
     public function FileAttachReport1To()
     {
         $tb = new CertiCBReport;
         return $this->belongsTo(CertiCBAttachAll::class, 'id','ref_id')
                     ->where('table_name',$tb->getTable())
                     ->where('file_section',1)
                     ->orderby('id','desc');
     }
     //ไฟล์แนบ
     public function  FileAttachReport2Many()
     {
        $tb = new CertiCBReport;
        return $this->hasMany(CertiCBAttachAll::class, 'ref_id','id')
                    ->select('id','file_desc','file','file_client_name')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2);
     }                           
}
