<?php

namespace App\Models\Ssurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Bsection5\TestItem;

class SaveExampleMapLapDetail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'save_example_map_lap_detail';

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
    protected $fillable = ['test_id', 'example_id', 'test_item_id', 'test_result'];

    /*
      Sorting
    */
    public $sortable = ['test_id', 'example_id', 'test_item_id', 'test_result'];

    public function test_item(){
        return $this->belongsTo(TestItem::class, 'test_item_id');
    }

    public function results(){
        return $this->hasMany(SaveExampleMapLapResult::class, 'map_lab_detail_id');
    }

}
