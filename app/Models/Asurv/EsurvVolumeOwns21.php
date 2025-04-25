<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Asurv\EsurvOwns21;

class EsurvVolumeOwns21 extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_21owns';

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
    protected $fillable = ['applicant_21own_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];
    /*
      Sorting
    */
    public $sortable = ['applicant_21own_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];

    public function getTisNameAttribute(){
        $esurv_own21 = EsurvOwns21::where('id',$this->applicant_21own_id)->first();
        $tis_no = json_decode($esurv_own21->different_no);
        $tb3_tisno = Tis::where('tb3_TisAutono',$tis_no[0])->first();
      return $tb3_tisno->tb3_Tisno;
    }

    public function esurv_ters21_to() {
      return $this->belongsTo(EsurvOwns21::class, 'applicant_21own_id');
   }
}
