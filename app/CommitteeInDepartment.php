<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CommitteeInDepartment extends Model
{
    protected $table = 'committee_in_departments'; 
    //ผู้ทรงวุฒิ = 0
    //ผู้แทนหลัก = 1
    //ผู้แทนสำรอง = 2
    //ฝ่ายเลขานุการ = 3
    protected $fillable = [
        'committee_special_id',
        'department_id',
        'name',
        'committee_type',
        'level',
        'represent_group',
        'position',
        'address',
        'tel',
        'fax',
        'email',
        'token'
    ];

    public function get_department()
    {
        $department = DB::table('basic_departments')->where('id',$this->department_id)->first();
        if ($department){
            return $department;
        }
        return null;
    }

    public function get_committee_type()
    {
        $legate_list = ['ผู้ทรงวุฒิ','ผู้แทนหลัก','ผู้แทนสำรอง','ฝ่ายเลขานุการ'];
        $legate = $this->committee_type;
        return $legate_list[$legate];
    }

}
