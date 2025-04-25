<?php

namespace App\Models\Certify\ApplicantCB;

use HP;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCbExportMapreq;

class CertiCBExport extends Model
{
    protected $table = 'app_certi_cb_export';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'app_certi_cb_id', //TB: app_certi_cb
                            'type_standard',
                            'app_no',
                            'certificate',
                            'cb_name',
                            'name_standard',
                            'radio_address',
                            'address',
                            'allay',
                            'village_no',
                            'road',
                            'province_name',
                            'amphur_name',
                            'district_name',
                            'postcode',
                            'formula',
                            'attachs',
                            'status',
                            'check_badge',
                            'accereditatio_no',
                            'accereditatio_no_en',
                            'date_start',
                            'date_end',
                            'created_by',
                            'updated_by',
                            'name_en','name_standard_en','address_en','allay_en','village_no_en','road_en','province_name_en','amphur_name_en','district_name_en','formula_en',

                            'attach_client_name','cer_type', 'certificate_path', 'certificate_file', 'certificate_newfile', 'sign_id','sign_position','sign_instead',
                            'certificate_period', 'contact_name', 'contact_tel', 'contact_mobile', 'contact_email','set_format'
                            ];

                            
    public function applications()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }

    public function CertiCbTo()
    {
        return $this->belongsTo(CertiCb::class,'app_certi_cb_id');
    }

    public function UserTo()
    {
        return $this->belongsTo(User::class,'created_by','runrecno');
    }

    public function app_certi_cb_auditors()
    {
        return $this->belongsTo(CertiCBAuditors::class,'app_certi_cb_id', 'app_certi_cb_id');
    }

    public function certificate_cb_export_mapreq()
    {
        return $this->hasMany(CertiCbExportMapreq::class, 'certificate_exports_id');
    }
    
    public function getStatusTitleAttribute() {
        $list = '';
        if($this->status == 19){
            $list =  'ลงนามเรียบร้อย';
        }else{
            $list =  'ออกใบรับรอง และ ลงนาม';
        }
        return  $list ?? '-';
    }
    
    public function getCertiCBFileAllAttribute() {
        $files = $this->certificate_cb_export_mapreq()->select('app_certi_cb_id')->groupBy('app_certi_cb_id')->get();
        if(!empty($files) && count($files) > 0){
            return $files->pluck('app_certi_cb_file_all')->flatten();
        }
    }

    public function getFormatAddressAttribute() {
        $address   = [];
        $address[] = @$this->address;

        if($this->allay!='' && $this->allay !='-'  && $this->allay !='--'){
        $address[] =  "หมู่ที่ " . $this->allay;
        }
        if($this->village_no!='' && $this->village_no !='-'  && $this->village_no !='--'){
            $address[] = "ซอย"  . $this->village_no;
        }
        if($this->road !='' && $this->road !='-'  && $this->road !='--'){
            $address[] =  "ถนน"  . $this->road;
        }


        if($this->province_name=='กรุงเทพมหานคร'){
            $address[] =  "แขวง".$this->district_name;
        }else{
            $address[] = " ตำบล".$this->district_name;

        }

        if($this->amphur_name!=''){
            if($this->province_name=='กรุงเทพมหานคร'){
                $address[] = "เขต".$this->amphur_name;
            }else{
                $address[] = "อำเภอ".$this->amphur_name;
            }
        }

        if($this->province_name!=''){
            if($this->province_name=='กรุงเทพมหานคร'){
                $address[] =   $this->province_name;
            }else{
                $address[] =  "จังหวัด".$this->province_name;
            }
        }

        if($this->postcode!=''){
            $address[] =  $this->postcode;
        }

        return implode(' ', $address);
    }

    public function getFormatAddressEnAttribute() {
        $address   = [];
    
        if(isset($this->address_en)){
            $address[] = $this->address_en;
        }
        
        if($this->allay_en!=''){
            $address[] =    'Moo '.$this->allay_en;
        }

        if($this->village_no_en!='' && $this->village_no_en !='-'  && $this->village_no_en !='--'){
            $address[] =   $this->village_no_en;
        }
        if($this->road_en!='' && $this->road_en !='-'  && $this->road_en !='--'){
            $address[] =   $this->road_en.',';
        }
        if($this->district_name_en!='' && $this->district_name_en !='-'  && $this->district_name_en !='--'){
            $address[] =   $this->district_name_en.',';
        }
        if($this->amphur_name_en!='' && $this->amphur_name_en !='-'  && $this->amphur_name_en !='--'){
            $address[] =   $this->amphur_name_en.',';
        }
        if($this->province_name_en!='' && $this->province_name_en !='-'  && $this->province_name_en !='--'){
            $address[] =   $this->province_name_en;
        }

        if($this->postcode!=''){
            $address[] =  $this->postcode;
        }

        return implode(' ', $address);
    }
  
}
