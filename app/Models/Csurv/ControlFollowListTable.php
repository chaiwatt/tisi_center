<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ControlFollowListTable extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'control_follow_list_table';

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
    protected $fillable = ['id_follow', 'operator_name', 'address', 'month_check', 'original_grade', 'notification', 'system_control_check'
        , 'Product_test_results', 'follow_check', 'control_check', 'consider_grades','id_Autono'];
    /*
      Sorting
    */
    public $sortable = ['id_follow', 'operator_name', 'address', 'month_check', 'original_grade', 'notification', 'system_control_check'
        , 'Product_test_results', 'follow_check', 'control_check', 'consider_grades','id_Autono'];

}
