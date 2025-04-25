<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
class IpaymentCompanycode extends Model
{
    use Sortable;


     
    protected $table = 'iPayment_CompanyCode';

    protected $primaryKey = 'Runrecno';
    protected $fillable = [
                            'PrefixBillNo',
                            'BillNo',
                            'CompanyCode',
                            'ProductName',
                            'CompanyId',
                            'CompanyName',
                             'TaxidAndServiceCode',
                             'AutoOrManual',
                             'Header_1',
                             'Header_2',
                             'Header_3',
                             'Header_4',
                             'Footer_1',
                             'Footer_2' 
                            ];
 
}
