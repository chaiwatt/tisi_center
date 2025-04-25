<?php

namespace App\Models\Law\Log;

use Illuminate\Database\Eloquent\Model;

use App\Models\Law\Log\LawNotify;
use App\Models\Law\Log\LawNotifyUser;
use App\User;
use Illuminate\Support\Facades\Auth;

class LawSystemCategory extends Model
{
           /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_system_categories';

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
    protected $fillable = ['name', 'color', 'state', 'state_notify', 'updated_by', 'created_by'];


    public function color_list() {
        return ['danger' => 'แดง', 'warning' => 'เหลือง', 'success' => 'เขียว'];
    }

    public function getColorHtmlAttribute() {
        return !empty($this->color) ?'<i class="mdi mdi-solid pre-icon bg-color '. $this->color .'" data-color="'. $this->color .'"></i>':null;
    }

    public function law_notify(){
        return $this->hasMany(LawNotify::class, 'law_system_category_id','id');
    }

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

    public function getNotifyIconAttribute() {
        $btn = '';

        if( !in_array($this->state_notify,[1]) ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_notify" data-id="'.($this->id).'"  data-state="1" title="เปิด"><span class="text-danger">ปิด</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_notify" data-id="'.($this->id).'"  data-state="0" title="ปิด"><span class="text-success">เปิด</span></a>';
        }
        return $btn;
    }



}
