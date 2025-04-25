<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;

class ReportTestProductDetailItem extends Model
{
                /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_product_details_items';

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
        'detail_id',
        'test_product_id',
        'test_item_id',
        'test_item_name',
        'test_result',
        'state' 
    ];
}
