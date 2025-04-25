<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class ConfigsReportPowerBIGroup extends Model
{

    use Sortable;

       /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'configs_report_power_bi_group';

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
                           'title',
                           'ordering',
                           'created_by',
                           'updated_by',
                           'updated_at'
                          ];

    /*
      Sorting
    */
    public $sortable = [
                        'title',
                        'ordering',
                        'created_by',
                        'updated_by',
                        'updated_at'
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

    public function config_report_power_bis(){
        return $this->hasMany(ConfigsReportPowerBI::class, 'group_id');
    }

}
