<?php

namespace App\Models\Besurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Besurv\Department;
use App\AttachFile;

class Signer extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'besurv_signers';

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
    protected $fillable = ['tax_number','name', 'name_eng', 'position', 'main_group','line_token','signed','attach', 'state', 'created_by', 'updated_by','user_register_id'];

    /*
      Sorting
    */
    public $sortable = ['tax_number','name', 'name_eng', 'position', 'main_group','line_token','signed','attach', 'state', 'created_by', 'updated_by'];



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

    public function getDepartmentNameAttribute() {
            $html = '';
            $department_list = !empty($this->main_group)?json_decode($this->main_group):null;
            if($department_list){
              $department_datas = Department::WhereIn('did', $department_list)->get();
              foreach($department_datas as $department_data){
                $html .= $department_data['depart_nameShort']." : ".$department_data['depart_name']."<br>";
              }
            } else {
              $html = 'n/a';
            }
        return $html;
    }

    public function AttachFileAttachTo()
    { 
       $tb = new Signer;
        return $this->belongsTo(AttachFile::class,'id','ref_id')->where('ref_table','besurv_signers')->where('section','attach')->orderby('id','desc');
    }

    public function user()
  {
      return $this->belongsTo(User::class, 'user_register_id', 'runrecno');
  }

}
