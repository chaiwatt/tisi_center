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

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderCases;
use App\Models\Law\Offense\LawOffenderLicense;
use App\Models\Law\Offense\LawOffenderProduct;
use App\Models\Law\Offense\LawOffenderStandard;

use App\Models\Law\Basic\LawSection;
use Carbon\Carbon;


use stdClass;

class LawReportSummaryLawOffenderCasesController extends Controller
{
    private $permission;
    private $option_section;
    public function __construct()
    {
        $this->middleware('auth');
        $this->permission     = str_slug('law-report-summary-law-offender-cases','-');
        $this->option_section = LawSection::select(DB::Raw('number AS title, id'))->orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id')->toArray();
        set_time_limit(0);

    }

    public function query(Request $request)
    {
        $filter_condition_search    = $request->input('filter_condition_search');
        $filter_search              = $request->input('filter_search');

        $filter_standard            = $request->input('filter_standard');
        $filter_license_number      = $request->input('filter_license_number');
        $filter_section             = $request->input('filter_section');

        $filter_assign_start_date   = $request->input('filter_assign_start_date');
        $filter_assign_end_date     = $request->input('filter_assign_end_date');

        $filter_amount_min          = $request->input('filter_amount_min');
        $filter_amount_max          = $request->input('filter_amount_max');


        $query =  LawOffenderCases::query()
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%");

                                                break;
                                            case "2":
                                                return $query->whereHas('law_offender', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "3":
                                                return $query->whereHas('license_list', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "4":
                                                return $query->whereHas('standard_list.tis_data', function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                break;
                                            default:
                                                return $query->where( function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrwhereHas('law_offender', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                    ->OrWhere(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        })
                                                                        ->OrwhereHas('license_list', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        })
                                                                        ->OrwhereHas('standard_list.tis_data', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                ->OrWhere(DB::raw("REPLACE(tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        });
                                                            });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_standard, function ($query, $filter_standard){
                                        return $query->whereHas('standard_list', function($query) use ($filter_standard){
                                                            return $query->where('tis_id', $filter_standard);
                                                        });
                                    })
                                    ->when($filter_license_number, function ($query, $filter_license_number){
                                        return $query->whereHas('license_list', function($query) use ($filter_license_number){
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
                                    ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                        $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                        return  $query->where('assign_date', '>=', $filter_assign_start_date);
                                    })
                                    ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                        $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                        return  $query->where('assign_date', '<=', $filter_assign_end_date);
                                    })
                                    ->when($filter_amount_min, function ($query, $filter_amount_min) use($filter_amount_max){

                                        if( empty($filter_amount_max) ){
                                            return $query->whereHas('product_list', function($query) use($filter_amount_min) {
                                                                $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_min );
                                                                $query->select(DB::raw('SUM(total_price) as balance'))->havingRaw('balance = '.$search_full);
                                                            });
                                        }else{
                                            return $query->whereHas('product_list', function($query) use($filter_amount_min) {
                                                                $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_min );
                                                                $query->select(DB::raw('SUM(total_price) as balance'))->havingRaw('balance >= '.$search_full);
                                                            });
                                        }
                                    })
                                    ->when($filter_amount_max, function ($query, $filter_amount_max){
                                        return $query->whereHas('product_list', function($query) use($filter_amount_max) {
                                                            $search_full = preg_replace("/[^a-z\d]/i", '', $filter_amount_max );
                                                            $query->select(DB::raw('SUM(total_price) as balance'))->havingRaw('balance <= '.$search_full);
                                                        });
                                    })
                                    ->with([
                                        'law_offender' => function($query){
                                            $query->select('name', 'taxid','id');
                                        },
                                        'standard_list' => function($query){
                                            $query->select('law_offenders_cases_id', 'id', 'tb3_tisno', 'tis_id');
                                        },
                                        'license_list' => function($query){
                                            $query->select('law_offenders_cases_id', 'id', 'tb4_tisilicense_id', 'license_number');
                                        },
                                        'product_list' => function($query){
                                            $query->select( 'law_offenders_cases_id','id', 'detail', 'amount', 'unit');
                                        },
                                    ])
                                    ->whereNull('law_cases_id');
        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number) ? $item->case_number : '<em>รอดำเนินการ</em>';
                            })
                            ->addColumn('owner_name', function ($item) {
                                $law_offender = $item->law_offender;
                                return ( !empty($law_offender->name) ? $law_offender->name:'N/A'  ).'<div>( '.( !empty($law_offender->taxid) ? $law_offender->taxid:'N/A'  ).' )</div>';
                            })
                            ->addColumn('tis', function ($item) {
                                return !empty($item->StandardHtml) ? $item->StandardHtml : 'N/A';
                            })
                            ->addColumn('product', function ($item) {
                                return !empty($item->ProductHtml) ? $item->ProductHtml : 'N/A';
                            })
                            ->addColumn('product', function ($item) {
                                return !empty($item->ProductHtml) ? $item->ProductHtml : 'N/A';
                            })
                            ->addColumn('license', function ($item) {
                                return !empty($item->LicenseHtml) ? $item->LicenseHtml : 'N/A';
                            })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer->FullName) ? $item->user_lawyer->FullName: 'N/A';
                            })
                            ->addColumn('department_name', function ($item) {
                                $type = ['1'=>'ภายใน (สมอ.)','2'=>'ภายนอก'];
                                return (!empty($item->department_name)?$item->department_name:'N/A').( array_key_exists( $item->depart_type, $type )?'<div>'.($type[ $item->depart_type ]).'</div>':null );
                            })
                            ->addColumn('section', function ($item) {
                                return !empty($item->section)?($item->SectionListName):'N/A';
                            })
                            ->addColumn('total_product', function ($item) {
                                $product_list = !empty( $item->product_list)? $item->product_list->sum('amount'):0;
                                return number_format($product_list);
                            })
                            ->addColumn('total_amount', function ($item) {
                                $total_price = !empty( $item->total_price)? $item->total_price:0;
                                return number_format($total_price);
                            })
                            ->addColumn('penalty', function ($item) {
                                return !empty($item->total_compare) ?   number_format($item->total_compare,2) : '0.00'; 
                            })
                            ->addColumn('assign_at', function ($item) {
                                return  !empty($item->assign_date)?HP::DateThai($item->assign_date):'N/A';
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox',  'owner_name', 'case_number', 'product','tis','license','department_name'])
                            ->make(true);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-law-offender-cases",  "name" => 'รายงานประวัติกระทำความผิด' ],
            ];
            return view('laws.report.summary-law-offender-cases.index',compact('breadcrumbs'));

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
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-law-offender-cases",  "name" => 'รายงานประวัติกระทำความผิด' ],
            ];

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
        $sheet->setCellValue('A1', 'รายงานสืบค้นประวัติการกระทำความผิด ');
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

        //หัวตาราง
        $sheet->setCellValue($start_column.'5', 'ลำดับ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'กอง');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'กลุ่ม/คดีอาญาที่');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วดป. ได้รับมอบหมาย');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เลขคดี');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ชื่อผู้ประกอบการ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เลขประจำตัวผู้เสียภาษี');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ผลิตภัณฑ์');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เลขที่ มอก.');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'จำนวนของกลาง');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'หน่วยของกลาง');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'มูลค่าของกลาง');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'มาตราความผิด');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เสนอลมอ./คกก.');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วันที่เสนอ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วันที่อนุมัติ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ค่าปรับ ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วดป.ชำระเงินค่าปรับ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'สรุปเรื่องให้ลมอ. ทราบ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'นิติกร');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ครั้งที่กระทำความผิด');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ดำเนินคดี');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เสนอลงนามคำสั่ง กมอ.');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'คำสั่งกมอ. ที่');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วันที่คำสั่ง กมอ. ทำให้สิ้นสภาพ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'แจ้งคำสั่ง กมอ.(ปคบ.)');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'แจ้งคำสั่ง กมอ.(บริษัท)');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'แจ้งคำสั่ง กมอ. คืนเรื่องเดิม (กต.)');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'แจ้งผล การเปรียบเทียบปรับ(ปคบ.)');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'วันที่ทำลาย/ ส่งคืน');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->getStyle('A5:E5')->applyFromArray($styleArray_header);

        $sheet->mergeCells('A1:'.$start_column.'1');
        $sheet->mergeCells('A2:'.$start_column.'2');
        $sheet->mergeCells('A3:'.$start_column.'3');
        $sheet->mergeCells('A4:'.$start_column.'4');

        $row = 5;
        $i = 0;

        foreach($query as $item){

            $standard     = $item->standardName;
            $product      = $item->productName;
            $license      = $item->LicenseName;
            $law_offender = $item->law_offender;

            $SectionListName = [];
            if( !empty($item->section) && is_array($item->section) ){
                foreach( $item->section AS $Is){
                    $SectionListName[] = array_key_exists( $Is, $this->option_section  )?$this->option_section[ $Is]:null;
                }
            }

            $row++;
            $start_column_item = 'A';

            //ลำดับ
            $sheet->setCellValue( $start_column_item.$row,  ++$i );
            $start_column_item++;

            //กอง
            $sheet->setCellValue($start_column_item.$row, (!empty($item->department_name)   ? $item->department_name: 'N/A'));
            $start_column_item++;

            //กลุ่ม/คดีอาญาที่
            $sheet->setCellValue($start_column_item.$row, (!empty($item->criminal_case_no)   ? $item->criminal_case_no: 'N/A'));
            $start_column_item++;

            //วันที่มอบหมาย
            $sheet->setCellValue($start_column_item.$row, (!empty($item->assign_date)?HP::DateThai($item->assign_date):'N/A'));
            $start_column_item++;

            //เลขคดี
            $sheet->setCellValue( $start_column_item.$row, (!is_null($item->case_number)?$item->case_number:'N/A'));
            $start_column_item++;

            //ผู้ประกอบการ
            $sheet->setCellValue( $start_column_item.$row, ( !empty($law_offender->name) ? $law_offender->name:'N/A' ));
            $start_column_item++;

            //เลขประจำตัวผู้เสียภาษี
            $sheet->setCellValue( $start_column_item.$row, ( !empty($law_offender->taxid) ? $law_offender->taxid:'N/A'  ));
            $sheet->getStyle($start_column_item.$row)
                    ->getNumberFormat()
                    ->setFormatCode(
                        \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                    );

            $start_column_item++;

            //ผลิตภัณฑ์
            $sheet->setCellValue( $start_column_item.$row, ( !empty($product) ? implode("\n", $product ):'N/A' ));
            $start_column_item++;

            //มอก
            $sheet->setCellValue( $start_column_item.$row, ( !empty($standard) ? implode("\n", $standard ):'N/A' ));
            $start_column_item++;

            //จำนวนของกลาง
            $sheet->setCellValue($start_column_item.$row, (!empty( $item->product_list)? $item->product_list->sum('amount'):0));
            $start_column_item++;

            //หน่วยของกลาง
            $sheet->setCellValue($start_column_item.$row, (!empty( $item->product_list)? $item->product_list->pluck('unit', 'unit')->implode("\n"):'N/A'));
            $start_column_item++;

            //มูลค่าของกลาง
            $sheet->setCellValue($start_column_item.$row, (!empty( $item->total_price)? $item->total_price:0));
            $start_column_item++;

            //มาตราความผิด
            $sheet->setCellValue($start_column_item.$row, (!empty($item->section)?implode(", ", $SectionListName):'N/A'));
            $start_column_item++;

            //เสนอลมอ./คกก.
            $sheet->setCellValue($start_column_item.$row, (!empty($item->power)?$item->power:'N/A'));
            $start_column_item++;

            //วันที่เสนอ
            $sheet->setCellValue($start_column_item.$row, (!empty($item->power_present_date)?HP::DateThai($item->power_present_date):'N/A'));
            $start_column_item++;

            //วันที่อนุมัติ
            $sheet->setCellValue($start_column_item.$row, (!empty($item->approve_date)?HP::DateThai($item->approve_date):'N/A'));
            $start_column_item++;

            //ค่าปรับ 
            $sheet->setCellValue($start_column_item.$row, (!empty( $item->total_compare)? $item->total_compare:0));
            $start_column_item++;

            //วดป.ชำระเงินค่าปรับ
            $sheet->setCellValue($start_column_item.$row, (!empty($item->approve_date)?HP::DateThai($item->approve_date):'N/A'));
            $start_column_item++;

            //สรุปเรื่องให้ ลมอ. ทราบ
            $sheet->setCellValue( $start_column_item.$row, ( !empty( $item->result_summary)? $item->result_summary:'N/A' ));
            $start_column_item++;

            //นิติกร
            $sheet->setCellValue($start_column_item.$row, (!empty($item->user_lawyer->FullName)   ? $item->user_lawyer->FullName: 'N/A'));
            $start_column_item++;

            //ครั้งที่กระทำความผิด
            $sheet->setCellValue($start_column_item.$row, (!empty($item->episode_offenders)   ? $item->episode_offenders: 'N/A'));
            $start_column_item++;

            //ดำเนินคดี
            $sheet->setCellValue($start_column_item.$row, (!empty($item->prosecute) && $item->prosecute == 1 ? "/": ""));
            $start_column_item++;

            //เสนอลงนามคำสั่ง กมอ.
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_present)   ? $item->tisi_present: 'N/A'));
            $start_column_item++;

            //คำสั่ง กมอ. ที่
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_dictation_no)   ? $item->tisi_dictation_no: 'N/A'));
            $start_column_item++;

            //วันที่คำสั่ง กมอ. ทำให้สิ้นสภาพ
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_dictation_date)?HP::DateThai($item->tisi_dictation_date):'N/A'));
            $start_column_item++;

            //แจ้งผลการเปรียบเทียบปรับ(ปคบ.)
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_dictation_cppd)   ? $item->tisi_dictation_cppd: 'N/A'));
            $start_column_item++;

            //แจ้งคำสั่ง กมอ.(บริษัท)
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_dictation_company)   ? $item->tisi_dictation_company: 'N/A'));
            $start_column_item++;

            //แจ้งคำสั่ง กมอ. คืนเรื่องเดิม (กต.)
            $sheet->setCellValue($start_column_item.$row, (!empty($item->tisi_dictation_committee)   ? $item->tisi_dictation_committee: 'N/A'));
            $start_column_item++;

            //แจ้งผล การเปรียบเทียบปรับ(ปคบ.)
            $sheet->setCellValue($start_column_item.$row, (!empty($item->cppd_result)   ? $item->cppd_result: 'N/A'));
            $start_column_item++;

            //วันที่ทำลาย/ ส่งคืน
            $sheet->setCellValue($start_column_item.$row, (!empty($item->destroy_date)?HP::DateThai($item->destroy_date):'N/A'));
            $start_column_item++;

        }

        // $sheet->getStyle("J6".":L".$row)->getNumberFormat()->setFormatCode("#,##0.00");

        $filename = 'รายงานสืบค้นประวัติการกระทำความผิด_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }


}
