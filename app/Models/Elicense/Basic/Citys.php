<?php

namespace App\Models\Elicense\Basic;

use Illuminate\Database\Eloquent\Model;

class Citys extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_rbasicdata_citys';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'country_id',
        'state_id',
        'title',
        'state',
        'ordering',
        'created',
        'created_by',
        'modified',
        'modified_by',
        'country_id_old',
        'country_id_new'
        
        
    ];
}
