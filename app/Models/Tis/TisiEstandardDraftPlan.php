<?php

namespace App\Models\Tis;

use HP;
use App\User;
use Carbon\Carbon;
use App\AttachFile;
use App\Models\Basic\Method;
use App\Models\Bcertify\Reason;
use App\Models\Tis\EstandardOffers;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Bcertify\Standardtype;
use Illuminate\Database\Eloquent\Model;


class TisiEstandardDraftPlan extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tisi_estandard_draft_plan';

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
    protected $fillable = ['draft_id', 'offer_id', 'std_type', 'start_std', 'ref_std', 'tis_number', 'tis_book', 'tis_year', 'tis_name', 'tis_name_eng', 'method_id', 'ref_document', 'confirm_time', 'industry_target',
     'assign_id', 'assign_date', 'status_id', 'plan_startdate', 'plan_enddate', 'confirm_by', 'confirm_at', 'created_by', 'updated_by','period','budget','confirm_detail','ref_budget','budget_by','remark','reason'];

    /*
      Sorting
    */
    public $sortable =    ['draft_id', 'offer_id', 'std_type', 'start_std', 'ref_std', 'tis_number', 'tis_book', 'tis_year', 'tis_name', 'tis_name_eng', 'method_id', 'ref_document', 'confirm_time', 'industry_target',
    'assign_id', 'assign_date', 'status_id', 'plan_startdate', 'plan_enddate', 'confirm_by', 'confirm_at', 'created_by', 'updated_by','period','budget','confirm_detail','ref_budget','budget_by','remark','reason'];

    public function user_created(){
       return $this->belongsTo(User::class, 'created_by');
     }


    public function user_updated(){
       return $this->belongsTo(User::class, 'updated_by');
     }

     public function user_confirm(){
      return $this->belongsTo(User::class, 'confirm_by');
    }

    public function assign_user(){
        return $this->belongsTo(User::class, 'assign_id');
    }

     public function estandard_offers_to(){
      return $this->belongsTo(EstandardOffers::class, 'offer_id');
    }


    public function tisi_estandard_draft_to(){
      return $this->belongsTo(TisiEstandardDraft::class, 'draft_id');
    }
    // เหตุผลและความจำเป็น
    public function reason_to(){
      return $this->belongsTo(Reason::class, 'reason','id');
    }
    // เพิ่ม เหตุผลและความจำเป็น จากระบบร่างแผนการกำหนดมาตรฐานการตรวจสอบและรับรอง
    public function reason_draft_plan_to(){
      return $this->belongsTo(Reason::class, 'id','draft_plan_id');
    }
    
    public function tisi_estandard_draft_plan_log(){
      return $this->hasMany(TisiEstandardDraftPlanLog::class, 'plan_id');
    }

    public function getTisiEstandardDraftPlanLogCheckAttribute(){
        return @$this->tisi_estandard_draft_plan_log->count() > 0;
    }

    public function getAssignNameAttribute(){
        return $this->assign_user->FullName.(!empty($this->ShortAssignDate)?'<br/>'.$this->ShortAssignDate:null);
    }

    public function getConfirmNameAttribute(){
        return @$this->user_confirm->FullName;
    }

    public function getUpdatedNameAttribute(){
        return @$this->user_updated->FullName;
    }

    public function getRefBudgetTitleAttribute(){
        $ref_butget_arr = ['1' => 'งบประมาณ', '2' => 'ผู้สนับสนุน'];
        return array_key_exists($this->ref_budget, $ref_butget_arr)?$ref_butget_arr[$this->ref_budget]:'N/A';
    }

    public function getShortAssignDateAttribute(){
        if(Carbon::hasFormat($this->assign_date, 'Y-m-d H:i:s')){
            return Carbon::parse($this->assign_date)->addYear(543)->isoFormat('D MMM YYYY');
        }
    }
    
    public function boards(){
      return $this->hasMany(TisiEstandardDraftBoard::class, 'draft_plan_id');
    }

     // ประเภทมาตรฐาน
    public function standard_type_to(){
      return $this->belongsTo(Standardtype::class, 'std_type');
    }
    // วิธีการ
    public function method_to(){
      return $this->belongsTo(Method::class, 'method_id');
    }
    // สถานะ
    public function getStatusNameAttribute(){
      // $arr = ['1'=>'ร่างแผน', '2'=>'จัดทำแผน', '3'=>'อนุมัติแผน', '4'=>'ไม่อนุมัติแผน', '5'=>'แจ้งแก้ไขแผน'];
      $arr = ['1'=>'ร่างแผน', '2'=>'อยู่ระหว่างจัดทำแผน', '3'=>'นำส่งแผน', '4'=>'บรรจุแผน', '5'=>'ไม่อนุมัติแผน', '6'=>'แจ้งแก้ไขแผน'];
      return array_key_exists( $this->status_id,$arr) ?  $arr[$this->status_id] : 'n/a'  ;
    }

    public function AttachFileAttachTo()
    {
       $tb = new TisiEstandardDraftPlan;
        return $this->belongsTo(AttachFile::class,'id','ref_id')->where('ref_table',$tb->getTable())->where('section','attach')->orderby('id','desc');
    }
    public function AttachFileConfirmAttachTo()
    {
       $tb = new TisiEstandardDraftPlan;
        return $this->belongsTo(AttachFile::class,'id','ref_id')->where('ref_table',$tb->getTable())->where('section','confirm_attach')->orderby('id','desc');
    }

    public function getMethodTitleAttribute(){
        return @$this->method_to->title;
    }

}
