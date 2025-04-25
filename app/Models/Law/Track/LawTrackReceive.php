<?php

namespace App\Models\Law\Track;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;

use App\Models\Law\Basic\LawJobType;
use App\Models\Law\Basic\LawDepartment;
use App\Models\Law\Basic\LawStatusOperation;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Track\LawTrackReceiveAssign;
use App\Models\Law\Track\LawTrackOperation;

use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;

class LawTrackReceive extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_track_receives';

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
                            'reference_no',
                            'book_no',
                            'receive_no',
                            'receive_date',
                            'receive_time',
                            'assign_by',
                            'assign_at',
                            'lawyer_by',
                            'lawyer_at',
                            'lawyer_check',
                            'law_deperment_type',
                            'law_bs_deperment_id',
                            'law_bs_deperment_other',
                            'department_id',
                            'sub_departments_id',
                            'law_bs_job_type_id',
                            'title',
                            'description',
                            'status_job_track_id',
                            'close_date',
                            'close_by',
                            'cancel_status',
                            'cancel_remark',
                            'cancel_by',
                            'cancel_at',
                            'created_by',
                            'updated_by',
                            'created_at',
                            'updated_at',
                            'remarks',
                            'noti_sytem_status',
                            'noti_email_status',
                            'send_mail_status',
                            'noti_email',
                        ];

    protected $casts = ['send_mail_status' => 'json', 'noti_email' =>  'json' ];

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

    public function law_job_types(){
        return $this->belongsTo(LawJobType::class, 'law_bs_job_type_id');
    }

    public function getJopTypeNameAttribute() {
        return @$this->law_job_types->title;
    }

    public function law_deparment(){
        return $this->belongsTo(LawDepartment::class, 'law_bs_deperment_id');
    }

    public function deparment(){
        return $this->belongsTo(Department::class, 'department_id');
    }


    public function sub_deparment(){
        return $this->belongsTo(SubDepartment::class, 'sub_departments_id');
    }

    public function law_status_job_tracks(){
        return $this->belongsTo(LawStatusOperation::class, 'status_job_track_id');
    }

    public function file_law_track_receives()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->getTable())->where('section','file_law_track_receives');
    }

    public function law_trackreceives_assign(){
        return $this->hasMany(LawTrackReceiveAssign::class, 'law_track_receives_id');
    }

    //  ข้อมูลมอบหมายครั้งล่าสุด
    public function law_trackreceives_assign_to(){
        return $this->belongsTo(LawTrackReceiveAssign::class,'id','law_track_receives_id')->orderby('id','desc');
    }

    public function users_assign()
    {
        return $this->belongsToMany(User::class, (new LawTrackReceiveAssign)->getTable() , 'law_track_receives_id', 'user_id');
    }

    public function users_lawyer()
    {
        return $this->belongsToMany(User::class, (new LawTrackReceiveAssign)->getTable() , 'law_track_receives_id', 'lawyer_by');
    }

    public function law_track_operation(){
        return $this->hasMany(LawTrackOperation::class, 'law_track_receives_id');
    }

    public function getLawDeparmentNameAttribute() {
        $other = (!empty($this->law_deparment->other) && $this->law_deparment->other == 1) ? '<div>'.$this->law_bs_deperment_other.'</div>' :' ';
        return !is_null($this->law_deparment) && !empty($this->law_deparment->title_short)?(($this->law_deparment->title_short != '-')?$this->law_deparment->title_short:$this->law_deparment->title).$other:null;
    }

    public function getDeparmentNameAttribute() {
        $sub_deparment = !empty($this->sub_deparment) ? '<div>'.$this->sub_deparment->sub_depart_shortname.'</div>' :' ';
        return (!is_null($this->deparment) && !empty($this->deparment->depart_nameShort))?$this->deparment->depart_nameShort.$sub_deparment:null;
    }

    public function getDeparmentTypeNameAttribute() {
        $type = [ 1 => 'ภายใน', 2 => 'ภายนอก' ];
        return array_key_exists( $this->law_deperment_type, $type )?$type[ $this->law_deperment_type ]:null;
    }

    // มอบหมาย
    public function user_assign_to(){
        return $this->belongsTo(User::class, 'assign_by');
    }
    
    // นิติกรผู้รับผิดชอบ
    public function user_lawyer_to(){
        return $this->belongsTo(User::class, 'lawyer_by');
    }

    public function getLawyerNameAttribute() {
        return @$this->user_lawyer_to->reg_fname.' '.@$this->user_lawyer_to->reg_lname;
    }

    
}
