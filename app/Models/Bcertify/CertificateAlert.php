<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class CertificateAlert extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'certificate_alert';

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
    protected $fillable = ['id', 'color', 'date_start', 'date_end', 'status', 'created_at', 'updated_at'];

    /*
      Sorting
    */
    public $sortable = ['id', 'color', 'date_start', 'date_end', 'status', 'created_at', 'updated_at'];

}
