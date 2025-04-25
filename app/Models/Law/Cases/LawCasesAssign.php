<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Basic\SubDepartment;

class LawCasesAssign extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_assign';
  
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
    protected $fillable = ['law_case_id', 'sub_department_id', 'assign_by', 'lawyer_check', 'lawyer_by', 'created_by', 'updated_by'];
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
 
    public function sub_department_to(){
        return $this->hasMany(SubDepartment::class, 'sub_department_id');
     }
     public function assign_to(){
        return $this->hasMany(User::class, 'assign_by');
     }
    //  อีเมลผู้มอบหมาย
     public function getEmailNameAttribute() {
      $email = '';
      if(!empty($this->user_created->reg_email) &&  filter_var($this->user_created->reg_email, FILTER_VALIDATE_EMAIL)  ){
        $email =  $this->user_created->reg_email;
      }
      return $email ;
  }

}
