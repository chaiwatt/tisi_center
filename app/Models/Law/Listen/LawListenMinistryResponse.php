<?php

namespace App\Models\Law\Listen;

use App\User;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Listen\LawListenMinistry;

class LawListenMinistryResponse extends Model
{
      /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_listen_ministry_responses';
  
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
                            'listen_id', 
                            'comment_point', 
                            'comment_more', 
                            'trader_type', 
                            'trader_other', 
                            'tax_number', 
                            'name', 
                            'agency', 
                            'position', 
                            'address', 
                            'tel', 
                            'email', 
                            'created_by', 
                            'updated_by'
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
  
    public function getStateIconAttribute() {
        $btn = '';
  
        if( $this->state != 1 ){
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="1" title="เปิดใช้งาน"><span class="text-danger">ปิดใช้งาน</span> </a>';
        }else{
            $btn = ' <a href="javascript:void(0)" class="btn_update_state" data-id="'.($this->id).'"  data-state="0" title="ปิดใช้งาน"><span class="text-success">เปิดใช้งาน</span></a>';
        }
        return $btn;
    }

    public function getContactAttribute() {
        $contact = '';
        $contact .=  !empty($this->tel)? '<i class="mdi mdi-phone"></i> '.$this->tel:null; 
        $contact .=  !empty($this->email)? '<br> <i class="mdi mdi-email-outline"></i> '.$this->email:null; 
        
      return @$contact;
    }

    static function list_comment_point() {
        $comment = [
                    "1" => "เห็นชอบให้บังคับตามร่างกฎกระกระทรวงฯ ทุกประการ",
                    // "2" => "ไม่เห็นชอบให้บังคับตามร่างกฎกระกระทรวงฯ และมีความคิดเห็นเพิ่มเติม",
                    "2" => "มีความคิดเห็นเพิ่มเติม",
                    "3" => "เห็นชอบกับการขยายระยะเวลา",
                    "4" => "ไม่เห็นชอบกับการขยายระยะเวลา",
                  ];
        return $comment;
    }

    public function getCommentAttribute() {
        $list = self::list_comment_point();
        $text = array_key_exists($this->comment_point,$list)?$list[$this->comment_point]:null;
        return $text;
      }

      static function list_trader_type() {
        $comment = [
                    "1" => "นิติบุคคล",
                    "2" => "บุคคลธรรมดา",
                    "3" => "อื่นๆ",
                  ];
        return $comment;
    }

    //ประเภทสถานประกอบการ
    public function getTraderTypeNameAttribute() {
        $list = self::list_trader_type();

        if($this->trader_type == 3){//อื่นๆ
          $text = $this->trader_other;
        }else{
          $text = array_key_exists($this->trader_type,$list)?$list[$this->trader_type]:null;
        }
        return $text;
      }

    public function listen_ministry(){
        return $this->belongsTo(LawListenMinistry::class, 'listen_id');
    }

    public function getRefNoAttribute() {
        return @$this->listen_ministry->ref_no;
    }

    public function getTileAttribute() {
      return @$this->listen_ministry->title;
    }

    public function getDateStartAttribute() {
        return @$this->listen_ministry->date_start;
    }

    public function getTisNoAttribute() {
        return @$this->listen_ministry->tis_no;
    }

    public function getTisNameAttribute() {
        return @$this->listen_ministry->tis_name;
    }

    public function AttachFileResponse()
    {
        return $this->hasMany(AttachFileLaw::class,'ref_id','id')->where('ref_table',$this->table)->where('section','file_response');
    }
}
