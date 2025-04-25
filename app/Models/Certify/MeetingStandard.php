<?php

namespace App\Models\Certify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Bcertify\Meetingtype;
use App\Models\Certify\MeetingStandardRecord;
use HP;
use App\AttachFile;
class MeetingStandard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_setstandard_meeting';

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
                           'meeting_type_id',
                           'start_date',
                           'start_time',
                           'end_date',
                           'end_time',
                           'meeting_place',
                           'meeting_detail',
                           'attach',
                           'status_id',
                           'created_by',
                           'updated_by'
                        ];

    /*
      Sorting
    */
        public $sortable = ['title',
                          'meeting_type_id',
                          'start_date',
                          'start_time',
                          'end_date',
                          'end_time',
                          'meeting_place',
                          'meeting_detail',
                          'attach',
                          'status_id',
                          'created_by',
                          'updated_by'
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

    public function meeting_type(){
        return $this->belongsTo(Meetingtype::class, 'meeting_type_id');
    }

    public function meeting_commitees(){
        return $this->hasMany(MeetingStandardCommitee::class, 'setstandard_meeting_id');
    }

    // บันทึกการประชุม
    public function record(){
        return $this->hasOne(MeetingStandardRecord::class, 'setstandard_meeting_id', 'id');
    }

    public function certify_setstandard_meeting_type_many(){
        return $this->hasMany(CertifySetstandardMeetingType::class, 'setstandard_meeting_id');
    }
    
    // วาระการประชุม
    public function getMeetingTypesNameAttribute() {
        $datas = [];
        if(count($this->certify_setstandard_meeting_type_many )> 0){
            $data = HP::getArrayFormSecondLevel($this->certify_setstandard_meeting_type_many->toArray(), 'meetingtype_id');
            if(count($data)> 0){
                foreach ($data as $key => $list) {
                    $title = Meetingtype::where('id',$list)->value('title') ;
                    if(!is_null($title)){
                        $datas[$title] = $title ;
                    }
                }
            }
        }
        return  $datas;
      }



    public function getMeetingTypeNameAttribute() {
  		return @$this->meeting_type->title;
  	}

    public function getStatusTextAttribute()
    {
        if ($this->status_id == 1){
            return "นัดหมายการประชุม";
        }elseif ($this->status_id == 2){
            return "บันทึกผลการประชุม";
        }elseif ($this->status_id == 3){
            // return "ยกเลิกนัดหมาย";
            return "อยู่ระหว่างดำเนินการประชุม";
        }else{
            return "N/A";
        }
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
