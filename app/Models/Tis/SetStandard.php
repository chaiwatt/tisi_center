<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Tis\Appoint;
use App\Models\Basic\Method;
use App\Models\Basic\StandardType;
use App\Models\Basic\StandardFormat;
use App\Models\Basic\ProductGroup;
use App\Models\Basic\IndustryTarget;
use App\Models\Basic\StaffGroup;

use HP;
class SetStandard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_set_standards';

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
        'title',
        'title_en',
        'start_year',
        'plan_year',
        'tis_no',
        'tis_book',
        'made_by',
        'sdo_name',
        'product_group_id',
        'appoint_id',
        'standard_type_id',
        'standard_format_id',
        'remark',
        'set_format_id',
        'method_id',
        'method_id_detail',
        'industry_target_id',
        'refer',
        'staff_group',
        'secretary',
        'attach',
        'state',
        'created_by',
        'updated_by',
        'review_status',
        'revise_status',
        'cluster_id',
        'standard_id',
        'announce',
        'tis_tisno','tis_tisshortno','publishing_status'
    ];

    /*
      Sorting
    */
    public $sortable = ['title', 'title_en', 'start_year', 'plan_year', 'tis_no', 'made_by', 'sdo_name', 'product_group_id', 'appoint_id', 'standard_type_id', 'standard_format_id', 'remark', 'set_format_id', 'method_id', 'method_id_detail', 'industry_target_id', 'refer', 'staff_group', 'secretary', 'attach', 'state', 'standard_id', 'announce', 'created_by', 'updated_by','tis_tisno','tis_tisshortno','publishing_status'];

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

    /*
      Standard Type Relation
    */
    public function standard_type(){
      return $this->belongsTo(StandardType::class, 'standard_type_id');
    }

    /*
      Standard Type Relation
    */
    public function standard_format(){
      return $this->belongsTo(StandardFormat::class, 'standard_format_id');
    }

    /*
      method Relation
    */
    public function method(){
      return $this->belongsTo(Method::class, 'method_id');
    }

    /*
      product_group Relation
    */
    public function product_group(){
      return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    /*
      Standard Relation
    */
    public function standard(){
      return $this->belongsTo(Standard::class, 'standard_id');
    }

    public function staffgroup(){
      return $this->belongsTo(StaffGroup::class, 'staff_group');
    }

    public function getStandardTypeNameAttribute()
    {
        return @$this->standard_type->title;
    }

    public function getBoardTypeNameAttribute(){
      return @$this->standard->BoardTypeName ?? 'n/a';
    }

    public function boardTypeSortable($query, $direction){
        return $query->leftjoin('tis_standards', 'tis_standards.id', '=', 'tis_set_standards.standard_id')
                    ->leftjoin('tis_appoints', 'tis_appoints.id', '=', 'tis_standards.board_type_id')
                    ->orderBy('tis_appoints.board_position', $direction)
                    ->select('tis_set_standards.*');
    }

    public function getStaffGroupNameAttribute(){
      return @$this->staffgroup->order;
    }

    public function staffGroupSortable($query, $direction){
        return $query->leftJoin('basic_staff_groups', 'basic_staff_groups.id', '=', 'tis_set_standards.staff_group')
                    ->orderbyRaw("CONVERT(`basic_staff_groups`.`order` USING tis620) $direction")
                    ->select('tis_set_standards.*');
    }

    public function getStandardTypeShortNameAttribute()
    {
        return @$this->standard_type->acronym;
    }

    public function standardTypeSortable($query, $direction){
        return $query->leftjoin('basic_standard_types', 'basic_standard_types.id', '=', 'tis_set_standards.standard_type_id')
                    ->orderBy('acronym', $direction)
                    ->select('tis_set_standards.*');
    }


    public function getStandardFormatNameAttribute()
    {
        return @$this->standard_format->title ?? 'n/a';
    }

    public function standardFormatSortable($query, $direction){
        return $query->leftjoin('basic_standard_formats', 'basic_standard_formats.id', '=', 'tis_set_standards.standard_format_id')
                    ->orderBy('basic_standard_formats.title', $direction)
                    ->select('tis_set_standards.*');
    }

    public function getMethodNameAttribute()
    {
        return @$this->method->title ?? 'n/a';
    }

    public function getMethodDetailNameAttribute()
    {
        $arr = explode(',',$this->method->details);
        return $arr[$this->method_id_detail] ?? 'n/a';
    }

    public function methodSortable($query, $direction){
        return $query->leftjoin('basic_methods', 'basic_methods.id', '=', 'tis_set_standards.method_id')
                    ->orderBy('basic_methods.title', $direction)
                    ->select('tis_set_standards.*');
    }

    public function getProductGroupNameAttribute()
    {
        return @$this->product_group->title;
    }

    public function productGroupSortable($query, $direction){
        return $query->leftjoin('basic_product_groups', 'basic_product_groups.id', '=', 'tis_set_standards.product_group_id')
                    // ->orderBy('basic_product_groups.title', $direction)
                    ->orderbyRaw("CONVERT(basic_product_groups.title USING tis620) $direction")
                    ->select('tis_set_standards.*');
    }

    public function set_standard_plan()
    {
        return $this->hasMany(SetStandardPlan::Class, 'id_tis_set_standards');
    }

    public function set_standard_result()
    {
        return $this->hasMany(SetStandardResult::Class, 'id_tis_set_standards');
    }

    public function totalAllowances() {
        $sum = 0;
        foreach ($this->set_standard_plan as $set_standard_plan) {
            $sum += $set_standard_plan->totalAllowances();
        }
        return $sum;
    }

    public function totalAllowances2() {
        $sum = 0;
        foreach ($this->set_standard_plan as $set_standard_plan) {
            $sum += $set_standard_plan->totalAllowances2();
        }
        return $sum;
    }

    public function totalFoods() {
        $sum = 0;
        foreach ($this->set_standard_plan as $set_standard_plan) {
            $sum += $set_standard_plan->totalFoods();
        }
        return $sum;
    }

    public function totalFoods2() {
        $sum = 0;
        foreach ($this->set_standard_plan as $set_standard_plan) {
            $sum += $set_standard_plan->totalFoods2();
        }
        return $sum;
    }

    public function total() {
        return $this->totalAllowances() + $this->totalFoods();
    }

    public function total2() {
        return $this->totalAllowances2() + $this->totalFoods2();
    }

    public function totalAllowancesResult() {
        $sum = 0;
        foreach ($this->set_standard_result as $set_standard_plan) {
            $sum += $set_standard_plan->totalAllowances();
        }
        return $sum;
    }

    public function totalAllowancesResult2() {
        $sum = 0;
        foreach ($this->set_standard_result as $set_standard_result) {
            $sum += $set_standard_result->totalAllowances2();
        }
        return $sum;
    }

    public function totalFoodsResult() {
        $sum = 0;
        foreach ($this->set_standard_result as $set_standard_plan) {
            $sum += $set_standard_plan->totalFoods();
        }
        return $sum;
    }

    public function totalFoodsResult2() {
        $sum = 0;
        foreach ($this->set_standard_result as $set_standard_result) {
            $sum += $set_standard_result->totalFoods2();
        }
        return $sum;
    }

    public function totalResult() {
        return $this->totalAllowancesResult() + $this->totalFoodsResult();
    }

    public function totalResult2() {
        return $this->totalAllowancesResult2() + $this->totalFoodsResult2();
    }

    /*
      industry_target Relation
    */
    public function industry_target(){
      return $this->belongsTo(IndustryTarget::class, 'industry_target_id');
    }

    /*
      appoint Relation
    */
    public function appoint(){
      return $this->belongsTo(Appoint::class, 'appoint_id');
    }

    public function getOperationNameAttribute() {
        $status_operate = [];
        foreach ($this->set_standard_plan as $key=>$set_standard_plan) {
            $status_operate[] = $set_standard_plan->getStatusOperationNameAttribute();
        }
         $result = end($status_operate);

        return ($result!=false)?$result:'n/a';
    }

    public function getOperationResultNameAttribute() {
        $status_operate = [];
        $status_enddate = [];

        foreach ($this->set_standard_result as $key=>$set_standard_result) {
            // $status_operate[] = $set_standard_result->getOperationResultNameAttribute();
            $status_operate[] = $set_standard_result->status_operation->title;
            $status_enddate[] = $set_standard_result->enddate;
        }
          $result = end($status_operate);
          $end_date = end($status_enddate);

          $html = '';
          $html .= $result;
          $html .= '<br>';
          $html .= !empty($end_date)?'<span style="color:red">('.HP::DateThai($end_date).')</span>':'';

        return ($html!=false)?$html:'n/a';
    }

    public function getAppointStdPlanNameAttribute() {
        $appoint_name = [];
          foreach ($this->set_standard_plan as $key=>$set_standard_plan) {
              $appoint_name[] = $set_standard_plan->AppointName;
          }
        return implode(', ', $appoint_name);
    }

    public function getAppointNameAttribute() {
        return ($this->appoint->board_position??'')." ".($this->appoint->title??'n/a');
    }

    /* Btn Switch Input*/
    public function getStateIconAttribute(){
      $btn = '';
      if ($this->state == 1) {
          $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'" checked></div>';
      }else {
          $btn = '<div class="checkbox"><input class="js-switch" name="state" type="checkbox" value="'.$this->id.'"></div>';
      }
      return $btn;
  }
}
