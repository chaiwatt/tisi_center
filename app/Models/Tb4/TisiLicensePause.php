<?php

namespace App\Models\Tb4;

use Illuminate\Database\Eloquent\Model;

class TisiLicensePause extends Model
{
          /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb4_tisilicense_pauses';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'Autono';

    protected $fillable = [

        'tbl_licenseNo',
        'input_data',
        'case_number',
        'date_pause_start',
        'date_pause_end',
        'remark',
        'evidence_file',
        'date_pause_cancel',
        'remark_pause_cancel',
        'pause_cancel_by',
        'pause_cancel_at',
        'created_by',

    ];
}
