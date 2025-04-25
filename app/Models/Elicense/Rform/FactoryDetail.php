<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;

class FactoryDetail extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_rform_factory_detail';

    public function status_list(){
        return  [
                    2 => 'รับคำขอ',
                    3 => 'ไม่รับคำขอ',
                    4 => 'ยกเลิกโดยระบบ'
                ];
    }

    public function inspect_status_list(){
        return  [
                    1 => 'ตรวจโรงงาน',
                    2 => 'แก้ไขข้อบกพร่อง',
                    3 => 'ประเมินผลแล้ว',
                    4 => 'จัดทำรายงานแล้ว'
                ];
    }

    public function inspect_result_list(){
        return [1 => 'ผ่าน', 2 => 'ไม่ผ่าน'];
    }

}
