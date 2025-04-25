<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlFreezeSeizureList extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_freeze_seizure_list';

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
    protected $fillable = ['id_freeze', 'list_seizure', 'amount_seizure', 'unit_seizure', 'value_seizure'];

    /*
      Sorting
    */
    public $sortable = ['id_freeze', 'list_seizure', 'amount_seizure', 'unit_seizure', 'value_seizure'];

}
