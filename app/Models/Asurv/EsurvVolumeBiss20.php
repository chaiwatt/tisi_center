<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\Tis;

class EsurvVolumeBiss20 extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_20biss';

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
    protected $fillable = ['applicant_20bis_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];
    /*
      Sorting
    */
    public $sortable = ['applicant_20bis_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];

        public function getTisNameAttribute(){
        $esurv_ter21 = EsurvTers21::where('id',$this->applicant_20bis_id)->first();
        $tis_no = json_decode($esurv_ter21->different_no);
        $tb3_tisno = Tis::where('tb3_TisAutono',$tis_no[0])->first();
      return $tb3_tisno->tb3_Tisno;
    }

}
