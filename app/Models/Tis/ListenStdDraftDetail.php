<?php

namespace App\Models\Tis;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ListenStdDraftDetail extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_listen_std_draft_details';

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
    protected $fillable = ['listen_std_draft_id', 'comment_detail', 'attach', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['listen_std_draft_id', 'comment_detail', 'attach', 'created_by', 'updated_by'];



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
}
