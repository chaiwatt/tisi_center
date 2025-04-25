<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Section5\ApplicationInspectorStatus;
class ApplicationInspectorAudit extends Model
{
    protected $table = 'section5_application_inspectors_audit';

    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 

        'application_id',
        'application_no',
        'audit_date',
        'audit_result',
        'audit_remark',
        'send_mail_status',
        'noti_email',
        'audit_approve',
        'audit_approve_description',
        'audit_approve_by',
        'audit_updated_by',
        'audit_approve_at',
        'audit_updated_at',
        'created_by',
        'updated_by',
        'approve_send_mail_status',
        'approve_noti_email'
  
    ];

    /*
      User Relation
    */

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }

    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function audit_created(){
        return $this->belongsTo(User::class, 'audit_approve_by');
    }

    public function audit_updated(){
        return $this->belongsTo(User::class, 'audit_updated_by');
    }

    public function getAuditCreatedNameAttribute() {
        return @$this->audit_created->reg_fname.' '.@$this->audit_created->reg_lname;
    }

    public function getAuditUpdatedNameAttribute() {
        return @$this->audit_updated->reg_fname.' '.@$this->audit_updated->reg_lname;
    }

    public function getNotiEmailsAttribute() {
        $noti_emails = !empty($this->noti_email)?json_decode($this->noti_email):[];
        $noti_emails = !empty($noti_emails)?implode(',', $noti_emails):null;
        return @$noti_emails;
    }

    public function inspector_status(){
        return $this->belongsTo(ApplicationInspectorStatus::class, 'audit_approve', 'id');
    }

    public function getAuditApproveStatusTitleAttribute(){
        $status = !is_null($this->inspector_status) ? $this->inspector_status->title : null ;
        return $status;
    }


}
