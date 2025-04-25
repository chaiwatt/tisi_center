<?php

namespace App\Http\Controllers\Report;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Sso\User AS SSO_User;
use App\User;
use App\Permission;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class ReportRolesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    function data_list(Request $request)
    {
        $filter_search = $request->input('filter_search');
        $filter_group  = $request->input('filter_group');

        $query = Role::query()->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                    $query->where( function($query) use($search_full) {
                                                $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                        ->Orwhere( function($query) use($search_full) {
                                                            $query->whereHas('users_sso', function ($query) use($search_full) {
                                                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(tax_number,' ','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(email,' ','')"),  'LIKE', "%$search_full%");
                                                                    });
                                                        })
                                                        ->Orwhere( function($query) use($search_full) {
                                                            $query->whereHas('users', function ($query) use($search_full) {
                                                                        $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%")
                                                                                ->Orwhere(DB::Raw("REPLACE(reg_13ID,' ','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(reg_13ID,'-','')"),  'LIKE', "%$search_full%")
                                                                                ->Orwhere(DB::Raw("REPLACE(reg_email,' ','')"),  'LIKE', "%$search_full%");
                                                                    });
                                                        });
                                            });
                               
                                })
                                ->when($filter_group, function ($query, $filter_group){
                                    $query->whereHas('role_setting_group', function($query) use ($filter_group){
                                        $query->where('id', $filter_group);
                                    });
                                })
                                ->with(['permissions'=> function($query) {
                                    $query->select(DB::Raw("
                                                            CASE
                                                                WHEN name LIKE '%view-%' THEN REPLACE(name, 'view-', '')
                                                                WHEN name LIKE '%add-%' THEN REPLACE(name, 'add-', '')
                                                                WHEN name LIKE '%edit-%' THEN REPLACE(name, 'edit-', '')
                                                                WHEN name LIKE '%delete-%' THEN REPLACE(name, 'delete-', '')
                                                                WHEN name LIKE '%other-%' THEN REPLACE(name, 'other-', '')
                                                                WHEN name LIKE '%poko_approve-%' THEN REPLACE(name, 'poko_approve-', '')
                                                                WHEN name LIKE '%poao_approve-%' THEN REPLACE(name, 'poao_approve-', '')
                                                                WHEN name LIKE '%assign_work-%' THEN REPLACE(name, 'assign_work-', '')
                                                                WHEN name LIKE '%view_all-%' THEN REPLACE(name, 'view_all-', '')
                                                                ELSE name
                                                            END AS name
                                                            "))->orderBy('name');
                                }])
                                ->withCount(['users', 'users_sso']);

        return Datatables::of($query)
                            ->addIndexColumn()      
                            ->addColumn('name', function ($item) {
                                return !empty($item->name)?$item->name:'-';
                            })
                            ->addColumn('label', function ($item) {
                                return !empty($item->label)?($item->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-';
                            })
                            ->addColumn('users', function ($item) {
                                $users = 0;
                                if( $item->label=='staff' ){
                                    $users = $item->users_count;
                                }else{
                                    $users = $item->users_sso_count;
                                }
                                return ' <a href="'. url('report/roles/users/'.$item->id) .'" class="" data-toggle="tooltip" data-placement="top" title="รายละเอียด">'.(number_format( $users )).'</a>';
                            })
                            ->addColumn('sytems', function ($item) {
                                $permissions = $item->permissions->pluck('name','name')->toArray();
                                return ' <a href="'. url('report/roles/system/'.$item->id) .'" class="" data-toggle="tooltip" data-placement="top" title="รายละเอียด">'.(number_format( HP::MenuListAllRoleCount( $item->label, $permissions )  )).'</a>';
                            })
                            ->order(function ($query) {
                                $query->orderbyRaw('CONVERT(name USING tis620)');
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
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/report/roles",  "name" => 'รายงานการกำหนดสิทธิ์' ],
            ];
            return view('report.roles.index',compact('breadcrumbs'));
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
        $model = str_slug('report-roles', '-');
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
        $model = str_slug('report-roles', '-');
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
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {


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
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {


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
        $model = str_slug('report-roles', '-');
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
        $model = str_slug('report-roles', '-');
        if(auth()->user()->can('view-'.$model)) {


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

            $roles = Role::findOrfail($id);


            return view('report.roles.show_system',compact('breadcrumbs','roles'));

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

            $roles = Role::findOrfail($id);

            return view('report.roles.show_users',compact('breadcrumbs','roles'));

        }
        abort(403);
    }

    public function data_trader_list(Request $request){
        $filter_search = $request->input('filter_search');
        $filter_role_id = $request->input('filter_role_id');

        $query = SSO_User::query()->where(function($query) use($filter_role_id){
                                        $query->whereHas('data_list_roles', function ($query) use($filter_role_id) {
                                            $query->where( 'role_id', $filter_role_id  );
                                        });
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
                                return $item->tax_number;
                            })
                            ->addColumn('email', function ($item) {
                                return $item->email;
                            })
                            ->addColumn('branch_code', function ($item) {
                                return  !empty($item->branch_code) ? $item->branch_code : '-';
                            })
                            ->addColumn('applicant_types', function ($item) {
                                $applicant_types = HP::applicant_types();
                                return   array_key_exists($item->applicanttype_id, $applicant_types) ? $applicant_types[$item->applicanttype_id] : '<i class="text-muted">ไม่มีข้อมูล</i>' ;
                            })
                            ->rawColumns(['checkbox', 'sytems', 'applicant_types'])
                            ->make(true);  

    }

    public function data_staff_list(Request $request){
        $filter_search = $request->input('filter_search');
        $filter_role_id = $request->input('filter_role_id');

        $query = User::query()->where(function($query) use($filter_role_id){
                                    $query->whereHas('data_list_roles', function ($query) use($filter_role_id) {
                                        $query->where( 'role_id', $filter_role_id  );
                                    });
                                });


        return Datatables::of($query)
                            ->addIndexColumn()   
                            ->order(function ($query) {
                                $query->orderbyRaw('CONVERT(reg_fname USING tis620)');
                            })
                            ->addColumn('reg_fname', function ($item) {
                                return ($item->reg_fname.' '.$item->reg_lname);
                            })
                            ->addColumn('reg_13ID', function ($item) {
                                return @$item->reg_13ID;
                            })
                            ->addColumn('reg_email', function ($item) {
                                return @$item->reg_email;
                            })
                            ->addColumn('sub_departname', function ($item) {
                                return @$item->subdepart->sub_departname  ?? null;
                            })
                            ->rawColumns(['checkbox', 'sytems', 'users'])
                            ->make(true);

    }

    public function expoet_excel(Request $request)
    {

        $filter_search = $request->input('filter_search');

        $query = Role::with(['permissions'=> function($query) {
                            $query->select(DB::Raw("
                                                    CASE
                                                        WHEN name LIKE '%view-%' THEN REPLACE(name, 'view-', '')
                                                        WHEN name LIKE '%add-%' THEN REPLACE(name, 'add-', '')
                                                        WHEN name LIKE '%edit-%' THEN REPLACE(name, 'edit-', '')
                                                        WHEN name LIKE '%delete-%' THEN REPLACE(name, 'delete-', '')
                                                        WHEN name LIKE '%other-%' THEN REPLACE(name, 'other-', '')
                                                        WHEN name LIKE '%poko_approve-%' THEN REPLACE(name, 'poko_approve-', '')
                                                        WHEN name LIKE '%poao_approve-%' THEN REPLACE(name, 'poao_approve-', '')
                                                        WHEN name LIKE '%assign_work-%' THEN REPLACE(name, 'assign_work-', '')
                                                        WHEN name LIKE '%view_all-%' THEN REPLACE(name, 'view_all-', '')
                                                        ELSE name
                                                    END AS name
                                                    "))->orderBy('name');
                        }])
                        ->withCount(['users', 'users_sso'])
                        ->when($filter_search, function ($query, $filter_search){
                            $search_full = str_replace(' ', '', $filter_search );
                            $query->where( function($query) use($search_full) {
                                        $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                ->Orwhere( function($query) use($search_full) {
                                                    $query->whereHas('users_sso', function ($query) use($search_full) {
                                                                $query->where(DB::Raw("REPLACE(name,' ','')"),  'LIKE', "%$search_full%")
                                                                        ->Orwhere(DB::Raw("REPLACE(tax_number,' ','')"),  'LIKE', "%$search_full%")
                                                                        ->Orwhere(DB::Raw("REPLACE(email,' ','')"),  'LIKE', "%$search_full%");
                                                            });
                                                })
                                                ->Orwhere( function($query) use($search_full) {
                                                    $query->whereHas('users', function ($query) use($search_full) {
                                                                $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%")
                                                                        ->Orwhere(DB::Raw("REPLACE(reg_13ID,' ','')"),  'LIKE', "%$search_full%")
                                                                        ->Orwhere(DB::Raw("REPLACE(reg_13ID,'-','')"),  'LIKE', "%$search_full%")
                                                                        ->Orwhere(DB::Raw("REPLACE(reg_email,' ','')"),  'LIKE', "%$search_full%");
                                                            });
                                                });
                                    });
                       
                        })
                        ->orderbyRaw('CONVERT(name USING tis620)')
                        ->get();

                        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A2:I2');

        //หัวตาราง
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'กลุ่มบทบาท');
        $sheet->setCellValue('C4', 'ส่วนควบคุม');
        $sheet->setCellValue('D4', 'จำนวนระบบที่ใช้งาน');
        $sheet->setCellValue('E4', 'จำนวนผู้ใช้งาน');

        $row = 4;
        $i = 0;
        foreach($query as $key =>$item){

            $users = 0;
            if( $item->label=='staff' ){
                $users = $item->users_count;
            }else{
                $users = $item->users_sso_count;
            }

            $permissions = $item->permissions->pluck('name','name')->toArray();

            $row++;
            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, (!empty($item->name)?$item->name:null));
            $sheet->setCellValue('C'.$row, (!empty($item->label)?($item->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-'));
            $sheet->setCellValue('D'.$row, ( HP::MenuListAllRoleCount( $item->label, $permissions ) ));
            $sheet->setCellValue('E'.$row, ($users));

            $sheet->getStyle('D' . $row)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            );

            $sheet->getStyle('E' . $row)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            );
        }

        $sheet->getStyle("D5:L".$row)->getNumberFormat()->setFormatCode("#,##0");
        $sheet->getStyle("E5:L".$row)->getNumberFormat()->setFormatCode("#,##0");

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $filename = 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }

    
    public function expoet_excel_role(Request $request)
    {
        $role_id     = $request->input('role_id');

        $roles       = Role::find($role_id);


        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท');
        $sheet->mergeCells('A1:AA1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'กลุ่มบทบาท : '.$roles->name);
        $sheet->mergeCells('A2:AA2');
        $sheet->getStyle('A2')->getFont()->setSize(12);

        $sheet->setCellValue('A3', 'ส่วนการควบคุม : '.( !empty($roles->label)?($roles->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-' ));
        $sheet->mergeCells('A3:AA3');
        $sheet->getStyle('A3')->getFont()->setSize(12);

        $sheet->setCellValue('A4', 'ผู้ส่งออกข้อมูล : '.(auth()->user()->FullName));
        $sheet->mergeCells('A4:AA4');
        $sheet->getStyle('A4')->getFont()->setSize(12);

        $sheet->setCellValue('A5', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A5:AA5');
        $sheet->getStyle('A5')->getFont()->setSize(12);


        //หัวตาราง
        $sheet->setCellValue('A7', 'No');
        $sheet->setCellValue('B7', 'ระบบงาน');
        $sheet->setCellValue('C7', 'ดู');
        $sheet->setCellValue('D7', 'เพิ่ม');
        $sheet->setCellValue('E7', 'แก้ไข');
        $sheet->setCellValue('F7', 'ลบ');        
        $sheet->setCellValue('G7', 'มอบหมาย');        
        $sheet->setCellValue('H7', 'ผก.อนุมัติ');
        $sheet->setCellValue('I7', 'ผอ.อนุมัติ');
        $sheet->setCellValue('J7', 'ดูได้ทุกรายการ');

        
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

        $sheet->getStyle('A7:J7')->applyFromArray($styleArray_header);

        $arr_permissions  = Permission::all()->pluck('id', 'name')->toArray();
        $role_permissions = $roles->permissions()->pluck('id')->toArray();
        $permissions_role = [];
        foreach ( $roles->permissions->pluck('name','name')->toArray() as  $permissions) {
            $message                       = $permissions;
            $message                       = str_replace("add-", "", $message);
            $message                       = str_replace("view-", "", $message);
            $message                       = str_replace("edit-", "", $message);
            $message                       = str_replace("delete-", "", $message);
            $message                       = str_replace("other-", "", $message);
            $message                       = str_replace("assign_work-", "", $message);
            $message                       = str_replace("poko_approve-", "", $message);
            $message                       = str_replace("poao_approve-", "", $message);
            $message                       = str_replace("view_all-", "", $message);
            $permissions_role[  $message ] =  $message;
        }

        if( $roles->label=='staff' ){
            $ListMenu = HP::MenuSidebar(false); 
        }else{
            $ListMenu = HP::MenuTraderSidebar(); 
        }

        
        $column_header = [
            'font' => [ // จัดตัวอักษร
                'bold' => true, // กำหนดเป็นตัวหนา
            ],
        ];


        $i   = 0;
        $row = 7;
        foreach (  $ListMenu as $Menu ){

            if( HP::CheckRoleMenuItem( $Menu->items , $permissions_role ) ){

                $row++;
                $sheet->setCellValue('A'.$row, $Menu->_comment );
                $sheet->mergeCells('A'.$row.':J'.$row);
                $sheet->getStyle('A'.$row )->applyFromArray($column_header);
                if(isset( $Menu->items )){

                    foreach (  $Menu->items  as $Item ){

                        $row++;
                        $sheet->setCellValue('A'.$row, $Item->display );
                        $sheet->mergeCells('A'.$row.':J'.$row);
                        $sheet->getStyle('A'.$row )->applyFromArray($column_header);

                        if( isset(  $Item->sub_menus ) ){
                            foreach ( $Item->sub_menus as $sub_menus ){
                           
                                if( property_exists($sub_menus, 'title') && HP::CheckRoleMenuItem( $sub_menus , $permissions_role )){
    
                                    $permissions =	HP::permissionList( $sub_menus->title , $arr_permissions   );
                                    $i++; 
                                    $row++;
                                    $sheet->setCellValue('A'.$row, $i );
                                    $sheet->setCellValue('B'.$row, $sub_menus->display );

                                    $sheet->setCellValue('C'.$row, ( in_array($permissions['view'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('D'.$row, ( in_array($permissions['add'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('E'.$row, ( in_array($permissions['edit'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('F'.$row, ( in_array($permissions['delete'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('G'.$row, ( in_array($permissions['assign_work'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('H'.$row, ( in_array($permissions['poko_approve'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('I'.$row, ( in_array($permissions['poao_approve'], $role_permissions)?"✔":null ) );
                                    $sheet->setCellValue('J'.$row, ( in_array($permissions['view_all'], $role_permissions)?"✔":null ) );


    
                                }
                            }
                        }else{
                            if( property_exists($Item, 'title') &&  HP::CheckRoleMenuItem( $Item , $permissions_role ) ){
                                $permissions =	HP::permissionList( $Item->title , $arr_permissions   );
                                $i++;
                                $row++;
                                $sheet->setCellValue('A'.$row, $i );
                                $sheet->setCellValue('B'.$row, $Item->display );

                                $sheet->setCellValue('C'.$row, ( in_array($permissions['view'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('D'.$row, ( in_array($permissions['add'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('E'.$row, ( in_array($permissions['edit'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('F'.$row, ( in_array($permissions['delete'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('G'.$row, ( in_array($permissions['assign_work'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('H'.$row, ( in_array($permissions['poko_approve'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('I'.$row, ( in_array($permissions['poao_approve'], $role_permissions)?"✔":null ) );
                                $sheet->setCellValue('J'.$row, ( in_array($permissions['view_all'], $role_permissions)?"✔":null ) );
                            }
                        }

                    }

                }

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

        $styleArray_column = [
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('C8:J'.$row)->applyFromArray($styleArray_column);

        $filename = 'รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท_'.$roles->name.'_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }
}
