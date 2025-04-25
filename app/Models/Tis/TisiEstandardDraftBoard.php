<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
use App\User;
use App\Models\Tis\EstandardOffers;
class TisiEstandardDraftBoard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft_board';

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
    protected $fillable = ['draft_id', 'draft_plan_id', 'offer_id', 'ordering', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['draft_id', 'draft_plan_id', 'offer_id', 'ordering', 'created_by', 'updated_by'];

    public function user_created(){
       return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
       return $this->belongsTo(User::class, 'updated_by');
    }

    public function estandard_offers_to(){
      return $this->belongsTo(EstandardOffers::class, 'offer_id');
   }
}
