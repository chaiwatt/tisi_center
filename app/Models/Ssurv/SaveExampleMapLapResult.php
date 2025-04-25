<?php

namespace App\Models\Ssurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SaveExampleMapLapResult extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'save_example_map_lap_result';

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
    protected $fillable = ['map_lab_detail_id', 'test_no', 'test_result'];

    /*
      Sorting
    */
    public $sortable = ['map_lab_detail_id', 'test_no', 'test_result'];

    public function map_lab_detail(){
        return $this->belongsTo(SaveExampleMapLapDetail::class, 'map_lab_detail_id', 'id');
    }

}
