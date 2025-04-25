<?php

namespace App\Models\Certificate;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Bcertify\StatusAuditor;
class  TrackingAuditorsList extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking_auditors_list";
    protected $primaryKey = 'id';
    protected $fillable = ['auditors_status_id', 'user_id', 'temp_users', 'temp_departments','auditors_id' ,'status_id'];
    public function status_auditor_to()
    {
      return $this->belongsTo(StatusAuditor::class,'status_id');
    }
    
    public function getStatusAuditorTitleAttribute() { 
     
      return @$this->status_auditor_to->title ?? '-';
    }
}
