<?php

namespace App\Http\Controllers\Report;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Elicense\RosUserGroupMap;
use App\Models\Elicense\RosUserGroup;
use App\Models\Elicense\RosUsers;

use App\Models\Elicense\Racl\RaclComponent;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use HP;
class ReportElicenseRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function query($request){
        $filter_search = $request->input('filter_search');

        $query = RosUserGroup::query()
                                ->withCount([
                                    'users',
                                    'racl_view' => function($query){
                                        $query->whereHas('racl_permission_list', function($query){
                                            $query->where(function($query){
                                                        $query->where('stateaccess', 1)
                                                                ->Orwhere('stateadd', 1)
                                                                ->Orwhere('stateedit', 1)
                                                                ->Orwhere('statedelete', 1)
                                                                ->Orwhere('stateeditown', 1)
                                                                ->Orwhere('stateprint', 1)
                                                                ->Orwhere('stateexcel', 1)
                                                                ->Orwhere('statecopy', 1)
                                                                ->Orwhere('stateapply', 1)
                                                                ->Orwhere('statecheckout', 1);
                                                    })
                                                    ->whereRaw('group_id = ros_usergroups.id');
                                        });
                                    }
                                 ])
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                                $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                        ->Orwhere( function($query) use($search_full) {
                                                            $query->whereHas('users', function ($query) use($search_full) {
                                                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(tax_number,' ','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(email,' ','')"),  'LIKE', "%$search_full%");
                                                                    });
                                                        })
                                                        ->Orwhere( function($query) use($search_full) {
                                                            $query->whereHas('racl_view', function ($query) use($search_full) {
                                                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%");
                                                                    });
                                                        });
                                            });
                                });
        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);

        return Datatables::of($query)
                        ->addIndexColumn() 
                        ->addColumn('name', function ($item) {
                            return !empty($item->title)?$item->title:'-';
                        })
                        ->addColumn('users', function ($item) {
                            return ' <a href="'. url('report/elicense-roles/users/'.$item->id) .'" class="" data-toggle="tooltip" data-placement="top" title="รายละเอียด">'.(number_format( $item->users_count  )).'</a>';
                        })
                        ->addColumn('sytems', function ($item) {
                            return ' <a href="'. url('report/elicense-roles/system/'.$item->id) .'" class="" data-toggle="tooltip" data-placement="top" title="รายละเอียด">'.(number_format( $item->racl_view_count )).'</a>';
                        })
                        ->rawColumns(['checkbox', 'sytems', 'users'])
                        ->make(true); 


    }

    public function data_users_list(Request $request){
        $filter_search  = $request->input('filter_search');
        $filter_role_id = $request->input('filter_role_id');

        $query = RosUsers::query()->where(function($query) use($filter_role_id){
                                        $query->whereHas('data_list_group', function ($query) use($filter_role_id) {
                                            $query->where( 'group_id', $filter_role_id  );
                                        });
                                    })
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                            ->Orwhere(DB::Raw("REPLACE(tax_number,' ','')"),  'LIKE', "%$search_full%")
                                            ->Orwhere(DB::Raw("REPLACE(email,' ','')"),  'LIKE', "%$search_full%");
                                    });

        return Datatables::of($query)
                        ->addIndexColumn()   
                        ->order(function ($query) {
                            $query->orderbyRaw('CONVERT(name USING tis620)');
                        })
                        ->addColumn('name', function ($item) {
                            return $item->name;
                        })
                        ->addColumn('tax_number', function ($item) {
                            return @$item->tax_number;
                        })
                        ->addColumn('reg_email', function ($item) {
                            return @$item->email;
                        })
                        ->addColumn('block', function ($item) {
                            return ($item->block == 1)?'Active':"block";
                        })
                        ->rawColumns(['checkbox', 'sytems', 'users'])
                        ->make(true);

    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('report-elicense-roles', '-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/report/elicense-roles",  "name" => 'รายงานการกำหนดสิทธิ์ (Elicense)' ],
            ];
            return view('report.elicense-roles.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function show_system($id)
    {
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/report/roles",  "name" => 'รายงานการกำหนดสิทธิ์' ],
            ];

            $usergroup = RosUserGroup::findOrfail($id);


            return view('report.elicense-roles.show_system',compact('breadcrumbs','usergroup'));

        }
        abort(403);
    }

    
    public function show_users($id)
    {
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/report/roles",  "name" => 'รายงานการกำหนดสิทธิ์' ],
            ];

            $usergroup = RosUserGroup::findOrfail($id);

            return view('report.elicense-roles.show_users',compact('breadcrumbs','usergroup'));

        }
        abort(403);
    }


    public function expoet_excel(Request $request)
    {

        $filter_search = $request->input('filter_search');

        $query =  $this->query($request)->get();

                                                        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท (Elicense)');
        $sheet->mergeCells('A1:AA1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A2:AA2');

        //หัวตาราง
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'กลุ่มบทบาท');
        $sheet->setCellValue('C4', 'จำนวนระบบที่ใช้งาน');
        $sheet->setCellValue('D4', 'จำนวนผู้ใช้งาน');

        $row = 4;
        $i = 0;
        foreach($query as $key =>$item){

            $row++;
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, (!empty($item->title)?$item->title:null));
            $sheet->setCellValue('C'.$row, (!is_null($item->racl_view_count)?$item->racl_view_count:null));
            $sheet->setCellValue('D'.$row, (!is_null($item->users_count)?$item->users_count:null));      

            $sheet->getStyle('C' . $row)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            );

            $sheet->getStyle('C' . $row)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            );

        }

        
        $sheet->getStyle("C5:D".$row)->getNumberFormat()->setFormatCode("#,##0");

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        $filename = 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท(Elicense)_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }

    public function expoet_excel_role(Request $request)
    {
        $role_id     = $request->input('role_id');

        $usergroup   = RosUserGroup::find($role_id);


        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท (Elicense)');
        $sheet->mergeCells('A1:AA1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'กลุ่มบทบาท : '.$usergroup->title);
        $sheet->mergeCells('A2:AA2');
        $sheet->getStyle('A2')->getFont()->setSize(12);

        $sheet->setCellValue('A3', 'ผู้ส่งออกข้อมูล : '.(auth()->user()->FullName));
        $sheet->mergeCells('A3:AA3');
        $sheet->getStyle('A3')->getFont()->setSize(12);

        $sheet->setCellValue('A4', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A4:AA4');
        $sheet->getStyle('A4')->getFont()->setSize(12);

        //หัวตาราง
        $sheet->setCellValue('A6', 'No');
        $sheet->setCellValue('B6', 'ระบบงาน');
        $sheet->setCellValue('C6', 'ดู');
        $sheet->setCellValue('D6', 'เพิ่ม');
        $sheet->setCellValue('E6', 'แก้ไข');
        $sheet->setCellValue('F6', 'ลบ');        
        $sheet->setCellValue('G6', 'มอบหมายงาน');        
        $sheet->setCellValue('H6', 'พิมพ์');
        $sheet->setCellValue('I6', 'Excel');
        $sheet->setCellValue('J6', 'คัดลอก');
        $sheet->setCellValue('K6', 'ดูรายละเอียด');
        $sheet->setCellValue('L6', 'ปลดล็อค');

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

        $sheet->getStyle('A6:L6')->applyFromArray($styleArray_header);

        $column_header = [
            'font' => [ // จัดตัวอักษร
                'bold' => true, // กำหนดเป็นตัวหนา
            ],
        ];

        $ListMenu         = $usergroup->racl_view()
                                        ->whereHas('racl_permission_list', function($query) use($usergroup){
                                            $query->where('group_id',$usergroup->id)
                                                    ->where(function($query){
                                                        $query->where('stateaccess', 1)
                                                                ->Orwhere('stateadd', 1)
                                                                ->Orwhere('stateedit', 1)
                                                                ->Orwhere('statedelete', 1)
                                                                ->Orwhere('stateeditown', 1)
                                                                ->Orwhere('stateprint', 1)
                                                                ->Orwhere('stateexcel', 1)
                                                                ->Orwhere('statecopy', 1)
                                                                ->Orwhere('stateapply', 1)
                                                                ->Orwhere('statecheckout', 1);
                                                    });
                                        })
                                        ->get()
                                        ->groupby('ref_id') ;
        $component        = RaclComponent::pluck('title', 'id')->toArray();

        $permissions_list = $usergroup->racl_permission()
                                        ->where(function($query){
                                            $query->where('stateaccess', 1)
                                                    ->Orwhere('stateadd', 1)
                                                    ->Orwhere('stateedit', 1)
                                                    ->Orwhere('statedelete', 1)
                                                    ->Orwhere('stateeditown', 1)
                                                    ->Orwhere('stateprint', 1)
                                                    ->Orwhere('stateexcel', 1)
                                                    ->Orwhere('statecopy', 1)
                                                    ->Orwhere('stateapply', 1)
                                                    ->Orwhere('statecheckout', 1);
                                        })
                                        ->get();

        $i   = 0;
        $row = 6;

        foreach (  $ListMenu  as $key_com => $Menu ){

            $row++;
            $sheet->setCellValue('A'.$row, ( array_key_exists( $key_com, $component )?$component[  $key_com ]:null ) );
            $sheet->mergeCells('A'.$row.':L'.$row);
            $sheet->getStyle('A'.$row )->applyFromArray($column_header);

            foreach (  $Menu as $Item ){

                $permissions = $permissions_list->where('view_id', $Item->id )->first();

                $i++; 
                $row++;
                $sheet->setCellValue('A'.$row, $i );
                $sheet->setCellValue('B'.$row, $Item->name );

                $sheet->setCellValue('C'.$row, ( !empty($permissions)  && in_array($permissions->stateaccess, [1] )?"✔":null ) );
                $sheet->setCellValue('D'.$row, ( !empty($permissions)  && in_array($permissions->stateadd, [1] )?"✔":null ) );
                $sheet->setCellValue('E'.$row, ( !empty($permissions)  && in_array($permissions->stateedit, [1] )?"✔":null ) );
                $sheet->setCellValue('F'.$row, ( !empty($permissions)  && in_array($permissions->statedelete, [1] )?"✔":null ) );
                $sheet->setCellValue('G'.$row, ( !empty($permissions)  && in_array($permissions->stateeditown, [1] )?"✔":null ) );
                $sheet->setCellValue('H'.$row, ( !empty($permissions)  && in_array($permissions->stateprint, [1] )?"✔":null ) );
                $sheet->setCellValue('I'.$row, ( !empty($permissions)  && in_array($permissions->stateexcel, [1] )?"✔":null ) );
                $sheet->setCellValue('J'.$row, ( !empty($permissions)  && in_array($permissions->statecopy, [1] )?"✔":null ) );
                $sheet->setCellValue('K'.$row, ( !empty($permissions)  && in_array($permissions->stateapply, [1] )?"✔":null ) );
                $sheet->setCellValue('L'.$row, ( !empty($permissions)  && in_array($permissions->statecheckout, [1] )?"✔":null ) );

            }

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


        $styleArray_column = [
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('C8:L'.$row)->applyFromArray($styleArray_column);

        $filename = 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท(Elicense)_'.$usergroup->transliterator_create_from_rules.'_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }
}