<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\RoleGroup;
use App\RoleSettingGroup;

class RoleUser extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'role_user';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['role_id', 'user_trader_autonumber', 'user_runrecno', 'user_id'];

    public $timestamps = false;

    //Reletion Role
    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function role_setting_group()
    {
        return $this->belongsToMany(RoleSettingGroup::class, (new RoleGroup)->getTable() , 'role_id', 'setting_systems_id');
    }

}
