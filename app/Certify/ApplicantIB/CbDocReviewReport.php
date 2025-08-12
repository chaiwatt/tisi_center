<?php

namespace App\Certify\ApplicantIB;

use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCBSaveAssessment;

class CbDocReviewReport extends Model
{
    use Sortable;
    protected $table = "cb_doc_review_reports";
    protected $primaryKey = 'id';
    protected $fillable = ['app_certi_cb_id','template','report_type','status' ,'signers' ];

    public function certiCb(){
        return $this->belongsTo(CertiCb::class, 'app_certi_cb_id', 'id');
    }
}
