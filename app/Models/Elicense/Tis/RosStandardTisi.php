<?php

namespace App\Models\Elicense\Tis;

use Illuminate\Database\Eloquent\Model;

class RosStandardTisi extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_rbasicdata_standard_tisi';
    public $timestamps = false;

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
                            'phase',
                            'tisi_shortnumber',
                            'tis_number',
                            'tis_name',
                            'eng_name',
                            'product_name',
                            'productline',
                            'productline_id',
                            'category',
                            'for_moao_use',
                            'for_lab_use',
                            'for_ib_use',
                            'using_product_license',
                            'product_duration',
                            'using_factory_license',
                            'factory_duration',
                            'code',
                            'code_id',
                            'department',
                            'department_id',
                            'duration',
                            'status',
                            'ordering',
                            'state',
                            'checked_out_time',
                            'checked_out',
                            'created',
                            'created_by',
                            'modified',
                            'modified_by',
                            'inherit_id',
                            'set_type',
                            'bigdata_standard_tisi_id'

                        ];
}
