<?php

namespace App;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class LawLogSendmailSuccess extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    // protected $table = 'tb10_nsw_lite_trader';
    protected $table = 'law_log_sendmail_successe';
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
                            'ref_table',
                            'ref_id',
                            'state'
                        ];

}
