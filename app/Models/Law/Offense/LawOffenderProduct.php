<?php

namespace App\Models\Law\Offense;

use Illuminate\Database\Eloquent\Model;

class LawOffenderProduct extends Model
{
                /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_offenders_products';

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
        'law_offender_id',
        'law_cases_id',
        'case_number',
        'detail',
        'amount',
        'unit',
        'total_price',
        'law_offenders_cases_id'

    ];
}
