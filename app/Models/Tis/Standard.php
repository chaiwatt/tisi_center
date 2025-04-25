<?php

namespace App\Models\Tis;

use App\Models\Basic\SetFormat;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\IndustryTarget;
use App\Models\Basic\ProductGroup;
use App\Models\Basic\Method;
use App\Models\Basic\StandardType;
use App\Models\Basic\StandardFormat;
use App\Models\Basic\StaffGroup;
use App\Models\Basic\Ics;
use App\Models\Bsection5\TestItem;

class Standard extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_standards';

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
    protected $fillable = [ 'title',
                            'title_en',
                            'tis_force',
                            'issue_date',
                            'amount_date',
                            'gaz_date',
                            'gaz_no',
                            'gaz_space',
                            'tis_no',
                            'tis_year',
                            'tis_book',
                            'remark',
                            'board_type_id',
                            'standard_type_id',
                            'standard_format_id',
                            'set_format_id',
                            'method_id',
                            'method_id_detail',
                            'product_group_id',
                            'industry_target_id',
                            'staff_group_id',
                            'staff_responsible',
                            'refer',
                            'attach',
                            'state',
                            'review_status',
                            'ics',
                            'isbn',
                            'minis_dated',
                            'minis_dated_compulsory',
                            'issue_date_compulsory',
                            'minis_no_compulsory',
                            'gaz_date_compulsory',
                            'gaz_no_compulsory',
                            'gaz_space_compulsory',
                            'announce_compulsory',
                            'government_gazette',
                            'created_by',
                            'updated_by',
                            'minis_no',
                            'cancel_date',
                            'cancel_reason',
                            'cancel_minis_no',
                            'cancel_attach',
                            'amount_date_compulsory',
                            'set_std_id',
                            'tis_product_name',
                            'tis_tisno',
                            'tis_tisshortno','publishing_status','tisid_ref','tisno_ref'
                          ];

    /*
      Sorting
    */
    public $sortable = ['title',
                            'title_en',
                            'tis_force',
                            'issue_date',
                            'amount_date',
                            'gaz_date',
                            'gaz_no',
                            'gaz_space',
                            'tis_no',
                            'tis_year',
                            'tis_book',
                            'remark',
                            'board_type_id',
                            'standard_type_id',
                            'standard_format_id',
                            'set_format_id',
                            'method_id',
                            'method_id_detail',
                            'product_group_id',
                            'industry_target_id',
                            'staff_group_id',
                            'staff_responsible',
                            'refer',
                            'attach',
                            'state',
                            'review_status',
                            'ics',
                            'isbn',
                            'minis_dated',
                            'minis_dated_compulsory',
                            'issue_date_compulsory',
                            'minis_no_compulsory',
                            'gaz_date_compulsory',
                            'gaz_no_compulsory',
                            'gaz_space_compulsory',
                            'announce_compulsory',
                            'government_gazette',
                            'created_by',
                            'updated_by',
                            'minis_no',
                            'cancel_date',
                            'cancel_reason',
                            'cancel_minis_no',
                            'cancel_attach',
                            'amount_date_compulsory',
                            'set_std_id',
                            'tis_product_name',
                            'tis_tisno',
                            'tis_tisshortno','publishing_status','tisid_ref','tisno_ref'
                          ];

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
      industry_target Relation
    */
    public function industry_target(){
      return $this->belongsTo(IndustryTarget::class, 'industry_target_id');
    }

    /*
      product_group Relation
    */
    public function product_group(){
      return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    /*
      method Relation
    */
    public function method(){
      return $this->belongsTo(Method::class, 'method_id');
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
    // เวียนร่างและประกาศรับฟังความคิดเห็นร่างกฎกระทรวง  
    public function note_std_draft_to(){
      return $this->belongsTo(NoteStdDraft::class,'id' ,'standard_id');
    }


    public function board_type(){
      return $this->belongsTo(Appoint::class, 'board_type_id');
    }

    public function staff_group(){
      return $this->belongsTo(StaffGroup::class, 'staff_group_id');
    }
	/*
	Set Format C Relation
  */
	public function set_format(){
		return $this->belongsTo(SetFormat::class, 'set_format_id');
  }

  public function ics_iso(){
		return $this->belongsTo(Ics::class, 'ics');
	}

	public function getSetFormatNameAttribute() {
		return @$this->set_format->title;
  }

  public function getBoardTypeNameAttribute() {
		return @$this->board_type->board_position ?? 'n/a';
	}

    public function getStandardFormatNameAttribute() {
        return @$this->standard_format->title;
    }

    public function getStandardTypeNameAttribute() {
        return @$this->standard_type->title;
    }

    public function getProductGroupNameAttribute() {
        return @$this->product_group->title;
    }

    public function getStaffGroupNameAttribute() {
        return @$this->staff_group->title ?? 'n/a';
    }

    public function getInductryTargetNameAttribute() {
        return @$this->industry_target->title;
    }

    public function getMethodNameAttribute() {
        return @$this->method->title;
    }

    public function getReviewStatusNameAttribute() {
      $arr = ['1'=>'มาตรฐานเดิม','2'=>'ทบทวนมาตรฐาน'];
      return @$arr[$this->review_status];
    }

    public function getIsoCodeNameAttribute() {
            $html = '';
            $ics_list = !empty($this->ics)?json_decode($this->ics):null;
            if($ics_list){
              $ics_datas = Ics::WhereIn('id', $ics_list)->get();
              foreach($ics_datas as $ics_data){
                $html .= $ics_data['code']."<br>";
              }
            } else {
              $html = 'n/a';
            }
        return $html;
    }

    public function productGroupSortable($query, $direction){
        return $query->leftjoin('basic_product_groups', 'basic_product_groups.id', '=', 'tis_standards.product_group_id')
                    ->orderByRaw("CONVERT(basic_product_groups.title USING tis620) $direction")
                    ->select('tis_standards.product_group_id');
    }

    public function getGovernmentGazetteNameAttribute() {
        $arr = ['y'=>'มาตรฐานที่ประกาศราชกิจจาแล้ว','w'=>'มาตรฐานที่ผ่าน กมอ. แล้ว รอประกาศราชกิจจา'];
        return @$arr[$this->government_gazette];
    }

    public function test_item_data()
    {
        return $this->hasMany(TestItem::class,'tis_id', 'id');
    }

}
