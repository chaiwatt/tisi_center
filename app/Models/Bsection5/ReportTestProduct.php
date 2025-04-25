<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bsection5\ReportTestProductDetail;

class ReportTestProduct extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_product';

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
        'sample_id',
        'sample_bill_no',
        'lab_code',
        'lab_name',
        'tis_no',
        'trader_name',
        'trader_taxid',
        'trader_email',
        'sample_from',
        'department',
        'sub_department',
        'receive_date',
        'test_date',
        'test_finish_date',
        'test_duration',
        'test_price',
        'total_test_price',
        'report_date',
        'payment_date',
        'ref_report_no',
        'remark',
        'created_by',
        'updated_by',
        'type'

    ];

    public function TestProductDetailData()
    {
        return $this->hasMany(ReportTestProductDetail::class,'test_product_id', 'id');
    }
}
