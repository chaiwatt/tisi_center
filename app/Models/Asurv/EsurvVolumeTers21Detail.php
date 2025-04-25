<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EsurvVolumeTers21Detail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_21ter_product_details';

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
    protected $fillable = ['volume_21ter_id', 'detail_id', 'quantity', 'quantity_export', 'created_at','updated_at'];
    /*
      Sorting
    */
    public $sortable = ['volume_21ter_id', 'detail_id', 'quantity', 'quantity_export', 'created_at','updated_at'];

    public function esurv_volume_ters21(){
      return $this->belongsTo(EsurvVolumeTers21::class, 'volume_21ter_id');
    }
}
