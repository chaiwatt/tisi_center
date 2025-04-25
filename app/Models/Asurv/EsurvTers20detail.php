<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\UnitCode;
use App\Models\Asurv\EsurvVolumeTers20Detail;

class EsurvTers20detail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_applicant_20ter_product_details';

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
    protected $fillable = ['detail', 'quantity', 'id_unit', 'unit', 'unit_other','applicant_20ter_id'];
    /*
      Sorting
    */
    public $sortable = ['detail', 'quantity', 'id_unit', 'unit', 'unit_other','applicant_20ter_id'];

    	public function data_unit(){
		return $this->belongsTo(UnitCode::class, 'id_unit', 'id_unit');
	}

	public function informed(){
		return $this->hasMany(EsurvVolumeTers20Detail::class, 'detail_id', 'id');
	}

}
