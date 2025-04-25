<?php

namespace App\Models\Law\Listen;

use App\Models\Law\File\AttachFileLaw;
use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Listen\LawListenMinistry;

class LawListenMinistryTrack extends Model
{
    /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'law_listen_ministry_track';

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
                          'date_track', 
                          'date_due', 
                          'status_id', 
                          'detail', 
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

  public function listen_ministry(){
      return $this->belongsTo(LawListenMinistry::class, 'listen_id');
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

  public function AttachFileTrack()
  {
      return $this->belongsTo(AttachFileLaw::class,'id','ref_id')->where('ref_table',$this->getTable())->where('section','file_law_listministry_track');
  }

}