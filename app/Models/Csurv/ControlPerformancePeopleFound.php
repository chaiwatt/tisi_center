<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlPerformancePeopleFound extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_performance_people_found';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['full_name', 'permission', 'id_perform', 'people_tel', 'people_email'];

    /*
      Sorting
    */
    public $sortable = ['full_name', 'permission', 'id_perform', 'people_tel', 'people_email'];

}
