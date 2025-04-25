<?php

namespace App\Models\Law\Cases;

use App\User;
 
use Illuminate\Database\Eloquent\Model;

class LawCasesCompareBook extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_compare_book';
  
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

        'book_number',
        'book_date',
        'title',
        'send_to',
        'refer',
        'offend_name',
        'offend_address',
        'detail',
        'amount',
        'created_by',
        'updated_by',
        'law_cases_id'

    ];

    protected $casts = ['book_date' => 'json', 'refer' => 'json'];
  
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
 
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
}
