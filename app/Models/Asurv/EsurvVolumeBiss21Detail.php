<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EsurvVolumeBiss21Detail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_21bis_product_details';

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
    protected $fillable = ['volume_21bis_id', 'detail_id', 'quantity', 'created_at','updated_at'];
    /*
      Sorting
    */
    public $sortable = ['volume_21bis_id', 'detail_id', 'quantity', 'created_at','updated_at'];

}
