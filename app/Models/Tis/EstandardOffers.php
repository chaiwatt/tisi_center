<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable; 
use HP;
use DB;
use App\Models\Basic\Department;
use App\User;
use App\AttachFile;
use App\Models\Bcertify\Standardtype;
use App\Models\Bcertify\StandardTypeAssign;

class EstandardOffers extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_offers';

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
    protected $fillable = ['title','owner', 'title_eng', 'std_type', 'scope', 'objectve', 'path','caption', 'attach_old', 'attach_new', 'attach_type',
     'stakeholders', 'name', 'telephone','department_id', 'department', 'email', 'address', 'ip_address', 'user_agent', 'state', 'created_by', 'updated_by',
    'standard_types','details','refno'];

    /*
      Sorting
    */
    public $sortable =  ['title', 'title_eng', 'std_type', 'scope', 'objectve', 'path','caption', 'attach_old', 'attach_new', 'attach_type',
     'stakeholders', 'name', 'telephone','department_id', 'department', 'email', 'address', 'ip_address', 'user_agent', 'state', 'created_by', 'updated_by',
     'standard_types','details','refno'];

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function departmentTo(){
      return $this->belongsTo(Department::class, 'department_id');
    }

    public function bcertify_standard_type_assign(){
      return $this->belongsToMany(StandardTypeAssign::class, 'standard_types');
    }

    /* Btn Switch Input*/
    public function getStateTitleAttribute(){
       $state = HP::StateEstandardOffers();
      return  array_key_exists($this->state,$state)? $state[$this->state] : null ;
  }

  public function AttachFileAttachFileTo()
  { 
     $tb = new EstandardOffers;
      return $this->belongsTo(AttachFile::class,'id','ref_id')->where('ref_table',$tb->getTable())->where('section','attach_file')->orderby('id','desc');
  }
  public function AttachFileAttachTo()
  { 
     $tb = new EstandardOffers;
      return $this->belongsTo(AttachFile::class,'id','ref_id')->where('ref_table',$tb->getTable())->where('section','attach')->orderby('id','desc');
  }

  // ประเภทมาตรฐาน
  public function standard_type_to()
  { 
      return $this->belongsTo(Standardtype::class,'std_type');
  }

  public function tisi_estandard_offers_asigns()
  {
      return $this->hasMany(EstandardOffersAsign::class, 'comment_id');
  }
  
    public function getEstandardOffersAsignNameAllAttribute(){
        return @$this->tisi_estandard_offers_asigns->pluck('EstandardOffersAsignName')->implode(', ');
    }
  
    public function getEstandardOffersAsignName2Attribute(){
        return @$this->tisi_estandard_offers_asigns->where('ordering', 2)->pluck('EstandardOffersAsignName')->implode(', ');
    }
  
    public function getEstandardOffersAsignName3Attribute(){
        return @$this->tisi_estandard_offers_asigns->where('ordering', 3)->pluck('EstandardOffersAsignName')->implode(', ');
    }

  public function estandard_offers_asigns2_many()
  {
      return $this->hasMany(EstandardOffersAsign::class,'comment_id')->where('status',1)->where('ordering',2);
  }
  public function getAsigns2TitleAttribute() {
    $datas = [];
        if(count($this->estandard_offers_asigns2_many) > 0){ 
            $user_ids = HP::getArrayFormSecondLevel($this->estandard_offers_asigns2_many->toArray(), 'user_id');
            $Users = User::select(DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name")  )->whereIn('runrecno', $user_ids)->pluck('name')->toArray();
             foreach ($Users as $key => $item) {
                if(!is_null($item)){
                    $datas[] = $item;
                }
             }
         }
      return $datas;
  }

  public function estandard_offers_asigns3_many()
  {
      return $this->hasMany(EstandardOffersAsign::class,'comment_id')->where('status',1)->where('ordering',3);
  }
  public function getAsigns3TitleAttribute() {
    $datas = [];
        if(count($this->estandard_offers_asigns3_many) > 0){  
            $user_ids = HP::getArrayFormSecondLevel($this->estandard_offers_asigns3_many->toArray(), 'user_id');
            $Users = User::select(DB::raw("CONCAT(reg_fname, ' ', reg_lname) AS name")  )->whereIn('runrecno', $user_ids)->pluck('name')->toArray();
             foreach ($Users as $key => $item) {
                if(!is_null($item)){
                    $datas[] = $item;
                }
             }
         }
      return $datas;
  }
  
}
