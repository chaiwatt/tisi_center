<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\BoardType;

class AppointBoardType extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_appoint_board_types';

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
    protected $fillable = ['appoint_id', 'board_type_id', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['appoint_id', 'board_type_id', 'created_by', 'updated_by'];

    /* board_type */
    public function board_type(){
      return $this->belongsTo(BoardType::class, 'board_type_id');
    }

}
