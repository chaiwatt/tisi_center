<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\IbcbsScopeDetail;
use App\Models\Section5\IbcbsScopeTis;
use App\Models\Basic\BranchGroup;
class IbcbsScope extends Model
{
    protected $table = 'section5_ibcbs_scopes';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'ibcb_id',
        'ibcb_code',
        'branch_group_id',
        'isic_no',
        'start_date',
        'end_date',
        'state',
        'ref_ibcb_application_no',
        'created_by',
        'updated_by',
        'type',
        'close_date',
        'close_remarks',
        'close_by'
        
    ];

    public function bs_branch_group(){
        return $this->belongsTo(BranchGroup::class,  'branch_group_id');
    }  

    public function getScopeBranchGroupNameAttribute() {
        return $this->bs_branch_group->title??'n/a';
    }

    public function scopes_details(){
        return $this->hasMany(IbcbsScopeDetail::class,  'ibcb_scope_id', 'id');
    }

    public function scopes_tis(){
        return $this->hasMany(IbcbsScopeTis::class,  'ibcb_scope_id', 'id');
    }

}
