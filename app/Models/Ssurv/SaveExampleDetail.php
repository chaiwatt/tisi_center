<?php

namespace App\Models\Ssurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Esurv\TisiLicenseDetail;

class SaveExampleDetail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'save_example_detail';

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
    protected $fillable = ['num_row', 'detail_volume', 'number', 'unit', 'num_ex', 'id_example', 'sum', 'action'];

    /*
      Sorting
    */
    public $sortable = ['num_row', 'detail_volume', 'number', 'unit', 'num_ex', 'id_example', 'sum', 'action'];


    public function license_detail(){
        return $this->belongsTo(TisiLicenseDetail::class, 'detail_volume');
    }

    public function getSizeDetialAttribute(){
        return @$this->license_detail->sizeDetial;
    }

}
