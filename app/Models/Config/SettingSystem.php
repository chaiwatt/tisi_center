<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Config\SettingSystemGroup;

class SettingSystem extends Model
{

    use Sortable;

       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'setting_systems';

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
                           'title', 'details',
                           'urls', 'icons',
                           'colors', 'state',
                           'created_by',
                           'updated_by', 'updated_at',
                           'transfer_method', 'app_name',
                           'group_id', 'branch_block'
                          ];

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

    public function group(){
        return $this->belongsTo(SettingSystemGroup::class, 'group_id');
    }

    static function transfer_methods(){
        return ['redirect' => 'Redirect', 'form_post' => 'Form Post'];
    }

}
