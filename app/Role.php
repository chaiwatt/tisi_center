<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\RoleGroup;
use App\RoleUser;
use App\User;
use App\RoleSettingGroup;
use App\Models\Sso\User AS SSO_User;
use HP;

class Role extends Model
{
    use Sortable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $fillable = ['name', 'label','user_id','group','description','state', 'level','created_by','updated_by'];

    /**
     * A role may be given various permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Grant the given permission to a role.
     *
     * @param  Permission $permission
     *
     * @return mixed
     */
    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    public function role_group()
    {
        return $this->hasMany(RoleGroup::class, 'role_id');
    }

    public function role_setting_group()
    {
        return $this->belongsToMany(RoleSettingGroup::class, (new RoleGroup)->getTable() , 'role_id', 'setting_systems_id');
    }

    public function getGroupNameAttribute()
    {

        $group = $this->role_setting_group->pluck('title')->toArray();

        if( count($group) >= 1 ){
            return implode( ', ', $group  );
        }else{
            return 'N/A';
        }

    }

    public function users()
    {
        return $this->belongsToMany(User::class, (new RoleUser)->getTable() , 'role_id', 'user_runrecno');
    }

    public function users_sso()
    {
        return $this->belongsToMany(SSO_User::class, (new RoleUser)->getTable() , 'role_id', 'user_id');
    }

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by','runrecno');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }

    public function user_updated()
    {
        return $this->belongsTo(User::class, 'updated_by','runrecno');
    }

    public function getUpdatedNameAttribute()
    {
        return @$this->user_updated->reg_fname . ' ' . @$this->user_updated->reg_lname;
    }

    public function getLatestUpdateAttribute(){
        if(!empty($this->updated_by)){
            $html_updated = '';
            $html_updated .= @$this->user_updated->reg_fname . ' ' . @$this->user_updated->reg_lname;
            $html_updated .= "<br>";
            $html_updated .= HP::DateTimeThai($this->updated_at);
            return $html_updated;
        }else{
            $html_created = '';
            $html_created .= @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
            $html_created .= "<br>";
            $html_created .= HP::DateTimeThai($this->created_at);
            return $html_created;
        }
    }

}
