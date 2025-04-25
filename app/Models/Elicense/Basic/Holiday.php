<?php

namespace App\Models\Elicense\Basic;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $connection = 'mysql_elicense';

    protected $table = 'ros_rbasicdata_holiday';

    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'title_en',
        'fis_year',
        'holiday_date',
        'ordering',
        'state',
        'created_date',
        'created_by',
        'modified',
        'modified_by'
        
    ];

}
