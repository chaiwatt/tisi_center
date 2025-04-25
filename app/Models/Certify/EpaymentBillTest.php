<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class EpaymentBillTest extends Model
{
    use Sortable;
    protected $table = "epayment_bill_test";
    protected $primaryKey = 'id';
    protected $fillable = ['Ref1', 'CGDRef1','Amount','Status','BankCode','BillCreateDate','Etc1Data','Etc2Data','InvoiceCode','PaymentDate','ReceiptCode' ,'ReceiptCreateDate' ,'ReconcileDate' ,'SourceID' 
                            ];
 
}
