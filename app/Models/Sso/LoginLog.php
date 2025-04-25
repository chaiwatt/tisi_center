<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class LoginLog extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    // protected $table = 'tb10_nsw_lite_trader';
    protected $table = 'sso_login_logs';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'session_id',
                            'user_id',
                            'ip_address',
                            'user_agent',
                            'login_at',
                            'logout_at',
                            'last_visit_at',
                            'act_instead',
                            'channel',
                            'app_name'
                        ];

    /*
      Sorting
    */
    public $sortable = [
                        'session_id',
                        'user_id',
                        'ip_address',
                        'user_agent',
                        'login_at',
                        'logout_at',
                        'last_visit_at',
                        'act_instead',
                        'channel',
                        'app_name'
                       ];

    public $timestamps = false;

}
