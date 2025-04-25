<?php

namespace App\Models\Esurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Soko extends Model
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
    protected $fillable = [
        'trader_id',
        'trader_date',
        'trader_inti',
        'trader_operater_name',
        'trader_type',
        'trader_name',
        'trader_id',
        'trader_id_register',
        'trader_boss' ,
        'trader_address',
        'trader_address_soi',
        'trader_address_road',
        'trader_address_moo',
        'trader_address_tumbol',
        'trader_address_amphur',
        'trader_provinceID',
        'trader_address_poscode',
        'trader_phone',
        'trader_phone_to',
        'trader_fax',
        'trader_fax_to',
        'trader_mobile',
        'agent_name',
        'agent_position',
        'agent_email',
        'agent_mobile',
        'agent_officephone',
        'agent_officephone_to',
        'agent_fax',
        'agent_offficefax_to',
        'date_of_data',
        'trader_username',
        'trader_password',
        'trader_file_detial',
        'trader_remark',
        'trader_remarkby',
        'trader_remarkdate',
        'deleted_at',
        'params',
        'updated_at',
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
