<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\LabsScope;


class LabsScopeLog extends Model
{
    protected $table = 'section5_labs_scopes_logs';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'labs_scopes_id',
        'old_end_date',
        'new_end_date',
        'app_cert_lab_file_all_id',
        'labs_certify_id',
        'created_by'
  
    ];

    public function labs_scopes(){
        return $this->belongsTo(LabsScope::class, 'labs_scopes_id', 'id');
    }  

    
}
