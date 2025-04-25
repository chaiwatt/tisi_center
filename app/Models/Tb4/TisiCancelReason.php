<?php

namespace App\Models\Tb4;

use Illuminate\Database\Eloquent\Model;

class TisiCancelReason extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb4_cancel_reason';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = [

        'reason',
        'status',
        'type_date',
        'view'

    ];

    public $timestamps = false;

}
