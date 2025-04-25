<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
 
class SettingFee extends Model
{

    use Sortable;
    protected $table = "bcertify_setting_fee";
    protected $fillable = [
        'fee_name',
        'fee_ref',
        'fee_ib',
        'fee_cb',
        'fee_lab',
        'fee_start',
        'created_by',
        'updated_by'
    ];


 
}
