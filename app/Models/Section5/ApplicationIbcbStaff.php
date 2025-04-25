<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\User;
class ApplicationIbcbStaff extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_application_ibcb_staff';

    protected $primaryKey = 'id';
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
                            'application_id',
                            'application_no',
                            'staff_id',
                            'assign_date',
                            'created_by'
                        ];

    public function staff(){
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function getStaffNameAttribute() {
        return @$this->staff->reg_fname.' '.@$this->staff->reg_lname;
    }
}
