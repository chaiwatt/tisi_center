<?php

namespace App\Models\Law\Log;

use App\User;
use App\Models\Law\File\AttachFileLaw;

use Illuminate\Database\Eloquent\Model;
use App\Models\Law\Log\LawSystemCategory;

class LawLogWorking extends Model
{
        /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_log_working';

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
    protected $fillable = ['law_system_category_id', 'ref_table', 'ref_id', 'ref_no', 'ref_system', 'title', 'status', 'remark', 'created_by', 'updated_by'];
        /*
      User Relation
    */
    public function user_created(){
      return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
      return $this->belongsTo(User::class, 'updated_by');
    }
    public function system_category(){
      return $this->belongsTo(LawSystemCategory::class, 'law_system_category_id');
    }

    public function getSystemNameAttribute() {
      return @$this->system_category->name;
    }

    public function getSysteColorAttribute() {
      return @$this->system_category->color;
    }

    public function getCreatedNameAttribute() {
      return (@$this->user_created->reg_fname).(!empty($this->user_created->reg_lname)?' '.$this->user_created->reg_lname:null);
    }

    public function getUpdatedNameAttribute() {
      return (@$this->user_updated->reg_fname).(!empty($this->user_updated->reg_lname)?' '.$this->user_updated->reg_lname:null);
    }

    public function getAttachFilesAttribute()
    {
        return $this->belongsTo(AttachFileLaw::class, 'id', 'ref_id')->where('ref_table', $this->getTable());
    }

}
