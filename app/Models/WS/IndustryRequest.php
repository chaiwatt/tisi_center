<?php

namespace App\Models\WS;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class IndustryRequest extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ws_industry_requests';

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
    protected $fillable = ['user_id', 'AgentID', 'reason', 'ip_request', 'User_Agent'];

    /*
      Sorting
    */
    public $sortable = ['user_id', 'AgentID', 'reason', 'ip_request', 'User_Agent'];

}
