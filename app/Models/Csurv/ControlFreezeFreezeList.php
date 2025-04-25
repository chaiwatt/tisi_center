<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlFreezeFreezeList extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_freeze_freeze_list';

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
    protected $fillable = ['id_freeze', 'list_freeze', 'amount_freeze', 'unit_freeze', 'value_freeze'];

    /*
      Sorting
    */
    public $sortable = ['id_freeze', 'list_freeze', 'amount_freeze', 'unit_freeze', 'value_freeze'];

}
