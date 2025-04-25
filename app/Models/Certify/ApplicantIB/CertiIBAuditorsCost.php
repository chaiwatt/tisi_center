<?php

namespace App\Models\Certify\ApplicantIB;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;
use  App\Models\Certify\Applicant\CostDetails;
use App\Models\Bcertify\StatusAuditor;
class CertiIBAuditorsCost  extends Model
{
    protected $table = 'app_certi_ib_auditors_cost';
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
