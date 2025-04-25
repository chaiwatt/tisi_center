<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Maplab extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ros_rbasicdata_maplab';

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
    protected $fillable = ['lab_id', 'std_id', 'product_detail', 'ordering', 'state', 'checked_out_time', 'checked_out', 'created', 'created_by', 'modified', 'modified_by'];

    /*
      Sorting
    */
    public $sortable = ['lab_id', 'std_id', 'product_detail', 'ordering', 'state', 'checked_out_time', 'checked_out', 'created', 'created_by', 'modified', 'modified_by'];

}
