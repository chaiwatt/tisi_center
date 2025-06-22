<?php

namespace App\Certificate;

use App\CommitteeSpecial;

use App\Models\Besurv\Signer;
use App\Models\Certify\SetStandards;
use Illuminate\Database\Eloquent\Model;

class MeetingInvitation extends Model
{
    protected $table = 'meeting_invitations';
    protected $primaryKey = 'id';
    protected $fillable = [
        'type',              // ประเภท เช่น "เชิญประชุมอนุกรรมการวิชาการ"
        'reference_no',      // หมายเลขอ้างอิง
        'date',              // วันที่
        'subject',           // เรื่อง
        'attachments',       // สิ่งที่ส่งมาด้วย
        'details',           // รายละเอียด
        'ps_text',           // รายละเอียด
        'qr_file_path',      // เก็บชื่อไฟล์ QR
        "google_form_qr",
        'committee_special_id', // รหัสคณะกรรมการพิเศษ
        'signer_id',         // รหัสผู้ลงนาม
        'signer_position',         // รหัสผู้ลงนาม
        'status',
    ];


    // ความสัมพันธ์กับ Signer
    public function signer()
    {
        return $this->belongsTo(Signer::class, 'signer_id', 'id');
    }

    // ความสัมพันธ์กับ SetStandards (many-to-many)
    public function setStandards()
    {
        return $this->belongsToMany(SetStandards::class, 'meeting_invitation_setstandards', 'meeting_invitation_id', 'setstandard_id');
    }

    // ความสัมพันธ์กับ CommitteeSpecial (many-to-many)
    public function committeeSpecials()
    {
        return $this->belongsToMany(CommitteeSpecial::class, 'meeting_invitation_committee_specials', 'meeting_invitation_id', 'committee_special_id');
    }
}
