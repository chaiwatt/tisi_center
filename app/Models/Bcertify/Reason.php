<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
class Reason extends Model
{

    use Sortable;
    protected $table = "bcertify_reason";
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'condition',
        'state',
        'created_by',
        'updated_by',
        'draft_plan_id'
    ];
 
        /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function getConditionIconAttribute(){
      $btn = '';
      if ($this->condition == 1) {
          $btn = '<span class="js-condition condition"  data-id="'.$this->id.'" data-condition="2"   title="ปิดใช้งาน"> <i class="fa fa-check-circle fa-lg text-success"></i>  </span>';
      }else {
         $btn = '<span class="js-condition condition"  data-id="'.$this->id.'"  data-condition="1" title="เปิดใช้งาน" >  <i class="fa fa-times-circle fa-lg text-danger"></i> </span>';    
      }
      return $btn;
   }
    
    public function getStateIconAttribute(){
        $btn = '';
        if ($this->state == 1) {
            $btn = '<span class="js-state state"  data-id="'.$this->id.'" data-state="2"   title="ปิดใช้งาน"> <i class="fa fa-check-circle fa-lg text-success"></i>  </span>';
        }else {
          $btn = '<span class="js-state state"  data-id="'.$this->id.'"  data-state="1" title="เปิดใช้งาน" >   <i class="fa fa-times-circle fa-lg text-danger"></i> </span>';    
        }
        return $btn;
     }


}
