<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Basic\Branchgroupstis;
use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\User;
use App\Models\Sso\User AS SSO_USER;

use App\Models\Section5\InspectorsScopeTis;
class InspectorsScope extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_inspectors_scopes';

    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inspectors_id',
        'inspectors_code',
        'branch_id',
        'branch_group_id',
        'agency_id',
        'agency_taxid',
        'start_date',
        'end_date',
        'state',
        'application_id',
        'ref_inspector_application_no',
        'created_by',
        'updated_by',
        'type',
        'close_state_date', 
        'close_remarks', 
        'close_by'
    ];

    public function bs_branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function bs_branch_group(){
        return $this->belongsTo(BranchGroup::class,  'branch_group_id');
    }

    public function bs_branch_groups_tis(){
        return $this->belongsTo(Branchgroupstis::class, 'branch_group_id', 'branch_groups_id');
    }

    public function agency_user() {
        return $this->belongsTo(SSO_USER::class, 'agency_id');
    }

    public function getBranchGroupTitleAttribute(){
        return @$this->bs_branch_group->title;
    }

    public function getTisTisNoTitleAttribute() {
  		return @$this->bs_branch_groups_tis->TisTisNoTitle;
  	}

    public function getBranchTitleAttribute() {
        return @$this->bs_branch->title;
    }

    public function ins_scopes_tis(){
        return $this->hasMany(InspectorsScopeTis::class, 'inspector_scope_id');
    }



}
