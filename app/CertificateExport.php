<?php

namespace App;

use HP;
use DB; 
use App\User;

use App\Models\Certificate\Tracking;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\SetStandardUserSub;
use App\Models\Certify\SetStandardUser;
use Illuminate\Database\Eloquent\Model;

use App\Models\Certify\BoardAuditorDate;
use App\Models\Certify\Applicant\CertiLab;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Certify\Applicant\CheckExaminer;
use App\Models\Certify\Applicant\CertLabsFileAll;
use App\Models\Certify\Applicant\CertiLabExportMapreq;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertifyLabCalibrate;
class CertificateExport extends Model
{

    use SoftDeletes;
    protected $table = "certificate_exports";
    protected $primaryKey = 'id';
    protected $fillable = ['request_number','lang','certificate_no','status','certificate_order','certificate_for',
                            'org_name','lab_name','address_no',     'address_moo','address_soi', 'address_road','address_province', 'address_district', 
                            'address_subdistrict', 'address_postcode','formula',
                            // 'accereditatio_no_en',
                            'accereditatio_no', 'certificate_date_start','certificate_date_end', 'certificate_date_first', 'issue_no', 'scope_permanent',
                            'scope_site', 'scope_temporary', 'scope_mobile', 'attachs', 'lab_type', 'radio_address','attachs_client_name',
                            'title_en','lab_name_en','address_no_en','address_moo_en','address_soi_en','address_road_en','address_province_en','address_district_en','address_subdistrict_en','formula_en',
                            'sign_id', 'sign_name', 'sign_position','sign_instead',
                            'cer_type','certificate_path','certificate_file','certificate_newfile','documentId','signtureid','status_revoke','date_revoke','reason_revoke','user_revoke','reference_refno','reference_check','reference_date',
                            'review',
                            'address_province_en',
                            'address_district_en',
                            'address_subdistrict_en',
                            'formula_en',
                            'accereditatio_no_en',
                            'certificate_period', 'contact_name', 'contact_tel', 'contact_mobile', 'contact_email',
                            'set_format','hold_status'

                        ];
    public function getTraderNameAttribute()
    {
        $cer = CertiLab::find($this->certificate_for);
        if ($cer){
            return @$cer->name;
        }
        return "N/A";
    }
    public function applications()
    {
        return $this->belongsTo(CertiLab::class,'certificate_for');
    }

    public function CheckExaminerTo()
    {
        return $this->belongsTo(CheckExaminer::class,'certificate_for','app_certi_lab_id');
    }

    public function CertiLabTo()
    {
        return $this->belongsTo(CertiLab::class,'certificate_for');
    }

    public function cert_labs_file(){
        return $this->hasMany(CertLabsFileAll::class, 'ref_id','id')->where('ref_table', $this->getTable());
    }

    public function app_certi_tracking(){
        return $this->hasMany(Tracking::class, 'ref_id','id')->where('ref_table', $this->getTable());
    }

    public function tracking_has_one(){
        return $this->hasOne(Tracking::class, 'ref_id','id')->where('ref_table', $this->getTable())->orderby('id','desc');
    }
    
    public function cert_labs_file_all(){
        return $this->belongsTo(CertLabsFileAll::class,'certificate_for','app_certi_lab_id')->where('state',1)->orderby('id','desc');
    }

    public function board_auditors(){
        return $this->belongsTo(BoardAuditor::class,'certificate_for','app_certi_lab_id');
    }

    public function app_lab_calibrate_user_standard()
    {
        return $this->hasManyThrough(SetStandardUserSub::class, CertifyLabCalibrate::class, 'app_certi_lab_id', 'items_id', 'certificate_for', 'branch_id' );
    }

    public function app_lab_test_scope_user_standard()
    {
        return $this->hasManyThrough(SetStandardUserSub::class, CertifyTestScope::class, 'app_certi_lab_id', 'test_branch_id', 'certificate_for', 'branch_id' );
    }

    public function certificate_lab_export_mapreq()
    {
        return $this->hasMany(CertiLabExportMapreq::class, 'certificate_exports_id');
    }
    public function getCertiLabFileAllAttribute() {
        $files = $this->certificate_lab_export_mapreq()->select('app_certi_lab_id')->groupBy('app_certi_lab_id')->get();
        if(!empty($files) && count($files) > 0){
            return $files->pluck('app_certi_lab_file_all')->flatten();
        }
    }

    public function getLabTypeTitleAttribute(){
        $lab_type = $this->lab_type; // ประเภทการตรวจ
        $data =   ['3'=> 'ทดสอบ','4'=>'สอบเทียบ'];
        return   array_key_exists($lab_type,$data) ? $data[$lab_type] : '';
    }
    

    public function getFormatAddressAttribute() {
        $address   = [];
        $address[] = @$this->address_no;

        if($this->address_moo !='' && $this->address_moo !='-'  && $this->address_moo !='--'){
            $address[] =  "หมู่ที่ " . $this->address_moo;
        }
        if($this->address_soi !='' && $this->address_soi !='-'  && $this->address_soi !='--'){
            $address[] = "ซอย"  . $this->address_soi;
        }
        if($this->address_road !='' && $this->address_road !='-'  && $this->address_road !='--'){
            $address[] =  "ถนน"  . $this->address_road;
        }

        if($this->address_province=='กรุงเทพมหานคร'){
            $address[] =  "แขวง".$this->address_subdistrict;
        }else{
            $address[] = " ตำบล".$this->address_subdistrict;

        }

        if($this->address_district!=''){
            if($this->address_province=='กรุงเทพมหานคร'){
                $address[] = "เขต".$this->address_district;
            }else{
                $address[] = "อำเภอ".$this->address_district;
            }
        }

        if($this->address_province!=''){
            if($this->address_province=='กรุงเทพมหานคร'){
                $address[] =   $this->address_province;
            }else{
                $address[] =  "จังหวัด".$this->address_province;
            }
        }

        if($this->address_postcode!=''){
            $address[] =  $this->address_postcode;
        }


        return implode(' ', $address);
    }

    public function getFormatAddressEnAttribute() {
        $address   = [];
    
        if(isset($this->address_no_en)){
            $address[] = $this->address_no_en;
        }
        
        if($this->address_moo_en!=''){
            $address[] =    'Moo '.$this->address_moo_en.',';
        }

        if($this->address_soi_en!='' && $this->address_soi_en !='-'  && $this->address_soi_en !='--'){
            $address[] =   $this->address_soi_en.',';
        }
        if($this->address_road_en!='' && $this->address_road_en !='-'  && $this->address_road_en !='--'){
            $address[] =   $this->address_road_en.',';
        }
        if($this->address_subdistrict_en!='' && $this->address_subdistrict_en !='-'  && $this->address_subdistrict_en !='--'){
            $address[] =   $this->address_subdistrict_en;
        }
        if($this->address_district_en!='' && $this->address_district_en !='-'  && $this->address_district_en !='--'){
            $address[] =   $this->address_district_en.',';
        }
        if($this->address_province_en!='' && $this->address_province_en !='-'  && $this->address_province_en !='--'){
            $address[] =   $this->address_province_en.',';
        }
        if($this->address_postcode!=''){
            $address[] =  $this->address_postcode;
        }
        return implode(' ', $address);
    }
 
}
