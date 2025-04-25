<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\BranchGroup;
use App\Models\Section5\ApplicationInspectorRegister;

class ApplicationInspectorRegisterSub extends Model
{
    
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'sso_application_inspector_register_subs';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
                            'inspector_register_id',
                            'branch_group_id',
                            'branch',
                            'created_by',
                            'updated_by'
                        ];


    public function sso_application_inspector_registers(){
        return $this->belongsTo(ApplicationInspectorRegister::class, 'branch_group_id');
    }

    public function basic_branch_groups(){
        return $this->belongsTo(BranchGroup::class, 'inspector_register_id');
    }

    public function getBranchGroupNameAttribute() {
        return !empty($this->basic_branch_groups->title)?$this->basic_branch_groups->title:null;
    }
}
