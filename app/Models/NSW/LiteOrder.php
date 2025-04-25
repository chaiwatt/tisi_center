<?php

namespace App\Models\NSW;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LiteOrder extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb10_nsw_lite_order';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'auto_no';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [];


}
