<?php

namespace App\Models\Elicense\Rform;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Tis\Standard;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\Tis\RosStandardTisi;

class Product extends Model
{
    protected $connection = 'mysql_elicense';
    protected $table      = 'ros_rform_product';
    protected $primaryKey = 'id';
    public    $timestamps = false;

    protected $fillable   = [ 
        'id',
        'refno',
        'execute_date',
        'coordinator',
        'coordinator_position',
        'coordinator_tel',
        'moao_type',
        'tis_number',
        'tis_id',
        'test_format',
        'tax_number',
        'factory_name',
        'factory_address_no',
        'factory_street',
        'factory_moo',
        'factory_soi',
        'factory_subdistrict',
        'factory_district',
        'factory_province',
        'factory_country',
        'factory_zipcode',
        'factory_regis_no',
        'factory_same_address',
        'storage_name',
        'storage_address_no',
        'storage_street',
        'storage_moo',
        'storage_soi',
        'storage_subdistrict',
        'storage_district',
        'storage_province',
        'storage_zipcode',
        'evidence_first_one',
        'evidence_first_two',
        'evidence_first_three',
        'evidence_first_four',
        'evidence_second',
        'evidence_third',
        'evidence_fourth_name',
        'evidence_fourth',
        'status_id',
        'checking_comment',
        'checking_by',
        'checking_date',
        'approve_comment',
        'approve_by',
        'approve_date',
        'assign_to',
        'assign_date',
        'revoke_reason',
        'revoke_time',
        'revoke_auto_date',
        'accept',
        'ordering',
        'state',
        'checked_out',
        'checked_out_time',
        'created_by',
        'modified_by',
        'test_item_status',

    ];

    public function tis_standard(){
        return $this->belongsTo(RosStandardTisi::class, 'tis_number', 'tis_number');
    }

    public function user_created(){
        return $this->belongsTo(RosUsers::class, 'created_by', 'id');
    }

}
