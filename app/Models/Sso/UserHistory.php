<?php

namespace App\Models\Sso;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User AS User_Register;
class UserHistory extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sso_users_historys';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'user_id',
                            'data_field',
                            'data_old',
                            'data_new',
                            'remark',
                            'created_at',
                            'created_by',
                            'editor_type'
                        ];

    /*
      Sorting
    */
    public $sortable = [
                        'user_id',
                        'data_field',
                        'data_old',
                        'data_new',
                        'remark',
                        'created_at',
                        'created_by',
                        'editor_type'
                       ];

    public $timestamps = false;

    /* บันทึกข้อมูล */
    static function Add($user_id, $data_field, $data_old, $data_new, $remark=null, $created_by=null, $editor_type='staff'){

        if(is_null($created_by)){//ถ้าไม่มีส่งเข้ามา
            $created_by = auth()->user()->getKey();
        }

        $history = new UserHistory;
        $history->user_id    = $user_id;
        $history->data_field = $data_field;
        $history->data_old   = $data_old;
        $history->data_new   = $data_new;
        $history->remark     = $remark;
        $history->created_by = $created_by;
        $history->created_at = date('Y-m-d H:i:s');
        $history->editor_type = $editor_type;
        $history->save();

    }

    public function user_history_many(){
        return $this->hasMany(UserHistory::class, 'user_id', 'user_id')->where('created_at',$this->created_at )->where('created_by',$this->created_by );
    }


    public function user_created() {
        return $this->belongsTo(User_Register::class, 'created_by');
     }


    public function getDataFieldNameAttribute()
    {
        $field_names = [
                        'id' => 'ไอดี',
                        'name' => 'ชื่อผู้ประกอบการ',
                        'name_en' => 'ชื่อผู้ประกอบการ (ภาษาอังกฤษ)',
                        'username' => 'ชื่อผู้ใช้งานระบบ',
                        'email' => 'e-Mail',
                        'contact_name' => 'ชื่อผู้ติดต่อ',
                        'password' => 'รหัสผ่าน',
                        'picture' => 'ภาพประจำตัว',
                        'block' => 'สถานะการใช้งาน',
                        'sendEmail' => 'สถานะการส่งรับอีเมล',
                        'registerDate' => 'วันที่ลงทะเบียน',
                        'lastvisitDate' => 'วันที่เข้าเข้างานระบบล่าสุด',
                        'params' => 'พารามิเตอร์เพิ่มเติม',
                        'lastResetTime' => 'วันที่รีเซ็ตรหัสผ่านล่าสุด',
                        'resetCount' => 'จำนวนครั้งที่เปลี่ยนรหัสผ่าน',
                        'applicanttype_id' => 'ประเภทการลงทะเบียน',
                        'juristic_status' => 'สถานะนิติบุคคล',
                        'juristic_cause_quit' => 'สาเหตุที่เลิกกิจการ',
                        'check_api' => 'ตรวจสอบข้อมูลจากหน่วยงาน',
                        'date_niti' => 'วันที่จดทะเบียน',
                        'tax_number' => 'เลขประจำตัวผู้เสียภาษี',
                        'nationality' => 'สัญชาติ',
                        'date_of_birth' => 'วันที่เกิด',
                        'prefix_name' => 'รหัสคำนำหน้าชื่อ',
                        'address_no' => 'เลขที่',
                        'street' => 'ถนน',
                        'moo' => 'หมู่',
                        'soi' => 'ตรอก/ซอย',
                        'subdistrict' => 'แขวง/ตำบล',
                        'district' => 'เขต/อำเภอ',
                        'province' => 'จังหวัด',
                        'zipcode' => 'รหัสไปรษณีย์',
                        'tel' => 'เบอร์โทร',
                        'fax' => 'เบอร์โทรสาร',
                        'latitude' => 'ละติจูด',
                        'longitude' => 'ลองจิจูด',
                        'contact_street' => 'ถนน (ผู้ติดต่อ)',
                        'contact_address_no' => 'เลขที่ (ผู้ติดต่อ)',
                        'contact_moo' => 'หมู่ (ผู้ติดต่อ)',
                        'contact_soi' => 'ตรอก/ซอย (ผู้ติดต่อ)',
                        'contact_subdistrict' => 'แขวง/ตำบล (ผู้ติดต่อ)',
                        'contact_district' => 'เขต/อำเภอ (ผู้ติดต่อ)',
                        'contact_province' => 'จังหวัด (ผู้ติดต่อ)',
                        'contact_zipcode' => 'รหัสไปรษณีย์ (ผู้ติดต่อ)',
                        'personfile' => 'เอกสารแนบการยืนยันตัวตนบุคคล',
                        'corporatefile' => 'เอกสารแนบการยืนยันตัวตนหน่วยงาน',
                        'remember_token' => 'Token Login',
                        'state' => 'สถานะการยืนยันตัวตน',
                        'person_type' => 'ประเภทเลขประจำตัวที่ใช้ลงทะเบียน',
                        'branch_type' => 'ประเภทสาขา',
                        'branch_code' => 'รหัสสาขา',
                        'building' => 'อาคาร/หมู่บ้าน',
                        'contact_building' => 'อาคาร/หมู่บ้าน (ผู้ติดต่อ)',
                        'contact_tax_id' => 'เลขบัตรประจำตัวประชาชน (ผู้ติดต่อ)',
                        'contact_prefix_name' => 'รหัสคำนำหน้าชื่อ (ผู้ติดต่อ)',
                        'contact_prefix_text' => 'คำนำหน้าชื่อ (ผู้ติดต่อ)',
                        'contact_first_name' => 'ชื่อ',
                        'contact_last_name' => 'สกุล',
                        'contact_position' => 'ตำแหน่ง',
                        'contact_tel' => 'เบอร์โทร (ผู้ติดต่อ)',
                        'contact_fax' => 'โทรสาร',
                        'contact_phone_number' => 'เบอร์โทรศัพท์มือถือ',
                        'prefix_text' => 'คำนำหน้าชื่อ',
                        'person_first_name' => 'ชื่อ(บุคคล)',
                        'person_last_name' => 'สกุล(บุคคล)',
                        'google2fa_status' => 'สถานะการใช้ Google 2FA',
                        'google2fa_secret' => 'รหัสลับ Google 2FA',
                        'address_en' => 'เลขที่ (ภาษาอังกฤษ)',
                        'moo_en' => 'หมู่ (ภาษาอังกฤษ)',
                        'soi_en' => 'ซอย (ภาษาอังกฤษ)',
                        'street_en' => 'ถนน (ภาษาอังกฤษ)',
                        'subdistrict_en' => 'ตำบล/แขวง (ภาษาอังกฤษ)',
                        'district_en' => 'อำเภอ/เขต (ภาษาอังกฤษ)',
                        'province_en' => 'จังหวัด (ภาษาอังกฤษ)',
                        'zipcode_en' => 'รหัสไปรษณีย์ (ภาษาอังกฤษ)',
                        'contact_address_en' => 'ชื่อ(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_moo_en' => 'ชื่อ(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_soi_en' => 'ซอย(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_street_en' => 'ถนน(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_subdistrict_en' => 'ตำบล/แขวง(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_district_en' => 'อำเภอ/เขต(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_province_en' => 'จังหวัด(ผู้ติดต่อ) (อังกฤษ)',
                        'contact_zipcode_en' => 'รหัสไปรษณีย์(ผู้ติดต่อ) (อังกฤษ)'
                       ];

        return array_key_exists($this->data_field, $field_names) ? $field_names[$this->data_field] : 'N/A' ;

    }



}
