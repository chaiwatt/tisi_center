<?php

namespace App\Http\Controllers\Csurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\control_follow;
use App\Models\Csurv\ControlFollow;
use App\Models\Csurv\ControlFollowListTable;
use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;

class ControlFollowController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_year'] = $request->get('filter_year', '');

        $Query = new ControlFollow;

        if ($filter['filter_year'] != '') {
            $Query = $Query->where('make_annual', $filter['filter_year']);
        }

        $control_follow = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $control_follow->firstItem();

        return view('csurv.control_follow.index', compact('control_follow', 'filter', 'temp_num'));
    }

    public function create(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 100);
        $filter['select_year'] = $request->get('select_year', '');
        $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
        $filter['filter_name'] = $request->get('filter_name', '');
        $filter['filter_address'] = $request->get('filter_address', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_sub_department'] = $request->get('filter_sub_department', '');

        if ($filter['select_year'] != '') {
            $c_year = DB::table('control_follow')->where('make_annual', $filter['select_year'])->first();
            if ($c_year != null) {
                $control_follow = null;

                return view('csurv.control_follow.create', ['select_year' => $filter['select_year'], 'c_year' => $c_year], compact('control_follow', 'filter'));
            } else {

                $ck_year = ControlFollow::query()->get();
                $ck_year1 = ControlFollow::query()->first();
                if ($ck_year1 != null) {
                    $ck_table_year = ControlFollowListTable::query()->where('id_follow', $ck_year1->id)
                        ->orderByRaw("CASE
	      WHEN consider_grades = 'X'          THEN 1
	      WHEN consider_grades = 'H'           THEN 2
	      WHEN consider_grades = 'M'      THEN 3
	      WHEN consider_grades = 'L' THEN 4
	      WHEN consider_grades = 'อื่นๆ'	THEN 5
	      ELSE 6
         END, consider_grades")->get();
                    $data_table = array();
                    foreach ($ck_table_year as $list_table) {
                        $data_table[] = $list_table->id_Autono;
                    }

                    $ids_ordered = implode(',', $data_table);

                    // $control_follow = DB::table('tb4_tisilicense')
                    //     ->select()
                    //     ->where('tbl_licenseStatus', '=','1')
                    //     ->groupBy('tbl_taxpayer')
                    //     ->orderByRaw(DB::raw("FIELD(Autono,$ids_ordered) asc"))
                    //     ->paginate($filter['perPage']);

                    $Query = new Tis4;
                    if ($filter['filter_tb3_Tisno']!='') {
                        $Query = $Query->where('tbl_tisiNo', $filter['filter_tb3_Tisno']);
                    }
                    if ($filter['filter_name']!='') {
                        $Query = $Query->where('tbl_tradeName', 'like', '%' . $filter['filter_name'] . '%');
                    }
                    if ($filter['filter_address']!='') {
                        $Query = $Query->where('tbl_tradeAddress', 'like', '%' . $filter['filter_address'] . '%');
                    }
                    if ($filter['filter_department']!='') {
                        $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                        $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                        $Query = $Query->whereIn('tbl_tisiNo', $tis_subdepartments);
                        $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
                        // dd($subDepartments);
                    }
                    if ($filter['filter_sub_department']!='') {
                        $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                        $Query = $Query->whereIn('tbl_tisiNo', $tis_subdepartments);
                    }

                    $Query = $Query->where('tbl_licenseStatus', '1');

                    $Query = $Query->groupBy('tbl_taxpayer');
                    $Query = $Query->orderByRaw(DB::raw("FIELD(Autono,$ids_ordered) asc"));

                    $control_follow = $Query->sortable()->paginate($filter['perPage']);

                }else{
                    $Query = new Tis4;
                    if ($filter['filter_tb3_Tisno']!='') {
                        $Query = $Query->where('tbl_tisiNo', $filter['filter_tb3_Tisno']);
                    }
                    if ($filter['filter_name']!='') {
                        $Query = $Query->where('tbl_tradeName', 'like', '%' . $filter['filter_name'] . '%');
                    }
                    if ($filter['filter_address']!='') {
                        $Query = $Query->where('tbl_tradeAddress', 'like', '%' . $filter['filter_address'] . '%');
                    }
                    if ($filter['filter_department']!='') {
                        $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                        $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                        $Query = $Query->whereIn('tbl_tisiNo', $tis_subdepartments);
                        $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
                        // dd($subDepartments);
                    }else{
                        $subDepartments = [];
                    }
                    if ($filter['filter_sub_department']!='') {
                        $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                        $Query = $Query->whereIn('tbl_tisiNo', $tis_subdepartments);
                    }

                    $Query = $Query->where('tbl_licenseStatus', '1');

                    $Query = $Query->groupBy('tbl_taxpayer');

                    $control_follow = $Query->sortable()->paginate($filter['perPage']);
                }




                return view('csurv.control_follow.create', ['ck_year' => $ck_year, 'select_year' => $filter['select_year'], 'c_year' => null], compact('control_follow', 'filter' ,'subDepartments'));
            }
        } else {
            $control_follow = null;
            $subDepartments = [];
            return view('csurv.control_follow.create', ['select_year' => null, 'c_year' => null], compact('control_follow', 'filter', 'subDepartments'));
        }

    }

    public function show($id)
    {
        return view('csurv.control_follow.show');
    }

    public function edit($id)
    {
        $filter = [];
        $filter['perPage'] = 100;
        $data = ControlFollow::query()->where('id', $id)->first();
        $Query = new ControlFollowListTable;

        $Query = $Query->where('id_follow', $id);

        $control_follow = $Query->sortable()->paginate(100);

        return view('csurv.control_follow.edit', ['data' => $data, 'select_year' => $data->make_annual, 'c_year' => null, 'control_follow' => $control_follow], compact('filter'));
    }

    public function update(Request $request)
    {
        return redirect('control_follow/control_follow');
    }

    public function save_data(Request $request)
    {
        $data = new ControlFollow([
            'make_annual' => $request->get('make_annual'),
            'check_officer' => $request->get('check_officer'),
        ]);
        if ($data->save()) {
            if ($request->get('num_row') != null) {
                for ($i = 0; $i < count($request->num_row); $i++) {
                    $data_table = new ControlFollowListTable([
                        'id_follow' => $data->id,
                        'operator_name' => $request->operator_name[$i],
                        'address' => $request->address[$i],
                        'month_check' => $request->month_check[$i],
                        'original_grade' => $request->original_grade[$i],
                        'notification' => $request->notification[$i],
                        'system_control_check' => $request->system_control_check[$i],
                        'Product_test_results' => $request->Product_test_results[$i],
                        'follow_check' => $request->follow_check[$i],
                        'control_check' => $request->control_check[$i],
                        'consider_grades' => $request->consider_grades[$i],
                        'id_Autono' => $request->id_Autono[$i],
                    ]);
                    $data_table->save();
                }
            }
            if ($request->status == 'บันทึก') {
                return response()->json([
                    'status' => 'success',
                ]);
            } else {
                return response()->json([
                    'status' => 'excel',
                    'id' => $data->id
                ]);
            }


        }
    }

    public function update_data(Request $request)
    {

        if ($request->status == 'บันทึก') {
            $del_data = ControlFollow::find($request->id);
            $del_data->delete();
            $del_data_sub = ControlFollowListTable::query()->where('id_follow', $request->id)->get();
            foreach ($del_data_sub as $list_del) {
                $del_sub = ControlFollowListTable::find($list_del->id);
                $del_sub->delete();
            }
            $data = new ControlFollow([
                'make_annual' => $request->get('make_annual'),
                'check_officer' => $request->get('check_officer'),
            ]);
            if ($data->save()) {
                if ($request->get('num_row') != null) {
                    for ($i = 0; $i < count($request->num_row); $i++) {
                        $data_table = new ControlFollowListTable([
                            'id_follow' => $data->id,
                            'operator_name' => $request->operator_name[$i],
                            'address' => $request->address[$i],
                            'month_check' => $request->month_check[$i],
                            'original_grade' => $request->original_grade[$i],
                            'notification' => $request->notification[$i],
                            'system_control_check' => $request->system_control_check[$i],
                            'Product_test_results' => $request->Product_test_results[$i],
                            'follow_check' => $request->follow_check[$i],
                            'control_check' => $request->control_check[$i],
                            'consider_grades' => $request->consider_grades[$i],
                            'id_Autono' => $request->id_Autono[$i],
                        ]);
                        $data_table->save();
                    }
                }
            }
            return response()->json([
                'status' => 'success',
            ]);
        } else {
            $data = new ControlFollow([
                'make_annual' => $request->get('make_annual'),
                'check_officer' => $request->get('check_officer'),
            ]);
            if ($data->save()) {
                if ($request->get('num_row') != null) {
                    for ($i = 0; $i < count($request->num_row); $i++) {
                        $data_table = new ControlFollowListTable([
                            'id_follow' => $data->id,
                            'operator_name' => $request->operator_name[$i],
                            'address' => $request->address[$i],
                            'month_check' => $request->month_check[$i],
                            'original_grade' => $request->original_grade[$i],
                            'notification' => $request->notification[$i],
                            'system_control_check' => $request->system_control_check[$i],
                            'Product_test_results' => $request->Product_test_results[$i],
                            'follow_check' => $request->follow_check[$i],
                            'control_check' => $request->control_check[$i],
                            'consider_grades' => $request->consider_grades[$i],
                        ]);
                        $data_table->save();
                    }
                }
            }
            return response()->json([
                'status' => 'excel',
                'id' => $data->id
            ]);
        }
    }

    public function export_excel($id)
    {
        $data = ControlFollow::find($id);
        $data_sub = ControlFollowListTable::query()->where('id_follow', $id)->get();

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet()->mergeCells('C5:C6');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('D5:D6');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('E5:E6');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('F5:F6');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('G5:I5');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('J5:K5');
        $sheet = $spreadsheet->getActiveSheet()->mergeCells('L5:L6');

        $sheet->setCellValue('C3', 'ทำแผนประจำปี: ' . ' ' . $data->make_annual);
        $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(38);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(12);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(36);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(26);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(16);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(18);

        $spreadsheet->getActiveSheet()->getStyle('C5:L5')->getFont()->setBold(true);
        $spreadsheet->getActiveSheet()->getStyle('C6:L6')->getFont()->setBold(true);

        $spreadsheet->getActiveSheet()->getStyle('C5:L5')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0283CC');
        $spreadsheet->getActiveSheet()->getStyle('G6:K6')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('0283CC');

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('C5:L5')->getFont()->getColor()->setARGB('ffffff');
        $sheet->getStyle('G6:K6')->getFont()->getColor()->setARGB('ffffff');

        $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('E5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('F5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('G6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('H6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('I6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('J5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('J6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('K6')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K6')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getStyle('L5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->setCellValue('C5', 'ชื่อผู้ประกอบการ');
        $sheet->setCellValue('D5', 'ที่อยู่');
        $sheet->setCellValue('E5', 'เดือนที่ตรวจ');
        $sheet->setCellValue('F5', 'เกรดเดิม');
        $sheet->setCellValue('G5', 'Self-Declaration');
        $sheet->setCellValue('G6', 'การแจ้งข้อมูล');
        $sheet->setCellValue('H6', 'การตรวจระบบควบคุมคุณภาพ');
        $sheet->setCellValue('I6', 'ผลทดสอบผลิตภัณฑ์');
        $sheet->setCellValue('J5', 'ปีที่ตรวจครั้งล่าสุด');
        $sheet->setCellValue('J6', 'ตรวจติดตาม');
        $sheet->setCellValue('K6', 'ตรวจควบคุม');
        $sheet->setCellValue('L5', 'พิจารณาเกรด');

        $spreadsheet->getActiveSheet()->getStyle('D')->getAlignment()->setWrapText(true);

        $row = 7;
        foreach ($data_sub as $list) {
            $sheet->setCellValue('C' . $row, $list->operator_name);
            $sheet->setCellValue('D' . $row, $list->address);
            $sheet->setCellValue('E' . $row, $list->month_check);
            $sheet->setCellValue('F' . $row, $list->original_grade);
            $sheet->setCellValue('G' . $row, $list->notification);
            $sheet->setCellValue('H' . $row, $list->system_control_check);
            $sheet->setCellValue('I' . $row, $list->Product_test_results);
            $sheet->setCellValue('J' . $row, $list->follow_check);
            $sheet->setCellValue('K' . $row, $list->control_check);
            $sheet->setCellValue('L' . $row, $list->consider_grades);
            $row++;
        }

        $sheet->getStyle('E7:L' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E7:L' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $writer = new Xlsx($spreadsheet);
        ob_start();
        $writer->save('php://output');
        $content = ob_get_contents();
        ob_end_clean();

        $path = 'documents/report_follow/';
        $file = Storage::disk('public')->put($path . date('d_m_y') . '_' . 'ReportFollow' . ".xlsx", $content);
        $del_data = ControlFollow::find($id);
        $del_data->delete();
        $del_data_sub = ControlFollowListTable::query()->where('id_follow', $id)->get();
        foreach ($del_data_sub as $list_del) {
            $del_sub = ControlFollowListTable::find($list_del->id);
            $del_sub->delete();
        }
        return Storage::disk('public')->download($path . date('d_m_y') . '_' . 'ReportFollow' . ".xlsx");
    }

    public function del_data($id)
    {
        $del_data = ControlFollow::find($id);
        $del_data->delete();
        $del_data_sub = ControlFollowListTable::query()->where('id_follow', $id)->get();
        foreach ($del_data_sub as $list_del) {
            $del_sub = ControlFollowListTable::find($list_del->id);
            $del_sub->delete();
        }
        $filter = [];
        $filter['perPage'] = 10;

        $Query = new ControlFollow;

        $control_follow = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $control_follow->firstItem();

        return view('csurv.control_follow.index', compact('control_follow', 'filter', 'temp_num'));
    }
}
