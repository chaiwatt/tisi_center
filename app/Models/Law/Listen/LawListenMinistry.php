<?php

namespace App\Models\Law\Listen;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Listen\LawListenMinistryResponse;
use App\Models\Law\Listen\LawListenMinistryTrack;
class LawListenMinistry extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_listen_ministry';
  
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
                            'ref_no',
                            'title',
                            'listen_type',
                            'tis_name',
                            'tis_no', 
                            'remark', 
                            'dear', 
                            'status_dear', 
                            'url_type', 
                            'url', 
                            'date_due', 
                            'date_start', 
                            'date_end', 
                            'mail_status', 
                            'mail_list', 
                            'status_id', 
                            'date_diagnosis', 
                            'status_diagnosis',
                            'mail_status_diagnosis',
                            'mail_list_diagnosis',
                            'status_close',
                            'close_date',
                            'close_by',
                            'state',
                            'book_no',
                            'book_date',
                            'amount',
                            'sign_id',            
                            'sign_name',
                            'sign_position',
                            'sign_img',
                            'responses_type',
                            'created_by', 
                            'updated_by',
                            'tis_type'
                        ];
                        

                        
    public function law_listen_ministry_response(){
        return $this->hasMany(LawListenMinistryResponse::class,'listen_id');
    }

    public function law_listen_ministry_results(){
        return $this->hasOne(LawListenMinistryResults::class,'listen_id');
    }

    public function law_listen_ministry_track(){
        return $this->hasMany(LawListenMinistryTrack::class, 'listen_id');
    }

    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }
  
    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedPhoneAttribute() {
        return @$this->user_created->reg_phone;
    }

    public function getCreatedSubdepartAttribute() {
        return @$this->user_created->DepartName;
    }

    public function getCreatedWPhoneAttribute() {
        return @$this->user_created->reg_wphone;
    }

    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }
  
    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
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
    static function list_status() {
        $status = [
                    "1" => "จัดทำหนังสือแบบรับฟังความเห็น",
                    "2" => "ประกาศรับฟังความเห็น",
                    "3" => "ปิดประกาศ (อยู่ระหว่างสรุปความเห็น)",
                    "4" => "แจ้งผลคำวินิจฉัย",
                    "5" => "ประกาศราชกิจจาแล้ว",
                ];
        return $status;
    }

    static function list_status_diagnosis() {
        $status = [
                    "1" => "อนุมัติให้เป็นไปตามมาตรฐาน",
                    "2" => "ไม่อนุมัติให้เป็นไปตามมาตรฐาน",
                ];
        return $status;
    }

    public function getStatusTextAttribute() {
           $list = self::list_status();
           $text = array_key_exists($this->status_id,$list)?$list[$this->status_id]:null;
        return $text;
    }

    public function AttachFileListenMinistry()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_listen_ministry');
    }

    public function AttachFileDraftMinisterial()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_draft_ministerial');
    }

    public function AttachFileDraftStandard()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_draft_standard');
    }

    public function AttachFileOther()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_other');
    }

    public function AttachFileResult()
    {
        return $this->hasOne(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_result');
    }




}
