<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Role;
use App\RoleGroup;
use Illuminate\Support\Facades\File;

class RoleSettingGroup extends Model
{
    protected $table = 'roles_setting_groups';
    
    protected $primaryKey = 'id';

    protected $fillable = ['title', 'description', 'state', 'created_by', 'updated_by','urls', 'icons', 'colors','ordering','menu_jsons','displays'];

    public function role()
    {
        return $this->belongsToMany(Role::class, (new RoleGroup)->getTable() , 'setting_systems_id', 'role_id');
    }

    
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
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function getFileMenuJsonAttribute() {
        if ( !empty($this->menu_jsons)  && File::exists(base_path('resources/laravel-admin/'.$this->menu_jsons))) {
            $laravelMenu = json_decode(File::get(base_path('resources/laravel-admin/'.$this->menu_jsons)));
            return $laravelMenu;
        }
    }
}
