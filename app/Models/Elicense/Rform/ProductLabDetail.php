<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductLabDetail extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table      = 'ros_rform_product_labdetail';

    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable   = [ 
        'product_lab_id',
        'product_detail_id',
        'test_item',
        'state'

    ];

    public function product_detail(){
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }

}
