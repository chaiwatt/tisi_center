<?php

namespace App\Models\Certificate;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use App\CertificateExport;
use App\AttachFile;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;
class  TrackingAuditorsStep extends Model
{
    use Sortable;
    protected $table = "app_certi_tracking_auditors_step";
    protected $primaryKey = 'id';
    protected $fillable = ['title'];

 

}
