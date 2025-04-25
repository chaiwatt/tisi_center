<?php

namespace App\Models\Law\Offense;

use Illuminate\Database\Eloquent\Model;
use App\Models\Basic\Tis;

class LawOffenderStandard extends Model
{
            /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_offenders_standards';

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
        'tis_id',
        'tb3_tisno',
        'law_offenders_cases_id',
        'tis_name'
    ];

    /* มอก. */
    public function tis_data(){
        return $this->belongsTo(Tis::class, 'tis_id');
    }
    
}
