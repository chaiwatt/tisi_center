<?php

namespace App\Models\Besurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class InspectorInspectorType extends Model
{
    use Sortable;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'besurv_inspector_inspector_types';

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
    protected $fillable = ['inspector_id', 'inspector_type_id'];

    /*
      Sorting
    */
    public $sortable = ['inspector_id', 'inspector_type_id'];

}
