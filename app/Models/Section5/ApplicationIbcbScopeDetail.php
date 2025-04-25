<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Branch;

class ApplicationIbcbScopeDetail extends Model
{
    protected $table = 'section5_application_ibcb_scopes_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ibcb_scope_id',
        'application_no',
        'branch_id',
        'audit_result',
        'remark',
        'ibcb_id', 
        'ibcb_code'
    ];

    public function bs_branch(){
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function ibcb_scopes_tis(){
        return $this->hasMany(ApplicationIbcbScopeTis::class, 'ibcb_scope_detail_id');
    }

    public function getBranchTitleAttribute() {
        return @$this->bs_branch->title;
    }
}
