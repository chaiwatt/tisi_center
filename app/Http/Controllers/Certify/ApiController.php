<?php

namespace App\Http\Controllers\Certify;

use App\CertificateExport;
use Illuminate\Http\Request;
use App\Models\Basic\District;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\BranchLabAdress;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CertiLabInfo;
use App\Models\Bcertify\LabCalScopeTransaction;
use App\Models\Bcertify\LabCalScopeUsageStatus;
use App\Models\Certify\Applicant\CertiLabPlace;
use App\Models\Bcertify\CalibrationBranchParam1;
use App\Models\Bcertify\CalibrationBranchParam2;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Bcertify\CalibrationBranchInstrument;
use App\Models\Certify\Applicant\CertiLabAttachMore;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class ApiController extends Controller
{
    public function apiTest(Request $request)
    {
        $branchs = DB::table('bcertify_test_branches')->select('*')->where('state',1)->get();
        return $branchs;
    }

    public function apiCalibrate(Request $request)
    {
        $branchs = DB::table('bcertify_calibration_branches')->select('*')->where('state',1)->get();
        // dd($branchs);
        return $branchs;
    }

    public function apiInstrumentGroup(Request $request)
    {
        $bcertify_calibration_branche_id = $request->bcertify_calibration_branche_id;

        $calibrationBranchInstrumentGroups = CalibrationBranchInstrumentGroup::where('bcertify_calibration_branche_id',$bcertify_calibration_branche_id)
                                        ->where('state',1)
                                        ->get();

        return $calibrationBranchInstrumentGroups;
    }

    public function apiInstrumentAndParameter(Request $request)
    {
        $calibration_branch_instrument_group_id = $request->calibration_branch_instrument_group_id;
       
        // ดึงข้อมูล instruments, parameter_one และ parameter_two
        $calibrationBranchInstruments = CalibrationBranchInstrument::where('calibration_branch_instrument_group_id', $calibration_branch_instrument_group_id)
                                            ->where('state', 1)
                                            ->get();

        $calibrationBranchParam1s = CalibrationBranchParam1::where('calibration_branch_instrument_group_id', $calibration_branch_instrument_group_id)
                                            ->where('state', 1)
                                            ->get();

        $calibrationBranchParam2s = CalibrationBranchParam2::where('calibration_branch_instrument_group_id', $calibration_branch_instrument_group_id)
                                            ->where('state', 1)
                                            ->get();

        // ส่งคืนข้อมูลในรูปแบบ JSON
        return response()->json([
            'instrument' => $calibrationBranchInstruments,
            'parameter_one' => $calibrationBranchParam1s,
            'parameter_two' => $calibrationBranchParam2s,
        ]);
    }
    public function apiGetScope(Request $request)
    {
        $certiLab = CertiLab::find($request->certi_lab_id);

        $labCalScopeTransactions = LabCalScopeTransaction::where('app_certi_lab_id', $certiLab->id)->where('group',$request->group)->with([
            'calibrationBranch',
            'calibrationBranchInstrumentGroup',
            'calibrationBranchInstrument',
            'calibrationBranchParam1',
            'calibrationBranchParam2'
        ])
        ->get();

        // dd($request->certi_lab_id,$labCalScopeTransactions);

        if (is_null($labCalScopeTransactions)) {
        $labCalScopeTransactions = [];
        }


        $branchLabAdresses = BranchLabAdress::where('app_certi_lab_id', $certiLab->id)->with([
                    'certiLab', 
                    'province', 
                    'amphur', 
                    'district'
                ])->get();

        return response()->json([
            // 'attach_path' => $this->attach_path,
            'certiLab' => $certiLab,
            'labCalScopeTransactions' => $labCalScopeTransactions,
            'branchLabAdresses' => $branchLabAdresses
        ]);
    }
    

    public function apiGetCertificated(Request $request)
    {
       
        $certificateExport = CertificateExport::find($request->certified_id);
 
        $certiLab = CertiLab::where('app_no',$certificateExport->request_number)->first();

        
        $district= District::where('DISTRICT_NAME',trim($certiLab->district))->where('PROVINCE_ID',$certiLab->province)->first();

        // Fetching files for section 4
        $file_sectionn4s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '4')
            ->get();

        // Fetching files for section 5
        $file_sectionn5s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '5')
            ->get();

        $file_sectionn71s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '71')
            ->get();

        $file_sectionn72s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '72')
            ->get();

            $file_sectionn8s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '8')
            ->get();

            $file_sectionn9s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '9')
            ->get();

            $file_sectionn10s = CertiLabAttachAll::where('app_certi_lab_id', $certiLab->id)
            ->where('file_section', '10')
            ->get();

            $file_others = CertiLabAttachMore::where('app_certi_lab_id', $certiLab->id)
            ->get();

            $certi_lab_place = CertiLabPlace::Where('app_certi_lab_id',$certiLab->id)->first();


            $labCalScopeUsageStatus = LabCalScopeUsageStatus::where('app_certi_lab_id', $certiLab->id)
            ->where('status', 2)
            ->first();


        
            $labCalScopeTransactions = $labCalScopeUsageStatus ? 
            $labCalScopeUsageStatus->transactions()->with([
            'calibrationBranch',
            'calibrationBranchInstrumentGroup',
            'calibrationBranchInstrument',
            'calibrationBranchParam1',
            'calibrationBranchParam2'
            ])->get() : [];

            if (is_null($labCalScopeTransactions)) {
            $labCalScopeTransactions = [];
            }


            $branchLabAdresses = BranchLabAdress::where('app_certi_lab_id', $certiLab->id)->with([
                                    'certiLab', 
                                    'province', 
                                    'amphur', 
                                    'district'
                                ])->get();

            $certiLabInfo =  CertiLabInfo::where('app_certi_lab_id',$certiLab->id)->first();
        return response()->json([
            // 'attach_path' => $this->attach_path,
            'certiLab' => $certiLab,
            'certificateExport' => $certificateExport,
            'address' => $this->GetAddreess($district->DISTRICT_ID),
            'file_sectionn4s' => $file_sectionn4s,
            'file_sectionn5s' => $file_sectionn5s,
            'file_sectionn71s' => $file_sectionn71s,
            'file_sectionn72s' => $file_sectionn72s,
            'file_sectionn8s' => $file_sectionn8s,
            'file_sectionn9s' => $file_sectionn9s,
            'file_sectionn10s' => $file_sectionn10s,
            'file_others' => $file_others,
            'certi_lab_place' => $certi_lab_place,
            'branchLabAdresses' => $branchLabAdresses,
            'labCalScopeTransactions' => $labCalScopeTransactions,
            'certiLabInfo' => $certiLabInfo,
        ]);
    }


}
