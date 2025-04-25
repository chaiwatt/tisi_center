<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;

class ConfigsReportPowerBIVisit extends Model
{

    public $timestamps = false;

    /**
      * The database table used by the model.
      *
      * @var string
    */
    protected $table = 'configs_report_power_bi_visit';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                           'session_id',
                           'power_bi_id',
                           'visit_at'
                          ];
}
