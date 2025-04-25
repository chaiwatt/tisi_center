<?php

namespace App\Models\Law\Cases;

use App\User;
use Illuminate\Support\Facades\DB;

use App\Models\Basic\Amphur as District;
use App\Models\Law\Basic\LawResource;
use App\Models\Basic\Province as Province;

use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\File\AttachFileLaw;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\District as Subdistrict;
use App\Models\Law\Cases\LawCasesStaffList;
use App\Models\Law\Cases\LawCasesImpoundProduct;

class LawCasesImpound extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_impounds';
  
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

    protected $fillable =
    [
       'law_case_id','impound_status','ref_tb','ref_id','date_impound','same_product', 'location','storage_name','storage_address_no','storage_soi','storage_street','storage_moo','storage_subdistrict_id','storage_district_id','storage_province_id','storage_zipcode','storage_tel','law_basic_resource_id','total_value','status','created_by','updated_by'
    ];

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
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

    public function law_case(){
        return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }


    // บันทึกดำเนินการกับผลิตภัณฑ์
    public function law_cases_tmpound_product_to(){
        return $this->belongsTo(LawCasesImpoundProduct::class, 'id','law_case_impound_id');
    }

    
    public function impound_product() {
        return $this->hasMany(LawCasesImpoundProduct::class, 'law_case_impound_id');
    }

    //เจ้าหน้าที่
    public function staff_list() {
        return $this->hasMany(LawCasesStaffList::class, 'law_case_impound_id');
    }
    
    public function getAmountProductAttribute() {
        //จำนวนที่ยึด
        $amount_impounds = $this->impound_product->sum('amount_impounds');
        //จำนวนที่อายัด
        $amount_keep     = $this->impound_product->sum(DB::raw('REPLACE(amount_keep, ",", "")'));
        return  $amount_keep + $amount_impounds;
    }
    
    
    public function getAmountImpoundAttribute() { // จำนวนที่ยึด
        return  @$this->impound_product->sum('amount_impounds');
    }
    
    public function getAmountKeepAttribute() { // จำนวนที่อายัด
        return  @$this->impound_product->sum('amount_keep');
    }

    //Address Relation
    public function storage_subdistricts(){
        return $this->belongsTo(Subdistrict::class, 'storage_subdistrict_id');
    }

    public function storage_districts(){
        return $this->belongsTo(District::class,  'storage_district_id');
    }

    public function storage_provinces(){
        return $this->belongsTo(Province::class, 'storage_province_id');
    }

    // แหล่งที่มาราคาผลิตภัณฑ์
    public function law_basic_resource_to(){
        return $this->belongsTo(LawResource::class, 'law_basic_resource_id');
    }
  
    public function getImpoundDataAdressAttribute()
    {
        $provinces    = $this->storage_provinces;
        $districts    = $this->storage_districts;
        $subdistricts = $this->storage_subdistricts;

        $text = '';
        $text .= (!empty($this->storage_address_no)?trim($this->storage_address_no):null);
        $text .= !empty($this->storage_address_no)?' ':'';
        $text .= (!empty($this->storage_moo)?'หมู่ที่ '.trim($this->storage_moo):null);
        $text .= !empty($this->storage_moo)?' ':'';

        if(!is_null($this->storage_soi) &&  $this->storage_soi != '-'){
            $text .= (!empty($this->storage_soi)?'ตรอก/ซอย '.trim($this->storage_soi):null);
            $text .= ' ';
        }
        if(!is_null($this->storage_street) &&  $this->storage_street != '-'){
            $text .= (!empty($this->storage_street)?'ถนน '.trim($this->storage_street):null);
            $text .= ' ';
        }

        $subdistrict = !empty($provinces) && ($provinces->PROVINCE_ID == 1) ? 'แขวง' : 'ตำบล';
        $text .= (!empty($subdistricts)?$subdistrict.trim( str_replace("แขวง","",$subdistricts->DISTRICT_NAME) ):null);
        $text .= !empty($subdistricts)?' ':'';

        $district_name = !empty($provinces) && ($provinces->PROVINCE_ID  == 1) ? 'เขต' : 'อำเภอ';
        $text .= (!empty($districts)?$district_name.trim( str_replace("เขต","",$districts->AMPHUR_NAME) ):null);
        $text .= !empty($districts)?' ':'';

        $text .= (!empty($provinces)?'จังหวัด'.trim($provinces->PROVINCE_NAME):null);
        $text .= !empty($provinces)?' ':'';
        $text .= (!empty($this->storage_zipcode)?$this->storage_zipcode:(  !empty($districts)?trim($districts->POSTCODE):null ));

        return  $text;
    }

}
