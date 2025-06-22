<?php

namespace App\Certificate;

use Illuminate\Database\Eloquent\Model;

class MeetingInvitationSetstandard extends Model
{
    protected $table = 'meeting_invitation_setstandards';
    protected $primaryKey = 'id';

    // กำหนดฟิลด์ที่สามารถ mass-assign ได้ (ถ้าต้องการ)
    protected $fillable = [
        'meeting_invitation_id',
        'setstandard_id',
    ];
}
