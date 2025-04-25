<?php

namespace App\Models\Section5;

use App\User;

use Carbon\Carbon;
use App\AttachFile;
use App\Models\Basic\Amphur;
use App\Models\Basic\Prefix;
use App\Models\Basic\District;
use App\Models\Basic\Province;
use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\InspectorsScope;

class InspectorsAgreement extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'section5_inspectors_agreements';

    protected $primaryKey = 'id';

    
    protected $fillable = [ 

        'application_id',
        'application_no',
        'inspectors_id',
        'inspectors_code',
        'inspectors_prefix',
        'inspectors_first_name',
        'inspectors_last_name',
        'inspectors_taxid',
        'agency_name',
        'agency_taxid',
        'agency_address',
        'agency_moo',
        'agency_soi',
        'agency_road',
        'agency_subdistrict',
        'agency_district',
        'agency_province',
        'agency_zipcode',
        'start_date',
        'end_date',
        'first_date',
        'agreement_status',
        'created_by',
        'updated_by',
        'description',
        'file_created_by',
        'file_updated_by', 
        'file_created_at',
        'file_updated_at'

    ];

    public function user_created(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user_updated(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getCreatedNameAttribute() {
        return @$this->user_created->reg_fname.' '.@$this->user_created->reg_lname;
    }

    public function getUpdatedNameAttribute() {
        return @$this->user_updated->reg_fname.' '.@$this->user_updated->reg_lname;
    }

    public function file_created(){
        return $this->belongsTo(User::class, 'file_created_by');
    }

    public function file_updated(){
        return $this->belongsTo(User::class, 'file_updated_by');
    }

    public function inspectors_scopes(){
        return $this->hasMany(InspectorsScope::class, 'application_id', 'application_id');
    }

    public function getInspectorsScopeFirstAttribute() {
        return @$this->inspectors_scopes->first();
    }

    public function getFileCreatedNameAttribute() {
        return @$this->file_created->reg_fname.' '.@$this->file_created->reg_lname;
    }

    public function getFileUpdatedNameAttribute() {
        return @$this->file_updated->reg_fname.' '.@$this->file_updated->reg_lname;
    }
    
    // วันที่ขึ้นทะเบียนครั้งแรก รูปแบบ 31/01/2565
    public function getFirstRegistrationDateAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->first_date, 'Y-m-d')){
            $date = Carbon::parse($this->first_date)->addYear(543)->format('d/m/Y');
        }
        return $date;
    }
    
    // วันที่ขึ้นทะเบียนครั้งแรก รูปแบบ 31 มกราคม 2565
    public function getFirstRegistrationDateFullAttribute(){
        $date = null;
        if(Carbon::hasFormat($this->first_date, 'Y-m-d')){
            $date = Carbon::parse($this->first_date)->addYear(543)->isoFormat('D MMMM YYYY');
        }
        return $date;
    }

    public function file_attach_document(){
        return $this->belongsTo(AttachFile::class, 'id', 'ref_id')->where('ref_table', ( new InspectorsAgreement )->getTable() );
    }

}
