<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EsurvBiss21detail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_applicant_21bis_product_details';

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
    protected $fillable = ['detail', 'quantity', 'id_unit', 'unit', 'unit_other', 'applicant_21bis_id'];
    /*
      Sorting
    */
    public $sortable = ['detail', 'quantity', 'id_unit', 'unit', 'unit_other', 'applicant_21bis_id'];

}
