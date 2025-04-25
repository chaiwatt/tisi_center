<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use HP_Law;

use App\Models\Law\Cases\LawCasesForm;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Html;

use stdClass;

class LawReportSummaryLawCasesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function query(Request $request)
    {
        $filter_condition_search    = $request->input('filter_condition_search');
        $filter_search              = $request->input('filter_search');
        $filter_status              = $request->input('filter_status');
        $filter_standard            = $request->input('filter_standard');
        $filter_license_number      = $request->input('filter_license_number');
        $filter_section             = $request->input('filter_section');
        $filter_impounds_start_date = $request->input('filter_impounds_start_date');
        $filter_impounds_end_date   = $request->input('filter_impounds_end_date');
        $filter_assign_start_date   = $request->input('filter_assign_start_date');
        $filter_assign_end_date     = $request->input('filter_assign_end_date');
        $filter_type_department     = $request->input('filter_type_department');
        $filter_sub_department_id   = $request->input('filter_sub_department_id');
        $filter_law_deperment_id    = $request->input('filter_law_deperment_id');
        $filter_created_start_date  = $request->input('filter_created_start_date');
        $filter_created_end_date    = $request->input('filter_created_end_date');
        $filter_law_arrest          = $request->input('filter_law_arrest');
        $filter_payments_status     = $request->input('filter_payments_status');
        $filter_amount_min          = $request->input('filter_amount_min');
        $filter_amount_max          = $request->input('filter_amount_max');

        $query = LawCasesForm::query()
                                ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                    $search_full = str_replace(' ', '', $filter_search);

                                    switch ( $filter_condition_search ):
                                        case "1":
                                            return $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%");
                                            break;
                                        case "2":
                                            return $query->where( function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");

                                                            });
                                                break;
                                        case "3":
                                            return $query->Where(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                            break;
                                        case "4":
                                            return $query->whereHas('tis', function($query) use ($search_full){
                                                            $query->Where(DB::raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%");
                                                        });
                                            break;
                                        default:
                                            return $query->where( function($query) use ($search_full){
                                                            $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrwhereHas('tis', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                        });
                                            break;
                                    endswitch;
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( is_array($filter_status) ){
                                        return $query->whereIn('status', $filter_status);
                                    }
                                })
                                ->when($filter_standard, function ($query, $filter_standard){
                                    return $query->where(function($query) use ($filter_standard){
                                                        return $query->where('tis_id', $filter_standard);
                                                    });
                                })
                                ->when($filter_license_number, function ($query, $filter_license_number){
                                    return $query->where( function($query) use ($filter_license_number){
                                                        return $query->where('tb4_tisilicense_id', $filter_license_number);
                                                    });
                                })
                                ->when($filter_section, function ($query, $filter_section){
                                    return $query->where( function($query) use ($filter_section){
                                                        if( is_array($filter_section) ){
                                                            return $query->whereJsonContains('law_basic_section_id', $filter_section);
                                                        }
                                                    });
                                })
                                ->when($filter_impounds_start_date, function ($query, $filter_impounds_start_date){
                                    $filter_impounds_start_date = HP::convertDate($filter_impounds_start_date, true);
                                    return  $query->whereHas('cases_impound', function($query) use ($filter_impounds_start_date){
                                                        $query->where('date_impound', '>=', $filter_impounds_start_date);
                                                    });
                                })
                                ->when($filter_impounds_end_date, function ($query, $filter_impounds_end_date){
                                    $filter_impounds_end_date = HP::convertDate($filter_impounds_end_date, true);
                                    return  $query->whereHas('cases_impound', function($query) use ($filter_impounds_end_date){
                                                        $query->where('date_impound', '<=', $filter_impounds_end_date);
                                                    });
                                })
                                ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                    $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                    return  $query->whereHas('law_cases_assign_to', function($query) use ($filter_assign_start_date){
                                                        $query->where('created_at', '>=', $filter_assign_start_date);
                                                    });
                                })
                                ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                    $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                    return  $query->whereHas('law_cases_assign_to', function($query) use ($filter_assign_end_date){
                                                        $query->where('created_at', '<=', $filter_assign_end_date);
                                                    });
                                })
                                ->when($filter_type_department, function ($query, $filter_type_department){
                                    $query->where('owner_depart_type', $filter_type_department);
                                })
                                ->when($filter_sub_department_id, function ($query, $filter_sub_department_id){
                                    $query->where('owner_sub_department_id', $filter_sub_department_id);
                                })
                                ->when($filter_law_deperment_id, function ($query, $filter_law_deperment_id){
                                    $query->where('owner_basic_department_id', $filter_law_deperment_id);
                                })
                                ->when($filter_created_start_date, function ($query, $filter_created_start_date){
                                    $filter_created_start_date = HP::convertDate($filter_created_start_date, true);
                                    return  $query->where('created_at', '>=', $filter_created_start_date);
                                })
                                ->when($filter_created_end_date, function ($query, $filter_created_end_date){
                                    $filter_created_end_date = HP::convertDate($filter_created_end_date, true);
                                    return $query->where('created_at', '<=', $filter_created_end_date);
                                })
                                ->when($filter_law_arrest, function ($query, $filter_law_arrest){
                                    $query->where('law_basic_arrest_id', $filter_law_arrest);
                                })
                                ->when($filter_payments_status, function ($query, $filter_payments_status){
                                    if( $filter_payments_status == 99 ){
                                        return $query->doesntHave('law_cases_payments_to');
                                    }else{
                                        return $query->whereHas('law_cases_payments_to', function ($query)  use ($filter_payments_status){
                                                            $query->Where('paid_status',  $filter_payments_status )->whereNull('cancel_status');
                                                        });
                                    }
                                })
                                ->when($filter_amount_min, function ($query, $filter_amount_min) use($filter_amount_max){

                                    if( empty($filter_amount_max) ){
                                        return $query->whereHas('cases_impound', function($query) use($filter_amount_min) {
                                                            $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_min );
                                                            $query->select(DB::raw('SUM(total_value) as balance'))->havingRaw('balance = '.$search_full);
                                                        });
                                    }else{
                                        return $query->whereHas('cases_impound', function($query) use($filter_amount_min) {
                                                            $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_min );
                                                            $query->select(DB::raw('SUM(total_value) as balance'))->havingRaw('balance >= '.$search_full);
                                                        });
                                    }
                                })
                                ->when($filter_amount_max, function ($query, $filter_amount_max){
                                    return $query->whereHas('cases_impound', function($query) use($filter_amount_max) {
                                                        $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_max );
                                                        $query->select(DB::raw('SUM(total_value) as balance'))->havingRaw('balance <= '.$search_full);
                                                    });
                                })
                                ->where(function($query){
                                    $query->whereNotIn('status',[0,99]);
                                });

        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number)?$item->case_number:'<span class="text-muted">รอผลพิจารณา</span>';
                            })
                            ->addColumn('owner_name', function ($item) {
                                return ( !empty($item->offend_name) ? $item->offend_name:' '  ).'<div>( '.( !empty($item->offend_taxid) ? $item->offend_taxid:' '  ).' )</div>';
                            })
                            ->addColumn('tb3_tisno', function ($item) {
                                return !empty($item->StandardNo) ? $item->StandardNo:' ';
                            })
                            ->addColumn('tb3_tisname', function ($item) {
                                return !empty($item->StandardName) ? $item->StandardName:' ';
                            })
                            ->addColumn('offend_license_number', function ($item) {
                                return !empty($item->LicenseNumber) ? $item->LicenseNumber:' ';
                            })
                            ->addColumn('assign_name', function ($item) {
                                return !empty($item->user_assign_to->FullName) ? $item->user_assign_to->FullName : ' ';
                            })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer_to->FullName) ? $item->user_lawyer_to->FullName: ' ';
                            })
                            ->addColumn('owner_department_name', function ($item) {
                                $type = ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'];
                                if($item->owner_depart_type == 1 ){
                                    return (!empty($item->sub_deparment)?$item->sub_deparment->DepartmentNameShort .' ('.$item->sub_deparment->sub_depart_shortname.')':' ').( array_key_exists( $item->owner_depart_type, $type )?'<div>'.($type[ $item->owner_depart_type ]).'</div>':null );
                                }else if($item->owner_depart_type == 2 ){
                                   return (!empty($item->law_deparment)?$item->law_deparment->title_short:' ').( array_key_exists( $item->owner_depart_type, $type )?'<div>'.($type[ $item->owner_depart_type ]).'</div>':null );          
                                }else{
                                    return '';
                                }
                            })
                            ->addColumn('law_section', function ($item) {
                                return !empty($item->section_list)?($item->SectionListName):' ';
                            })
                            ->addColumn('cases_impound_product', function ($item) {
                                $cases_impound = !empty( $item->cases_impound)? $item->cases_impound->sum('AmountProduct'):0;
                                return number_format($cases_impound);
                            })
                            ->addColumn('cases_impound_amount', function ($item) {
                                $cases_impound = !empty( $item->cases_impound)? $item->cases_impound->sum('total_value'):0;
                                return number_format($cases_impound);
                            })
                            ->addColumn('penalty', function ($item) {
                                return !empty($item->law_cases_payments_to->law_cases_payments_detail_to->amount) ?   number_format($item->law_cases_payments_to->law_cases_payments_detail_to->amount,2) : '0.00'; 
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StatusText)?$item->StatusText:' ';
                            })
                            ->addColumn('assign_at', function ($item) {
                                $created_at = !is_null($item->law_cases_assign_to)? $item->law_cases_assign_to->max('created_at'):null;
                                return  !empty($created_at)?HP::DateThai($created_at):' ';
                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->created_at)?HP::DateThai($item->created_at):' ';
                            })
                            ->addColumn('paid_status', function ($item) { 
                                $law_cases_payments = $item->law_cases_payments_to;
                                if(!empty($law_cases_payments->paid_status) && in_array($law_cases_payments->paid_status,[2])  ){
                                    $paid_date =  !empty($law_cases_payments->paid_date) ? '<div>'.HP::DateThai($law_cases_payments->paid_date).'</div>'  : null;
                                    return '<span class="text-success">ชำระเงินแล้ว</span>'.$paid_date;
                                }else if(!empty($law_cases_payments->paid_status)  && in_array($law_cases_payments->paid_status,[1])  ){
                                    return '<span class="text-danger">ยังไม่ชำระเงิน</span>';
                                }else{
                                    return '<span class="text-muted">ยังไม่สร้าง Pay-in</span>';
                                }
                            })  
                            ->addColumn('law_arrest', function ($item) {
                                return !empty($item->law_basic_arrest_to)?($item->law_basic_arrest_to->title):' ';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['owner_name','owner_department_name', 'paid_status','case_number'])
                            ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-report-summary-law-cases','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-law-cases",  "name" => 'รายงานสรุปคดี' ],
            ];

            return view('laws.report.summary-law-cases.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('law-report-summary-law-cases','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = str_slug('law-report-summary-law-cases','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('law-report-summary-law-cases','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-law-cases",  "name" => 'รายงานสรุปคดี' ],
            ];

            return view('laws.report.summary-law-cases.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('law-report-summary-law-cases','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-law-cases",  "name" => 'รายงานสรุปคดี' ],
            ];

            return view('laws.report.summary-law-cases.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function export_excel(Request $request)
    {

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        //ข้อมูลคดี
        $query       = $this->query($request);
        $query       = $query->get();
                                    
        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานสรุปคดี');
        $sheet->setCellValue('A2',  'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->setCellValue('A3', 'ผู้ส่งออกข้อมูล : '.auth()->user()->FullName);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $filter = $request->all();

        $styleArray_header = [
            'font' => [ // จัดตัวอักษร
                'bold' => true, // กำหนดเป็นตัวหนา
            ],
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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

        //หัวตาราง
        $start_column   = 'A';
        $start_coltemp  = '';

        $product_number = null; 
        $amount_number  = null; 
        $penalty_number = null; 
        
        if( !isset($filter['column_select_row']) || ($filter['column_select_row'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'ลำดับ');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_case_number']) || ($filter['column_select_case_number'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'เลขคดี');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_owner_name']) || ($filter['column_select_owner_name'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'ผู้ประกอบการ');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
            $sheet->setCellValue($start_column.'5', 'เลขประจำตัวผู้เสียภาษี');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_tb3_tisno']) || ($filter['column_select_tb3_tisno'] == 1) ){
            $sheet->setCellValue($start_column.'5', ' เลขที่ มอก.');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_tb3_tis']) || ($filter['column_select_tb3_tis'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'ชื่อ มอก.');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_license_number']) || ($filter['column_select_license_number'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'เลขที่ใบอนุญาต');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_lawyer_by']) || ($filter['column_select_lawyer_by'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'นิติกร');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_department']) || ($filter['column_select_department'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'กลุ่มงานแจ้งคดี');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_section']) || ($filter['column_select_section'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'มาตราความผิด');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_product']) || ($filter['column_select_product'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'จำนวนของกลาง');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp  = $start_column;
            $product_number = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_amount']) || ($filter['column_select_amount'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'มูลค่าของกลาง');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $amount_number = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_penalty']) || ($filter['column_select_penalty'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'ค่าปรับ ');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp  = $start_column;
            $penalty_number = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_created_at']) || ($filter['column_select_created_at'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'วันที่แจ้ง');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_status']) || ($filter['column_select_status'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'สถานะคดี');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_payment']) || ($filter['column_select_payment'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'สถานะชำระค่าปรับ');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;

            $sheet->setCellValue($start_column.'5', 'วันที่ชำระ');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_law_arrest']) || ($filter['column_select_law_arrest'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'การจับกุม');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        if( !isset($filter['column_select_assign_at']) || ($filter['column_select_assign_at'] == 1) ){
            $sheet->setCellValue($start_column.'5', 'วันที่มอบหมาย');
            $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
            $sheet->getColumnDimension($start_column)->setAutoSize(true);
            $start_coltemp = $start_column;
            $start_column++;
        }

        $sheet->mergeCells('A1:'.$start_coltemp.'1');
        $sheet->mergeCells('A2:'.$start_coltemp.'2');
        $sheet->mergeCells('A3:'.$start_coltemp.'3');
        $sheet->mergeCells('A4:'.$start_coltemp.'4');

        $row = 5;
        $i = 0;
        foreach($query as $item){
            $row++;
            $start_column_item = 'A';

            if( !isset($filter['column_select_row']) || ($filter['column_select_row'] == 1) ){
                $sheet->setCellValue( $start_column_item.$row,  ++$i );
                $start_column_item++;
            }


            if( !isset($filter['column_select_case_number']) || ($filter['column_select_case_number'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!is_null($item->case_number)?$item->case_number:(!empty($item->ref_no)?$item->ref_no:'N/A')));
                $start_column_item++;
            }

            if( !isset($filter['column_select_owner_name']) || ($filter['column_select_owner_name'] == 1) ){

                $sheet->setCellValue($start_column_item.$row, ( !empty($item->offend_name) ? $item->offend_name:'N/A' ));
                $start_column_item++;

                $sheet->setCellValue($start_column_item.$row, ( !empty($item->offend_taxid) ? $item->offend_taxid:'N/A'  ));
                    
                $sheet->getStyle($start_column_item.$row)
                        ->getNumberFormat()
                        ->setFormatCode(
                            \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                        );

                $start_column_item++;
            }
    
            if( !isset($filter['column_select_tb3_tisno']) || ($filter['column_select_tb3_tisno'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->StandardNo)?$item->StandardNo:'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_tb3_tis']) || ($filter['column_select_tb3_tis'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->StandardName)?$item->StandardName:'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_license_number']) || ($filter['column_select_license_number'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->offend_license_number)?$item->offend_license_number:'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_lawyer_by']) || ($filter['column_select_lawyer_by'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->user_lawyer_to->FullName)   ? $item->user_lawyer_to->FullName: 'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_department']) || ($filter['column_select_department'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->owner_department_name)?$item->owner_department_name:'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_section']) || ($filter['column_select_section'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->section_list)?($item->SectionListName):'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_product']) || ($filter['column_select_product'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty( $item->cases_impound)? $item->cases_impound->sum('AmountProduct'):0));
                    
                $sheet->getStyle($start_column_item.$row)
                        ->getNumberFormat()
                        ->setFormatCode(
                            \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                        );

                $start_column_item++;
            }
    
            if( !isset($filter['column_select_amount']) || ($filter['column_select_amount'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty( $item->cases_impound)? $item->cases_impound->sum('total_value'):0));
                    
                $sheet->getStyle($start_column_item.$row)
                        ->getNumberFormat()
                        ->setFormatCode(
                            \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                        );

                $start_column_item++;
            }
    
            if( !isset($filter['column_select_penalty']) || ($filter['column_select_penalty'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, ( !empty($item->law_cases_payments_to->law_cases_payments_detail_to->amount) ?   $item->law_cases_payments_to->law_cases_payments_detail_to->amount : '0.00' ));
                    
                $sheet->getStyle($start_column_item.$row)
                        ->getNumberFormat()
                        ->setFormatCode(
                            \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                        );

                $start_column_item++;
            }

            if( !isset($filter['column_select_created_at']) || ($filter['column_select_created_at'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->created_at)?HP::DateThai($item->created_at):'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_status']) || ($filter['column_select_status'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->StatusText)?$item->StatusText:'N/A'));
                $start_column_item++;
            }
    
            if( !isset($filter['column_select_payment']) || ($filter['column_select_payment'] == 1) ){

                $law_cases_payments = $item->law_cases_payments_to;
                if(!empty($law_cases_payments->paid_status)  && in_array($law_cases_payments->paid_status, [2] ) ){
                    $paymentStatus = 'ชำระเงินแล้ว';
                    $paymentDate   = !empty($law_cases_payments->paid_date) ? HP::DateThai($law_cases_payments->paid_date) : null;
                }else if(!empty($law_cases_payments->paid_status)  && in_array($law_cases_payments->paid_status, [1] ) ){
                    $paymentStatus = 'ยังไม่ชำระเงิน';
                    $paymentDate   = null;
                }else{
                    $paymentStatus = 'ยังไม่สร้าง Pay-in';
                    $paymentDate   = null;    
                }

                $sheet->setCellValue($start_column_item.$row, (!empty($paymentStatus)?($paymentStatus):'N/A'));
                $start_column_item++;

                $sheet->setCellValue($start_column_item.$row, (!empty($paymentDate)?($paymentDate):'N/A'));
                $start_column_item++;
            }

            if( !isset($filter['column_select_law_arrest']) || ($filter['column_select_law_arrest'] == 1) ){
                $sheet->setCellValue($start_column_item.$row, (!empty($item->law_basic_arrest_to)?($item->law_basic_arrest_to->title):'N/A'));
                $start_column_item++;
            }

            if( !isset($filter['column_select_assign_at']) || ($filter['column_select_assign_at'] == 1) ){
                $created_at = !is_null($item->law_cases_assign_to)? $item->law_cases_assign_to->max('created_at'):null;
                $sheet->setCellValue($start_column_item.$row, (!empty($created_at)?HP::DateThai($created_at):'N/A'));
                $start_column_item++;
            }   

        }

        if( !empty($product_number) ){
            $sheet->getStyle($product_number."6".":".$product_number.$row)->getNumberFormat()->setFormatCode("#,##0");
        }

        if( !empty($amount_number) ){
            $sheet->getStyle($amount_number."6".":".$amount_number.$row)->getNumberFormat()->setFormatCode("#,##0.00");
        }

        if( !empty($penalty_number) ){
            $sheet->getStyle($penalty_number."6".":".$penalty_number.$row)->getNumberFormat()->setFormatCode("#,##0.00");
        }

        $filename = 'รายงานสรุปคดี_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }
}
