<?php

namespace App\Models\Law\Basic;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Law\Basic\LawBookGroup;
use App\Models\Law\Books\LawBookManage;
class LawBookType extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_basic_book_type';

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
    protected $fillable = ['title', 'book_group_id', 'icons', 'state', 'created_by', 'updated_by'];
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
      return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
    }

    public function getUpdatedNameAttribute() {
        return (@$this->user_updated->reg_fname).(!empty($this->user_updated->reg_lname)?' '.$this->user_updated->reg_lname:null);
    }

    public function law_book_group(){
      return $this->belongsTo(LawBookGroup::class, 'book_group_id');
    }

    public function getBookGroupTitleAttribute() {
      return @$this->law_book_group->title;
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

    public function getIconAttribute() {
      return @'<i class="mdi '.$this->icons.' pre-icon"></i>';
    }
    
    public function law_book_manage(){
        return $this->hasMany(LawBookManage::class, 'basic_book_type_id', 'id');
    }

}
