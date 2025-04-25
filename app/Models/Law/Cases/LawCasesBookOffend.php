<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;

use App\User;
 

class LawCasesBookOffend extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_book_offend';
  
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

        'law_case_result_id',
        'created_by',
        'updated_by',
        'book_title',
        'book_date',
        'book_to',
        'book_enclosure',
        'offend_act',
        'offend_report',
        'law_cases_id',
        'lawyer_id',
        'offend_found',
        'book_number'
        
    ];

    protected $casts    = ['book_date' => 'json', 'offend_act' => 'json', 'offend_report' => 'json', 'book_enclosure' => 'json'];

        /*
      User Relation
    */
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

    public function law_cases_result_to(){
        return $this->belongsTo(LawCasesResult::class, 'law_case_result_id');
    }
 
    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'law_cases_id');
    }

    // นิติกรผู้รับผิดชอบ
    public function user_lawyer(){
        return $this->belongsTo(User::class, 'lawyer_id');
    }
}
