<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\Rform\Product;

use App\Models\Elicense\Rform\ProductLabDetail;
use App\Models\Elicense\Rform\ProductLabDetailItem;


class ProductLab extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table      = 'ros_rform_product_lab';

    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable   = [ 

        'product_id',
        'order',
        'lab_id',
        'officer_order',
        'status',
        'date_sent',
        'start_date',
        'end_date',
        'checking_by',
        'checking_date',
        'checking_comment',
        'approve_by',
        'approve_date',
        'approve_comment'

    ];

    //ใบรับนำส่งตัวอย่าง
    public function samples(){
        return $this->hasMany(Sample::class, 'product_lab_id');
    }

    public function status_list(){
        return  [
                '1' => 'รอการตอบรับ',
                '2' => 'รับคำขอ',
                '3' => 'ไม่รับคำขอ',
                '4' => 'ยกเลิกโดยระบบ',
                '5' => 'รับตัวอย่าง อยู่ระหว่างทดสอบ',
                '6' => 'ตัวอย่างผลิตภัณฑ์ไม่ครบ',
                '7' => 'ขอตัวอย่างผลิตภัณฑ์เพิ่ม',
                '8' => 'ส่งผลการทดสอบ'
        ];
    }

    public function lab(){
        return $this->belongsTo(RosUsers::class, 'lab_id', 'id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function lab_detail(){
        return $this->hasMany(ProductLabDetail::class, 'product_lab_id');
    }

    public function lab_detail_item(){
        return $this->hasMany(ProductLabDetailItem::class, 'product_lab_id');
    }
}
