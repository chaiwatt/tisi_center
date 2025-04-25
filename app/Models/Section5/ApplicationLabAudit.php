<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;

class ApplicationLabAudit extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'section5_application_labs_audit';

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
    protected $fillable = ['application_lab_id', 'application_no', 'audit_date', 'audit_result', 'audit_remark', 'send_mail_status', 'noti_email', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['application_lab_id', 'application_no', 'audit_date', 'audit_result', 'audit_remark', 'send_mail_status', 'noti_email', 'created_by', 'updated_by'];

    

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
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getNotiEmailsAttribute() {
        $noti_emails = !empty($this->noti_email)?json_decode($this->noti_email):[];
        $noti_emails = !empty($noti_emails)?implode(',', $noti_emails):null;
        return @$noti_emails;
    }

    public function getAuditDateShowAttribute() {
      $audit_dates = !empty($this->audit_date)?json_decode($this->audit_date):[];
      $audit_dates = !empty($audit_dates)?implode(',', $audit_dates):null;
      return @HP::DateThai($audit_dates);
  }
    
}
