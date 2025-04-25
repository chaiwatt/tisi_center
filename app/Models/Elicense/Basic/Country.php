<?php

namespace App\Models\Elicense\Basic;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_rbasicdata_country';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'title',
        'title_en',
        'full_title',
        'full_title_en',
        'timezone',
        'initial',
        'ordering',
        'state',
        'checked_out',
        'checked_out_time',
        'created',
        'created_by',
        'modified',
        'modified_by'
    ];
}
