<?php

namespace App\Models\Law\Cases;

use Illuminate\Database\Eloquent\Model;

class LawCasesCompareCalculate extends Model
{
          /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_compare_calculates';
  
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

    protected $fillable =
    [
        'law_case_result_section_id',
        'cal_type',
        'division',
        'total_value',
        'amount',
        'mistake',
        'law_cases_id',
        'created_by'

    ];

}
