<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use App\Models\Elicense\Rform\Product;
use DB;

class Sample extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_rform_sample';

    public function sample_details(){
        return $this->hasMany(SampleDetail::class, 'sample_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_lab(){
        return $this->belongsTo(ProductLab::class, 'product_lab_id', 'id');
    }

}
