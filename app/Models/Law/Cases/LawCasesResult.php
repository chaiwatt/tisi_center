<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Basic\SubDepartment;
use App\Models\Law\File\AttachFileLaw;
use App\Models\Law\Basic\LawSection;


class LawCasesResult extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_result';
  
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
    protected $fillable = ['law_case_id', 'person', 'license', 'product',  'prosecute', 'remark', 'created_by', 'updated_by'];
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

    public function law_case_to(){
      return $this->belongsTo(LawCasesForm::class, 'law_case_id');
    }
    // มาตราความผิด/บทกำหนดลงโทษ
    public function law_case_result_section_many(){
      return $this->hasMany(LawCasesResultSection::class, 'law_case_result_id');
    }
    // ผลการพิจารณาแจ้งงานคดี  (การดำเนินการงานคดี)
    public function AttachFileConsider()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_consider');
    }
     // บันทึกพิจารณาคดี 
    public function AttachFileConsiderResult()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_consider_result');
    }
    // เปรียบเทียบปรับ
    public function AttachFileConsiderCompares()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_consider_compares');
    }
    // ข้อเท็จจริงการเปรียบเทียบปรับ
    public function AttachFileConsiderComparisonFacts()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_consider_comparison_facts');
    }
    //ไฟล์แนบอื่นๆ
    public function AttachFileOther()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_other');
    }

    public function law_case_license_result_to(){
        return $this->belongsTo(LawCasesLicenseResult::class, 'law_case_id','law_case_id');
    }

    public function law_section()
    {
        return $this->belongsToMany(LawSection::class, (new LawCasesResultSection)->getTable() , 'law_case_result_id', 'section');
    }

    public function law_punish()
    {
        return $this->belongsToMany(LawSection::class, (new LawCasesResultSection)->getTable() , 'law_case_result_id', 'punish');
    }

  // หมายเลข มาตราความผิด	 
    public function getOffenseSectionNumberAttribute() {
      $numbers = [];
      if(count($this->law_case_result_section_many) > 0){
        foreach ($this->law_case_result_section_many as $key => $item ){
            if(!empty($item->section_to->number)){
              $numbers[] = $item->section_to->number;
            }
         }
      }
      return $numbers;
  }

    // หมายเลข อัตราโทษ 	 
    public function getPunishNumberAttribute() {
      $numbers = [];
      if(count($this->law_case_result_section_many) > 0){
        foreach ($this->law_case_result_section_many as $key => $item ){
            if(!empty($item->punish_to->number)){
              $numbers[] = $item->punish_to->number;
            }
         }
      }
      return $numbers;
  }
 
 
}
