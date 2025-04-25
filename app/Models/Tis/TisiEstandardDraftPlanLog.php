<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class TisiEstandardDraftPlanLog extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft_plan_logs';

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
    protected $fillable = ['plan_id', 'reverse_user', 'reverse_detail', 'reverse_date', 'update_user', 'update_date', 'update_detail', 'update_status'];

    /*
      Sorting
    */
    public $sortable =    ['plan_id', 'reverse_user', 'reverse_detail', 'reverse_date', 'update_user', 'update_date', 'update_detail', 'update_status'];

    public function reversed_user(){
       return $this->belongsTo(User::class, 'reverse_user');
     }


    public function updated_user(){
       return $this->belongsTo(User::class, 'update_user');
     }

}
