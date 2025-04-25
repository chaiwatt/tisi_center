<?php

namespace App;

use App\Models\Basic\Department;
use App\Models\Basic\ProductGroup;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;

class IdeaPublic extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_idea_publics';

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
    protected $fillable = ['product', 'product_groups_id', 'description', 'standards_ref', 'attach', 'commentator', 'tel', 'email', 'departments_id', 'other_departments', 'state', 'created_by', 'updated_by'];

    /*
      Sorting
    */
    public $sortable = ['product', 'product_groups_id', 'description', 'standards_ref', 'commentator', 'tel', 'email', 'departments_id', 'other_departments', 'state', 'created_by', 'updated_by'];

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
  		return $this->user_created->reg_fname.' '.$this->user_created->reg_lname;
  	}

    public function getUpdatedNameAttribute() {
  		return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
  	}
	

	public function department(){
		return $this->belongsTo(Department::class, 'departments_id');
	}
	
	public function getDepartmentNameAttribute() {
		return @$this->department->title;
	}
	
	public function product_group(){
		return $this->belongsTo(ProductGroup::class, 'product_groups_id');
	}
	
	public function getProductGroupNameAttribute() {
		return @$this->product_group->title;
	}
	
}
