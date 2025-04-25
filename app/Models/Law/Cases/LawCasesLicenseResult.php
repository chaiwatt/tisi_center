<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use HP;

use App\Models\Basic\TisiLicense;
use App\Models\Tb4\TisiCancelReason;
use App\Models\Tb4\TisiLicensePause;
use App\Models\Tb4\TisiLicenseCancel;
class LawCasesLicenseResult extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_license_result';
  
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
                            'law_case_id',
                            'offend_tb4_tisilicense_id',
                            'offend_license_number',
                            'status',
                            'status_result',
                            'date_pause_start',
                            'date_pause_end',
                            'date_revoke',
                            'basic_revoke_type_id',
                            'remark',
                            'created_by',
                            'updated_by',
                            'date_pause_amount'
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
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }
 
    public function law_case_to(){
        return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }

    public function status_result_color_list() {
        return [ 
                    '1'=> 'success',
                    '2'=> 'warning',
                    '3'=> 'danger'
                ];
    }

    public static  function status_result_list() {
        return [ 
                    '1'=> 'ใช้งาน',
                    '2'=> 'พักใช้',
                    '3'=> 'เพิกถอน'
                ];
    }
 
    // สถานะ (สี)
    public function getStatusResultColorHtmlAttribute() {
        return  !empty($this->status_result) && array_key_exists($this->status_result, $this->status_result_color_list()) ? '<span class="text-'.$this->status_result_color_list()[$this->status_result].'">'.$this->status_result_list()[$this->status_result].'</span>' : '-';
    }

    public function getStatusResultColorHtmlWithDatePauseAttribute() {
        $html = !empty($this->status_result) && array_key_exists($this->status_result, $this->status_result_color_list()) ? '<span class="text-'.$this->status_result_color_list()[$this->status_result].'">'.$this->status_result_list()[$this->status_result].'</span>' : '-';
        $html .= '<br>';
        $html .= '<span class="text-muted">';

        if( $this->status_result == 3 ){
            $html .= '('.HP::DateThai($this->date_revoke).')';
        }else{
            $html .= '('.HP::DateThai($this->date_pause_start). " ถึง <br>" . HP::DateThai($this->date_pause_end).')';
        }

        $html .= '</span>';
        return  $html;
    }

    // หลักฐานผลการพิจารณา
    public function FileAttachTo()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','attachs')  ->orderby('id','desc');
    }

    public function tb4_tisilicense(){
        return $this->belongsTo(TisiLicense::class, 'offend_tb4_tisilicense_id');
    }
    
    public function cancel_reasonl(){
        return $this->belongsTo(TisiCancelReason::class, 'basic_revoke_type_id');
    }

    //พักใช้
    public function license_pause(){
        return $this->belongsTo(TisiLicensePause::class, 'offend_license_number', 'tbl_licenseNo')->where('case_number', $this->law_case_to->case_number ); 
    }

    //ยกเลิก
    public function license_cancel(){
        return $this->belongsTo(TisiLicenseCancel::class, 'offend_license_number', 'tbl_licenseNo')->where('case_number', $this->law_case_to->case_number );
    }
    

}
 