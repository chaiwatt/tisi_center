<?php

namespace App\Models\Besurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User;
use App\Models\Basic\SubDepartment;

class HsCode extends Model
{

    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb_hscode';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'tb_hscode_autonum';

}
