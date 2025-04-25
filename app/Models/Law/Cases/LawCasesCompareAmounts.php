<?php

namespace App\Models\Law\Cases;

use App\User;
 
use Illuminate\Database\Eloquent\Model;

 

class LawCasesCompareAmounts extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_compare_amounts';
  
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
        'law_case_compare_id','detail_amounts','amount', 'created_by' 
    ];

  

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
 
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
 
   
}
