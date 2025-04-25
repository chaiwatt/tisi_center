<?php

namespace App\Models\Law\Track;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use HP;

use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawStatusOperation;


class LawTrackOperation extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_track_operations';

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
        'operation_date',
        'due_date',
        'status_job_track_id',
        'detail',
        'created_by',
        'updated_by'

    ];

    public function attach_file()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','file_law_operator');
    }
     
    public function law_status_job_tracks(){
        return $this->belongsTo(LawStatusOperation::class, 'status_job_track_id');
    }
}
