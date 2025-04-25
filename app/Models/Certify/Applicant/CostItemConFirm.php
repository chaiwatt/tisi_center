<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;
use  App\Models\Certify\Applicant\CostDetails;
use App\Models\Bcertify\StatusAuditor;
class CostItemConFirm extends Model
{
    protected $table = "app_certi_lab_cost_items_confirm";
    protected $fillable = [
        'app_certi_lab_id', 'board_auditors_id', 'desc', 'amount','amount_date'
    ];
    public function cost_details() {
        return $this->belongsTo(CostDetails::class, 'desc');
    }
    public function StatusAuditorTo()
    {
      return $this->belongsTo(StatusAuditor::class,'desc');
    }
}
