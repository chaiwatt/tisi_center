<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Section5\LabsCertify;
use App\Models\Section5\LabsScopeLog;
use App\User;

class LabsCertifyLog extends Model
{
    protected $table = 'section5_labs_certificates_logs';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'labs_certify_id',
        'old_end_date',
        'new_end_date',
        'app_cert_lab_file_all_id',
        'created_by'
        
    ];

    public function lab_certify(){
        return $this->belongsTo(LabsCertify::class, 'labs_certify_id', 'id');
    }  

    public function scope_logs(){
        return $this->hasMany(LabsScopeLog::class, 'labs_certify_id', 'labs_certify_id')->where('app_cert_lab_file_all_id', $this->app_cert_lab_file_all_id);
    }  
    
    public function user_created() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCreatedNameAttribute() {
        return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
    }
  
}
