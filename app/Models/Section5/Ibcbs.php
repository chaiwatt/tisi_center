<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;

use App\Models\Section5\IbcbsScope;
use App\Models\Section5\IbcbsCertificate;
use App\Models\Section5\IbcbsHistory;

class Ibcbs extends Model
{
    protected $table = 'section5_ibcbs';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ibcb_code',
        'ibcb_type',
        'name',
        'initial',
        'taxid',
        'ibcb_name',
        'ibcb_user_id',
        'ibcb_address',
        'ibcb_building',
        'ibcb_moo',
        'ibcb_soi',
        'ibcb_road',
        'ibcb_subdistrict_id',
        'ibcb_district_id',
        'ibcb_province_id',
        'ibcb_zipcode',
        'ibcb_phone',
        'ibcb_fax',
        'co_name',
        'co_position',
        'co_mobile',
        'co_phone',
        'co_fax',
        'co_email',
        'ibcb_start_date',
        'ibcb_end_date',
        'state',
        'ref_ibcb_application_no',
        'created_by',
        'updated_by',
        'type'
    ];

    public function ibcb_subdistrict(){
        return $this->belongsTo(District::class, 'ibcb_subdistrict_id');
    }

    public function ibcb_district(){
        return $this->belongsTo(Amphur::class,  'ibcb_district_id');
    }

    public function ibcb_province(){
        return $this->belongsTo(Province::class, 'ibcb_province_id');
    }

    public function getIbcbSubdistrictNameAttribute() {
        return !empty($this->ibcb_subdistrict)?$this->ibcb_subdistrict->DISTRICT_NAME:null;
    }

    public function getIbcbDistrictNameAttribute() {
        return !empty($this->ibcb_district)?$this->ibcb_district->AMPHUR_NAME:null;
    }

    public function getIbcbProvinceNameAttribute() {
        return !empty($this->ibcb_province)?$this->ibcb_province->PROVINCE_NAME:null;
    }

    public function getIbcbPostcodeNameAttribute() {
        return !empty($this->ibcb_zipcode)?$this->ibcb_zipcode:null;
    }

    /* Btn Switch Input*/
    public function getStateIconAttribute(){

        $max_data = $this->scopes_group()->whereNotNull('end_date')->orderBy('end_date','desc')->first();

        $StateHtml = [ 1 => '<i class="fa fa-check-circle fa-lg text-success"></i>', 2 => '<i class="fa fa-times-circle fa-lg text-danger"></i>' ];

        $btn = '';

        if( !empty( $max_data ) ){
            return  ( !empty($max_data->end_date) && $max_data->end_date >= date('Y-m-d') ) && array_key_exists( $max_data->state, $StateHtml )?$StateHtml[ $max_data->state ]:'<i class="fa fa-times-circle fa-lg text-danger"></i>';
        }else{
            return '<i class="fa fa-times-circle fa-lg text-danger"></i>';
        }

        return $btn;

  	}

    public function scopes_group(){
        return $this->hasMany(IbcbsScope::class, 'ibcb_id');
    }

    //ขอบข่ายตามมาตรฐานที่ active อยู่
    public function scope_standard_active(){
        $date_now  = date('Y-m-d');
        $scope_ids = $this->scopes_group()
                          ->where('state', 1)
                          ->whereDate('start_date', '<=', $date_now)
                          ->whereDate('end_date', '>=', $date_now)
                          ->pluck('id');
        return IbcbsScopeTis::whereIn('ibcb_scope_id', $scope_ids);
    }

    public function getScopeGroupAttribute(){

        $app_scope = $this->scopes_group()->select('branch_group_id')->groupBy('branch_group_id')->get();

        $list = [];
        foreach( $app_scope AS $item ){
            $bs_branch_group = $item->bs_branch_group;

            if( !is_null($bs_branch_group) ){
                $list[] = $bs_branch_group->title;
            }

        }

        $txt = implode( ', ',  $list );

        return $txt;
    }

    public function ibcbs_certify(){
        return $this->hasMany(IbcbsCertificate::class, 'ibcb_id');
    }

    public function historys(){
        return $this->hasMany(IbcbsHistory::class, 'ibcb_id');
    }
}
