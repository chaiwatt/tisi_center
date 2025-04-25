<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Tis\Board;

class AppointBoard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_appoint_boards';

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
    protected $fillable = ['appoint_id', 'department_set', 'appoint_department_id', 'board_id', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['appoint_id', 'department_set', 'appoint_department_id', 'board_id', 'created_by', 'updated_by'];

    /* product_group */
    public function product_group(){
      return $this->belongsTo(Board::class, 'board_id');
    }

}
