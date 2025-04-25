<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
 
class CommitteeSpecials extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bcertify_committee_specials';

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
    protected $fillable =   ['committee_group', 'appoint_number', 'appoint_date', 'message', 'user_id', 'authorize_file', 'token'];

    /*
      Sorting
    */
    public $sortable =   ['committee_group', 'appoint_number', 'appoint_date', 'message', 'user_id', 'authorize_file', 'token'];
 


}
