<?php

namespace App\Models\Tb4;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tb4\TisiCancelReason;

class TisiLicenseCancel extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb4_tisilicense_cancel';
  
    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'Autono';

    protected $fillable = [

        'tbl_licenseNo',
        'tbl_tisiNo',
        'tbl_tradeName',
        'tbl_cancelDate',
        'tbl_depart',
        'tbl_sub',
        'reason_code',
        'tbl_reason',
        'pdf_path',
        'crby',
        'date_create',
        'updateby',
        'date_update',
        'input_data',
        'case_number'

    ];

    public $timestamps = false;

    public function cancel_reason(){
        return $this->belongsTo(TisiCancelReason::class, 'reason_code');
    }


}
