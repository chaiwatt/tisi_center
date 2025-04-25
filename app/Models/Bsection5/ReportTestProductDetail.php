<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;

class ReportTestProductDetail extends Model
{
            /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_product_details';

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
        'test_product_id',
        'sample_bill_no',
        'product_detail',
        'sample_no',
        'sample_qty'
    ];
}
