<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

use App\Models\Tis\Standard;


use App\User;
class NoteStdDraft extends Model
{
    use Sortable;

    protected $table = "tis_note_std_drafts";

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'standard_id',
        'tis_no',
        'title',
        'title_en',
        'title_draft',
        'attach',
        'result_draft',
        'minis_dated',
        'minis_dated_compulsory',
        'issue_date_compulsory',
        'minis_no_compulsory',
        'gaz_date_compulsory',
        'gaz_no_compulsory',
        'gaz_space_compulsory',
        'announce_compulsory',
        'government_gazette',
        'status_id',
        'state',
        'created_by',
        'updated_by',
        'result_draft',
        'amount_date_compulsory',
        'start_date','end_date','status_publish','attach_note'
    ];

    /*
      Sorting
    */
    public $sortable = [
       'standard_id',
        'tis_no',
        'title',
        'title_en',
        'title_draft',
        'attach',
        'result_draft',
        'minis_dated',
        'minis_dated_compulsory',
        'issue_date_compulsory',
        'minis_no_compulsory',
        'gaz_date_compulsory',
        'gaz_no_compulsory',
        'gaz_space_compulsory',
        'announce_compulsory',
        'government_gazette',
        'status_id',
        'state',
        'created_by',
        'updated_by',
        'result_draft',
        'amount_date_compulsory',
        'start_date','end_date','status_publish','attach_note'
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
  		return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}

    public function standard(){
      return $this->belongsTo(Standard::class, 'standard_id');
    }

    public function getProductGroupNameAttribute(){
      return $this->standard->ProductGroupName??'n/a';
    }

    public function getResultDraftNameAttribute(){
      $arr = ['1'=>'แก้ไขมาตรฐาน', '2'=>'ประกาศเป็นมาตรฐานบังคับ'];
      return $arr[$this->result_draft]??'รอผลการเวียนร่าง';
    }
    public function getStatusPublishNameAttribute(){
      $arr = ['1'=>'เผยแพร่', '0'=>'ยังไม่เผยแพร่'];
      return $arr[$this->status_publish]??'ยังไม่เผยแพร่';
    }
}
