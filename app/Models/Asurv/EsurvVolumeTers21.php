<?php

namespace App\Models\Asurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Asurv\EsurvTers21;
use App\Models\Basic\Tis;
class EsurvVolumeTers21 extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'esurv_volume_21ters';

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
    protected $fillable = ['applicant_21ter_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','signer_id','signer_name','signer_position','agent_id'];
    /*
      Sorting
    */
    public $sortable = ['applicant_21ter_id', 'start_date', 'end_date', 'attach','inform_close','because_close', 'applicant_name', 'tel'
        , 'email', 'state','officer_report','signer_id','signer_name','signer_position','remark_officer_report','state_notify_report','trader_id','signer_id','signer_name','signer_position','agent_id'];


    /* Referrent */
    public function applicant(){
      return $this->belongsTo(EsurvTers21::class, 'applicant_21ter_id');
    }

    public function getTisNameAttribute(){
        $esurv_ter21 = EsurvTers21::where('id',$this->applicant_21ter_id)->first();
        $tis_no = json_decode($esurv_ter21->different_no);
        $tb3_tisno = Tis::where('tb3_TisAutono',$tis_no[0])->first();
      return $tb3_tisno->tb3_Tisno;
    }
    public function esurv_ters21_detail(){
      return $this->belongsTo(EsurvTers21detail::class, 'applicant_21ter_id');
    }
    
}
