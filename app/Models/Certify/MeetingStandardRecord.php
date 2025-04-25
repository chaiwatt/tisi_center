<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
use App\Models\Certify\MeetingStandardRecordExperts;
use App\AttachFile; 
use App\User;
class MeetingStandardRecord extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_setstandard_meeting_record';

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
    protected $fillable = ['title',
                           'setstandard_meeting_id',
                           'start_date',
                           'start_time',
                           'end_date',
                           'end_time',
                           'meeting_detail',
                           'attach',
                           'status_id',
                           'created_by',
                           'updated_by',
                           'amount'
                        ];

    /*
      Sorting
    */
        public $sortable = ['title',
                          'setstandard_meeting_id',
                          'start_date',
                          'start_time',
                          'end_date',
                          'end_time',
                          'meeting_detail',
                          'attach',
                          'status_id',
                          'created_by',
                          'updated_by',
                          'amount'
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
        return !is_null($this->user_created) ? $this->user_created->reg_fname.' '.$this->user_created->reg_lname : '-' ;
    }

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    //id ผู้เข้าร่วมการประชุม
    public function experts(){
      return $this->hasMany(MeetingStandardRecordExperts::class, 'meeting_record_id');
    }

    //รายการค่าใช้จ่าย
    public function costs(){
        return $this->hasMany(MeetingStandardRecordCost::class, 'meeting_record_id');
    }

    public function meeting_record_participant_many(){
      return $this->hasMany(CertifySetstandardMeetingRecordParticipant::class, 'meeting_record_id');
    }

    

    //วันเวลาที่ประชุม
    public function getMeetingTimeTextAttribute(){
        $text = '';
        if($this->start_date == $this->end_date){
            $text .= HP::DateThaiFull($this->start_date);
            $text .= ' ';
            $text .= HP::formatTime(date('Y-m-d').' '.$this->start_time);
            $text .= ' ถึง ';
            $text .= HP::formatTime(date('Y-m-d').' '.$this->end_time);
        }else{
            $text .= HP::DateTimeThai($this->start_date.' '.$this->start_time);
            $text .= ' ถึง ';
            $text .= HP::DateTimeThai($this->end_date.' '.$this->end_time);
        }

        return $text;
    }

    public function AttachFileMeetingStandardAttachTo()
    {
        return $this->hasMany(AttachFile::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_meeting_standard');
    }

}
