<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Books\LawBookManage;
use App\Models\Law\Basic\LawBookType;
use App\Models\Law\Basic\LawBookGroup;
use App\Models\Law\Books\LawBookManageAccess;
use App\Models\Law\Books\LawBookManageVisit;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LawReportBooklistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function query(Request $request)
    {
        $filter_condition_search   = $request->input('filter_condition_search');
        $filter_search             = $request->input('filter_search');
        $filter_book_group         = $request->input('filter_book_group');
        $filter_book_type          = $request->input('filter_book_type');
        $filter_publish_start_date = !empty($request->input('filter_publish_start_date'))? HP::convertDate($request->input('filter_publish_start_date'),true):null;
        $filter_publish_end_date   = !empty($request->input('filter_publish_end_date'))? HP::convertDate($request->input('filter_publish_end_date'),true):null;

        $query = LawBookManage::query()->withCount(['BookManageVisitView','BookManageVisitDownload'])
                                    ->when($filter_book_group, function ($query, $filter_book_group){
                                        return $query->where('basic_book_group_id', $filter_book_group);
                                    })
                                    ->when($filter_book_type, function ($query, $filter_book_type){
                                        return $query->where('basic_book_type_id', $filter_book_type);
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->Where('title', 'LIKE', '%' . $filter_search . '%');
                                                break;
                                            case "2":
                                                return $query->whereHas('book_type', function ($query) use($search_full) {
                                                                    $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                });
                                                break;
                                            case "3":
                                                return $query->whereHas('book_group', function ($query) use($search_full) {
                                                                    $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                });
                                                break;
                                            default:
                                                $query->where( function($query) use($search_full) {
                                                            $query->Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                                                    ->OrwhereHas('book_type', function ($query) use($search_full) {
                                                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                    })
                                                                    ->OrwhereHas('book_group', function ($query) use($search_full) {
                                                                        $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                                    });
                                                        });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_publish_start_date, function ($query, $filter_publish_start_date){
                                        return $query->where('date_publish', '>=', $filter_publish_start_date);
                                    })
                                    ->when($filter_publish_end_date, function ($query, $filter_publish_end_date){
                                        return $query->where('date_publish', '<=', $filter_publish_end_date);
                                    });
        return $query;
    }

    public function data_list(Request $request)
    {
        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('book_group', function ($item) {
                                return !empty($item->BookGroupName)?$item->BookGroupName:null;
                            })
                            ->addColumn('book_type', function ($item) {
                                return !empty($item->BookTypeName)?$item->BookTypeName:null;
                            })
                            ->addColumn('date_publish', function ($item) {
                                return !empty($item->date_publish)?HP::DateThai($item->date_publish):null;
                            })
                            ->addColumn('manage_visit_view', function ($item) {
                                return number_format($item->book_manage_visit_view_count);
                            })
                            ->addColumn('manage_visit_download', function ($item) {
                                return number_format($item->book_manage_visit_download_count);
                            })
                            ->addColumn('manage_access', function ($item) {
                                return !empty($item->ManageAccessName)?$item->ManageAccessName:null;
                            })
                            ->addColumn('status', function ($item) {
                                return  @$item->StateName;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'manage_access'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-report-book-list','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/book-list",  "name" => 'รายงานสรุปข้อมูลห้องสมุด' ],
            ];
            return view('laws.report.book-list.index',compact('breadcrumbs'));

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
        $model = str_slug('law-report-book-list','-');
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
        $model = str_slug('law-report-book-list','-');
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
        $model = str_slug('law-report-book-list','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/book-list",  "name" => 'รายงานสรุปข้อมูลห้องสมุด' ],
            ];

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
        $model = str_slug('law-report-book-list','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/book-list",  "name" => 'รายงานสรุปข้อมูลห้องสมุด' ],
            ];

        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('law-report-book-list','-');
        if(auth()->user()->can('view-'.$model)) {


        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('law-report-book-list','-');
        if(auth()->user()->can('view-'.$model)) {


        }
        abort(403);
    }

    public function export_excel(Request $request)
    {

        $query = $this->query($request);
        $query = $query->get();
                                  
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $book = '';
        if( !empty($request->input('book_group')) ){
            $book .= ' หมวดหมู่: '.$request->input('book_group');
        }

        if( !empty($request->input('book_type')) ){
            $book .= ' ประเภท: '.$request->input('book_type');
        }

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานสรุปข้อมูลห้องสมุด'.$book );
        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->setCellValue('A3', 'ผู้ส่งออกข้อมูล : '.auth()->user()->FullName);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');

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
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'หมวดหมู่');
        $sheet->setCellValue('C5', 'ประเภท');
        $sheet->setCellValue('D5', 'ชื่อเรื่อง');
        $sheet->setCellValue('E5', 'เผยแพร่เมื่อ');
        $sheet->setCellValue('F5', 'เข้าชม');
        $sheet->setCellValue('G5', 'ดาวน์โหลด');
        $sheet->setCellValue('H5', 'สิทธิ์การเข้าถึง');
        $sheet->setCellValue('I5', 'สถานะ');

        $sheet->getStyle('A5:I5')->applyFromArray($styleArray_header);

        $row = 5;
        $i = 0;
        foreach($query as $key =>$item){
            $row++;

            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, ( !empty($item->BookGroupName)?$item->BookGroupName:null ));
            $sheet->setCellValue('C'.$row, ( !empty($item->BookTypeName)?$item->BookTypeName:null ));
            $sheet->setCellValue('D'.$row, ( !empty($item->title)?$item->title:null ));
            $sheet->setCellValue('E'.$row, ( !empty($item->date_publish)?HP::DateThai($item->date_publish):null ));
            $sheet->setCellValue('F'.$row, ( !empty($item->book_manage_visit_view_count)?$item->book_manage_visit_view_count:0 ));
            $sheet->setCellValue('G'.$row, ( !empty($item->book_manage_visit_download_count)?$item->book_manage_visit_download_count:0 ));
            $sheet->setCellValue('H'.$row, ( !empty($item->ManageAccessText)?$item->ManageAccessText:null ));
            $sheet->setCellValue('I'.$row, ( !empty($item->StateName)?$item->StateName:null ));

            
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

        $filename = 'รายงานสรุปข้อมูลห้องสมุด_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }
}
