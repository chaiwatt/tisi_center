<?php

namespace App\Models\Certify\Applicant;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = "app_certi_lab_report_assessments";
    protected $primaryKey = 'id';
    protected $fillable = [
        'app_certi_assessment_id', 'app_certi_lab_id', 'meet_date', 'file', 'status', 'desc', 'save_date', 'created_by','file_loa','file_loa_client_name',
        'start_date','end_date','start_date', 'status_alert','ability_confirm'
    ];

    protected $dates = [
        'meet_date',
        'save_date',
    ];

    public function assessment() {
        return $this->belongsTo(Assessment::class, 'app_certi_assessment_id');
    }

    public function applicant() {
        return $this->belongsTo(CertiLab::class, 'app_certi_lab_id');
    }

    public function files() {
        return $this->hasMany(ReportFile::class, 'app_certi_report_assessment_id');
    }
}
