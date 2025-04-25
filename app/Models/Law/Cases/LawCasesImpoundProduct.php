<?php

namespace App\Models\Law\Cases;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;

use App\Models\Law\Cases\LawCasesProductResult;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesForm;


class LawCasesImpoundProduct extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_impound_products';
  
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
        'law_case_impound_id','detail','amount_impounds','amount_keep','unit','price', 'total', 'total_price','created_by','updated_by'
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
  
    public function getStateIconAttribute() {
        $btn = '';
  
        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function cases_impound(){
        return $this->belongsTo(LawCasesImpound::class, 'law_case_impound_id');
    }

    public function getCasesIDAttribute() {
        return @$this->cases_impound->law_case_id;
    }

    public function law_cases(){
        return $this->belongsTo(LawCasesForm::class, 'CasesID' );
    }

    public function cases_result(){
        return $this->belongsTo(LawCasesResult::class, 'CasesID', 'law_case_id');
    }
}
