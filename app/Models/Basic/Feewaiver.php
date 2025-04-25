<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\Province;

class Feewaiver extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_feewaiver';

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
    protected $fillable = ['payin1_status', 'payin1_start_date', 'payin1_end_date', 'payin1_file','payin1_file_client_name', 'payin2_status', 'payin2_start_date', 'payin2_end_date', 'payin2_file','payin2_file_client_name', 'created_by', 'updated_by','certify'];

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

      public function getDatePayIn1Attribute() {
          $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
          $date = '';
          $payin1_start_date =  $this->payin1_start_date;
          $payin1_end_date =  $this->payin1_end_date;
        if(!is_null($payin1_start_date) &&!is_null($payin1_end_date)){
                   // ปี
                   $StartYear = date("Y", strtotime($payin1_start_date)) +543;
                   $EndYear = date("Y", strtotime($payin1_end_date)) +543;
                  // เดือน
                  $StartMonth= date("n", strtotime($payin1_start_date));
                  $EndMonth= date("n", strtotime($payin1_end_date));
                  //วัน
                  $StartDay= date("j", strtotime($payin1_start_date));
                  $EndDay= date("j", strtotime($payin1_end_date));
                  if($StartYear == $EndYear){
                      if($StartMonth == $EndMonth){
                            if($StartDay == $EndDay){
                              $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                            }else{
                              $date =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                            }
                      }else{
                          $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                      }
                  }else{
                      $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                  }
          }
          return $date;
        }

        public function getDatePayIn2Attribute() {
          $strMonthCut = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
          $date = '';
          $payin2_start_date  =  $this->payin2_start_date;
          $payin2_end_date    =  $this->payin2_end_date;
        if(!is_null($payin2_start_date) &&!is_null($payin2_end_date)){
                   // ปี
                   $StartYear = date("Y", strtotime($payin2_start_date)) +543;
                   $EndYear = date("Y", strtotime($payin2_end_date)) +543;
                  // เดือน
                  $StartMonth= date("n", strtotime($payin2_start_date));
                  $EndMonth= date("n", strtotime($payin2_end_date));
                  //วัน
                  $StartDay= date("j", strtotime($payin2_start_date));
                  $EndDay= date("j", strtotime($payin2_end_date));
                  if($StartYear == $EndYear){
                      if($StartMonth == $EndMonth){
                            if($StartDay == $EndDay){
                              $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                            }else{
                              $date =  $StartDay.'-'.$EndDay.' '.$strMonthCut[$StartMonth].' '.$StartYear ;
                            }
                      }else{
                          $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                      }
                  }else{
                      $date =  $StartDay.' '.$strMonthCut[$StartMonth].' '.$StartYear.' - '.$EndDay.' '.$strMonthCut[$EndMonth].' '.$EndYear ;
                  }
          }
          return $date;
        }
 
}
