<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlCheckFile extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_check_file';

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
    protected $fillable = ['file', 'id_check','remark_file'];
    /*
      Sorting
    */
    public $sortable = ['file', 'id_check','remark_file'];

}
