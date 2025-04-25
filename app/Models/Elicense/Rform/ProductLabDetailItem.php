<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;

class ProductLabDetailItem extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table      = 'ros_rform_product_labdetail_item';

    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable   = [ 
        'product_lab_id',
        'product_detail_id',
        'test_item_id',
        'state',

    ];

    public function product_detail(){
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }

}
