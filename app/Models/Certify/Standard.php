<?php

namespace App\Models\Certify;

use HP;
use App\User;
use App\Models\Basic\Ics;
use App\Models\Basic\Method;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\IndustryTarget;
use App\Models\Certify\SetStandards;
use App\Models\Bcertify\Standardtype;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\StandardSendmail;

class Standard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_standards';

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
    protected $fillable = ['setstandard_id', 'std_type', 'std_no', 'std_book', 'std_year', 'std_full', 'std_title', 'std_title_en', 'std_page', 'method_id', 'format_id', 'std_abstract', 'std_abstract_en', 'isbn_no','isbn_issue_at', 'isbn_file', 'isbn_by', 'isbn_at', 'status_id', 'created_by', 'updated_by', 'std_price', 'standard_id', 'std_signname', 'std_signposition',
                           'std_file', 'std_sign_date', 'gazette_state', 'gazette_book', 'gazette_no', 'gazette_section', 'gazette_post_date', 'gazette_effective_date', 'gazette_file', 'publish_state', 'revoke_date', 'revoke_remark', 'revoke_book', 'revoke_file', 'std_force', 'ref_document', 'reason', 'confirm_time', 'industry_target', 'remark','gazette_govbook','step_tap'];

    /*
      Sorting
    */
    public $sortable = ['setstandard_id', 'std_type', 'std_no', 'std_book', 'std_year', 'std_full', 'std_title', 'std_title_en', 'std_page', 'method_id', 'format_id', 'std_abstract', 'std_abstract_en', 'isbn_no', 'isbn_issue_at', 'isbn_file', 'isbn_by', 'isbn_at', 'status_id', 'created_by', 'updated_by', 'std_price', 'standard_id', 'std_signname', 'std_signposition',
                        'std_file', 'std_sign_date', 'gazette_state', 'gazette_book', 'gazette_no', 'gazette_section', 'gazette_post_date', 'gazette_effective_date', 'gazette_file', 'publish_state', 'revoke_date', 'revoke_remark', 'revoke_book', 'revoke_file', 'std_force', 'ref_document', 'reason', 'confirm_time', 'industry_target', 'remark','gazette_govbook','step_tap'];

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
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function standard_type() {
      return $this->belongsTo(Standardtype::class, 'std_type');
    }

    public function method() {
        return $this->belongsTo(Method::class, 'method_id');
    }

    public function isbn_created(){
        return $this->belongsTo(User::class, 'isbn_by');
    }

    public function industry_target_data(){
        return $this->belongsTo(IndustryTarget::class, 'industry_target');
    }

    public function set_standard_to(){
      return $this->belongsTo(SetStandards::class, 'setstandard_id');
  }


    public function standard_ics_many(){
        return $this->hasMany(StandardIcs::class, 'std_id');
    }

    public function certify_standard_sendmail(){
        return $this->hasMany(StandardSendmail::class, 'std_id');
    }
    

    //รหัส ics
    public function ics(){
        return $this->belongsToMany(Ics::class, (new StandardIcs)->getTable(),  'std_id', 'ics_id');
    }

    public function getSetStandardStatusAttribute()
    {
      if ($this->status_id == 4){
          return "อยู่ระหว่างจัดทำมาตรฐานการรับรอง";
      }elseif ($this->status_id == 5){
          return "แจ้งระบุเลข ISBN";
      }elseif ($this->status_id == 6){
          return "ดำเนินการ และเสนอผู้มีอำนาจลงนาม";
      }elseif ($this->status_id == 7){
          return "ลงนามเรียบร้อย";
      }elseif ($this->status_id == 8){
          return "เสนอราชกิจจานุเบกษา";
      }elseif ($this->status_id == 9){
          return "ประกาศราชกิจจานุเบกษาเรียบร้อย";
      }else{
          return "n/a";
      }
    }

    public function getPublishStatusAttribute()
    {
      if ($this->publish_state == 1){
          return "รอเผยแพร่";
      }elseif ($this->publish_state == 2){
          return "เผยแพร่";
      }elseif ($this->publish_state == 3){
          return "ยกเลิก";
      }else{
          return "n/a";
      }
    }

    public function getStandardIcsTitleAttribute() {
      $datas = [];
          if(count($this->standard_ics_many) > 0){   
              $ics_ids = HP::getArrayFormSecondLevel($this->standard_ics_many->toArray(), 'ics_id');
              $ics = Ics::whereIn('id', $ics_ids)->pluck('code')->toArray();
               foreach ($ics as $key => $item) {
                  if(!is_null($item)){
                      $datas[$item] = $item;
                  }
               }
           }
        return $datas;
    }
  


}
