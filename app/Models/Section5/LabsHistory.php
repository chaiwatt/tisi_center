<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\User AS User_Register;
use HP;
class LabsHistory extends Model
{
    use Sortable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'section5_labs_historys';

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
                            'created_by'
                        ];

    /*
      Sorting
    */
    public $sortable = [
                        'lab_id',
                        'data_field',
                        'data_old',
                        'data_new',
                        'remark',
                        'created_at',
                        'created_by'
                       ];

    public $timestamps = false;

    /* บันทึกข้อมูล */
    static function Add($lab_id, $data_field, $data_old, $data_new, $remark=null, $created_by=null){

        if(is_null($created_by)){//ถ้าไม่มีส่งเข้ามา
            $created_by = auth()->user()->getKey();
        }

        $history = new LabsHistory;
        $history->lab_id     = $lab_id;
        $history->data_field = $data_field;
        $history->data_old   = $data_old;
        $history->data_new   = $data_new;
        $history->remark     = $remark;
        $history->created_by = $created_by;
        $history->created_at = date('Y-m-d H:i:s');
        $history->save();

    }

    public function user_created() {
        return $this->belongsTo(User_Register::class, 'created_by');
    }

    public function getDataFieldNameAttribute()
    {
        $data_field   = $this->data_field;

        $columns = [
            "lab_name"           => 'ชื่อห้องปฏิบัติการ',
            "lab_address"        => 'ที่อยู่',
            "lab_building"       => 'หมู่บ้าน/อาคาร',
            "lab_soi"            => 'ตรอก/ซอย',
            "lab_moo"            => 'หมู่ที่',
            "lab_phone"          => 'โทรศัพท์',
            "lab_fax"            => 'โทรสาร',
            "lab_subdistrict_id" => 'ตำบล/แขวง',
            "lab_district_id"    => 'อำเภอ/เขต',
            "lab_province_id"    => 'จังหวัด',
            "lab_zipcode"        => 'รหัสไปรษณีย์',
            "co_name"            => 'ชื่อผู้ประสานงาน',
            "co_position"        => 'ตำแหน่งผู้ประสานงาน',
            "co_mobile"          => 'โทรศัพท์มือถือผู้ประสานงาน',
            "co_phone"           => 'โทรศัพท์ผู้ประสานงาน',
            "co_fax"             => 'โทรสารผู้ประสานงาน',
            "co_email"           => 'อีเมลผู้ประสานงาน',
            "ibcb_end_date"      => 'วันที่สิ้นสุดเป็นหน่วยตรวจสอบ',
            'state'              => 'สถานะหน่วยตรวจสอบ'
        ];

        return array_key_exists( $data_field,  $columns )?$columns[ $data_field ]:'-';
    }


    public function getDataOldNameAttribute()
    {
        $text = [
            "lab_name",   
            "lab_address",
            "lab_building",  
            "lab_soi",   
            "lab_moo",   
            "lab_phone",  
            "lab_fax",
            "lab_zipcode", 
            "co_name" ,
            "co_position",
            "co_mobile",
            "co_phone" ,
            "co_fax",       
            "co_email"   
        ];

        $address = [
            "lab_subdistrict_id", 
            "lab_district_id",  
            "lab_province_id" ,
        ];

        $date = [
            "ibcb_end_date"            
        ];

        $state = [
            'state'             
        ];

        $StateHtml = [ 1 => '<span class="text-success">Active</span>', 2 => '<span class="text-danger">Not Active</span>' ];

        $data_field   = $this->data_field;
        $data         = $this->data_old;

        if( in_array( $data_field,  $text ) ){
            return $data;
        }else if(  in_array( $data_field,  $address ) ){

            if( $data_field == 'lab_subdistrict_id'  ){
                return HP::gat_district(  $data );
            }elseif( $data_field == 'lab_district_id' ){
                return HP::gat_amphur(  $data );
            }elseif( $data_field == 'lab_province_id' ){
                return HP::gat_province(  $data );
            }
        }else if(  in_array( $data_field,  $date ) ){
            return !empty( $data )?HP::revertDate($data,true):null;
        }else if(  in_array( $data_field,  $state ) ){
            return array_key_exists( $data, $StateHtml )?$StateHtml[ $data ]:'<span class="text-danger">Not Active</span>';;
        }

    }

    public function getDataNewNameAttribute()
    {
        $text = [
            "lab_name",   
            "lab_address",
            "lab_building",  
            "lab_soi",   
            "lab_moo",   
            "lab_phone",  
            "lab_fax",
            "lab_zipcode", 
            "co_name" ,
            "co_position",
            "co_mobile",
            "co_phone" ,
            "co_fax",       
            "co_email"   
        ];

        $address = [
            "lab_subdistrict_id", 
            "lab_district_id",  
            "lab_province_id" ,
        ];

        $date = [
            "ibcb_end_date"            
        ];

        $state = [
            'state'             
        ];

        $StateHtml = [ 1 => '<span class="text-success">Active</span>', 2 => '<span class="text-danger">Not Active</span>' ];

        $data_field   = $this->data_field;
        $data         = $this->data_new;

        if( in_array( $data_field,  $text ) ){
            return $data;
        }else if(  in_array( $data_field,  $address ) ){
            if( $data_field == 'lab_subdistrict_id'  ){
                return HP::gat_district(  $data );
            }elseif( $data_field == 'lab_district_id' ){
                return HP::gat_amphur(  $data );
            }elseif( $data_field == 'lab_province_id' ){
                return HP::gat_province(  $data );
            }
        }else if(  in_array( $data_field,  $date ) ){
            return !empty( $data )?HP::revertDate($data,true):null;
        }else if(  in_array( $data_field,  $state ) ){
            return array_key_exists( $data, $StateHtml )?$StateHtml[ $data ]:'<span class="text-danger">Not Active</span>';;
        }

    }

}
