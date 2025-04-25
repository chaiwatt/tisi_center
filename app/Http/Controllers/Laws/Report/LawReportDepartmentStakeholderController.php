<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use App\Models\Sso\User;
use App\Models\Basic\Tis;
use App\Models\Csurv\Tis4;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Books\LawBookManage;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\Law\Basic\LawDepartmentStakeholder;

class LawReportDepartmentStakeholderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function query($request)//ใช่ร่วมกับexcel
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_status           = $request->input('filter_status');
        $filter_search           = $request->input('filter_search');

        $basic_department_stakeholder     =   LawDepartmentStakeholder::where('state',1)
                                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                        switch ( $filter_condition_search ):
                                                            case "1":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('ref_no', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                                break;
                                                            case "2":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('title', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                                break;
                                                            case "3":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('tis_id', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                                break;
                                                            default:
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('title', 'LIKE', '%'.$search_full.'%')
                                                                               ->Orwhere('title', 'address_no', '%'.$search_full.'%')
                                                                               ->Orwhere('tis_id', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                                break;
                                                        endswitch;
                                                    })
                                                    ->select(
                                                        'id',
                                                        'title',
                                                        'address_no',
                                                        'tel',
                                                        'email',
                                                        'tis_id',
                                                         DB::raw('"1" AS type')
                                                    );


        $tb4_tisilicense     =   Tis4::where('tbl_licenseStatus', 1)
                                                ->leftJoin((new User)->getTable().' AS user_sso', 'user_sso.tax_number', '=', 'tbl_taxpayer')
                                                ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                    switch ( $filter_condition_search ):
                                                        case "1":
                                                            $search_full = str_replace(' ', '', $filter_search);
                                                            return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->where('tbl_tradeName', 'LIKE', '%'.$search_full.'%');
                                                                    });
                                                            break;
                                                        case "2":
                                                            $search_full = str_replace(' ', '', $filter_search);
                                                            return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->where('tbl_tradeAddress', 'LIKE', '%'.$search_full.'%');
                                                                    });
                                                            break;
                                                        case "3":
                                                            $search_full = str_replace(' ', '', $filter_search);
                                                            return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->where('tbl_tisiNo', 'LIKE', '%'.$search_full.'%');
                                                                    });
                                                            break;
                                                        default:
                                                            $search_full = str_replace(' ', '', $filter_search);
                                                            return  $query->where(function ($query2) use($search_full) {
                                                                    $query2->where('tbl_tradeName', 'LIKE', '%'.$search_full.'%')
                                                                           ->Orwhere('tbl_tradeAddress', 'LIKE', '%'.$search_full.'%')
                                                                           ->Orwhere('tbl_tisiNo', 'LIKE', '%'.$search_full.'%');
                                                                    });
                                                            break;
                                                    endswitch;
                                                })
                                                ->select(
                                                    DB::raw('Autono AS id'),
                                                    DB::raw('tbl_tradeName AS title'),
                                                    DB::raw('tbl_tradeAddress AS address_no'),
                                                    DB::raw('user_sso.tel AS tel'),
                                                    DB::raw('user_sso.email AS email'),
                                                    DB::raw('tbl_tisiNo AS tis_id'),
                                                    DB::raw('"2" AS type')
                                                );

        if (!empty($filter_status) && $filter_status == 1){//หน่วยงานอื่นๆ
            $query = $basic_department_stakeholder;
        }elseif(!empty($filter_status) && $filter_status == 2){//ผู้ได้รับใบอนุญาต
            $query = $tb4_tisilicense;
        }else{
            $query =  $basic_department_stakeholder->union($tb4_tisilicense);
        }

        return $query;

    }

    public function data_list(Request $request)
    {
        $list_type  =  [
                        "1" => "หน่วยงานอื่นๆ",
                        "2" => "ผู้ได้รับใบอนุญาต",
                       ];

        return Datatables::of(self::query($request))
                            ->addIndexColumn()
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('address_no', function ($item) {
                                return $item->address_no;
                            })
                            ->addColumn('tel', function ($item) {
                                return $item->tel;
                            })
                            ->addColumn('email', function ($item) {
                                return $item->email;
                            })
                            ->addColumn('tis_id', function ($item) {
                                if($item->type == 1){
                                    $datas = [];
                                    if(!empty($item->tis_id)){
                                        $data = json_decode($item->tis_id);
                                        $tb3_tis = Tis::select('tb3_TisAutono','tb3_Tisno')->pluck('tb3_Tisno', 'tb3_TisAutono')->toArray();
                                        if(count($data)> 0){
                                            foreach ($data as $key => $list) {
                                                $datas[] = array_key_exists($list,$tb3_tis)?$tb3_tis[$list]:null;
                                            }
                                        }
                                    }
                                    return implode('<br>', $datas );
                                }else{
                                    return $item->tis_id;
                                }
                            })
                            ->addColumn('type', function ($item) use($list_type) {
                                $type = array_key_exists($item->type,$list_type)?$list_type[$item->type]:null;
                                return $type;
                            })
                            ->rawColumns(['checkbox','tis_id'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-report-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/department-stakeholder",  "name" => 'รายงานผู้มีส่วนได้ส่วนเสีย' ],
            ];
            return view('laws.report.department-stakeholder.index',compact('breadcrumbs','query'));

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
        $model = str_slug('law-report-department-stakeholder','-');
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
        $model = str_slug('law-report-department-stakeholder','-');
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
        $model = str_slug('law-report-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/department-stakeholder",  "name" => 'รายงานผู้มีส่วนได้ส่วนเสีย' ],
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
        $model = str_slug('law-report-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/department-stakeholder",  "name" => 'รายงานผู้มีส่วนได้ส่วนเสีย' ],
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
        $model = str_slug('law-report-department-stakeholder','-');
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
        $model = str_slug('law-report-department-stakeholder','-');
        if(auth()->user()->can('view-'.$model)) {


        }
        abort(403);
    }

    public function export_excel(Request $request)
    {
        $query       = self::query($request)->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $list_type  =  [
            "1" => "หน่วยงานอื่นๆ",
            "2" => "ผู้ได้รับใบอนุญาต",
           ];

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานผู้มีส่วนได้ส่วนเสีย');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

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
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'ประเภทข้อมูล');
        $sheet->setCellValue('C4', 'ชื่อหน่วยงาน/ชื่อผู้ได้รับใบอนุญาต');
        $sheet->setCellValue('D4', 'ที่อยู่');
        $sheet->setCellValue('E4', 'เบอร์โทร');
        $sheet->setCellValue('F4', 'อีเมล');
        $sheet->setCellValue('G4', 'มอก. ที่เกี่ยวข้อง');

        $sheet->getStyle('A4:G4')->applyFromArray($styleArray_header);

        $row = 4;
        $i = 0;
        foreach($query as $key =>$item){
            $row++;

            if($item->type == 1){
                $datas = [];
                if(!empty($item->tis_id)){
                    $data = json_decode($item->tis_id);
                    $tb3_tis = Tis::select('tb3_TisAutono','tb3_Tisno')->pluck('tb3_Tisno', 'tb3_TisAutono')->toArray();
                    if(count($data)> 0){
                        foreach ($data as $key => $list) {
                            $datas[] = array_key_exists($list,$tb3_tis)?$tb3_tis[$list]:null;
                        }
                    }
                }
                $tis_id = implode('<br>', $datas );
            }else{
                $tis_id = $item->tis_id;
            }

            $type = array_key_exists($item->type,$list_type)?$list_type[$item->type]:null;

            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, ( !empty($type)?$type:null ));
            $sheet->setCellValue('C'.$row, ( !empty($item->title)?$item->title:null ));
            $sheet->setCellValue('D'.$row, ( !empty($item->address_no)?$item->address_no:null ));
            $sheet->setCellValue('E'.$row, ( !empty($item->tel)?$item->tel:null ));
            $sheet->setCellValue('F'.$row, ( !empty($item->email)?$item->email:null ));
            $sheet->setCellValue('G'.$row, ( !empty($tis_id)?$tis_id:null ));

            
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);


        $filename = 'รายงานผู้มีส่วนได้ส่วนเสีย_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }
}
