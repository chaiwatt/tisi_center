<?php

namespace App\Models\Elicense;

use Illuminate\Database\Eloquent\Model;

use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\RosUsers;

use App\Models\Elicense\Racl\RaclComponent;
use App\Models\Elicense\Racl\RaclPermission;
use App\Models\Elicense\Racl\RaclView;

class RosUserGroup extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table      = 'ros_usergroups';

    public $timestamps    = false;

    protected $fillable   = [ 
     
        'parent_id',
        'lft',
        'rgt',
        'title',
        'sso_group_ids'
        
    ];

    public function users()
    {
        return $this->belongsToMany(RosUsers::class, (new RosUserGroupMap)->getTable() , 'group_id', 'user_id');
    }

    public function racl_view()
    {
        return $this->belongsToMany(RaclView::class, (new RaclPermission)->getTable() , 'group_id', 'view_id');
    }

    public function racl_permission()
    {
        return $this->hasMany(RaclPermission::class, 'group_id', 'id');
    }

}
