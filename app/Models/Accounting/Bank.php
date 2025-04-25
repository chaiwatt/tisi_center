<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;

class Bank extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_banks';

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
                            'image', 
                            'bank_code',
                            'title', 
                            'title_en',
                            'title_short',
                            'state',
                            'created_by', 
                            'updated_by',
                            'com_code'
                        ];



    /* User Relation */
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

    /* Btn Switch Input*/
    public function getStateIconAttribute() {
        $btn = '';

        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

}
