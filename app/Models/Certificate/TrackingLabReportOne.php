<?php

namespace App\Models\Certificate;

use App\AttachFile;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate\TrackingAssessment;

class TrackingLabReportOne extends Model
{
    use Sortable;
    protected $table = 'tracking_lab_report_ones';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tracking_assessment_id',
        'book_no_text',
        'audit_observation_text',
        'chk_impartiality_yes',
        'chk_impartiality_no',
        'impartiality_text',
        'chk_confidentiality_yes',
        'chk_confidentiality_no',
        'confidentiality_text',
        'chk_structure_yes',
        'chk_structure_no',
        'structure_text',
        'chk_res_general_yes',
        'chk_res_general_no',
        'res_general_text',
        'chk_res_personnel_yes',
        'chk_res_personnel_no',
        'res_personnel_text',
        'chk_res_facility_yes',
        'chk_res_facility_no',
        'res_facility_text',
        'chk_res_equipment_yes',
        'chk_res_equipment_no',
        'res_equipment_text',
        'chk_res_traceability_yes',
        'chk_res_traceability_no',
        'res_traceability_text',
        'chk_res_external_yes',
        'chk_res_external_no',
        'res_external_text',
        'chk_proc_review_yes',
        'chk_proc_review_no',
        'proc_review_text',
        'chk_proc_method_yes',
        'chk_proc_method_no',
        'proc_method_text',
        'chk_proc_sampling_yes',
        'chk_proc_sampling_no',
        'proc_sampling_text',
        'chk_proc_sample_handling_yes',
        'chk_proc_sample_handling_no',
        'proc_sample_handling_text',
        'chk_proc_tech_record_yes',
        'chk_proc_tech_record_no',
        'proc_tech_record_text',
        'chk_proc_uncertainty_yes',
        'chk_proc_uncertainty_no',
        'proc_uncertainty_text',
        'chk_proc_validity_yes',
        'chk_proc_validity_no',
        'proc_validity_text',
        'chk_proc_reporting_yes',
        'chk_proc_reporting_no',
        'proc_reporting_text',
        'chk_proc_complaint_yes',
        'chk_proc_complaint_no',
        'proc_complaint_text',
        'chk_proc_nonconformity_yes',
        'chk_proc_nonconformity_no',
        'proc_nonconformity_text',
        'chk_proc_data_control_yes',
        'chk_proc_data_control_no',
        'proc_data_control_text',
        'chk_res_selection_yes',
        'chk_res_selection_no',
        'res_selection_text',
        'chk_res_docsystem_yes',
        'chk_res_docsystem_no',
        'res_docsystem_text',
        'chk_res_doccontrol_yes',
        'chk_res_doccontrol_no',
        'res_doccontrol_text',
        'chk_res_recordcontrol_yes',
        'chk_res_recordcontrol_no',
        'res_recordcontrol_text',
        'chk_res_riskopportunity_yes',
        'chk_res_riskopportunity_no',
        'res_riskopportunity_text',
        'chk_res_improvement_yes',
        'chk_res_improvement_no',
        'res_improvement_text',
        'chk_res_corrective_yes',
        'chk_res_corrective_no',
        'res_corrective_text',
        'chk_res_audit_yes',
        'chk_res_audit_no',
        'res_audit_text',
        'chk_res_review_yes',
        'chk_res_review_no',
        'res_review_text',
        'report_display_certification_none',
        'report_display_certification_yes',
        'report_scope_certified_only',
        'report_scope_certified_all',
        'report_activities_not_certified_yes',
        'report_activities_not_certified_no',
        'report_accuracy_correct',
        'report_accuracy_incorrect',
        'report_accuracy_detail',
        'multisite_display_certification_none',
        'multisite_display_certification_yes',
        'multisite_scope_certified_only',
        'multisite_scope_certified_all',
        'multisite_activities_not_certified_yes',
        'multisite_activities_not_certified_no',
        'multisite_accuracy_correct',
        'multisite_accuracy_incorrect',
        'multisite_accuracy_detail',
        'certification_status_correct',
        'certification_status_incorrect',
        'certification_status_details',
        'other_certification_status_correct',
        'other_certification_status_incorrect',
        'other_certification_status_details',
        'lab_availability_yes',
        'lab_availability_no',
        'ilac_mra_display_no',
        'ilac_mra_display_yes',
        'ilac_mra_scope_no',
        'ilac_mra_scope_yes',
        'ilac_mra_disclosure_yes',
        'ilac_mra_disclosure_no',
        'ilac_mra_compliance_correct',
        'ilac_mra_compliance_incorrect',
        'ilac_mra_compliance_details',
        'other_ilac_mra_compliance_no',
        'other_ilac_mra_compliance_yes',
        'other_ilac_mra_compliance_details',
        'mra_compliance_correct',
        'mra_compliance_incorrect',
        'mra_compliance_details',
        'evidence_mra_compliance_details_1',
        'evidence_mra_compliance_details_2',
        'evidence_mra_compliance_details_3',
        'evidence_mra_compliance_details_4',
        'offer_agreement_yes',
        'offer_agreement_no',
        'offer_ilac_agreement_yes',
        'offer_ilac_agreement_no',
        'attached_files',
        'persons',
        'status'
    ];

    public function trackingAssessment()
    {
        return $this->belongsTo(TrackingAssessment::class,'tracking_assessment_id');
    }



/**
     * Relationship กับ AttachFile
     */
    public function attachments()
    {
        return $this->hasMany(AttachFile::class, 'ref_id', 'id')
                    ->where('ref_table', 'tracking_lab_report_ones')
                    ->where('section', '11111');
    }

    /**
     * Accessor สำหรับ compatibility (ถ้ายังต้องการ)
     */
    public function getAttachmentsAttribute()
    {
        // ใช้ relationship แทนการ query ใหม่
        return $this->attachments()->get();
    }
}


