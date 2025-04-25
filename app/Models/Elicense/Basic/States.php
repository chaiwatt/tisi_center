<?php

namespace App\Models\Elicense\Basic;

use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_rbasicdata_states';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'country_id',
        'code',
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
