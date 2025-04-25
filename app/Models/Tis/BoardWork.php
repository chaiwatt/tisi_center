<?php

namespace App\Models\Tis;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Basic\Department;

class BoardWork extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tis_board_works';

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
    protected $fillable = ['board_id',
                           'position',
                           'department_id',
                           'responsible',
                           'abode',
                           'experience',
                           'belong_to',
                           'phone',
                           'ministry',
                           'fax',
                           'status',
                           'created_by',
                           'updated_by'
                          ];

    /*
      Sorting
    */
    public $sortable = ['board_id',
                        'position',
                        'department_id',
                        'responsible',
                        'abode',
                        'experience',
                        'belong_to',
                        'phone',
                        'ministry',
                        'fax',
                        'status',
                        'created_by',
                        'updated_by'
                       ];

    /* department */
    public function department(){
      return $this->belongsTo(Department::class, 'department_id');
    }

}
