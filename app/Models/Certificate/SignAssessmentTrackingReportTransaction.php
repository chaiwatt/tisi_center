<?php

namespace App\Models\Certificate;

use App\Models\Besurv\Signer;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate\TrackingCbReportTwo;
use App\Models\Certificate\TrackingIbReportOne;
use App\Models\Certificate\TrackingIbReportTwo;
use App\Models\Certificate\TrackingLabReportTwo;
use App\Models\Certificate\TrackingLabReportInfo;

class SignAssessmentTrackingReportTransaction extends Model
{
    use Sortable;
    protected $table = "sign_assessment_tracking_report_transactions";
    protected $primaryKey = 'id';
    protected $fillable = [
        'tracking_report_info_id', 'signer_id','app_id',  'certificate_type', 'signer_name', 'signer_position','signer_order','file_path','linesapce','view_url','approval','report_type'
    ];

    public function trackingLabReportInfo(){
        return $this->belongsTo(TrackingLabReportInfo::class, 'tracking_report_info_id', 'id')
                    ->where('certificate_type', 2);
    }

    public function trackingLabReportOne(){
        return $this->belongsTo(TrackingLabReportOne::class, 'tracking_report_info_id', 'id');
    }

    
    public function trackingIbReportOne(){
        return $this->belongsTo(TrackingIbReportOne::class, 'tracking_report_info_id', 'id');
    }


    public function trackingCbReportOne(){
        return $this->belongsTo(TrackingCbReportOne::class, 'tracking_report_info_id', 'id');
    }

    public function trackingLabReportTwo(){
        return $this->belongsTo(TrackingLabReportTwo::class, 'tracking_report_info_id', 'id');
    }

    
    public function trackingIbReportTwo(){
        return $this->belongsTo(TrackingIbReportTwo::class, 'tracking_report_info_id', 'id');
    }


    public function trackingCbReportTwo(){
        return $this->belongsTo(TrackingCbReportTwo::class, 'tracking_report_info_id', 'id');
    }

    public function signer(){
        return $this->belongsTo(Signer::class, 'signer_id', 'id');
    }

}
