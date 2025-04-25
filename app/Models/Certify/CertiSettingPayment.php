<?php

namespace App\Models\Certify;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
 
class CertiSettingPayment extends Model
{
    use Sortable;
    protected $table = "app_certi_setting_api_payment";
    protected $primaryKey = 'id';
    protected $fillable = ['certify','type','description','data','pid','payin'];
  
}
