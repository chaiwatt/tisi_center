<?php


namespace App\Models\Bcertify;

use App\Models\Bcertify\StandardTypeAssign;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
 
class StdTypeAssignRoles extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bcertify_std_type_assign_roles';

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
    protected $fillable = ['bc_std_assign_id', 'roles'];


    public function bcertify_standard_type_assign(){
        return $this->belongsTo(StandardTypeAssign::class, 'bc_std_assign_id');
    }

}
