<?php

namespace App\Models\Law\Cases;


use App\User;

use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Basic\LawStatusOperation;
use App\Models\Law\Cases\LawCasesOperationDetail;

class LawCasesOperation extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_operations';
  
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
    protected $fillable = ['law_cases_id','case_number', 'status', 'remark', 'created_by', 'updated_by'];
        /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function status_job_track(){
      return $this->belongsTo(LawStatusOperation::class, 'status_job_track_id');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }
    
    public function case_operations_details(){
      return $this->hasMany(LawCasesOperationDetail::class,'law_case_operations_id');
  }
     
    static function list_status() {
      $status = [                
                  "99" => "ไม่ต้องดำเนินการใดๆ",
                  "1" => "รอดำเนินการ",
                  "2" => "อยู่ระหว่างดำเนินการ",
                  "3" => "ดำเนินการเสร็จสิ้น",
              ];
      return $status;
  }

}
