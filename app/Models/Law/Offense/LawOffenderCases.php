<?php

namespace App\Models\Law\Offense;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Tis;
use App\Models\Law\Basic\LawSection;

use App\Models\Law\Cases\LawCasesForm;

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderStandard;
use App\Models\Law\Offense\LawOffenderLicense;
use App\Models\Law\Offense\LawOffenderProduct;
class LawOffenderCases extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_offenders_cases';

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
        'law_offender_id',
        'law_cases_id',
        'case_number',
        'date_offender_case',
        'tb4_tisilicense_id',
        'license_number',
        'tis_id',
        'tb3_tisno',
        'section',
        'punish',
        'case_person',
        'case_license',
        'case_product',
        'date_close',
        'status',
        'lawyer_by',
        'prosecute',
        'episode_offenders',
        'total_price',
        'total_compare',
        'payment_date',
        'power',
        'power_present_date',
        'approve_date',
        'assign_date',
        'tisi_present',
        'tisi_dictation_no',
        'tisi_dictation_date',
        'tisi_dictation_cppd',
        'tisi_dictation_company',
        'tisi_dictation_committee',
        'cppd_result',
        'result_summary',
        'destroy_date',
        'depart_type',
        'sub_department_id',
        'basic_department_id',
        'department_name',
        'criminal_case_no'
    ];

    /* มอก. */
    public function tis_data(){
        return $this->belongsTo(Tis::class, 'tis_id');
    }

    protected $casts = ['section' => 'json', 'punish' =>  'json' ];

    public function section_list() {
        return $this->hasMany(LawSection::class, 'id', 'section');
    }

    public function punish_list() {
        return $this->hasMany(LawSection::class, 'id', 'punish');
    }

    public function user_lawyer(){
        return $this->belongsTo(User::class, 'lawyer_by');
    }

    public function getLawyerNameAttribute() {
        return @$this->user_lawyer->reg_fname.' '.@$this->user_lawyer->reg_lname;
    }
  
    public function status_list() {
        return [ '1' => 'รอดำเนินการ', '2' => 'อยู่ระหว่างดำเนินการ', '3' => 'ปิดงานคดี' ];
    }

    public function getStatusNameAttribute() {
        return   !empty($this->status) && array_key_exists($this->status,$this->status_list()) ? $this->status_list()[$this->status] : '-'  ;
    }

    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'law_cases_id');
    }

    public function getSectionListNameAttribute(){
        if( !empty($this->section)){
            $result = LawSection::whereIn('id', $this->section)->pluck('number','number')->implode(', ');
            return  $result;
        }
    }

    public function standard_list() {
        return $this->hasMany(LawOffenderStandard::class, 'law_offenders_cases_id');
    }

    public function license_list() {
        return $this->hasMany(LawOffenderLicense::class, 'law_offenders_cases_id');
    }

    public function product_list() {
        return $this->hasMany(LawOffenderProduct::class, 'law_offenders_cases_id');
    }

    public function law_offender(){
        return $this->hasOne(LawOffender::class, 'id', 'law_offender_id')->withDefault();
    }

    public function getStandardNameAttribute(){
        $lits          = [];
        $standard_list = $this->standard_list;
        if( !empty($standard_list) && count($standard_list) >= 1 ){
            foreach( $standard_list AS $std ){
                $lits[ $std->tis_id ]  = $std->tb3_tisno;
            }
        }
        return $lits;
    }

    public function getStandardHtmlAttribute(){
        $lits = [];
        $txt  = null;
        if( !empty($this->standard_list) && count($this->standard_list) >= 1 ){
            $class               = count($this->standard_list) == 1?'list-unstyled':'list-styled';
            $txt                 = '';
            $txt                 .= '<ul class="'.$class.'">';
            foreach( $this->standard_list AS $std ){
                $key             = $std->tis_id;
                if( !array_key_exists( $key,  $lits ) ){

                    $txt         .= '<li>'.($std->tb3_tisno).'</li>';
                    $lits[$key]  = $std->tb3_tisno;
                }
            }
            $txt                 .= '</ul>';
        }
        return $txt;
    }

    public function getProductNameAttribute(){
        $lits         = [];
        $product_list = $this->product_list;
        if( !empty($product_list) && count($product_list) >= 1 ){
            foreach( $product_list AS $pro ){
                $key     = str_replace(' ', '', $pro->detail);
                $lits[$key]  = $pro->detail;
            }
        }
        return $lits;
    }

    public function getProductHtmlAttribute(){
        $lits = [];
        $txt  = null;
        if( !empty($this->product_list) && count($this->product_list) >= 1 ){
            $class               = count($this->product_list) == 1?'list-unstyled':'list-styled';
            $txt                 = '';
            $txt                 .= '<ul class="'.$class.'">';
            foreach( $this->product_list AS $pro ){
                $key             = str_replace(' ', '', $pro->detail);
                if( !array_key_exists( $key,  $lits ) ){
                    $txt         .= '<li>'.($pro->detail).'</li>';
                    $lits[$key]  = $pro->detail;
                }
            }
            $txt                 .= '</ul>';
        }
        return $txt;
    }

    public function getLicenseNameAttribute(){
        $lits = [];
        $license_list = $this->license_list;
        if( !empty($license_list) && count($license_list) >= 1 ){
            foreach( $license_list AS $li ){
                $key     = str_replace(' ', '', $li->tb4_tisilicense_id);
                $lits[$key]  = $li->license_number;
            }
        }
        return $lits;
    }

    public function getLicenseHtmlAttribute(){
        $lits = [];
        $txt  = null;
        if( !empty($this->license_list) && count($this->license_list) >= 1 ){
            $class               = count($this->license_list) == 1?'list-unstyled':'list-styled';
            $txt                 = '';
            $txt                 .= '<ul class="'.$class.'">';
            foreach( $this->license_list AS $li ){
                $key             = str_replace(' ', '', $li->tb4_tisilicense_id);
                if( !array_key_exists( $key,  $lits ) ){
                    $txt         .= '<li>'.($li->license_number).'</li>';
                    $lits[$key]  = $li->license_number;
                }
            }
            $txt                 .= '</ul>';
        }
        return $txt;
    }

}
