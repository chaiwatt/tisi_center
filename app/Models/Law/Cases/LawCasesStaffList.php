<?php

namespace App\Models\Law\Cases;


use App\User;


use App\Models\Basic\SubDepartment;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Basic\LawRewardGroup;
use App\Models\Law\Basic\LawDepartment;
use App\Models\Accounting\Bank;

class LawCasesStaffList extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_staff_lists';
  
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

    protected $fillable =
    [
        'law_cases_id',
        'depart_type',
        'sub_department_id',
        'basic_department_id',
        'department_name',
        'name',
        'address',
        'basic_reward_group_id',
        'created_by',
        'updated_by',
        'taxid',
        'mobile',
        'email',
        'basic_bank_id',
        'bank_account_name',
        'bank_account_number'
        
    ];

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
  
    public function getStateIconAttribute() {
        $btn = '';
  
        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    // กอง-กลุ่ม
    public function sub_department()
    {
        return $this->belongsTo(SubDepartment::class, 'sub_department_id', 'sub_id');
    }

    public function reward_group()
    {
        return $this->belongsTo(LawRewardGroup::class, 'basic_reward_group_id');
    }

    public function getRewardGroupNameAttribute() {
        return  $this->reward_group->title??'n/a';
    }

    public function law_deparment(){
        return $this->belongsTo(LawDepartment::class, 'basic_department_id');
    }

    public function getDeparmentNameAttribute() {
        if( $this->depart_type == 1){

            $depart         = !empty($this->sub_department->department)?$this->sub_department->department:null;
            $sub_department = $this->sub_department;
            if( !empty($sub_department->sub_depart_shortname) ){
                return (!empty($depart->depart_nameShort)?$depart->depart_nameShort:null).(!empty($sub_department->sub_depart_shortname)?" (".$sub_department->sub_depart_shortname.") ":null);
            }else{
                return !empty($depart->depart_nameShort)?$depart->depart_nameShort:null;
            }
        }else{
            return !is_null($this->law_deparment) && !empty($this->law_deparment->title)?$this->law_deparment->title:null;
        }
    }

    public function getDeparmentTypeNameAttribute() {
        $arr_depart_type = ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'];
        return array_key_exists( $this->depart_type,  $arr_depart_type  )? $arr_depart_type[ $this->depart_type ]: $arr_depart_type[1];
    }

    public function ac_bank()
    {
        return $this->belongsTo(Bank::class, 'basic_bank_id');
    }

    public function file_book_bank()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','law_book_bank')->orderby('id','desc');
    }


}
