<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use stdClass;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\Datatables\Datatables;
use App\Models\Law\Log\LawNotify;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Sso\User AS SSO_User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\Law\Log\LawNotifyUser;
use App\Models\Law\Log\LawSystemCategory;
use App\Models\Law\Listen\LawListenMinistry;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Law\Basic\LawDepartmentStakeholder;

class LawListenMinistryNotifysController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-notifys','-');
    }

    public function query($request){

        $filter_search             = $request->input('filter_search');
        $filter_created_at_start   = !empty($request->input('filter_created_at_start'))? HP::convertDate($request->input('filter_created_at_start'),true):null;
        $filter_created_at_end     = !empty($request->input('filter_created_at_end'))? HP::convertDate($request->input('filter_created_at_end'),true):null;

        $query = LawNotify::query()->when($filter_search, function ($query, $filter_search){
                                        return $query->where('email', 'LIKE', "%".$filter_search."%");
                                    })
                                    ->when($filter_created_at_start, function ($query, $filter_created_at_start){
                                        return $query->whereDate('created_at', '>=', $filter_created_at_start);
                                    })
                                    ->when($filter_created_at_end, function ($query, $filter_created_at_end){
                                        return $query->whereDate('created_at', '<=', $filter_created_at_end);
                                    })
                                    ->where(function($query){
                                        $query->where('ref_table',(new LawListenMinistry)->getTable());
                                            
                                    });
        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('name', function ($item) {
                                $user  = SSO_User::where('email', 'LIKE', "%".$item->email."%")->select('name')->first();
                                $department = LawDepartmentStakeholder::where('email', 'LIKE', "%".$item->email."%")->select('title')->first();
                                return !empty($user->name)?$user->name:(!empty($department->title)?$department->title:null);
                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->created_at)?HP::DateTimeThai($item->created_at):null;
                               
                            })
                            ->rawColumns(['name'])
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
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
            ];
            return view('laws.report.mail-listen-ministry.index',compact('breadcrumbs'));

        }
        abort(403);
    }

      //ส่งออกข้อมูล
      public function export_excel(Request $request) {

        //Create Spreadsheet Object
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $query       = self::query($request)->get();

           //หัวรายงาน
           $sheet->setCellValue('A1', 'รายงานประวัติการส่งเมลประกาศร่างกฎกระทรวง');
           $sheet->mergeCells('A1:P1');
           
           $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
           $sheet->getStyle("A1")->getFont()->setSize(18);

           //แสดงวันที่
           $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
           $sheet->mergeCells('A2:D2');

           $sheet->setCellValue('A3', 'ผู้ส่งออกข้อมูล : '.auth()->user()->FullName);
           $sheet->mergeCells('A3:D3');

           //หัวตาราง
           $sheet->setCellValue('A4', 'ลำดับ');
           $sheet->setCellValue('B4', 'ชื่อ');
           $sheet->setCellValue('C4', 'อีเมล');
           $sheet->setCellValue('D4', 'วันเวลา');
      
           $row = 4; //start row
            if(count($query) > 0){
                foreach ($query as $key => $item) {

  
                $user       = SSO_User::where('email', 'LIKE', "%".$item->email."%")->select('name')->first();
                $department = LawDepartmentStakeholder::where('email', 'LIKE', "%".$item->email."%")->select('title')->first();
                $name       = !empty($user->name)?$user->name:(!empty($department->title)?$department->title:null);
                $created_at = !empty($item->created_at)?HP::DateTimeThai($item->created_at):null;

                    $row++;
                    $sheet->setCellValue('A' . $row,$key+1);
                    $sheet->setCellValue('B' . $row, $name);
                    $sheet->setCellValue('C' . $row, $item->email);
                    $sheet->setCellValue('D' . $row, $created_at);

                }
            }
             //ใส่ขอบดำ
             $style_borders = [
               'borders' => [ // กำหนดเส้นขอบ
               'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                   'borderStyle' => Border::BORDER_THIN,
               ],
               ]
           ];
           $sheet->getStyle('A4:D'.$row)->applyFromArray($style_borders);


           //Set Column Width
           $sheet->getColumnDimension('A')->setAutoSize(true);
           $sheet->getColumnDimension('B')->setAutoSize(true);
           $sheet->getColumnDimension('C')->setAutoSize(true);
           $sheet->getColumnDimension('D')->setAutoSize(true);
        


           $filename = 'ข้อมูลความเห็น_' . date('Hi_dmY') . '.xlsx';
           header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header('Content-Disposition: attachment; filename="' . $filename . '"');
           $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
           $writer->save("php://output");
           exit;
        }

}
