<?php

namespace App\Models\Law\Cases;

use Illuminate\Database\Eloquent\Model;

use App\User;

class LawCasesFactBook extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_cases_fact_books';
  
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

        'created_by',
        'updated_by',
        'fact_book_numbers',
        'fact_book_date',
        'fact_offend_name',
        'fact_detection_date',
        'fact_locale',
        'fact_maker_by',
        'fact_lawyer_by',
        'law_cases_id',
        'fact_license_currently', 
        'fact_product_marking',
        'fact_product_sell', 
        'fact_product_reclaim'
        
    ];

    protected $casts = ['fact_book_date' => 'json'];
}
