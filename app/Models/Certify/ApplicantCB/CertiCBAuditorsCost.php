<?php

namespace App\Models\Certify\ApplicantCB;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;
use  App\Models\Certify\Applicant\CostDetails;
use App\Models\Bcertify\StatusAuditor;
class CertiCBAuditorsCost  extends Model
{
    protected $table = 'app_certi_cb_auditors_cost';
    protected $primaryKey = 'id';
    protected $fillable = [
                             'auditors_id' , 'detail', 'amount', 'amount_date'
                          ];
   
   public function CostDetailsTo()
   {
       return $this->belongsTo(CostDetails::class,'detail');
   }     
   public function StatusAuditorTo()
   {
     return $this->belongsTo(StatusAuditor::class,'detail');
   }
                   
}
