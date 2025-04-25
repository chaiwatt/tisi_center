<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Bsection5\ReportTestFactoryDetail;

class ReportTestFactory extends Model
{
            /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_factory';

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
        'ib_code',
        'ib_name',
        'tis_no',
        'trader_name',
        'trader_taxid',
        'factory_request_no',
        'test_price',
        'payment_date',
        'test_result',
        'ref_report_no',
        'test_result_file',
        'remark',
        'created_by',
        'updated_by'
    ];

    public function TestFactoryDetailData()
    {
        return $this->hasMany(ReportTestFactoryDetail::class,'test_factory_id', 'id');
    }
}
