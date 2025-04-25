<?php

namespace App\Models\Bcertify;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
 
class SettingConfig extends Model
{

    use Sortable;
    protected $table = "bcertify_setting_config";
    protected $fillable = [
        'grop_type',
        'from_filed',
        'warning_day',
        'condition_check',
        'check_first',
        'created_by',
        'updated_by'
    ];


    static function list_from_filed() {
        $status = [
                    "1" => "วันที่ออกใบรับรอง",
                    // "2" => "วันที่เริ่มต้นในขอบข่าย",
                    "3" => "วันที่ตรวจครั้งล่าสุด",

                ];
        return $status;
    }
    public function getStatusTextAttribute() {
        $list = self::list_from_filed();
        $text = array_key_exists($this->from_filed,$list)?$list[$this->from_filed]:null;
     return $text;
 }
}
