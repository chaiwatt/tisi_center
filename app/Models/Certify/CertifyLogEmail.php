<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Sso\User AS SSO_User;
use App\User;
class CertifyLogEmail extends Model
{
    use Sortable;
    protected $table = "app_certi_log_email";
    protected $primaryKey = 'id';
    protected $fillable = ['app_no','app_id', 'app_table', 'ref_id', 'ref_table','certify','subject','status','detail','email','email_to', 'email_cc','email_reply','user_id', 'agent_id','created_by','updated_by' ,'attach'];


    public function sso_user_to()
    {
        return $this->belongsTo(SSO_User::class, 'user_id');
    }
    public function sso_user_agent_to()
    {
        return $this->belongsTo(SSO_User::class, 'agent_id');
    }
    public function getCertifyTitleAttribute() {
        $data =  ['1'=>'ห้องปฏิบัติการ','2'=>'หน่วยตรวจสอบ','3'=>'หน่วยรับรอง','4'=>'ห้องปฏิบัติการ(ต่อตาม)','5'=>'หน่วยตรวจสอบ(ต่อตาม)','6'=>'หน่วยรับรอง(ต่อตาม)'];
        return  array_key_exists($this->certify,$data) ? $data[$this->certify] : null;
   }

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
}
