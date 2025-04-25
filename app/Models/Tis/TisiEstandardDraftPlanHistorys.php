<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
 
use App\User;
 


class TisiEstandardDraftPlanHistorys extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft_plan_historys';
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
                              'draft_plan_id',
                              'data_field',
                              'data_old',
                              'data_new',
                              'state',
                              'created_at',
                              'created_by',
                              'editor_type'
                          ];

    /*
      Sorting
    */
    public $sortable = [
                              'draft_plan_id',
                              'data_field',
                              'data_old',
                              'data_new',
                              'state',
                              'created_at',
                              'created_by',
                              'editor_type'
                          ];


    
    /* บันทึกข้อมูล */
    static function Add($draft_plan_id, $data_field, $data_old, $data_new, $state = 1){
 
      $history                    = new TisiEstandardDraftPlanHistorys;
      $history->draft_plan_id     = $draft_plan_id;
      $history->data_field        = $data_field;
      $history->data_old          = $data_old;
      $history->data_new          = $data_new;
      $history->state             =   $state ;
      $history->created_by        = auth()->user()->getKey();
      $history->created_at        = date('Y-m-d H:i:s');
      $history->save();

  }
    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
 
}
