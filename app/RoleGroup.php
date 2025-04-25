<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Models\Config\SettingSystem;

class RoleGroup extends Model
{
    public $timestamps = false;

    protected $table = 'roles_groups';
    
    protected $fillable = ['role_id', 'setting_systems_id'];

    //Reletion Role
    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    //Reletion Role
    public function setting_system(){
        return $this->belongsTo(RoleSettingGroup::class, 'setting_systems_id');
    }
    
}
