<?php

namespace App\Models\Elicense\Racl;

use Illuminate\Database\Eloquent\Model;

class RaclComponent extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_racl_component';

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
        'title',
        'descript'
    ];

    public function racl_view_list()
    {
        return $this->hasMany(RaclView::class, 'ref_id','id');
    }


}
