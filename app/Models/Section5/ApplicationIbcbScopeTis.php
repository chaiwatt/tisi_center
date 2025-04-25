<?php

namespace App\Models\Section5;

use App\Models\Basic\Tis;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tis\Standard;
use App\Models\Section5\ApplicationIbcbScope;

class ApplicationIbcbScopeTis extends Model
{

    protected $table = 'section5_application_ibcb_scopes_tis';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ibcb_scope_id',
        'ibcb_scope_detail_id',
        'tis_id',
        'tis_no',
        'tis_name',
        'ibcb_id', 
        'ibcb_code'
    ];

    public function tis_standards(){
        return $this->belongsTo(Tis::class, 'tis_id');
    }

    public function application_ibcb_scopes(){
        return $this->belongsTo(ApplicationIbcbScope::class, 'ibcb_scope_id');
    }

    public function application_ibcb_scope_detail(){
        return $this->belongsTo(ApplicationIbcbScopeDetail::class, 'ibcb_scope_detail_id');
    }

}
