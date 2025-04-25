<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ApplicationIbcbReport extends Model
{
    protected $table = 'section5_application_ibcb_reports';

    protected $primaryKey = 'id';
    protected $fillable = [ 
        'application_id',
        'application_no',
        'report_date',
        'report_by',
        'report_description',
        'report_approve',
        'report_approve_description',
        'report_approve_by',
        'report_updated_by',
        'report_approve_at',
        'report_updated_at',
        'created_by',
        'updated_by',
        'send_mail_status',
        'noti_email'
        
    ];

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

    public function approve_created(){
        return $this->belongsTo(User::class, 'report_approve_by');
    }

    public function approve_updated(){
        return $this->belongsTo(User::class, 'report_updated_by');
    }

    public function getApproveCreatedNameAttribute() {
        return @$this->approve_created->reg_fname.' '.@$this->approve_created->reg_lname;
    }

    public function getApproveUpdatedNameAttribute() {
        return @$this->approve_updated->reg_fname.' '.@$this->approve_updated->reg_lname;
    }
}
