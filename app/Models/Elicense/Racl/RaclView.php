<?php

namespace App\Models\Elicense\Racl;

use Illuminate\Database\Eloquent\Model;

use App\Models\Elicense\Racl\RaclPermission;

class RaclView extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_racl_view';

    public $timestamps = false;

    protected $fillable   = [ 
        
        'name',
        'state',
        'checked_out',
        'checked_out_time',
        'ordering',
        'created',
        'created_by',
        'modified',
        'modified_by',
        'ref_id',
        'title'
    ];

    public function racl_permission_list()
    {
        return $this->hasMany(RaclPermission::class, 'view_id','id');
    }
}
