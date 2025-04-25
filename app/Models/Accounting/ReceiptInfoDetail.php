<?php

namespace App\Models\Accounting;

use Illuminate\Database\Eloquent\Model;
use App\User;
use HP;
class ReceiptInfoDetail extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ac_receipt_info_details';

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

                            'taxid',
                            'name',
                            'address',
                            'email',
                            'tel',
                            'bs_bank_id',
                            'bank_book_name',
                            'bank_book_number',
                            'bank_book_file',
                            'state',
                            'created_by',
                            'updated_by',
                            'receipt_no',
                            'receipt_info_id'
        
                        ];
}
