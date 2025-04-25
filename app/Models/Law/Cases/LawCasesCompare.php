<?php

namespace App\Models\Law\Cases;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Cases\LawCasesCompareBook;
use App\Models\Law\Cases\LawCasesCompareCalculate;

class LawCasesCompare extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_compare';
  
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
        'law_cases_id','case_number','book_number','book_date','total','remark','created_by','updated_by'
    ];

  

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    // ผลเปรียบเทียบปรับ
    public function law_cases_compare_amounts_many() {
      return $this->hasMany(LawCasesCompareAmounts::class, 'law_case_compare_id');
    }
    
    // หนังสือแจ้งปรับเปรียบเทียบ
    public function file_law_cases_compare_to()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','compare')->orderby('id','desc');
    }
  
    public function compare_book(){
        return $this->belongsTo(LawCasesCompareBook::class, 'id', 'law_case_compare_id');
    }

    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'law_cases_id');
    }

    public function compare_calculate(){
        return $this->hasMany(LawCasesCompareCalculate::class, 'law_case_compare_id');
    }

}
