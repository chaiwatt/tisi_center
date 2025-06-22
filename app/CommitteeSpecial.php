<?php

namespace App;

use App\User;
use App\CommitteeLists;

use Illuminate\Support\Facades\DB;
use   App\Models\Basic\ProductGroup;
use App\Certificate\MeetingInvitation;
use Illuminate\Database\Eloquent\Model;

class CommitteeSpecial extends Model
{
    protected $table = 'bcertify_committee_specials'; 

    protected $fillable = [
        'committee_group',
        'appoint_number',
        'expert_group_id',
        'appoint_date',
        'message',
        'authorize_file',
        'user_id',
        'faculty',
        'faculty_no',
        'product_group_id',
        'token'
    ];

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function in_department()
    {
        return $this->hasMany('App\CommitteeInDepartment','committee_special_id');
    }

    public function get_user()
    {
        return $this->hasOne('App\User','runrecno','user_id');
    }

    public function get_user_to()
    {
        return $this->belongsTo(User::class,'user_id','runrecno');
    }


    public function user_FullName()
    {
        $user = $this->get_user()->first();
        $name = @$user->reg_fname.' '.@$user->reg_lname;
        return $name;
    }

    public static function boot() {
        parent::boot();
        static::deleting(function($committeeInDepartment) {
            $committeeInDepartment->in_department()->delete();
            DB::table('appointment_files')->where('committee_special_id',$committeeInDepartment->id)->delete();
        });
    }
    public function product_group_to()
    {
        return $this->belongsTo(ProductGroup::class,'product_group_id');
    }

    // ความสัมพันธ์ one-to-many กับ CommitteeLists
    public function committeeLists()
    {
        return $this->hasMany(CommitteeLists::class, 'committee_special_id', 'id');
    }

    // ความสัมพันธ์กับ MeetingInvitation (many-to-many)
    public function meetingInvitations()
    {
        return $this->belongsToMany(MeetingInvitation::class, 'meeting_invitation_committee_specials', 'committee_special_id', 'meeting_invitation_id');
    }
}
