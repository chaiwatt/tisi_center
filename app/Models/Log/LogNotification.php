<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Sso\User AS SSO_USER;

//Model Status
use App\Models\Section5\ApplicationInspectorStatus;
use App\Models\Section5\ApplicationLabStatus;
use App\Models\Section5\ApplicationIbcbStatus;

//Model Application
use App\Models\Section5\ApplicationInspector;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationIbcb;

class LogNotification extends Model
{
    protected $table = 'logs_notifications';

    protected $primaryKey = 'id';

    protected $fillable = [ 

        'id',
        'title',
        'details',
        'ref_applition_no',
        'ref_table',
        'ref_id',
        'status',
        'site',
        'root_site',
        'url',
        'read',
        'users_id' ,
        'read_all',
        'type',
        'ref_table_user'

    ];

    public function user_created_sso(){
        return $this->belongsTo(SSO_USER::class, 'users_id');
    }

    public function user_created(){
        return $this->belongsTo(User::class, 'users_id');
    }

    public function app_inspector(){
        return $this->belongsTo(ApplicationInspector::class, 'ref_id','id')->where('application_no', $this->ref_applition_no);
    }

    public function app_lab(){
        return $this->belongsTo(ApplicationLab::class, 'ref_id','id')->where('application_no', $this->ref_applition_no);
    }

    public function app_ibcb(){
        return $this->belongsTo(ApplicationIbcb::class, 'ref_id','id')->where('application_no', $this->ref_applition_no);
    }

}
