<?php

namespace App\Models\Tis;

use App\Models\Basic\Department;
use App\Models\Tis\PublicDraft;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ImportComment extends Model
{
       use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_import_comments';

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
                           'attach_old', 'attach_path',
                           'attach_files', 'attach_size', 'attach_excel', 'amount_import',
                           'amount_all', 'status', 'description',
                           'error_detail', 'save_date'
                          ];

    /*
      Sorting
    */
    public $sortable = [
                           'attach_old', 'attach_path',
                           'attach_files', 'attach_size', 'attach_excel', 'amount_import',
                           'amount_all', 'status', 'description',
                           'error_detail', 'save_date'
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
      return !empty($this->user_created->reg_fname) && !empty($this->user_created->reg_lname)?$this->user_created->reg_fname.' '.$this->user_created->reg_lname:'n/a';
    }

    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

}
