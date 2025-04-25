<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Trader extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    // protected $table = 'tb10_nsw_lite_trader';
    protected $table = 'user_trader';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'trader_autonumber';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['trader_id',
                          'trader_date',
                          'trader_id_register',
                          'trader_inti',
                          'trader_operater_name',
                          'trader_type',
                          'trader_password',
                          'trader_address',
                          'trader_address_moo',
                          'trader_address_soi',
                          'trader_address_road',
                          'trader_provinceID',
                          'trader_address_amphur',
                          'trader_address_tumbol',
                          'trader_address_poscode'
                        ];

    /*
      Sorting
    */
    public $sortable = ['trader_date', 'trader_inti', 'trader_operater_name', 'trader_type', 'trader_id', 'agent_email'];

    public function getFormatAddressAttribute() {
        $address   = [];
        $address[] = @$this->trader_address;

        if($this->village_no!='' && $this->trader_address_moo !='-'  && $this->trader_address_moo !='--'){
        $address[] =  "หมู่ที่ " . $this->trader_address_moo;
      }

      if($this->trader_address_soi!='' && $this->trader_address_soi !='-'  && $this->trader_address_soi !='--'){
        $address[] = "ซอย "  . $this->trader_address_soi;
      }

      if($this->trader_address_road !='' && $this->trader_address_road !='-'  && $this->trader_address_road !='--'){
        $address[] =  "ถนน "  . $this->trader_address_road;
      }
      if($this->trader_provinceID!=''){
          $address[] =  "จังหวัด " . $this->trader_provinceID;
        }
      if($this->trader_address_amphur!=''){
          $address[] =  "เขต/อำเภอ " . $this->trader_address_amphur;
      }
      if($this->trader_address_tumbol!=''){
          $address[] =  "แขวง/ตำบล " . $this->trader_address_tumbol;
       }
      if($this->trader_address_poscode!=''){
          $address[] =  "รหัสไปรษณีย " . $this->trader_address_poscode;
      }
      return implode(' ', $address);
    }
}
