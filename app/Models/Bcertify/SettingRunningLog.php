<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SettingRunningLog extends Model
{
    /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'bcertify_setting_running_logs';

    /**
     * Attributes that should be mass-assignable.
    *
    * @var array
    */
    protected $fillable = [
                            'format',
                            'data',
                            'version',
                            'sub_data',
                            'format_id',
                            'start_date', 
                            'end_date', 
                            'state',
                            'system'
                        ];

     /* Btn Switch Input*/
    public function getStateIconAttribute(){

        $btn = '';
        if ($this->state == 1) {
            $btn = '<span class="text-success">ใช้งาน</span>';
        }else {
            $btn = '<span class="text-danger">ปิดใช้งาน</span>';
        }

        return $btn;

  	}
}
