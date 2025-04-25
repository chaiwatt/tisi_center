<?php

namespace App\Models\Bsection5;

use Illuminate\Database\Eloquent\Model;

class ReportTestFactoryDetail extends Model
{
                /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bsection5_report_test_factory_details';

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
        'test_factory_id',
        'test_date',
        'test_finish_date',
        'test_result',
        'test_defect',
        'test_description',
        'test_result_file'
    
    ];
}
