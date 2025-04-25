<?php

namespace App\Models\Certify;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;

class CertifySetstandardMeetingRecordParticipant  extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certify_setstandard_meeting_record_participant';

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
    protected $fillable = ['meeting_record_id', 'name', 'department_id'];

    /*
      Sorting
    */
    public $sortable =  ['meeting_record_id', 'name', 'department_id'];

    
 
}
