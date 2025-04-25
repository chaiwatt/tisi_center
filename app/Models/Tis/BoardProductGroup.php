<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\ProductGroup;

class BoardProductGroup extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_board_product_groups';

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
    protected $fillable = ['board_id', 'product_group_id', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['board_id', 'product_group_id', 'created_by', 'updated_by'];

    /* product_group */
    public function product_group(){
      return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

}
