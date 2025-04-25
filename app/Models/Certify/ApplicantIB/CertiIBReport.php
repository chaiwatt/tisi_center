<?php

namespace App\Models\Certify\ApplicantIB;

use Illuminate\Database\Eloquent\Model;

class CertiIBReport extends Model
{
    protected $table = 'app_certi_ib_report';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_ib_id', //TB: app_certi_cb
                            'report_date',
                            'report_status',
                            'status_confirm',
                            'start_date',
                            'end_date',
                            'status_alert',
                            'details',
                            'created_by',
                            'updated_by',
                            'review_approve',
                            'ability_confirm'
                            ];

                            
    public function CertiIBCostTo()
    {
        return $this->belongsTo(CertiIb::class,'app_certi_ib_id');
    }
     //รายงาน Scope
     public function FileAttachReport1To()
     {
         $tb = new CertiIBReport;
         return $this->belongsTo(CertiIBAttachAll::class, 'id','ref_id')
                     ->where('table_name',$tb->getTable())
                     ->where('file_section',1)
                     ->orderby('id','desc');
     }
     //ไฟล์แนบ
     public function  FileAttachReport2Many()
     {
        $tb = new CertiIBReport;
        return $this->hasMany(CertiIBAttachAll::class, 'ref_id','id')
                    ->select('id','file_desc','file','file_client_name')
                    ->where('table_name',$tb->getTable())
                    ->where('file_section',2);
     }                           
}
