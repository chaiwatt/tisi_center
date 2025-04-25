<?php

namespace App\Models\Law\Books;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawBookType;
use App\Models\Law\Basic\LawBookGroup;
use App\Models\Law\Books\LawBookManageAccess;
use App\Models\Law\Books\LawBookManageVisit;

class LawBookManage extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_book_manage';

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
    protected $fillable = ['basic_book_group_id','basic_book_type_id', 'title', 'important', 'description', 'tag', 'type_file', 'url', 'date_publish', 'state', 'created_by', 'updated_by', 'owner', 'lawyer', 'operation_date', 'ordering'];
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

    public function getStateIconAttribute() {
        $btn = '';

        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="ไม่เผยแพร่"><span class="text-danger">ไม่เผยแพร่</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="เผยแพร่"><span class="text-success">เผยแพร่</span></a>';
        }
        return $btn;
    }
    
    public function getStateNameAttribute() {
        $btn = '';

        if( $this->state != 1 ){
            $btn = 'ไม่เผยแพร่';
        }else{
            $btn = 'เผยแพร่';
        }
        return $btn;
    }

    public function FileImageCoverBookManage()
    {
        return $this->hasOne(AttachFileLaw::class, 'ref_id', 'id')->where('ref_table', $this->table)->where('section', 'file_image_cover');
    }

    public function AttachFileBookManage()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_book_manage');
    }

    public function book_type(){
      return $this->belongsTo(LawBookType::class, 'basic_book_type_id');
    }

    public function book_group(){
      return $this->belongsTo(LawBookGroup::class, 'basic_book_group_id');
    }

    public function getBookTypeNameAttribute() {
      return @$this->book_type->title;
    }

    public function getBookGroupNameAttribute() {
        return @$this->book_group->title;
    }

    public function manage_access(){
      return $this->belongsTo(LawBookManageAccess::class, 'id','law_book_manage_id');
    }

    public function getManageAccessJsonAttribute() {
      return @$this->manage_access->access;
    }

    public function getManageAccessNameAttribute() {
        $datas = '';
        $accsess =  ['1'=>'<p class="label label-rounded label-info m-l-5" style="line-height: 2.3;">บุคคลทั่วไป</p>',
                   '2'=>'<p class="label label-rounded label-info m-l-5" style="line-height: 2.3;">เจ้าหน้าที่ สมอ.</p>'
                  ];

        if(!empty($this->ManageAccessJson)){
            $data = json_decode($this->ManageAccessJson);
            if(count($data)> 0){
                foreach ($data as $key => $list) {
                        $datas .= ' '.array_key_exists($list,$accsess)?$accsess[$list]:null;
                }
            }
        }
        return  @$datas;
    }

    public function getManageAccessTextAttribute() {
        $datas = [];
        $accsess =  ['1'=>'บุคคลทั่วไป','2'=>'เจ้าหน้าที่ สมอ.'];
        if(!empty($this->ManageAccessJson)){
            $data = json_decode($this->ManageAccessJson);
            if(count($data)> 0){
                foreach ($data as $key => $list) {
                    $datas[] = array_key_exists($list,$accsess)?$accsess[$list]:null;
                }
            }
        }
        return  implode(',', $datas );
    }

    public function BookManageVisitView(){
        return $this->hasMany(LawBookManageVisit::class,'law_book_manage_id','id')->where('action',1);
    }

    public function BookManageVisitDownload(){
        return $this->hasMany(LawBookManageVisit::class,'law_book_manage_id','id')->where('action',2);
    }

  }
