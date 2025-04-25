<?php

namespace App\Models\Law\Cases;


use Illuminate\Database\Eloquent\Model;

use App\User;
use App\Models\Law\Basic\LawSection;
use App\Models\Law\Cases\LawCasesCompareCalculate;
class LawCasesResultSection extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_case_result_section';
  
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
    protected $fillable = ['law_case_result_id', 'section', 'punish', 'power',  'created_by'];
        /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function law_cases_result_to(){
        return $this->belongsTo(LawCasesResult::class, 'law_case_result_id');
    }

    // มาตราความผิด
    public function section_to(){
        return $this->belongsTo(LawSection::class, 'section');
    }

    // บทกำหนดลงโทษ
    public function punish_to(){
        return $this->belongsTo(LawSection::class, 'punish');
    }

    public function getSectionNumberAttribute() {
        return @$this->section_to->number;
    }

    public function getSectionNameAttribute() {
        return @$this->section_to->number.' '.$this->section_to->title;
    }
    
    public function getPunishNumberAttribute() {
        return @$this->punish_to->number;
    }

    // อำนาจพิจารณาเปรียบเทียบปรับ
    public function getPowerNameAttribute() {
        $btn = '';

        if( $this->power == 1 ){
            $btn = 'เลขาธิการสำนักงานมาตรฐานอุตสาหกรรม (สมอ)';
        }else if( $this->power == 2 ){
            $btn = 'คณะกรรมการเปรียบเทียบ';
        }
        return $btn;
    }

    public function compare_calculate(){
        return $this->belongsTo(LawCasesCompareCalculate::class, 'id', 'law_case_result_section_id');
    }

}
