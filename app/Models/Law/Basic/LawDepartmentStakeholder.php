<?php

namespace App\Models\Law\Basic;

use App\User;

use App\Models\Basic\Province AS Province;
use App\Models\Basic\Amphur AS District;
use App\Models\Basic\District AS Subdistrict;
use App\Models\Basic\Tis;

use Illuminate\Database\Eloquent\Model;

class LawDepartmentStakeholder extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_basic_department_stakeholder';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                           'title',
                           'tis_id',
                           'address_no',
                           'moo',
                           'soi',
                           'street',
                           'subdistrict_id',
                           'district_id',
                           'province_id',
                           'zipcode',
                           'tel',
                           'fax',
                           'mobile',
                           'email',
                           'state',
                           'created_by',
                           'updated_by'
                          ];
        /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
      return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
    }

    public function getUpdatedNameAttribute() {
        return (@$this->user_updated->reg_fname).(!empty($this->user_updated->reg_lname)?' '.$this->user_updated->reg_lname:null);
    }

    public function subdistrict(){
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }

    public function district(){
        return $this->belongsTo(District::class,  'district_id');
    }

    public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function getSubdistrictNameAttribute() {
        return !empty($this->subdistrict)?$this->subdistrict->DISTRICT_NAME:null;
    }

    public function getDistrictNameAttribute() {
        return !empty($this->district)?$this->district->AMPHUR_NAME:null;
    }

    public function getProvinceNameAttribute() {
        return !empty($this->province)?$this->province->PROVINCE_NAME:null;
    }


    public function getStateIconAttribute() {
        $btn = '';

        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function getContactAttribute() {
      $contact = '';
      $contact .=  !empty($this->address_no)? $this->address_no:null; 
      $contact .=  !empty($this->moo)? ' หมู่ '.$this->moo:null; 
      $contact .=  !empty($this->soi)? ' ตรอก/ซอย '.$this->soi:null; 
      $contact .=  !empty($this->street)? ' ถนน '.$this->street:null; 
      if($this->province_id == 1 || $this->ProvinceName=='กรุงเทพมหานคร'){
        $contact .=  !empty($this->SubdistrictName)? ' แขวง. '.$this->SubdistrictName:null; 
        $contact .=  !empty($this->DistrictName)? ' เขต. '.$this->DistrictName:null; 
        $contact .=  !empty($this->ProvinceName)? '  '.$this->ProvinceName:null; 
      }else{
        $contact .=  !empty($this->SubdistrictName)? ' ตำบล. '.$this->SubdistrictName:null; 
        $contact .=  !empty($this->DistrictName)? ' อำเภอ. '.$this->DistrictName:null; 
        $contact .=  !empty($this->ProvinceName)? ' จังหวัด. '.$this->ProvinceName:null; 
      }
      $contact .=  !empty($this->zipcode)? $this->zipcode:null; 

    return @$contact;
  }

  public function getTisNoAttribute() {
    $datas = [];
    if(!empty($this->tis_id)){
        $data = json_decode($this->tis_id);
        if(count($data)> 0){
          $datas = Tis::whereIn('tb3_TisAutono',$data)->pluck('tb3_Tisno')->implode(', ');
        }
    }
    return $datas;
  }

}
