<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Tis\BoardBoardType;
use App\Models\Tis\BoardProductGroup;
use App\Models\Tis\BoardWork;

class Board extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_boards';

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
    protected $fillable = ['prefix_name', 'first_name', 'last_name', 'birth_date', 'identity_number', 'qualification', 'institute', 'contact', 'tel', 'email', 'picture', 'bank_account', 'bank_name', 'bank_branch', 'type_account', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['prefix_name', 'first_name', 'last_name', 'birth_date', 'identity_number', 'qualification', 'institute', 'contact', 'tel', 'email', 'picture', 'bank_account', 'bank_name', 'bank_branch', 'type_account', 'state', 'created_by', 'updated_by'];

    // public $sortableAs = ['board_type_list_name'];
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

    /* Board Type List */
    public function board_type_list(){
      return $this->hasMany(BoardBoardType::class);
    }

    /* Product Group List */
    public function product_group_list(){
      return $this->hasMany(BoardProductGroup::class);
    }

    /* Work List */
    public function work_list(){
      return $this->hasMany(BoardWork::class);
    }

    public function getFullNameAttribute() {
  		return $this->prefix_name.$this->first_name.' '.$this->last_name ?? "n/a";
    }

    public function getBoardTypeListNameAttribute() {
            $html = '';
  		 foreach ($this->board_type_list as $key => $board_type){
             $html .=  @$board_type->board_type->title;
       }
       return $html;
    }



}
