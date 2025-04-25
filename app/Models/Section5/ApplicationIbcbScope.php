<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Basic\BranchGroup;
use App\Models\Section5\ApplicationIbcbScopeDetail;
use Illuminate\Support\Facades\DB;

class ApplicationIbcbScope extends Model
{

    protected $table = 'section5_application_ibcb_scopes';

    protected $primaryKey = 'id';

    protected $fillable = [
        'application_id',
        'application_no',
        'branch_group_id',
        'isic_no',
        'created_by',
        'updated_by',
        'ibcb_id', 
        'ibcb_code'
    ];

    public function bs_branch_group(){
        return $this->belongsTo(BranchGroup::class,  'branch_group_id');
    }

    public function scopes_details(){
        return $this->hasMany(ApplicationIbcbScopeDetail::class, 'ibcb_scope_id');
    }

    public function ibcb_scopes_tis(){
        return $this->hasMany(ApplicationIbcbScopeTis::class, 'ibcb_scope_id');
    }

    public function getTisNameCommaAttribute(){
        return  @$this->ibcb_scopes_tis()->select(DB::raw("CONCAT(tis_no,' : ',tis_name ) AS tis_name"))->pluck('tis_name')->implode(', ');
    }

    public function getTisNumberCommaAttribute(){
        return  @$this->ibcb_scopes_tis()->pluck('tis_no')->implode(', ');
    }

    public function getScopeBranchsAttribute(){

        $app_scope = $this->scopes_details()->select('branch_id')->groupBy('branch_id')->get();
        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch = $item->bs_branch;

            if( !is_null($bs_branch) ){
                $list[] = $bs_branch->title;
            }

        }

        $txt = implode( ', ',  $list );

        return $txt;
    }

    public function getScopeBranchsForAuditResultAttribute(){

        $app_scope = $this->scopes_details()->select('branch_id')->groupBy('branch_id')->get();
        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch = $item->bs_branch;

            if( !is_null($bs_branch) ){
                $list[] = '<input class="form-control check" data-checkbox="icheckbox_flat-green" type="checkbox" name="branch_id[]" value="'.$bs_branch->id.'"> '.$bs_branch->title;
            }

        }

        $txt = implode( ',<br>',  $list );

        return $txt;
    }

    public function getBranchGroupTitleAttribute() {
        return @$this->bs_branch_group->title;
    }

}
