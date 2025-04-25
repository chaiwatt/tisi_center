<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;

class ApplicationIbcbAudit extends Model
{
    protected $table = 'section5_application_ibcb_audits';

    protected $primaryKey = 'id';
    protected $fillable = [ 
        'application_id',
        'application_no',
        'audit_date',
        'audit_result',
        'audit_remark',
        'send_mail_status',
        'noti_email',
        'created_by',
        'updated_by' 
    ];
                        
    public function getNotiEmailsAttribute() {
        $noti_emails = !empty($this->noti_email)?json_decode($this->noti_email):[];
        $noti_emails = !empty($noti_emails)?implode(',', $noti_emails):null;
        return @$noti_emails;
    }

}
