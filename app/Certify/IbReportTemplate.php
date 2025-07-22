<?php

namespace App\Certify;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantIB\CertiIBSaveAssessment;

class IbReportTemplate extends Model
{
      use Sortable;
    protected $table = "ib_report_templates";
    protected $primaryKey = 'id';
    protected $fillable = ['ib_assessment_id','template','report_type','status'  ];

      public function certiIBSaveAssessment(){
        return $this->belongsTo(CertiIBSaveAssessment::class, 'ib_assessment_id', 'id');
    }
}
