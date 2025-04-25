<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlFollow extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_follow';

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
    protected $fillable = ['make_annual', 'check_officer'];
    /*
      Sorting
    */
    public $sortable = ['make_annual', 'check_officer'];

}
