<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;

class SampleDetail extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_rform_sample_detail';

    public function product_lab_detail(){
        return $this->belongsTo(ProductLabDetail::class, 'product_labdetail_id', 'id');
    }

    public function getProductDetailAttribute(){

        $product_lab_detail = $this->product_lab_detail;
        if(!is_null($product_lab_detail)){//แบบเดิม มีรายละเอียดผลิตภัณฑ์
            return !empty($product_lab_detail->product_detail->product_detail) ? $product_lab_detail->product_detail->product_detail : null;
        }else{

        }

    }

    public function getTestItemDetailAttribute(){

        $product_lab_detail = $this->product_lab_detail;
        if(!is_null($product_lab_detail)){//แบบเดิม มีรายละเอียดผลิตภัณฑ์
            return !empty($product_lab_detail->test_item) ? $product_lab_detail->test_item : null;
        }else{

        }

    }

    public function sample_detail_items(){
        return $this->hasMany(SampleDetailItem::class, 'sample_detail_id');
    }

}
