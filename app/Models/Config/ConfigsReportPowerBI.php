<?php

namespace App\Models\Config;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Config\ConfigsReportPowerBIRole;
use App\Models\Config\ConfigsReportPowerBIVisit;

class ConfigsReportPowerBI extends Model
{

    use Sortable;

        /**
     * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'configs_report_power_bi';

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
                            'group_id',
                            'url',
                            'state',
                            'role_all',
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

    public function group(){
        return $this->belongsTo(ConfigsReportPowerBIGroup::class, 'group_id');
    }

    public function roles(){
        return $this->hasMany(ConfigsReportPowerBIRole::class, 'power_bi_id');
    }

    public function visits(){
        return $this->hasMany(ConfigsReportPowerBIVisit::class, 'power_bi_id');
    }

    public function check_role($user_roles=null){
        if($this->role_all==1){//ดูได้ทุกกลุ่ม
            return true;
        }else {
            $roles      = $this->roles()->pluck('role_id');
            $user_roles = is_null($user_roles) ? auth()->user()->roles()->pluck('role_id') : $user_roles;
            $math_roles = $roles->intersect($user_roles);
            return count($math_roles) > 0 ? true : false ;
        }
    }

    public function visit_count(){
        return $this->visits()->count();
    }

}
