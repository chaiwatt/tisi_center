<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;

class ReportTestProductDetailResult extends Model
{
                    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_product_details_result';

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
    protected $fillable = [
        'item_id',
        'test_no',
        'test_result'
    ];
}
