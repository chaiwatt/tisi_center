<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductDetail extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_rform_product_detail';
    public    $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable   = [ 
        'product_id',
        'product_detail'
        

    ];
}
