<?php

namespace App\Certify;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;

class CbReportTemplate extends Model
{
    use Sortable;
    protected $table = "cb_report_templates";
    protected $primaryKey = 'id';
    protected $fillable = ['cb_assessment_id','template','report_type','status'  ];

    public function certiCBSaveAssessment(){
        return $this->belongsTo(CertiCBSaveAssessment::class, 'cb_assessment_id', 'id');
    }
}
