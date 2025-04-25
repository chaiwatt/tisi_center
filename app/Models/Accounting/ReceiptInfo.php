<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;

class ReceiptInfo extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ac_receipt_info';

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
                            'depart_type',
                            'department_name',
                            'state',
                            'created_by',
                            'updated_by',
                            'receipt_no'
        
                        ];
}
