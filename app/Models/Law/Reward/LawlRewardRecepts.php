<?php

namespace App\Models\Law\Reward;

use App\User;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\File\AttachFileLaw;
 
class LawlRewardRecepts extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_reward_recepts';

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
        'recept_no','recepts_type','filter_case_number', 'filter_paid_date_month','filter_paid_date_year', 'filter_paid_date_start','filter_paid_date_end','recepts_type_detail','recept_place',
        'recept_date','taxid','name','address','amount','amount_th','status', 'condition_group', 'set_item','conditon_type','due_date','notices','send_status','send_remark',
        'ordering', 'cancel_by','cancel_at','cancel_remark','created_by','updated_by','deduct','deduct_vat','total','deduct_amount','deduct_vat_amount'

    ];
    protected $casts = [ 'set_item' => 'array'];
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
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function status_list() {
        return [ 1 => 'สร้างใบสำคัญรับเงิน', 2 => 'เรียนร้อย'];
    }

    public function status_css() {
        return [ 1 => 'text-success', 2 => 'text-success'];
    }
    public function getStatusHtmlAttribute() {
        return array_key_exists($this->status, $this->status_list()) ? '<span class="'.$this->status_css()[$this->status].'">'.$this->status_list()[$this->status].'</span>' : '';
    }
    

    public function recepts_list() {
        return [ 1 => 'รายคดี', 2 => 'รายเดือน', 3 => 'ช่วงวันที่'];
    }
    public function getReceptsTypeTextAttribute() {
        return array_key_exists($this->recepts_type, $this->recepts_list()) ?  $this->recepts_list()[$this->recepts_type]  : '';
    }
    

    public function attach_receipt_file()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','receipt_pdf')->orderby('id','desc');
    }
    public function attach_evidence_file()
    {
        return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','evidence')->orderby('id','desc');
    }

    public function lawl_reward_recepts_details_to()
    {
        return $this->belongsTo(LawlRewardReceptsDetails::class,'id','law_reward_recepts_id');
    }

}
 