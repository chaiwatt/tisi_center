<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Config\ConfigsFormatCodeSub;
use App\Models\Config\ConfigsFormatCodeLog;

class ConfigsFormatCode extends Model
{
        /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'configs_format_codes';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
    *
    * @var array
    */
    protected $fillable = [
                            'system',
                            'state',
                            'created_by',
                            'updated_by',
                            'created_at',
                            'updated_at',
                        ];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    /* Btn Switch Input*/
    public function getStateIconAttribute(){

        $btn = '';
        if ($this->state == 1) {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'" checked></div>';
        }else {
            $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'"></div>';
        }

        return $btn;

  	}

    public function format_codes_sub_last(){
        return $this->belongsTo(ConfigsFormatCodeSub::class, 'id', 'format_id')->latest();
    }

    public function format_codes_log_last(){
        return $this->belongsTo(ConfigsFormatCodeLog::class, 'id', 'format_id')->latest();
    }
}
