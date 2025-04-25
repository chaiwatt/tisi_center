<?php

namespace App\Models\Law\Cases;


use App\User;

use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Basic\LawStatusOperation;


class LawCaseProductOperations extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_product_operations';
  
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
    protected $fillable = ['law_cases_product_results_id','operation_date', 'due_date', 'status_job_track_id', 'detail','created_by', 'updated_by'];
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

    
    public function AttachFileOperations()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','file_law_product_operations');
    }
        
    static function list_status() {
      $status = [                
                  "1" => "รอดำเนินการ",
                  "2" => "อยู่ระหว่างดำเนินการ",
                  "3" => "ดำเนินการเสร็จสิ้น",
              ];
      return $status;
  }

  public function getStatusTextAttribute() {
        $list = self::list_status();
        $text = array_key_exists($this->status_job_track_id,$list)?$list[$this->status_job_track_id]:null;
    return $text;
    }

}
