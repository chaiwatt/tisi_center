<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class ConfigsReportPowerBIRole extends Model
{
    
    public $timestamps = false;

    /**
      * The database table used by the model.
      *
      * @var string
    */
    protected $table = 'configs_report_power_bi_role';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                           'power_bi_id',
                           'role_id'
                          ];
}
