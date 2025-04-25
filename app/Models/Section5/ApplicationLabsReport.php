<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class ApplicationLabsReport extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'section5_application_labs_report';

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
    protected $fillable = [
                            'application_lab_id', 
                            'application_no', 
                            'report_date', 
                            'report_by', 
                            'report_description', 
                            'created_by', 
                            'updated_by', 
                            'report_approve', 
                            'report_approve_description', 
                            'report_approve_by', 
                            'report_approve_at', 
                            'report_updated_by', 
                            'report_updated_at',
                            'send_mail_status',
                            'noti_email'
                        ];

    /*
      Sorting
    */
    public $sortable =  [
                          'application_lab_id', 
                          'application_no', 
                          'report_date', 
                          'report_by', 
                          'report_description', 
                          'created_by', 
                          'updated_by', 
                          'report_approve', 
                          'report_approve_description', 
                          'report_approve_by', 
                          'report_approve_at', 
                          'report_updated_by', 
                          'report_updated_at',
                          'send_mail_status',
                          'noti_email'
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

    public function user_report_by(){
      return $this->belongsTo(User::class, 'report_by');
    }

    public function getCreatedNameAttribute() {
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function getReportByNameAttribute() {
  		return @$this->user_report_by->reg_fname.' '.@$this->user_report_by->reg_lname;
  	}

}
