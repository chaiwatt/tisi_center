<?php

namespace App\Models\Law\Cases;

use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Basic\LawDelivery;

class LawCasesDelivery extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_cases_delivery';
  
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
        'send_type',
        'send_no',
        'title',
        'send_to',
        'condition',
        'date_due',
        'attach_response',
        'remark',
        'response_remark',
        'response_name',
        'response_tel',
        'response_email',
        'response_date',
        'created_by',
        'updated_by',
        'status',
        'noti_email_status',
        'noti_sytem_status',
        'send_mail_status',
        'noti_email'
        
    ];
    
    protected $casts = ['send_mail_status' => 'json', 'noti_email' =>  'json' ];
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

    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }

    public function basic_delivery(){
        return $this->belongsTo(LawDelivery::class, 'send_type');
    }

    public function getConditionNameAttribute() {
        $condition_arr = [ 1 => 'ตอบกลับ' , 2 => 'ไม่ต้องตอบกลับ' ];
        return   !empty($this->condition) && array_key_exists($this->condition,$condition_arr) ? $condition_arr[$this->condition] : ''  ;
    }

    public function file_law_cases_delivery()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->getTable())->where('section','law_cases_delivery_file');
    }

    public function file_law_cases_response()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->getTable())->where('section','law_cases_delivery_response');
    }
}
