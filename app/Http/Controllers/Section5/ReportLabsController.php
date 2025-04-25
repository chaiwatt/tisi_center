<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Section5\ApplicationLab;
use App\Models\Section5\ApplicationLabScope;
use App\Models\Bsection5\Workgroup;
use App\Models\Section5\ApplicationLabStaff;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Style\Alignment;
// use PhpOffice\PhpSpreadsheet\Style\Fill;
// use PhpOffice\PhpSpreadsheet\Style\Border;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportLabsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('report-labs','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('section5.report-labs.index', compact('filter'));
        }
        abort(403);
    }

    public function query($request)
    {
        $model = str_slug('report-labs','-');

        $filter_search = $request->input('filter_search');
        $filter_audit_type = $request->input('filter_audit_type');
        $filter_status = $request->input('filter_status');

        $filter_start_date = $request->input('filter_start_date');
        $filter_end_date   = $request->input('filter_end_date');

        $filter_audit_start_date = $request->input('filter_audit_start_date');
        $filter_audit_end_date   = $request->input('filter_audit_end_date');

        $filter_board_meeting_start_date = $request->input('filter_board_meeting_start_date');
        $filter_board_meeting_end_date   = $request->input('filter_board_meeting_end_date');

        $filter_announcement_start_date = $request->input('filter_announcement_start_date');
        $filter_announcement_end_date   = $request->input('filter_announcement_end_date');

        $filter_tis_number = $request->input('filter_tis_number');
        $filter_staff    = $request->input('filter_staff');

        //ผู้ใช้งาน
        $user = auth()->user();

        $query = ApplicationLab::query()->with([
                                            'app_scope_standard.tis_standards',
                                            'app_staff.user_staff',
                                            'app_audit',
                                            'board_approve',
                                            'app_gazette_details.app_gazette'
                                        ])
                                        ->when( $filter_search , function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            if( strpos( $search_full , 'LAB-' ) !== false){
                                                return $query->where('application_no',  'LIKE', "%$search_full%");
                                            }else{

                                                return  $query->where(function ($query2) use($search_full) {

                                                    $ids = ApplicationLabScope::where(function ($query) use($search_full) {
                                                                            $query->whereHas('tis_standards', function($query) use ($search_full){
                                                                                        $query->where(function ($query) use($search_full) {
                                                                                                $query->where(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")->Orwhere(DB::Raw("REPLACE(tb3_Tisno,' ','')"),  'LIKE', "%$search_full%");
                                                                                            });
                                                                                    }); 
                                                                        })
                                                                        ->select('application_lab_id');

                                                    $query2->Where(DB::raw("REPLACE(applicant_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(applicant_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                            ->OrwhereHas('app_staff.user_staff', function($query) use ($search_full){
                                                                $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                            })
                                                            ->OrwhereIn('id', $ids)
                                                            ->OrWhere('application_no',  'LIKE', "%$search_full%");
                                                });
                                            }
                                        })
                                        ->when($filter_audit_type, function ($query, $filter_audit_type){
                                            return $query->where('audit_type', $filter_audit_type);
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return $query->where('application_status', $filter_status);
                                        })
                                        ->when($filter_start_date, function ($query, $filter_start_date){
                                            $filter_start_date = HP::convertDate($filter_start_date, true);
                                            return $query->where('application_date', '>=', $filter_start_date);
                                        })
                                        ->when($filter_end_date, function ($query, $filter_end_date){
                                            $filter_end_date = HP::convertDate($filter_end_date, true);
                                            return $query->where('application_date', '<=', $filter_end_date);
                                        })
                                        ->when($filter_audit_start_date, function ($query, $filter_audit_start_date){
                                            $filter_audit_start_date = HP::convertDate($filter_audit_start_date, true);
                                            return  $query->whereHas('app_audit', function($query) use ($filter_audit_start_date){
                                                $query->whereRaw("JSON_EXTRACT(audit_date, '$[0]') >= '$filter_audit_start_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[1]') >= '$filter_audit_start_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[2]') >= '$filter_audit_start_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[3]') >= '$filter_audit_start_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[4]') >= '$filter_audit_start_date' ");
                                            });
                                        })
                                        ->when($filter_audit_end_date, function ($query, $filter_audit_end_date){
                                            $filter_audit_end_date = HP::convertDate($filter_audit_end_date, true);
                                            return  $query->whereHas('app_audit', function($query) use ($filter_audit_end_date){
                                                $query->whereRaw("JSON_EXTRACT(audit_date, '$[0]') <= '$filter_audit_end_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[1]') <= '$filter_audit_end_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[2]') <= '$filter_audit_end_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[3]') <= '$filter_audit_end_date' ");
                                                $query->orWhereRaw("JSON_EXTRACT(audit_date, '$[4]') <= '$filter_audit_end_date' ");
                                            });
                                        })
                                        ->when($filter_board_meeting_start_date, function ($query, $filter_board_meeting_start_date){
                                            $filter_board_meeting_start_date = HP::convertDate($filter_board_meeting_start_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_board_meeting_start_date){
                                                                $query->where('board_meeting_date', '>=', $filter_board_meeting_start_date);
                                                            });
                                        })
                                        ->when($filter_board_meeting_end_date, function ($query, $filter_board_meeting_end_date){
                                            $filter_board_meeting_end_date = HP::convertDate($filter_board_meeting_end_date, true);
                                            return  $query->whereHas('board_approve', function($query) use ($filter_board_meeting_end_date){
                                                                $query->where('board_meeting_date', '<=', $filter_board_meeting_end_date);
                                                            });
                                        })
                                        ->when($filter_announcement_start_date, function ($query, $filter_announcement_start_date){
                                            $filter_announcement_start_date = HP::convertDate($filter_announcement_start_date, true);
                                            return  $query->whereHas('app_gazette_details.app_gazette', function($query) use ($filter_announcement_start_date){
                                                                $query->where('announcement_date', '>=', $filter_announcement_start_date);
                                                            });
                                        })
                                        ->when($filter_announcement_end_date, function ($query, $filter_announcement_end_date){
                                            $filter_announcement_end_date = HP::convertDate($filter_announcement_end_date, true);
                                            return  $query->whereHas('app_gazette_details.app_gazette', function($query) use ($filter_announcement_end_date){
                                                                $query->where('announcement_date', '<=', $filter_announcement_end_date);
                                                            });
                                        })
                                        ->when($filter_tis_number, function ($query, $filter_tis_number){
                                            $query->whereHas('app_scope_standard', function($query) use ($filter_tis_number){
                                                $query->where('tis_tisno', $filter_tis_number);
                                            });
                                        })
                                        ->when($filter_staff, function ($query, $filter_staff){
                                            $query->whereHas('app_staff', function($query) use ($filter_staff){
                                                $query->where('staff_id', $filter_staff);
                                            });
                                        })
                                        ->when(!$user->isAdmin(), function($query) use ($user) {//ถ้าไม่ใช่ admin

                                            //id ตาราง basic_branch_groups สาขาผลิตภัณฑ์ตามเจ้าหน้าที่ที่รับผิดชอบ
                                            $tis_ids = Workgroup::UserTisIds($user->getKey());
                                      
                                            $id_query = ApplicationLabScope::whereIn('tis_id', $tis_ids)->select('application_lab_id');
                                            $query->whereIn('id', $id_query);

                                        })
                                        ->when( (!auth()->user()->can('view_all-'.$model)) , function($query) use ($user) {//ถ้าไม่ได้ดูได้ทั้งหมด จะแสดงตามที่ได้รับมอบหมาย
                                            $id_query = ApplicationLabStaff::where('staff_id', $user->getKey())->select('application_lab_id');
                                            $query->whereIn('id', $id_query);
                                        })
                                        ->where(function($query){
                                            $query->whereNotIn('application_status', [0]);
                                        });

        return $query;
    }

    //รายการข้อมูล
    public function data_list(Request $request)
    {
        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-app_no="'. $item->application_no .'" value="'. $item->id .'">';
                            })
                            ->addColumn('audit_type', function ($item) {
                                $audit_type_arr = [ '1' => '17025', '2' => 'ภาคผนวก ก'];
                                return array_key_exists( $item->audit_type,  $audit_type_arr )?$audit_type_arr[$item->audit_type]:'-';
                            })
                            ->addColumn('application_no', function ($item) {
                                return $item->application_no.'<div>('.(!empty($item->application_date)?HP::DateThai($item->application_date):'-').')</div>';
                            })
                            ->addColumn('applicant_name', function ($item) {
                                return '<div>'.(!empty($item->lab_name)?$item->lab_name:'-').'</div>'.(!empty($item->applicant_name)?'('.$item->applicant_name.')':'-');
                            })
                            ->addColumn('applicant_taxid', function ($item) {
                                return !empty($item->applicant_taxid)?$item->applicant_taxid:'-';
                            })
                            ->addColumn('standards', function ($item) {
                                return (!empty($item->ScopeStandard)?$item->ScopeStandard:'-');
                            })
                            ->addColumn('audit_date', function ($item) {
                                return !empty($item->app_audit->AuditDateShow)?$item->app_audit->AuditDateShow:'-';
                            })
                            ->addColumn('board_meeting_date', function ($item) {
                                return !empty($item->board_approve->board_meeting_date)?HP::DateThai($item->board_approve->board_meeting_date):'-';
                            })
                            ->addColumn('announcement_date', function ($item) {
                                return !empty($item->app_gazette_details->app_gazette->announcement_date)?HP::DateThai($item->app_gazette_details->app_gazette->announcement_date):'-';
                            })
                            ->addColumn('status_application', function ($item) {
                                if( !empty($item->delete_state) ){
                                    return (!empty($item->StatusFullTitle)?'<div class="text-danger">'.$item->StatusFullTitle.'<div>':'-').'<div><em>'.(!empty($item->delete_at)?HP::DateThai($item->delete_at):null).'</em><div>';
                                }else{
                                    return !empty($item->StatusFullTitle)?$item->StatusFullTitle:'ฉบับร่าง';
                                }
                            })
                            ->addColumn('assign_by', function ($item) {
                                return (!empty($item->assign_by)?$item->AssignStaff:'รอดำเนินการ').(!empty($item->assign_date)?'<br>'.HP::DateThaiFull($item->assign_date):null);
                            })

                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'assign_by', 'applicant_name','application_no','standards','status_application'])
                            ->make(true);
    }


    public function export_excel(Request $request)
    {        
        $query =  $this->query($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getRowDimension(3)->setRowHeight(3,6);

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานคำขอรับการแต่งตั้งผู้ตรวจสอบ LAB');
        $sheet->mergeCells('A1:R1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);
        
        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.');
        $sheet->mergeCells('A2:R2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getFont()->setSize(13);

        //หัวตาราง
        $sheet->setCellValue('A3', 'ลำดับ');
        $sheet->setCellValue('B3', 'เลขที่คำขอ');
        $sheet->setCellValue('C3', 'วันที่ยื่นคำขอ');
        $sheet->setCellValue('D3', 'สถานะคำขอ');
        $sheet->setCellValue('E3', 'ชื่อหน่วยงาน/บริษัท');
        $sheet->setCellValue('F3', 'ชื่อห้องปฏิบัติการ');
        $sheet->setCellValue('G3', 'มอก.');
        $sheet->setCellValue('H3', 'ชื่อ มอก.');
        $sheet->setCellValue('I3', 'การประเมิน');
        $sheet->setCellValue('J3', 'วันที่ตรวจประเมิน');
        $sheet->setCellValue('K3', 'วันที่เข้าประชุม');
        $sheet->setCellValue('L3', 'วันที่ประกาศราชกิจจา');
        $sheet->setCellValue('M3', 'ประกาศฉบับที่/ปีที่');
        $sheet->setCellValue('N3', 'เจ้าหน้าที่ผู้รับผิดชอบ');
        $sheet->setCellValue('O3', 'ชื่อผู้ประสานงาน');
        $sheet->setCellValue('P3', 'เบอร์ติดต่อ');
        $sheet->setCellValue('Q3', 'อีเมล');
        $sheet->setCellValue('R3', 'ที่อยู่');

        $styleArray_header = [
            'font' => [ // จัดตัวอักษร
                'bold' => true, // กำหนดเป็นตัวหนา
            ],
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหมด
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [ // กำหนดสีพื้นหลัง
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, // รูปแบบพื้นหลัง
                'rotation' => 90, // กำหนดองศาทิศทางการไล่เฉด
                'startColor' => [ // สีที่ 1
                    'argb' => 'FFA0A0A0',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว 
                ],
                'endColor' => [ // สีที่ 2
                    'argb' => 'FFFFFFFF',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว FFFFFF
                ],
            ],
        ];

        $sheet->getStyle('A3:R3')->applyFromArray($styleArray_header);
        $audit_type_arr = [ '1' => '17025', '2' => 'ภาคผนวก ก'];
        $row = 3;

        foreach($query as $key =>$item){

            $row++;
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, (!empty($item->application_no)?$item->application_no:null));
            $sheet->setCellValue('C'.$row, (!empty($item->application_date)?$item->application_date:null));
            $sheet->setCellValue('D'.$row, (!empty($item->StatusFullTitle)?$item->StatusFullTitle:'ฉบับร่าง'));
            $sheet->setCellValue('E'.$row, (!empty($item->applicant_name)?$item->applicant_name:null));
            $sheet->setCellValue('F'.$row, (!empty($item->lab_name)?$item->lab_name:null));
            $sheet->setCellValue('G'.$row, (!empty($item->ScopeStandard)?$item->ScopeStandard:null));
            $sheet->setCellValue('H'.$row, (!empty($item->ScopeStandardTisName)?$item->ScopeStandardTisName:null));
            $sheet->setCellValue('I'.$row, array_key_exists( $item->audit_type,  $audit_type_arr )?$audit_type_arr[$item->audit_type]:'-');
                                            $audit_dates = !empty($item->app_audit->audit_date)?json_decode($item->app_audit->audit_date):[];
                                            $audit_dates = !empty($audit_dates)?implode(',', $audit_dates):null;
            $sheet->setCellValue('J'.$row, (!empty($audit_dates)?$audit_dates:'-'));
            $sheet->setCellValue('K'.$row, (!empty($item->board_approve->board_meeting_date)?$item->board_approve->board_meeting_date:null));
            $sheet->setCellValue('L'.$row, (!empty($item->app_gazette_details->app_gazette->announcement_date)?$item->app_gazette_details->app_gazette->announcement_date:'-'));
            $sheet->setCellValue('M'.$row, (!empty($item->app_gazette_details->app_gazette->issue) && !empty($item->app_gazette_details->app_gazette->year)?$item->app_gazette_details->app_gazette->issue.'/'.$item->app_gazette_details->app_gazette->year:'-'));
            $sheet->setCellValue('N'.$row, (!empty($item->AssignStaff)?$item->AssignStaff:'-'));
            $sheet->setCellValue('O'.$row, (!empty($item->co_name)?$item->co_name:'-'));
            $sheet->setCellValue('P'.$row, (!empty($item->co_mobile)?$item->co_mobile:'-'));
            $sheet->setCellValue('Q'.$row, (!empty($item->co_email)?$item->co_email:'-'));
            $sheet->setCellValue('R'.$row, (!empty($item->LabDataAdress)?$item->LabDataAdress:null));

        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);
        $sheet->getColumnDimension('P')->setAutoSize(true);
        $sheet->getColumnDimension('Q')->setAutoSize(true);
        $sheet->getColumnDimension('R')->setAutoSize(true);

        $filename = 'รายงานคำขอรับการแต่งตั้งเป็นผู้ตรวจสอบ_LAB_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }


}
