<?php

namespace App\Models\Law\Config;

use HP;
use App\User;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Config\LawConfigNotification;

class LawConfigNotificationDetail extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_config_notification_detail';

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
    protected $fillable = ['law_config_notification_id', 'color', 'condition' ,'amount', 'created_by', 'updated_by'];

    /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function law_config_notification(){
      return $this->belongsTo(LawConfigNotification::class, 'law_config_notification_id');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

}
