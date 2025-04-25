<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Bcertify\AuditorInformation;

use App\Models\Bcertify\CertificationBranch; // CB
use App\Models\Bcertify\InspectBranch;  // IB
use App\Models\Bcertify\CalibrationBranch;  // LAB สอบเทียบ
use App\Models\Bcertify\TestBranch;  // LAB ทดสอบ
use App\Models\Bcertify\Formula;
use App\Models\Bcertify\StatusAuditor;
class AuditorExpertise extends Model
{

    use Sortable;
    protected $table = "auditor_expertises";
    protected $fillable = [
        'auditor_id',
        'type_of_assessment',
        'standard',
        'branch_id',
        'branch_path',
        'scope_name',
        'scope_path',
        'type_of_examination',
        'examination_category',
        'calibration_list',
        'product',
        'test_list',
        'specialized_expertise',
        'auditor_status',
        'token',
    ];

    public function getStatusAttribute()
    {
        return explode(",",$this->auditor_status);
    }


    public function auditor(){
        return $this->belongsTo(AuditorInformation::class, 'auditor_id');
    }

    public function formula(){
        return $this->belongsTo(Formula::class, 'standard');
    }

    public function type(){
        return $this->belongsTo(InspectType::class, 'type_of_examination');
    }

    public function category(){
        return $this->belongsTo(InspectCategory::class, 'examination_category');
    }

    public function calibration(){
        return $this->belongsTo(CalibrationItem::class, 'calibration_list');
    }

    public function product_show(){
        return $this->belongsTo(ProductItem::class, 'product');
    }

    public function test(){
        return $this->belongsTo(TestItem::class, 'test_list');
    }

    public function branchable()
    {
        return $this->morphTo(null, 'branch_path', 'branch_id');
    }


    public function InspectBranchTo(){   // IB
        return $this->belongsTo(InspectBranch::class, 'branch_id');
    }
 

    public function getInspectBranchTitleAttribute()
    {
        return @$this->InspectBranchTo->title ;
    }

    public function getBranchTitleAttribute()
    {
        $data = [];
        $expertise  =  AuditorExpertise::where('auditor_id', $this->auditor_id)->where('type_of_assessment', $this->type_of_assessment)->pluck('branch_id');
        if(!is_null($this->type_of_assessment) && count($expertise) > 0){
            if($this->type_of_assessment == 1){ // CB
              $Certificati  =   CertificationBranch::select('title')->whereIn('id',$expertise)->get();
              if(count($Certificati) > 0){
                 foreach($Certificati as $key => $item){
                     if(!is_null($item->title)){
                        $data[$item->title] = $item->title ?? '';
                     }
                 }
              } 
            } else if($this->type_of_assessment == 2){ // IB
                $Inspec  =  InspectBranch::select('title')->whereIn('id',$expertise)->get();
                if(count($Inspec) > 0){
                   foreach($Inspec as $key => $item){
                       if(!is_null($item->title)){
                          $data[$item->title] = $item->title ?? '';
                       }
                   }
                } 
            } else if($this->type_of_assessment == 3){ //  LAB สอบเทียบ
                $calibration  =  CalibrationBranch::select('title')->whereIn('id',$expertise)->get();
                if(count($calibration) > 0){
                   foreach($calibration as $key => $item){
                       if(!is_null($item->title)){
                          $data[$item->title] = $item->title ?? '';
                       }
                   }
                } 
            } else if($this->type_of_assessment == 4){ // LAB ทดสอบ
                $test  =  TestBranch::select('title')->whereIn('id',$expertise)->get();
                if(count($test) > 0){
                   foreach($test as $key => $item){
                       if(!is_null($item->title)){
                          $data[$item->title] =  $item->title;
                       }
                   }
                } 
            } 
        } 
        return  implode(",",$data);
    }

    public function certification_branch_to(){
        return $this->belongsTo(CertificationBranch::class, 'branch_id');
    }
    public function inspect_branch_to(){
        return $this->belongsTo(InspectBranch::class, 'branch_id');
    }
    public function calibration_branch_to(){
        return $this->belongsTo(CalibrationBranch::class, 'branch_id');
    }
    public function test_branch_to(){
        return $this->belongsTo(TestBranch::class, 'branch_id');
    }
    
    // สาขา
    public function getAuditorStatusTitleAttribute()
    {
        $auditor =  StatusAuditor::pluck('title','id')->toArray();
        $data = [];
        if(!is_null($this->type_of_assessment) ){
           $auditor_status =   explode(",",$this->auditor_status);
           foreach($auditor_status as $item){
               if( array_key_exists($item,$auditor) ){
                $data[] = $auditor[$item];
               }
           }
        } 
        return  implode(",",$data);
    }

    // สาขา
    public function getBranchTitleToAttribute()
    {
        $data = '';
        if(!is_null($this->type_of_assessment) ){
            if($this->type_of_assessment == 1){ // CB
                $data = @$this->certification_branch_to->title;
            } else if($this->type_of_assessment == 2){ // IB
                $data = @$this->inspect_branch_to->title;
            } else if($this->type_of_assessment == 3){ //  LAB สอบเทียบ
                $data = @$this->calibration_branch_to->title;
            } else if($this->type_of_assessment == 4){ // LAB ทดสอบ
                $data = @$this->test_branch_to->title;
            } 
        } 
        return $data;
    }

      // ขอบข่าย
        public function getScopeTitleToAttribute()
        {
             $information_scope = null;
             $scope = CertificationScope::where('certification_branch_id',$this->branch_id)->first();
            if(!is_null($this->type_of_assessment)  && !is_null($scope) ){
                if($this->type_of_assessment == 1){ // CB
                    if ($scope->scope_type == "ISIC"){
                        $information_scope = IndustryType::where('id',$this->scope_name)->first();  
                    }
                     elseif ($scope->scope_type == "IAF"){
                        $information_scope = Iaf::where('id',$this->scope_name)->first();  
                    }
                     elseif ($scope->scope_type == "Enms"){
                        $information_scope = Enms::where('id',$this->scope_name)->first();  
                    }
                     elseif ($scope->scope_type == "GHG"){
                        $information_scope = Ghg::where('id',$this->scope_name)->first();  
                    }
                } 
            } 
            return  !is_null($information_scope) ?  $information_scope->title : '';
        }

}
