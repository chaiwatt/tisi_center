<?php

namespace App\Models\Law\Cases;

use App\User;
 
use Illuminate\Database\Eloquent\Model;

 

class LawCasesPaymentsDetail extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_payments_detail';
  
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
        'law_case_payments_id','fee_name','amount','remark_fee_name', 'created_by' 
    ];

  

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
 
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
 
   
}
