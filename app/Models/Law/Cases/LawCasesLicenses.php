<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;


use App\Models\Basic\TisiLicense;

class LawCasesLicenses extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_licenses';
  
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
    protected $fillable = ['law_case_id', 'ref_no', 'license_number'];


    public function law_cases(){
      return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }

    public function tisi_license(){
      return $this->belongsTo(TisiLicense::class, 'license_number', 'tbl_licenseNo');
    }
}
