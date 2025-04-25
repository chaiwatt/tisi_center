<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;

class SettingRunningSub extends Model
{
        /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'bcertify_setting_running_subs';

    /**
     * Attributes that should be mass-assignable.
    *
    * @var array
    */
    protected $fillable = [
                            'format',
                            'data',
                            'sub_data',
                            'format_id'
                        ];
}
