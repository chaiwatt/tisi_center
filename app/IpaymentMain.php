<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class IpaymentMain extends Model
{
    use Sortable;

     
    protected $table = 'iPayment_Main';

    protected $primaryKey = 'Runrecno';
    protected $fillable = [
                            'Status',
                            'StatusRecive',
                            'BillNo',
                            'DateMake',
                            'CompanyCode',
                            'TaxId',
                             'Ref_1',
                             'CustName',
                             'CustName_1',
                             'CustAddress',
                             'Email',
                             'Ref_2',
                             'did',
                             'depart_name',
                             'depart_nameShort',
                             'sub_id',
                             'sub_departname',
                             'sub_depart_shortname',
                             'Ex_id',  
                             'Ex_name',
                             'Ex_Descrip',
                             'BookNo',
                             'BookDate',
                             'MoneyBill',
                             'BarCode',
                             'WantBaiS',
                             'BaiSName',
                             'Note',
                             'User_Name', 
                             'User_From', 
                             'User_date',
                             'MachineNo',
                             'Rec_id',
                             'Date_Juy',
                             'Chanel_name',
                             'IsShowInHelp'
                            ];
    public $timestamps = false;
}
