<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Asurv\EsurvTers20;
use App\Models\Basic\Tis;
 
class EsurvVolumeTers20 extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_20ters';

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
    protected $fillable = ['applicant_20ter_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];
    /*
      Sorting
    */
    public $sortable = ['applicant_20ter_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','agent_id'];


     public function getTisNameAttribute(){
        $esurv_ter20 = EsurvTers20::where('id',$this->applicant_20ter_id)->first();
        $tis_no = json_decode($esurv_ter20->different_no);
        $tb3_tisno = Tis::where('tb3_TisAutono',$tis_no[0])->first();
      return $tb3_tisno->tb3_Tisno;
    }

    public function getTisNosAttribute(){
        $esurv_ter20 = EsurvTers20::where('id',$this->applicant_20ter_id)->first();
        $tis_nos = json_decode($esurv_ter20->different_no);
        $tis_nos = is_null($tis_nos) ? [] : $tis_nos ;
        return Tis::whereIn('tb3_TisAutono', $tis_nos)->pluck('tb3_Tisno')->toArray();
    }

    public function esurv_ters20_to() {
      return $this->belongsTo(EsurvTers20::class, 'applicant_20ter_id');
   }



}
