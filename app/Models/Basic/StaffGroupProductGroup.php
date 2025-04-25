<?php

namespace App\Models\Basic;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\ProductGroup;

class StaffGroupProductGroup extends Model
{
    use Sortable;

    const CREATED_AT = null;
    const UPDATED_AT = null;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'basic_staff_group_product_groups';

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
    protected $fillable = ['staff_group_id', 'product_group_id'];

    /*
      Sorting
    */
    public $sortable = ['staff_group_id', 'product_group_id'];

    /* Product Group */
    public function product_group(){
      return $this->belongsTo(ProductGroup::class, 'product_group_id');
    }

    public function getStaffGroup()
    {
        return $this->belongsTo(StaffGroup::class,'staff_group_id');
    }

}
