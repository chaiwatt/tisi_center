<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Basic\Branch;
use App\Models\Basic\BranchGroup;
use App\Models\Basic\BranchTis;
use App\Models\Basic\Tis;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Html;

class ReportStandardBranchController extends Controller
{
    private $permission;
    public function __construct()
    {
        set_time_limit(0);
        $this->middleware('auth');
        $this->permission = str_slug('report-standard-branch','-');
    }


    public function query($request)
    {

        $filter_condition_search    = $request->input('filter_condition_search');
        $filter_search              = $request->input('filter_search');
        $filter_branch_tis          = $request->input('filter_branch_tis');
        $filter_standard            = $request->input('filter_standard');
        $filter_branch_group        = $request->input('filter_branch_group');
        $filter_branch              = $request->input('filter_branch');

        $TbBranch                   = ( new Branch )->getTable();
        $TbBranchTis                = ( new BranchTis )->getTable();
        $TbBranchGroup              = ( new BranchGroup )->getTable();

        $query = Tis::query()
                            ->leftJoin($TbBranchTis.' AS branch_tis',  'tb3_tis.tb3_TisAutono', '=', 'branch_tis.tis_id')
                            ->leftJoin($TbBranch.' AS branch',  'branch_tis.branch_id', '=', 'branch.id')
                            ->leftJoin($TbBranchGroup.' AS branch_group',  'branch_group.id', '=', 'branch.branch_group_id')
                            ->select( 
                                DB::raw('branch.title AS branch_name'), 
                                DB::raw('branch_group.title AS branch_group_name'), 
                                DB::raw('tb3_tis.tb3_TisThainame AS tb3_TisThainame'),
                                DB::raw('tb3_tis.tb3_Tisno AS tb3_Tisno')
                            )
                            ->when($filter_branch_tis, function ($query, $filter_branch_tis){
                                $ids = BranchTis::select('tis_id');
                                if( $filter_branch_tis == 1 ){
                                    return $query->whereIN('tb3_tis.tb3_TisAutono',  $ids);
                                }else{
                                    return $query->whereNotIN('tb3_tis.tb3_TisAutono', $ids);
                                }
                            })
                            ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                $search_full = str_replace(' ', '', $filter_search);
                                switch ( $filter_condition_search ):
                                    case "1":
                                        return $query->where( function($query) use ($search_full){
                                                        $query->Where(DB::raw("CONCAT(REPLACE(tb3_tis.tb3_Tisno,' ',''),':', REPLACE(tb3_tis.tb3_TisThainame,' ',''))"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tb3_tis.tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tb3_tis.tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%");
                                                    });
                                        break;
                                    case "2":
                                        return $query->Where(DB::raw("REPLACE(branch_group.title,' ','')"), 'LIKE', "%".$search_full."%");
                                        break;
                                    case "3":
                                        return $query->Where(DB::raw("REPLACE(branch.title,' ','')"), 'LIKE', "%".$search_full."%");
                                        break;
                                    default:
                                        return $query->where( function($query) use ($search_full){
                                                        $query->Where(DB::raw("CONCAT(REPLACE(tb3_tis.tb3_Tisno,' ',''),':', REPLACE(tb3_tis.tb3_TisThainame,' ',''))"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tb3_tis.tb3_Tisno,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(tb3_tis.tb3_TisThainame,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(branch_group.title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                ->OrWhere(DB::raw("REPLACE(branch.title,' ','')"), 'LIKE', "%".$search_full."%");
                                                    });
                                        break;
                                endswitch;
                            })
                            ->when($filter_standard, function ($query, $filter_standard){
                                return $query->Where('tb3_tis.tb3_TisAutono',  $filter_standard);
                            })
                            ->when($filter_branch_group, function ($query, $filter_branch_group){
                                return $query->Where('branch_group.id',  $filter_branch_group);
                            })
                            ->when($filter_branch, function ($query, $filter_branch){
                                return $query->Where('branch.id',  $filter_branch);
                            });

        return $query;
    }

    public function data_list(Request $request)
    {
        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('tis_no', function ($item) {
                                return !empty($item->tb3_Tisno)?$item->tb3_Tisno:null;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return !empty($item->tb3_TisThainame)?$item->tb3_TisThainame:null;
                            })
                            ->addColumn('branch_group_name', function ($item) {
                                return !empty($item->branch_group_name)?$item->branch_group_name:null;
                            })
                            ->addColumn('branch_name', function ($item) {
                                return !empty($item->branch_name)?$item->branch_name:null;
                            })
                            ->order(function ($query) {
                                $query->orderBy('tb3_Tisno');
                            })
                            ->rawColumns([])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(auth()->user()->can('view-'.$this->permission)) {

            // dd($this->query($request)->get());
            return view('section5.standard-branch.index');

        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(auth()->user()->can('add-'.$this->permission)) {

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
        if(auth()->user()->can('add-'.$this->permission)) {

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
        if(auth()->user()->can('edit-'.$this->permission)) {

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
        if(auth()->user()->can('edit-'.$this->permission)) {

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
        if(auth()->user()->can('delete-'.$this->permission)) {

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
        $sheet->setCellValue('A1', 'รายงานรายสาขาแยกกตามมาตรฐานมอก.');
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

        $sheet->setCellValue($start_column.'5', 'ลำดับ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'เลขที่ มอก.');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'ผลิตภัณฑ์ ฯ');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++; 
        
        $sheet->setCellValue($start_column.'5', 'หมวดสาขา/สาขา');
        $sheet->getStyle($start_column.'5')->applyFromArray($styleArray_header);
        $sheet->getColumnDimension($start_column)->setAutoSize(true);
        $start_column++;

        $sheet->setCellValue($start_column.'5', 'รายสาขา');
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

            $row++;
            $start_column_item = 'A';

            //ลำดับ
            $sheet->setCellValue( $start_column_item.$row,  ++$i );
            $start_column_item++;

            $sheet->setCellValue($start_column_item.$row, (!empty($item->tb3_Tisno)?$item->tb3_Tisno:null));
            $start_column_item++;

            $sheet->setCellValue($start_column_item.$row, (!empty($item->tb3_TisThainame)?$item->tb3_TisThainame:null));
            $start_column_item++;

            $sheet->setCellValue($start_column_item.$row, (!empty($item->branch_group_name)?$item->branch_group_name:null));
            $start_column_item++;

            $sheet->setCellValue($start_column_item.$row, (!empty($item->branch_name)?$item->branch_name:null));
            $start_column_item++;

        }

        $filename = 'รายงานรายสาขาแยกกตามมาตรฐานมอก_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }
}
