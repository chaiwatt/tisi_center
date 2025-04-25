<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\AttachFile;
class ConfigsFaqs extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'configs_faqs';

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
                            'title',
                            'description',
                            'state',
                            'created_by',
                            'updated_by'
                        ];

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
  
    public function getStateIconAttribute() {
        $btn = '';
  
        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function attach_file_faqs()
    {
        return $this->hasMany(AttachFile::class,'ref_id','id')->where('ref_table',$this->getTable())->where('section','file_faqs_configs');
    }
}

