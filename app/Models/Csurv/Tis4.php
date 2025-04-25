<?php

namespace App\Models\Csurv;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Tis4 extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tb4_tisilicense';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'Autono';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['tbl_licenseNo', 'tbl_licenseType', 'tbl_licenseDate', 'tbl_tisiNo', 'tbl_tradeName', 'tbl_tradeAddress', 'tbl_factoryName'
        , 'tbl_producer', 'tbl_exporter', 'tbl_input_data', 'tbl_licenseStatus', 'tbl_extend', 'tbl_newtisNo', 'tbl_pdf_path'
        , 'tbl_taxpayer', 'tbl_factoryID', 'tbl_factoryAddress', 'tbl_factoryInprovince', 'tbl_origin', 'tbl_userID'
        , 'tbl_date_timeEdit', 'tbl_trademark'];
    /*
      Sorting
    */
    public $sortable = ['tbl_licenseNo', 'tbl_licenseType', 'tbl_licenseDate', 'tbl_tisiNo', 'tbl_tradeName', 'tbl_tradeAddress', 'tbl_factoryName'
        , 'tbl_producer', 'tbl_exporter', 'tbl_input_data', 'tbl_licenseStatus', 'tbl_extend', 'tbl_newtisNo', 'tbl_pdf_path'
        , 'tbl_taxpayer', 'tbl_factoryID', 'tbl_factoryAddress', 'tbl_factoryInprovince', 'tbl_origin', 'tbl_userID'
        , 'tbl_date_timeEdit', 'tbl_trademark'];

}
