<?php

namespace App\Models\Elicense;

use Illuminate\Database\Eloquent\Model;

class RosUserGroupMap extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_user_usergroup_map';

    public $timestamps = false;

    protected $fillable   = [ 
     
        'user_id',
        'group_id',
        
    ];


}
