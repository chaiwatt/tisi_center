<?php

namespace App\Models\Elicense\ELoc;

use Illuminate\Database\Eloquent\Model;
use App\Models\Elicense\Tis\RosStandardTisi;
use App\Models\Elicense\ELoc\RosManufacturerForeign;


class RosManufacturerForeignScope extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_manufacturer_foreign_scope';
    public $timestamps = false;

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';


    protected $fillable = [
        'ros_manufacturer_foreign_id',
        'tis_standards_tisno',
        'status_id',
        'cer_on',
        'start_date',
        'end_date',
        'date_registration',
        'date_expiry',
        'age',
        'ordering',
        'created',
        'created_by',
        'modified',
        'modified_by',
        'type_import'
    ];

    public function tis_standard(){
        return $this->belongsTo(RosStandardTisi::class, 'tis_standards_tisno', 'tis_number');
    }  

    public function manufacturer_foreign(){
        return $this->belongsTo(RosManufacturerForeign::class, 'ros_manufacturer_foreign_id', 'id');
    }  

}
