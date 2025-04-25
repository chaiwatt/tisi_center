<?php

namespace App\Models\Certify;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

use App\Models\Bcertify\Meetingtype;

class CertifySetstandardMeetingType  extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_setstandard_meeting_type';

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
    protected $fillable = ['setstandard_id', 'setstandard_meeting_id', 'meetingtype_id', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['setstandard_id', 'setstandard_meeting_id', 'meetingtype_id', 'created_by', 'updated_by'];

    
    public function setstandard_to(){
      return $this->belongsTo(SetStandards::class, 'setstandard_id');
    }
    public function meetingtype_to(){
      return $this->belongsTo(Meetingtype::class, 'meetingtype_id');
    }

    public function meeting_standard_to(){
      return $this->belongsTo(MeetingStandard::class, 'setstandard_meeting_id');
    }
}
