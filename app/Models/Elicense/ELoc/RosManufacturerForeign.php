<?php

namespace App\Models\Elicense\ELoc;

use Illuminate\Database\Eloquent\Model;

use App\Models\Elicense\Basic\Country;
use App\Models\Elicense\Basic\Citys;
use App\Models\Elicense\Basic\States;

class RosManufacturerForeign extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_manufacturer_foreign';
    public $timestamps = false;

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = [
        'factory_number',
        'name',
        'address',
        'street',
        'city_id',
        'state_id',
        'country_id',
        'email',
        'mobile',
        'tel',
        'fax',
        'status',
        'remark',
        'ordering',
        'state',
        'created',
        'created_by',
        'modified',
        'modified_by',
        'ros_rfome_registeredforeign_id',
        'type_import'
        
    ];

    public function bs_el_country(){
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }  

    public function bs_el_states(){
        return $this->belongsTo(States::class, 'state_id', 'id');
    }  

    public function bs_el_citys(){
        return $this->belongsTo(Citys::class, 'city_id', 'id');
    }  

}
