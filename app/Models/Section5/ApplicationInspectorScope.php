<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;

class ApplicationInspectorScope extends Model
{
    protected $table = 'section5_application_inspectors_scope';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'application_id',
        'application_no',
        'branch_id',
        'branch_group_id',
        'created_by',
        'updated_by',
        'audit_result',
        'remark'
    ];

    public function bs_branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function bs_branch_group(){
        return $this->belongsTo(BranchGroup::class,  'branch_group_id');
    }

    public function scope_tis(){
        return $this->hasMany(ApplicationInspectorScopeTis::class, 'inspector_scope_id');
    }

    public function getBranchGroupTitleAttribute() {
        return @$this->bs_branch_group->title;
    }

    public function getBranchTitleAttribute() {
        return @$this->bs_branch->title;
    }

}
