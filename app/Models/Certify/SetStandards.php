<?php

namespace App\Models\Certify;

use App\User;
use App\AttachFile;
use App\Models\Basic\Method;
use Kyslik\ColumnSortable\Sortable;
use App\Certificate\MeetingInvitation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tis\TisiEstandardDraftPlan;
use App\Certificate\MeetingInvitationSetstandard;

class SetStandards extends Model
{
    use Sortable;

    protected $table = 'certify_setstandards';

    protected $primaryKey = 'id';

    protected $fillable = ['projectid', 'plan_id', 'method_id', 'format_id', 'estimate_cost', 'plan_time', 'status_id', 'status_sub_appointment_id','agreement_status','agreement_detail', 'created_by','standard_circular_doc_status', 'standard_circular_doc_details' , 'updated_by'];

    public $sortable = ['projectid', 'plan_id', 'method_id', 'format_id', 'estimate_cost', 'plan_time', 'status_id', 'created_by', 'updated_by'];


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

    public function estandard_plan_to(){
      return $this->belongsTo(TisiEstandardDraftPlan::class, 'plan_id');
    }

    public function getTisNameAttribute() {
  		return @$this->estandard_plan_to->tis_name;
  	}

    public function getTisYearAttribute() {
  		return @$this->estandard_plan_to->tisi_estandard_draft_to->draft_year;
  	}

    public function getPeriodAttribute() {
  		return @$this->estandard_plan_to->period;
  	}

    public function getStdTypeNameAttribute() {
  		return @$this->estandard_plan_to->standard_type_to->title;
  	}

    public function method_to(){
      return $this->belongsTo(Method::class, 'method_id');
    }

    public function meeting_standard_projects(){
        return $this->hasMany(MeetingStandardProject::class, 'setstandard_id');
    }

    public function certify_setstandard_meeting_type_many(){
      return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_id');
   }

   public function certify_setstandard_meeting_type_many_main_committees(){
      return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_id')
      ->whereHas('meeting_standard_to', function ($query) {
                        $query->where('meeting_group', 1);
                    });
   }

  public function certify_setstandard_meeting_type_many_sub_committees(){
      return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_id')
      ->whereHas('meeting_standard_to', function ($query) {
                        $query->where('meeting_group',2);
                    });
   }

    public function certify_setstandard_meeting_type_group_main_committees()
    {
        return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_id')
                    ->whereHas('meeting_standard_to', function ($query) {
                        $query->where('meeting_group', 1);
                    });
    }

    public function certify_setstandard_meeting_type_group_sub_committees()
    {
        return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_id')
                    ->whereHas('meeting_standard_to', function ($query) {
                        $query->where('meeting_group', 2);
                    });
    }

    public function getMetThodNameAttribute() {
  		return @$this->method_to->title;
  	}

    public function getStatusTextAttribute()
    {
        if ($this->status_id == 0){
            return "รอกำหนดมาตรฐาน";
         }elseif ($this->status_id == 1){
          return "อยู่ระหว่างดำเนินการ";
        } elseif ($this->status_id == 2){
            return "อยู่ระหว่างการประชุม";
        }elseif ($this->status_id == 3){
            return "อยู่ระหว่างสรุปรายงานการประชุม"; 
        }elseif ($this->status_id == 4){
            return "อยู่ระหว่างจัดทำมาตรฐาน";
        }elseif ($this->status_id == 5){
            return "สรุปวาระการประชุมเรียบร้อย";
        }else{
            return "N/A";
        }
    }
    // เอกสารที่เกี่ยวข้อง
    public function AttachFileSetStandardsDetailsAttachTo()
    {
        return $this->hasMany(AttachFile::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_set_standards_details');
    }

    public function AttachFileSetStandardsAttachTo()
    {
        return $this->hasMany(AttachFile::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_set_standards');
    }

    // public function meetingInvitations()
    // {
    //     return $this->belongsToMany(MeetingInvitation::class, 'meeting_invitation_setstandards', 'setstandard_id', 'meeting_invitation_id')
    //                 ->using(MeetingInvitationSetstandard::class);
    // }

    // ความสัมพันธ์กับ MeetingInvitation (many-to-many)
    public function meetingInvitations()
    {
        return $this->belongsToMany(MeetingInvitation::class, 'meeting_invitation_setstandards', 'setstandard_id', 'meeting_invitation_id');
    }

     public function subAppointmentMeetingApproved()
    {
      // dd($this->id);
        return $this->belongsToMany(MeetingInvitation::class, 'meeting_invitation_setstandards', 'setstandard_id', 'meeting_invitation_id')
                    ->where('meeting_invitations.status', 3)
                    ->where('meeting_invitations.type', 2);
    }

    public function mainAppointmentMeetingApproved()
    {
        return $this->belongsToMany(MeetingInvitation::class, 'meeting_invitation_setstandards', 'setstandard_id', 'meeting_invitation_id')
                    ->where('meeting_invitations.status', 3)
                    ->where('meeting_invitations.type', 1);
    }
}
