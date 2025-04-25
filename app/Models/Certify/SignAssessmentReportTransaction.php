<?php

namespace App\Models\Certify;

use App\Certify\CbReportInfo;
use App\Certify\IbReportInfo;
use App\Models\Besurv\Signer;
use App\Certify\CbReportTwoInfo;
use App\Certify\IbReportTwoInfo;
use Kyslik\ColumnSortable\Sortable;
use App\Models\Certify\LabReportInfo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate\LabReportTwoInfo;

class SignAssessmentReportTransaction extends Model
{
    use Sortable;
    protected $table = 'sign_assessment_report_transactions';
    protected $primaryKey = 'id';
    protected $fillable = [
        'report_info_id',
        'signer_id',
        'app_id',
        'certificate_type',
        'signer_name',
        'signer_position',
        'signer_order',
        'file_path',
        'linesapce',
        'view_url',
        'approval',
        'report_type'
    ];


    public function labReportInfo()
    {
        return $this->belongsTo(LabReportInfo::class, 'report_info_id', 'id');
    }

    public function labReportTwoiInfo()
    {
        return $this->belongsTo(LabReportTwoInfo::class, 'report_info_id', 'id');
    }

    public function cbReportInfo()
    {
        return $this->belongsTo(CbReportInfo::class, 'report_info_id', 'id');
    }

    public function ibReportInfo()
    {
        return $this->belongsTo(IbReportInfo::class, 'report_info_id', 'id');
    }

    public function cbReportTwoInfo()
    {
        return $this->belongsTo(CbReportTwoInfo::class, 'report_info_id', 'id');
    }

    public function ibReportTwoInfo()
    {
        return $this->belongsTo(IbReportTwoInfo::class, 'report_info_id', 'id');
    }


    public function signer(){
        return $this->belongsTo(Signer::class, 'signer_id', 'id');
    }
}
