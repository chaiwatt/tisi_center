<?php

namespace App\Models\Elicense\Racl;

use Illuminate\Database\Eloquent\Model;

class RaclPermission extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_racl_permission';

    public $timestamps = false;

    protected $fillable   = [

        'ordering',
        'state',
        'checked_out',
        'checked_out_time',
        'created_by',
        'stateadd',
        'stateedit',
        'stateeditown',
        'statedelete',
        'stateprint',
        'stateexcel',
        'statecopy',
        'stateapply',
        'statecheckout',
        'stateaccess',
        'group_id',
        'view_id',
        'modified_date'
        
    ];

}
