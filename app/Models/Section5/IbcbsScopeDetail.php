<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Branch;
use Illuminate\Database\Eloquent\Scope;

class IbcbsScopeDetail extends Model
{
    protected $table = 'section5_ibcbs_scopes_details';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'ibcb_id',
        'ibcb_code',
        'ibcb_scope_id',
        'branch_id',
        'audit_result',
        'type'
    ];

    public function bs_branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function ibcb(){
        return $this->belongsTo(Ibcbs::class, 'ibcb_id');
    }

    public function scope_tis(){
        return $this->hasMany(IbcbsScopeTis::class, 'ibcb_scope_detail_id');
    }

    public function getScopeBranchNameAttribute() {
        return $this->bs_branch->title??'n/a';
    }
    
}
