<?php

namespace App\Models\Section5;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section5\LabsScope;
use App\Models\Section5\LabsHistory;
use App\Models\Basic\Province;
use App\Models\Basic\District;
use App\Models\Basic\Amphur;
use App\Models\Sso\User AS SSO_USER;

use App\CertificateExport;
use App\Models\Certify\Applicant\CertiLabExportMapreq;
use App\Models\Certify\Applicant\CertLabsFileAll;

class LabsCertify extends Model
{
    protected $table = 'section5_labs_certificates';

    protected $primaryKey = 'id';

        /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [ 
        'lab_id',
        'lab_code',
        'ref_lab_application_no',
        'certificate_no',
        'certificate_id',
        'certificate_start_date',
        'certificate_end_date',
        'accereditatio_no',
        'issued_by',
        'application_labs_cer_id'
        
    ];

    public function certify_export(){
        return $this->belongsTo(CertificateExport::class, 'certificate_id');
    }  

    public function getCheckCertifyRenewAttribute() {
   
        $Mapreq = $this->certify_export_mapreq;
        $certify_scope_max = $this->certify_scope->max('end_date');
        $data = null;
        foreach( $Mapreq as $item ){
            if( !empty( $item->cert_labs_file_all) &&  !empty($item->cert_labs_file_all->end_date) && ($item->cert_labs_file_all->end_date >  $certify_scope_max) ){
                $data =  $item->cert_labs_file_all;
                break;
            }
        }

        return $data;

    }

    public function certify_export_mapreq(){
        return $this->hasMany(CertiLabExportMapreq::class, 'certificate_exports_id', 'certificate_id')->with('cert_labs_file_all');
    }  

    public function certify_scope(){
        return $this->hasMany(LabsScope::class, 'ref_lab_application_no', 'ref_lab_application_no');
    }  


}
