<?php

namespace App\Certificate;

use Illuminate\Database\Eloquent\Model;

class MeetingInvitationCommitteeSpecial extends Model
{
    protected $table = 'meeting_invitation_committee_specials';
    protected $primaryKey = 'id';

    // กำหนดฟิลด์ที่สามารถ mass-assign ได้ (ถ้าต้องการ)
    protected $fillable = [
        'meeting_invitation_id',
        'committee_special_id',
    ];
}
