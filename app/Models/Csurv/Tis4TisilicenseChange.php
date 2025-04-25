<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Tis4TisilicenseCancel extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb4_tisilicense_change';

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
    protected $fillable = [
                          'tbl_licenseNo','tbl_licenseType','refno','pageNo','change_type','change_field','change_from','change_to',
                          'ordering','created_by','updated_by'
                         ];
    /*
      Sorting
    */
    public $sortable =[
                          'tbl_licenseNo','tbl_licenseType','refno','pageNo','change_type','change_field','change_from','change_to',
                          'ordering','created_by','updated_by'
                         ];

}
