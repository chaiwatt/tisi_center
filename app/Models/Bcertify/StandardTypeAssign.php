<?php


namespace App\Models\Bcertify;

use App\Models\Bcertify\StdTypeAssignRoles;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use HP;
 
class StandardTypeAssign extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'bcertify_standard_type_assign';

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
    protected $fillable = ['bc_standard_type_id', 'ordering'];


    public function bcertify_std_type_assign_roles(){
        return $this->hasMany(StdTypeAssignRoles::class, 'bc_std_assign_id');
    }


}
