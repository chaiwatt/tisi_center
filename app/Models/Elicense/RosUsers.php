<?php

namespace App\Models\Elicense;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Elicense\RosUserGroupMap;

class RosUsers extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table = 'ros_users';
    
    public $timestamps = false;
    protected $primaryKey = 'id';


    protected $fillable = [
        'name',
        'username',
        'email',
        'contact_name',
        'password',
        'block',
        'sendEmail',
        'registerDate',
        'lastvisitDate',
        'activation',
        'params',
        'lastResetTime',
        'resetCount',
        'otpKey',
        'otep',
        'requireReset',
        'applicanttype_id',
        'date_niti',
        'tax_number',
        'nationality',
        'date_of_birth',
        'prefix_name',
        'address_no',
        'street',
        'moo',
        'soi',
        'subdistrict',
        'district',
        'province',
        'zipcode',
        'tel',
        'fax',
        'head_street',
        'head_address_no',
        'head_moo',
        'head_soi',
        'head_subdistrict',
        'head_district',
        'head_province',
        'head_zipcode',
        'head_tel',
        'head_fax',
        'attfile',
        'personfile',
        'corporatefile',
        'department_id',
        'authorize_name',
        'authorize_id_no',
        'copy_card_authorize',
        'agency_name',
        'agency_id_no',
        'agency_tel',
        'copy_card_agency',
        'letter_of_authority',
        'authorize',
        'authorize_data',
        'requireSign',
        'sign_tax_number',
        'sign_name',
        'sign_position',
        'sign_img',
        'token_otp',
        'consumer_secret',
        'agent_id',
        'consumer_key',
        'deleted_at',
        'remember_token',
        'state',
        'system',
        'person_type',
        'branch_type',
        'branch_code',
        'building',
        'head_building',
        'contact_tax_id',
        'contact_prefix_name',
        'contact_prefix_text',
        'contact_first_name',
        'contact_last_name',
        'contact_tel',
        'contact_fax',
        'contact_phone_number',
        'prefix_text',
        'person_first_name',
        'person_last_name',
        'name_en',
        'ibcb_code',
        'lab_code'        
    ];


    public function getColumnsAttribute(){
        return DB::connection($this->connection)->getSchemaBuilder()->getColumnListing($this->table);
    }

    public function data_list_group(){
        return $this->hasMany(RosUserGroupMap::class, 'user_id', 'id');
    }
}
