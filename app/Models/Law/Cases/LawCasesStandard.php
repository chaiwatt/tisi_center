<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;


use App\Models\Basic\Tis;

class LawCasesStandard extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_standard';
  
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
    protected $fillable = ['law_cases_id', 'ref_no', 'tis_id', 'tb3_tisno'];

    
    public function law_cases(){
      return $this->belongsTo(LawCasesForm::class, 'law_cases_id');
    }

    public function tis(){
      return $this->belongsTo(Tis::class, 'tis_id');
  }
}
