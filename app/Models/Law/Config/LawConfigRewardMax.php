<?php

namespace App\Models\Law\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
 
use App\User;
use HP;
use App\Models\Law\Basic\LawArrest;
class LawConfigRewardMax extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_config_reward_max';

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
    protected $fillable = ['law_config_section_id', 'law_basic_arrest_id', 'condition_percentage', 'amount', 'condition_money', 'money', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['law_config_section_id', 'law_basic_arrest_id', 'condition_percentage', 'amount', 'condition_money', 'money', 'state', 'created_by', 'updated_by'];

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
  		return @$this->user_created->reg_fname.' '.$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }
     
    // การจับกุม
    public function arrest(){
      return $this->belongsTo(LawArrest::class, 'law_basic_arrest_id');
    }
 
}
