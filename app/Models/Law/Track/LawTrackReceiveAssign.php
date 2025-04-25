<?php

namespace App\Models\Law\Track;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;

use App\Models\Law\Track\LawTrackReceive;
use App\Models\Law\Track\LawTrackOperation;

class LawTrackReceiveAssign extends Model
{
     /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_track_receives_assigns';

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
                            'law_track_receives_id',
                            'user_id',
                            'lawyer_by',
                            'lawyer_check',
                            'sub_department_id',
                            'created_by',
                            'updated_by',
                            'created_at',
                            'updated_at'
                        ];

    public function user_staff(){
        return $this->belongsTo(User::class, 'user_id');
    }

    // นิติกรผู้รับผิดชอบ
    public function user_lawyer_to(){
        return $this->belongsTo(User::class, 'lawyer_by');
    }

    public function getLawyerNameAttribute() {
        return @$this->user_lawyer_to->reg_fname.' '.@$this->user_lawyer_to->reg_lname;
    }

    public function getStaffNameAttribute() {
        return @$this->user_staff->reg_fname.' '.@$this->user_staff->reg_lname;
    }

    public function getStaffDeparmentNameAttribute() {
        return !empty($this->user_staff) && !empty($this->user_staff->subdepart) && !empty($this->user_staff->subdepart->sub_departname)?$this->user_staff->subdepart->sub_departname:null;
    }

    public function TrackReceiveAssignParentData()
    {
        return $this->hasMany(LawTrackReceiveAssign::class,'user_id', 'user_id');
    }

    public function law_track_receive(){
        return $this->belongsTo(LawTrackReceive::class, 'law_track_receives_id');
    }

    public function law_track_operation(){
        return $this->hasMany(LawTrackOperation::class, 'law_track_receives_id','law_track_receives_id');
    }

    public function getLastDateAttribute() {
    
        $law_track_receive_list = $this->TrackReceiveAssignParentData;

        $date = [];
        foreach(  $law_track_receive_list AS $receive ){

            $law_track_receive = $receive->law_track_receive;

            if( !is_null($law_track_receive) && !empty( $law_track_receive->close_date) ){
                $date[ $law_track_receive->close_date ] =  $law_track_receive->close_date;
            }
            $created_at = \Carbon\Carbon::parse( $receive->created_at )->format('Y-m-d');
            $date[  $created_at ] =  $created_at;
            
        }

        return max($date);

    }
}
