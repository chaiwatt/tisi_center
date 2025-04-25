<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
class ApplicationLabStaff extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_application_labs_staff';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
                            'application_lab_id',
                            'application_no',
                            'staff_id',
                            'assign_date',
                            'assign_comment',
                            'created_by'
                        ];

    public function user_staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getStaffNameAttribute() {
        return @$this->user_staff->reg_fname.' '.@$this->user_staff->reg_lname;
    }

}
