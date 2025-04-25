<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\LabsScope;
use App\Models\Section5\LabsHistory;
use App\Models\Section5\LabsCertify;

use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;
use App\Models\Sso\User AS SSO_USER;

class Labs extends Model
{
    protected $table = 'section5_labs';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'name',
                            'taxid',
                            'lab_code',
                            'lab_name',
                            'lab_user_id',
                            'lab_address',
                            'lab_moo',
                            'lab_soi',
                            'lab_building',
                            'lab_road',
                            'lab_subdistrict_id',
                            'lab_district_id',
                            'lab_province_id',
                            'lab_zipcode',
                            'lab_phone',
                            'lab_fax',
                            'co_name',
                            'co_position',
                            'co_mobile',
                            'co_phone',
                            'co_fax',
                            'co_email',
                            'lab_start_date',
                            'lab_end_date',
                            'state',
                            'ref_lab_application_no',
                            'created_by',
                            'updated_by'
                        ];

    public function lab_subdistrict(){
        return $this->belongsTo(District::class, 'lab_subdistrict_id');
    }

    public function lab_district(){
        return $this->belongsTo(Amphur::class,  'lab_district_id');
    }

    public function lab_province(){
        return $this->belongsTo(Province::class, 'lab_province_id');
    }

    public function getLabSubdistrictNameAttribute() {
        return !empty($this->lab_subdistrict)?$this->lab_subdistrict->DISTRICT_NAME:null;
    }

    public function getLabDistrictNameAttribute() {
        return !empty($this->lab_district)?$this->lab_district->AMPHUR_NAME:null;
    }

    public function getLabProvinceNameAttribute() {
        return !empty($this->lab_province)?$this->lab_province->PROVINCE_NAME:null;
    }

    public function historys(){
        return $this->hasMany(LabsHistory::class, 'lab_id');
    }

    public function scope_standard(){
        return $this->hasMany(LabsScope::class, 'lab_id');
    }

    public function section5_labs_scopes(){
        return $this->hasMany(LabsScope::class, 'lab_id');
    }

    public function scope_standard_active(){
        $date_now = date('Y-m-d');
        return $this->scope_standard()
                    ->where('state', 1)
                    ->whereDate('start_date', '<=', $date_now)
                    ->whereDate('end_date', '>=', $date_now);
    }

    public function getScopeStandardAttribute(){

        $scope_standard = $this->scope_standard()->select('tis_id')->groupBy('tis_id')->get();
        $list = [];
        foreach( $scope_standard AS $item ){
            $tis_standards = $item->tis_standards;

            if( !is_null($tis_standards) ){
                $list[] = $tis_standards->tb3_Tisno;
            }

        }

        $txt = implode( ' ,',  $list );

        return $txt;
    }

    public function getScopeStandardActiveAttribute(){
        $date_now = date('Y-m-d');
        $scope_standard = $this->scope_standard()
                               ->select('tis_id')
                               ->where('state', 1)
                               ->whereDate('start_date', '<=', $date_now)
                               ->whereDate('end_date', '>=', $date_now)
                               ->whereNotNull('test_item_id')
                               ->groupBy('tis_id')
                               ->get();
        $list = [];
        foreach( $scope_standard AS $item ){
            $tis_standards = $item->tis_standards;

            if( !is_null($tis_standards) ){
                $list[] = $tis_standards->tb3_Tisno;
            }

        }

        $txt = implode( ' ,',  $list );

        return $txt;
    }

    /* Btn Switch Input*/
    public function getStateIconAttribute(){

        $max_data = $this->scope_standard()->whereNotNull('end_date')->orderBy('end_date','desc')->first();

        $StateHtml = [ 1 => '<i class="fa fa-check-circle fa-lg text-success"></i>', 2 => '<i class="fa fa-times-circle fa-lg text-danger"></i>' ];

        $btn = '';

        if( !empty( $max_data ) ){
            return  ( !empty($max_data->end_date) && $max_data->end_date >= date('Y-m-d') ) && array_key_exists( $max_data->state, $StateHtml )?$StateHtml[ $max_data->state ]:'<i class="fa fa-times-circle fa-lg text-danger"></i>'; 
        }else{
            return '<i class="fa fa-times-circle fa-lg text-danger"></i>';
        }
        
        return $btn;

  	}

    /* ข้อความสถานะ */
    public function getStateTextAttribute(){

        $max_data = $this->scope_standard()->whereNotNull('end_date')->orderBy('end_date','desc')->first();

        $StateHtml = [ 1 => '<span class="text-bold-400 text-success">Active</span>', 2 => '<span class="text-bold-400 text-danger">Not Active</span>' ];

        $btn = '';

        if(!empty($max_data)){
            return (!empty($max_data->end_date) && $max_data->end_date >= date('Y-m-d')) && array_key_exists($max_data->state, $StateHtml) ? $StateHtml[$max_data->state] : $StateHtml[2] ;
        }else{
            return $StateHtml[2];
        }
        
        return $btn;

  	}

    //ข้อมูลผปก.
    public function user(){
        return $this->belongsTo(SSO_USER::class, 'lab_user_id');
    }

    public function lab_certify(){
        return $this->hasMany(LabsCertify::class, 'lab_id');
    }

}
