<?php

namespace App\Models\Law\Offense;

use Illuminate\Database\Eloquent\Model;

use App\Models\Law\Offense\LawOffender;

class LawOffenderLog extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'law_offenders_logs';

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
        'law_offender_id',
        'ref_table',
        'ref_id',
        'column',
        'data_old',
        'data_new',
        'created_by'
        
    ];

    public function getColumnNameAttribute(){

        $name = null;
        if( $this->ref_table == (new LawOffender)->getTable()  ){

            $arr_name = [
                'name'             => 'ชื่อผู้ประกอบการ',
                'taxid'            => 'เลขประตัวผู้เสียภาษี',
                'address_no'       => 'ที่ตั้งสำนักงานใหญ่ : ที่อยู่',
                'moo'              => 'ที่ตั้งสำนักงานใหญ่ : หมู่',
                'soi'              => 'ที่ตั้งสำนักงานใหญ่ : ซอย',
                'building'         => 'ที่ตั้งสำนักงานใหญ่ : อาคาร/หมู่บ้าน',
                'street'           => 'ที่ตั้งสำนักงานใหญ่ : ถนน',
                'subdistrict_id'   => 'ที่ตั้งสำนักงานใหญ่ : แขวง/ตำบล',
                'district_id'      => 'ที่ตั้งสำนักงานใหญ่ : เขต/อำเภอ',
                'province_id'      => 'ที่ตั้งสำนักงานใหญ่ : จังหวัด',
                'zipcode'          => 'ที่ตั้งสำนักงานใหญ่ : รหัสไปรษณีย์',
                'tel'              => 'เบอร์โทรศัพท์',
                'fax'              => 'เบอร์แฟกซ์',
                'email'            => 'อีเมล',
                'contact_name'     => 'ผู้ประสานงาน : ชื่อ-สกุล',
                'contact_position' => 'ผู้ประสานงาน : ตำแหน่ง',
                'contact_mobile'   => 'ผู้ประสานงาน : เบอร์มือถือ',
                'contact_phone'    => 'ผู้ประสานงาน : เบอร์โทรศัพท์',
                'contact_fax'      => 'ผู้ประสานงาน : เบอร์แฟกซ์',
                'contact_email'    => 'ผู้ประสานงาน : อีเมล',
                'date_offender'    => 'วันที่พบการกระทำผิด',
            ];

            $name  = array_key_exists( $this->column ,  $arr_name )? $arr_name[  $this->column ]:null;

        }


        return $name;
    }
    
}
