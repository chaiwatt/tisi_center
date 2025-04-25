<?php

namespace App\Http\Controllers\Rsurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\Esurv\ReceiveVolume as receive_volume;
use App\Models\Esurv\ReceiveVolume;
use App\Models\Esurv\ReceiveVolumeLicense;
use App\Models\Esurv\ReceiveVolumeLicenseDetail;
use App\report_volume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

use App\Models\Sso\User AS SSO_User;

class ReportVolumeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index_(Request $request)
    {
        ini_set('memory_limit','750M');
        ini_set('max_execution_time','3600');

        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new ReceiveVolumeLicenseDetail;

            $Query = $Query->leftJoin('esurv_inform_volume_licenses','esurv_inform_volume_licenses.id','=','esurv_inform_volume_license_details.inform_volume_license_id')
                    ->leftJoin('esurv_inform_volumes','esurv_inform_volumes.id','=','esurv_inform_volume_licenses.inform_volume_id')
                    ->whereIn('esurv_inform_volumes.state', 2);

            if ($filter['filter_tb3_Tisno']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('esurv_inform_volumes.tb3_Tisno', 'LIKE', "%{$filter['filter_tb3_Tisno']}%");
                });
            }

            if ($filter['filter_created_by']!='') {
                $Query = $Query->where('esurv_inform_volumes.created_by', $filter['filter_created_by']);
            }

            if ($filter['filter_start_month']!='' && $filter['filter_start_year']!='') {
                $year_month = $filter['filter_start_year'].'-'.$filter['filter_start_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) >= '$year_month'");
                });
            }

            if ($filter['filter_end_month']!='' && $filter['filter_end_year']!='') {
                $year_month = $filter['filter_end_year'].'-'.$filter['filter_end_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) <= '$year_month'");
                });
            }

            $report_volume = $Query->sortable()->paginate($filter['perPage']);
            $total_page = $report_volume->total();
            $temp_num = $report_volume->firstItem();

            return view('rsurv.report_volume.index',(['detail'=>$filter['filter_elicense_detail']]), compact('report_volume', 'filter', 'temp_num', 'total_page'));

        }
        abort(403);

    }

     public function index(Request $request)
    {
        ini_set('memory_limit','750M');
        ini_set('max_execution_time','3600');

        $user = auth()->user();
        $model = str_slug('report_volume','-');

        if($user->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = $request->get('perPage', '');
            $filter['sort'] = $request->get('sort','esurv_inform_volumes.id desc');

            $Query = new receive_volume;

            $sub_query = "SELECT SUM(volume1) as sum_volume1, SUM(volume2) as sum_volume2, SUM(volume3) as sum_volume3, inform_volume_license_id  FROM esurv_inform_volume_license_details GROUP BY inform_volume_license_id";
            $Query = $Query->leftJoin('esurv_inform_volume_licenses AS l','l.inform_volume_id','=','esurv_inform_volumes.id')
                    ->leftJoin(DB::Raw("($sub_query) as ld"), 'ld.inform_volume_license_id', '=', 'l.id')
                    ->where('esurv_inform_volumes.state', '2')
                    ->orderBy('esurv_inform_volumes.id', 'DESC');


            if ($filter['filter_tb3_Tisno']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('tb3_Tisno', 'LIKE', "%{$filter['filter_tb3_Tisno']}%");
                });
            }

            if ($filter['filter_created_by']!='') {
                $Query = $Query->where('created_by', $filter['filter_created_by']);
            }

            if ($filter['filter_start_month']!='' && $filter['filter_start_year']!='') {
                $year_month = $filter['filter_start_year'].'-'.$filter['filter_start_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) >= '$year_month'");
                });
            }

            if ($filter['filter_end_month']!='' && $filter['filter_end_year']!='') {
                $year_month = $filter['filter_end_year'].'-'.$filter['filter_end_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) <= '$year_month'");
                });
            }

            //Query
            $report_volume = $Query->sortable()->paginate($filter['perPage']);
            $total_page = $report_volume->total();
            $temp_num = $report_volume->firstItem();
            //สิทธิ์การตรวจตามกลุ่มงานย่อย
            $user_tis = $user->tis->pluck('tb3_Tisno');

            return view('rsurv.report_volume.index',(['detail'=>$filter['filter_elicense_detail']]), compact('report_volume', 'filter', 'user_tis', 'subDepartments', 'temp_num', 'total_page'));
        }

        abort(403);

    }



     public function index_old(Request $request)
    {
        ini_set('memory_limit','750M');
        ini_set('max_execution_time','3600');

        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new ReceiveVolumeLicenseDetail;
            // $tis_filter_state = receive_volume::select()->where('state', 2)->pluck('id');
            // $tis_no_filter_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id', $tis_filter_state)->pluck('id');
            // $Query = $Query->wherein('inform_volume_license_id', $tis_no_filter_id);

            $Query = $Query->LeftJoin('esurv_inform_volume_licenses','esurv_inform_volume_licenses.id','=','esurv_inform_volume_license_details.inform_volume_license_id')
                            ->LeftJoin('esurv_inform_volumes','esurv_inform_volumes.id','=','esurv_inform_volume_licenses.inform_volume_id')
                            ->where('state', 2);

            if ($filter['filter_tb3_Tisno']!='') {
                $tis_no_check = DB::table('esurv_inform_volumes')->select()->where('tb3_Tisno',$filter['filter_tb3_Tisno'])->where('state',2)->first();
                if ($tis_no_check==null){
                    $Query = $Query->where('esurv_inform_volume_license_details.id', 0);
                }else{
                    $tis_no = receive_volume::select()->where('tb3_Tisno',$filter['filter_tb3_Tisno'])->where('state',2)->pluck('id');
                    $tis_no_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id',$tis_no)->pluck('id');
                    $Query = $Query->wherein('inform_volume_license_id', $tis_no_id);
                }
            }
            if ($filter['filter_created_by']!='') {
                $created_by_check = DB::table('esurv_inform_volumes')->select()->where('created_by',$filter['filter_created_by'])->where('state',2)->first();
                if ($created_by_check==null){
                    $Query = $Query->where('esurv_inform_volume_license_details.id', 0);
                }else{
                    $created_by = receive_volume::select()->where('created_by',$filter['filter_created_by'])->where('state',2)->pluck('id');
                    $created_by_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id',$created_by)->pluck('id');
                    $Query = $Query->wherein('inform_volume_license_id', $created_by_id);
                }
            }

            $date_main = new ReceiveVolume;
            $date_main = $date_main->where('state',2);

            if ($filter['filter_start_month']!='') {
                $date_main = $date_main->where('inform_month', '>=', $filter['filter_start_month']);
            }
            if ($filter['filter_start_year']!='') {
                $date_main = $date_main->where('inform_year', '>=', $filter['filter_start_year']);
            }
            if ($filter['filter_end_month']!='') {
                $date_main = $date_main->where('inform_month', '<=', $filter['filter_end_month']);
            }
            if ($filter['filter_end_year']!='') {
                $date_main = $date_main->where('inform_year', '<=', $filter['filter_end_year']);
            }
            if ($filter['filter_start_month']!='') {
                $data_main = $date_main->sortable()->pluck('id');
                $data_main_check = $date_main->sortable()->first();
                if ($data_main_check!=null){
                    foreach ($data_main as $list_date){
                        $start_year_id[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->pluck('id');
                        $check_id[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->first();
                        $check_get[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->get();
                    }
                    $i=0;
                    foreach ($check_get as $list_start_year){
                        if ($check_id[$i]!=null){
                            for ($j=0;$j<count($check_get);$j++){
                                if ($check_id[$j]!=null) {
                                    if (isset($list_start_year[$j]->id)){
                                        $test[] = $list_start_year[$j]->id;
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                    $Query = $Query->wherein('inform_volume_license_id', $test);
                }else{
                    $Query = $Query->where('esurv_inform_volume_license_details.id', 0);
                }
            }

            if ($filter['filter_elicense_detail']!='')
            {
                $data = explode(',',$filter['filter_elicense_detail']);
                foreach ($data as $list_data){
                    $e_detail[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail','like','%'.$list_data.'%')
                        ->pluck('a.id');
                    $check[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail','like','%'.$list_data.'%')
                        ->first('a.id');
                }
                $temp_check = 0;
                foreach ($check as $check_error){
                    if ($check_error==null){
                        $temp_check +=1;
                    }
                }
                if ($temp_check>0){
                    $Query = $Query->where('elicense_detail_id', 0);
                }else{
                    foreach ($e_detail as $list_e_detail){
                        $Query = $Query->wherein('elicense_detail_id', $list_e_detail);
                    }
                }
            }

            // dd($Query->total());
            $report_volume = $Query->sortable()->paginate($filter['perPage']);
            $total_page = $report_volume->total();
            $temp_num = $report_volume->firstItem();

            return view('rsurv.report_volume.index',(['detail'=>$filter['filter_elicense_detail']]), compact('report_volume', 'filter', 'temp_num', 'total_page'));

        }
        abort(403);

    }

    public function export_excel(Request $request)
    {
        ini_set('memory_limit','4096M');
        ini_set('max_execution_time','3600');

        $model = str_slug('receive_volume', '-');
        if (auth()->user()->can('view-' . $model)) {

            $total_page = $request->get('total_page');

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['sort'] = $request->get('sort','esurv_inform_volumes.id desc');
            $filter['perPage'] = !empty($total_page)?$total_page:$request->get('perPage', '');

            $Query = new receive_volume;

            // $sub_query = "SELECT SUM(volume1) as sum_volume1, SUM(volume2) as sum_volume2, SUM(volume3) as sum_volume3, inform_volume_license_id  FROM esurv_inform_volume_license_details GROUP BY inform_volume_license_id";
            $Query = $Query->leftJoin('esurv_inform_volume_licenses AS l','l.inform_volume_id','=','esurv_inform_volumes.id')
                    ->leftJoin('esurv_inform_volume_license_details as ld', 'ld.inform_volume_license_id', '=', 'l.id')
                    ->where('esurv_inform_volumes.state', '2')
                    ->orderBy('esurv_inform_volumes.id', 'DESC');

            if ($filter['filter_tb3_Tisno'] != '') {
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('tb3_Tisno', 'LIKE', "%{$filter['filter_tb3_Tisno']}%");
                });
                $t_name = DB::table('tb3_tis')->select()->where('tb3_Tisno', $filter['filter_tb3_Tisno'])->first(['tb3_TisThainame']);
                $tis_name = $filter['filter_tb3_Tisno'] . ' (' . $t_name->tb3_TisThainame . ')';
            } else {
                $tis_name = '-';
            }

            if ($filter['filter_created_by'] != '') {
                $Query = $Query->where('created_by', $filter['filter_created_by']);
                $name_create = SSO_User::where('id', $filter['filter_created_by'])->value('name');
            } else {
                $filter['filter_created_by'] = '-';
                $name_create = '-';
            }

            if ($filter['filter_start_month'] != '' && $filter['filter_start_year'] != '') {

                $year_month = $filter['filter_start_year'].'-'.$filter['filter_start_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) >= '$year_month'");
                });

                $month_start = \HP::MonthShortConvertList($filter['filter_start_month']);
                $year_start = $filter['filter_start_year'] + 543;

            } else {
                $month_start = '-';
                $year_start = '';
            }

            if ($filter['filter_end_month']!='' && $filter['filter_end_year']!='') {

                $year_month = $filter['filter_end_year'].'-'.$filter['filter_end_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) <= '$year_month'");
                });

                $month_end = \HP::MonthShortConvertList($filter['filter_end_month']);
                $year_end = $filter['filter_end_year'] + 543;
            } else {
                $month_end = '-';
                $year_end = '';
            }

            if ($filter['filter_elicense_detail'] != '') {
                $data = explode(',', $filter['filter_elicense_detail']);
                foreach ($data as $list_data) {
                    $e_detail[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->pluck('a.id');
                    $check[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->first('a.id');
                }
                $temp_check = 0;
                foreach ($check as $check_error) {
                    if ($check_error == null) {
                        $temp_check += 1;
                    }
                }
                if ($temp_check > 0) {
                    $Query = $Query->where('elicense_detail_id', 0);
                } else {
                    foreach ($e_detail as $list_e_detail) {
                        $Query = $Query->wherein('elicense_detail_id', $list_e_detail);
                    }
                }
                $elicese_detail = $filter['filter_elicense_detail'];
            } else {
                $elicese_detail = '-';
            }

            $report_volume_query = $Query->sortable()->paginate($filter['perPage']);

            $totalRecords = $report_volume_query->count();
            // dd($totalRecords);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $sheet->setCellValue('A1', 'รายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'ตามเงื่อนไข ผู้ประกอบการ : ' . $name_create . '     มอก : ' . $tis_name . '      วันที่ผลิต : ' . $month_start . ' ' . $year_start . ' ถึง ' . $month_end . ' ' . $year_end . '     รายละเอียดผลิตภัณฑ์ : ' . $elicese_detail);
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N2:O2');
            $sheet->setCellValue('N2', 'ข้อมูล ณ วันที่ ' . \HP::DateTimeFullThai(date('Y-m-d H:i:s')));
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A3:A4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('B3:B4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('C3:C4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('D3:D4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('E3:E4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('F3:F4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('G3:G4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('H3:H4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('I3:I4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('J3:J4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('K3:K4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('L3:L4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('M3:M4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N3:N4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('O3:O4');

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);

            $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('CCE2F8');

            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue('A3', 'ผู้ประกอบการ');
            $sheet->setCellValue('B3', 'เลข มอก.');
            $sheet->setCellValue('C3', 'ชื่อ มอก.');
            $sheet->setCellValue('D3', 'เลขที่ใบอนุญาต');
            $sheet->setCellValue('E3', 'วันที่แจ้ง');
            $sheet->setCellValue('F3', 'เดือนที่ยื่น');
            $sheet->setCellValue('G3', 'ปีที่ยื่น');
            $sheet->setCellValue('H3', 'จำนวนผลิต(แสดง)');
            $sheet->setCellValue('I3', 'จำนวนผลิต(ไม่แสดง)');
            $sheet->setCellValue('J3', 'รวม(จำนวนผลิต)');
            $sheet->setCellValue('K3', 'หน่วย');
            $sheet->setCellValue('L3', 'ผู้บันทึก');
            $sheet->setCellValue('M3', 'เบอร์โทร');
            $sheet->setCellValue('N3', 'E-Mail');
            $sheet->setCellValue('O3', 'เจ้าหน้าที่รับเรื่อง');
            $row = 5;
            $total_volume1 = 0;
            $total_volume3 = 0;
            $total_sum = 0;

            $Query->chunk(3000, function($report_volume) use($sheet, &$row, $total_volume1, $total_volume3, $total_sum) {

                foreach ($report_volume as $list) {
                    // echo ("Writing User to row " . $row . "/" . $totalRecords);
                    $sheet->setCellValue('A' . $row, $list->CreatedName.' '.$list->TraderIdName);
                    $sheet->setCellValue('B' . $row, @$list->tis->tb3_Tisno);
                    $sheet->setCellValue('C' . $row, @$list->tis->tb3_TisThainame);
                    $sheet->setCellValue('D' . $row, $list->tbl_licenseNo);
                    $sheet->setCellValue('E' . $row, \HP::DateThai($list->created_at));
                    $sheet->setCellValue('F' . $row, \HP::MonthList()[$list->inform_month]);
                    $sheet->setCellValue('G' . $row, $list->inform_year+543);
                    if ($list->volume1 != null) {
                        $sheet->setCellValue('H' . $row, $list->volume1);
                    } elseif ($list->volume2 != null) {
                        $sheet->setCellValue('H' . $row, $list->volume2);
                    }
                    $sheet->setCellValue('I' . $row, $list->volume3);
                    if ($list->volume1 != null) {
                        $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume1, $list->volume3));
                    } elseif ($list->volume2 != null) {
                        $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume2, $list->volume3));
                    }
                    $sheet->setCellValue('K' . $row, \HP::get_unitcode_for_report_volume($list->unit));
                    $sheet->setCellValue('L' . $row, $list->applicant_name);
                    $sheet->setCellValue('M' . $row, $list->tel);
                    $sheet->setCellValue('N' . $row, $list->email);
                    $sheet->setCellValue('O' . $row, \HP::get_consider_name($list->consider));

                    if ($list->volume1 != null) {
                        $total_volume1 += (int) $list->volume1;
                    } elseif ($list->volume2 != null) {
                        $total_volume1 += (int) $list->volume2;
                    }
                    $total_volume3 += (int) $list->volume3;
                    if ($list->volume1 != null) {
                        $total_sum += (int) \HP::get_sum_row_volume($list->volume1, $list->volume3);
                    } elseif ($list->volume2 != null) {
                        $total_sum += (int) \HP::get_sum_row_volume($list->volume2, $list->volume3);
                    }
                    $row++;
                }

            });


            $row_table = $row - 1;
            if ($row != 5) {
                $sheet->getStyle('A3:A' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A3:O3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:O4')->applyFromArray($styleArray);
                $sheet->getStyle('B3:B' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('C3:C' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('D3:D' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('E3:E' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('F3:F' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('G3:G' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('H3:H' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('I3:I' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('J3:J' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('K3:K' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('L3:L' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('M3:M' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('N3:N' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('O3:O' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A' . $row . ':' . 'K' . $row)->applyFromArray($styleArray);
                $sheet = $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':' . 'G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'รวมปริมาณการผลิต');
                $sheet->setCellValue('H' . $row, $total_volume1);
                $sheet->setCellValue('I' . $row, $total_volume3);
                $sheet->setCellValue('J' . $row, $total_sum);
                $sheet->setCellValue('K' . $row, '');

                $sheet->getStyle('D5:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5:G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('K5:K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K5:K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('H5:H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H5:H' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('I5:I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I5:I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('J5:J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J5:J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            $writer = new Xlsx($spreadsheet);
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            $path = 'documents/report/';
            $file = Storage::disk('public')->put($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx", $content);
            return Storage::disk('public')->download($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx");
        }
    }

    public function export_excel_backup2(Request $request)
    {
        ini_set('memory_limit','4096M');
        ini_set('max_execution_time','3600');

        $model = str_slug('receive_volume', '-');
        if (auth()->user()->can('view-' . $model)) {

            $total_page = $request->get('total_page');

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = !empty($total_page)?$total_page:$request->get('perPage', '');

            $Query = new ReceiveVolumeLicenseDetail;
            $Query = $Query->leftJoin('esurv_inform_volume_licenses','esurv_inform_volume_licenses.id','=','esurv_inform_volume_license_details.inform_volume_license_id')
                    ->leftJoin('esurv_inform_volumes','esurv_inform_volumes.id','=','esurv_inform_volume_licenses.inform_volume_id')
                    ->where('esurv_inform_volumes.state', 2);

            if ($filter['filter_tb3_Tisno'] != '') {
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('esurv_inform_volumes.tb3_Tisno', 'LIKE', "%{$filter['filter_tb3_Tisno']}%");
                });
                $t_name = DB::table('tb3_tis')->select()->where('tb3_Tisno', $filter['filter_tb3_Tisno'])->first(['tb3_TisThainame']);
                $tis_name = $filter['filter_tb3_Tisno'] . ' (' . $t_name->tb3_TisThainame . ')';
            } else {
                $tis_name = '-';
            }

            if ($filter['filter_created_by'] != '') {
                $Query = $Query->where('esurv_inform_volumes.created_by', $filter['filter_created_by']);
                $name_create = SSO_User::where('id', $filter['filter_created_by'])->value('name');
            } else {
                $filter['filter_created_by'] = '-';
                $name_create = '-';
            }

            if ($filter['filter_start_month'] != '' && $filter['filter_start_year'] != '') {

                $year_month = $filter['filter_start_year'].'-'.$filter['filter_start_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) >= '$year_month'");
                });

                $month_start = \HP::MonthShortConvertList($filter['filter_start_month']);
                $year_start = $filter['filter_start_year'] + 543;

            } else {
                $month_start = '-';
                $year_start = '';
            }

            if ($filter['filter_end_month']!='' && $filter['filter_end_year']!='') {

                $year_month = $filter['filter_end_year'].'-'.$filter['filter_end_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) <= '$year_month'");
                });

                $month_end = \HP::MonthShortConvertList($filter['filter_end_month']);
                $year_end = $filter['filter_end_year'] + 543;
            } else {
                $month_end = '-';
                $year_end = '';
            }

            if ($filter['filter_elicense_detail'] != '') {
                $data = explode(',', $filter['filter_elicense_detail']);
                foreach ($data as $list_data) {
                    $e_detail[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->pluck('a.id');
                    $check[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->first('a.id');
                }
                $temp_check = 0;
                foreach ($check as $check_error) {
                    if ($check_error == null) {
                        $temp_check += 1;
                    }
                }
                if ($temp_check > 0) {
                    $Query = $Query->where('elicense_detail_id', 0);
                } else {
                    foreach ($e_detail as $list_e_detail) {
                        $Query = $Query->wherein('elicense_detail_id', $list_e_detail);
                    }
                }
                $elicese_detail = $filter['filter_elicense_detail'];
            } else {
                $elicese_detail = '-';
            }

            $report_volume_query = $Query->sortable()->paginate($filter['perPage']);

            $totalRecords = $report_volume_query->count();
            // dd($totalRecords);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $sheet->setCellValue('A1', 'รายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'ตามเงื่อนไข ผู้ประกอบการ : ' . $name_create . '     มอก : ' . $tis_name . '      วันที่ผลิต : ' . $month_start . ' ' . $year_start . ' ถึง ' . $month_end . ' ' . $year_end . '     รายละเอียดผลิตภัณฑ์ : ' . $elicese_detail);
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N2:O2');
            $sheet->setCellValue('N2', 'ข้อมูล ณ วันที่ ' . \HP::DateTimeFullThai(date('Y-m-d H:i:s')));
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A3:A4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('B3:B4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('C3:C4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('D3:D4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('E3:E4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('F3:F4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('G3:G4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('H3:H4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('I3:I4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('J3:J4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('K3:K4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('L3:L4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('M3:M4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N3:N4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('O3:O4');

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);

            $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('CCE2F8');

            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue('A3', 'ผู้ประกอบการ');
            $sheet->setCellValue('B3', 'เลข มอก.');
            $sheet->setCellValue('C3', 'ชื่อ มอก.');
            $sheet->setCellValue('D3', 'เลขที่ใบอนุญาต');
            $sheet->setCellValue('E3', 'วันที่แจ้ง');
            $sheet->setCellValue('F3', 'เดือนที่ยื่น');
            $sheet->setCellValue('G3', 'ปีที่ยื่น');
            $sheet->setCellValue('H3', 'จำนวนผลิต(แสดง)');
            $sheet->setCellValue('I3', 'จำนวนผลิต(ไม่แสดง)');
            $sheet->setCellValue('J3', 'รวม(จำนวนผลิต)');
            $sheet->setCellValue('K3', 'หน่วย');
            $sheet->setCellValue('L3', 'ผู้บันทึก');
            $sheet->setCellValue('M3', 'เบอร์โทร');
            $sheet->setCellValue('N3', 'E-Mail');
            $sheet->setCellValue('O3', 'เจ้าหน้าที่รับเรื่อง');
            $row = 5;
            $total_volume1 = 0;
            $total_volume3 = 0;
            $total_sum = 0;

            $Query->chunk(3000, function($report_volume) use($sheet, &$row, $total_volume1, $total_volume3, $total_sum) {

                foreach ($report_volume as $list) {
                    // echo ("Writing User to row " . $row . "/" . $totalRecords);
                    $sheet->setCellValue('A' . $row,  \HP::get_Create_name_trader($list->inform_volume_license_id));
                    $sheet->setCellValue('B' . $row, \HP::get_tb3_Tisno($list->inform_volume_license_id));
                    $sheet->setCellValue('C' . $row, \HP::get_tb3_TisThainame($list->inform_volume_license_id));
                    $sheet->setCellValue('D' . $row, $list->LicenseNo);
                    $sheet->setCellValue('E' . $row, \HP::DateThai(\HP::get_created_at($list->inform_volume_license_id)));
                    $sheet->setCellValue('F' . $row, \HP::MonthConvertList(\HP::get_inform_month($list->inform_volume_license_id)));
                    $sheet->setCellValue('G' . $row, \HP::get_inform_year($list->inform_volume_license_id) + 543);
                    if ($list->volume1 != null) {
                        $sheet->setCellValue('H' . $row, $list->volume1);
                    } elseif ($list->volume2 != null) {
                        $sheet->setCellValue('H' . $row, $list->volume2);
                    }
                    $sheet->setCellValue('I' . $row, $list->volume3);
                    if ($list->volume1 != null) {
                        $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume1, $list->volume3));
                    } elseif ($list->volume2 != null) {
                        $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume2, $list->volume3));
                    }
                    $sheet->setCellValue('K' . $row, \HP::get_unit_for_report_volume($list->unit));
                    $sheet->setCellValue('L' . $row, \HP::get_applicant_name($list->inform_volume_license_id));
                    $sheet->setCellValue('M' . $row, \HP::get_tel($list->inform_volume_license_id));
                    $sheet->setCellValue('N' . $row, \HP::get_email($list->inform_volume_license_id));
                    $sheet->setCellValue('O' . $row, \HP::get_consider_name(\HP::get_consider($list->inform_volume_license_id)));

                    if ($list->volume1 != null) {
                        $total_volume1 += (int) $list->volume1;
                    } elseif ($list->volume2 != null) {
                        $total_volume1 += (int) $list->volume2;
                    }
                    $total_volume3 += (int) $list->volume3;
                    if ($list->volume1 != null) {
                        $total_sum += (int) \HP::get_sum_row_volume($list->volume1, $list->volume3);
                    } elseif ($list->volume2 != null) {
                        $total_sum += (int) \HP::get_sum_row_volume($list->volume2, $list->volume3);
                    }
                    $row++;
                }

            });


            $row_table = $row - 1;
            if ($row != 5) {
                $sheet->getStyle('A3:A' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A3:O3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:O4')->applyFromArray($styleArray);
                $sheet->getStyle('B3:B' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('C3:C' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('D3:D' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('E3:E' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('F3:F' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('G3:G' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('H3:H' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('I3:I' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('J3:J' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('K3:K' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('L3:L' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('M3:M' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('N3:N' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('O3:O' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A' . $row . ':' . 'K' . $row)->applyFromArray($styleArray);
                $sheet = $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':' . 'G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'รวมปริมาณการผลิต');
                $sheet->setCellValue('H' . $row, $total_volume1);
                $sheet->setCellValue('I' . $row, $total_volume3);
                $sheet->setCellValue('J' . $row, $total_sum);
                $sheet->setCellValue('K' . $row, '');

                $sheet->getStyle('D5:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5:G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('K5:K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K5:K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('H5:H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H5:H' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('I5:I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I5:I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('J5:J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J5:J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            $writer = new Xlsx($spreadsheet);
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            $path = 'documents/report/';
            $file = Storage::disk('public')->put($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx", $content);
            return Storage::disk('public')->download($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx");
        }
    }

    public function export_excel_backup(Request $request)
    {
        ini_set('memory_limit','2048M');
        ini_set('max_execution_time','3600');

        $model = str_slug('receive_volume', '-');
        if (auth()->user()->can('view-' . $model)) {

            $total_page = $request->get('total_page');

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = !empty($total_page)?$total_page:$request->get('perPage', '');

            $Query = new ReceiveVolumeLicenseDetail;
            $Query = $Query->leftJoin('esurv_inform_volume_licenses','esurv_inform_volume_licenses.id','=','esurv_inform_volume_license_details.inform_volume_license_id')
                    ->leftJoin('esurv_inform_volumes','esurv_inform_volumes.id','=','esurv_inform_volume_licenses.inform_volume_id')
                    ->where('esurv_inform_volumes.state', 2);

            if ($filter['filter_tb3_Tisno'] != '') {
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->where('esurv_inform_volumes.tb3_Tisno', 'LIKE', "%{$filter['filter_tb3_Tisno']}%");
                });
                $t_name = DB::table('tb3_tis')->select()->where('tb3_Tisno', $filter['filter_tb3_Tisno'])->first(['tb3_TisThainame']);
                $tis_name = $filter['filter_tb3_Tisno'] . ' (' . $t_name->tb3_TisThainame . ')';
            } else {
                $tis_name = '-';
            }

            if ($filter['filter_created_by'] != '') {
                $Query = $Query->where('esurv_inform_volumes.created_by', $filter['filter_created_by']);
                $name_create = SSO_User::where('id', $filter['filter_created_by'])->value('name');
            } else {
                $filter['filter_created_by'] = '-';
                $name_create = '-';
            }

            if ($filter['filter_start_month'] != '' && $filter['filter_start_year'] != '') {

                $year_month = $filter['filter_start_year'].'-'.$filter['filter_start_month'];
                $Query = $Query->where(function ($query) use ($year_month) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) >= '$year_month'");
                });

                $month_start = \HP::MonthShortConvertList($filter['filter_start_month']);
                $year_start = $filter['filter_start_year'] + 543;

            } else {
                $month_start = '-';
                $year_start = '';
            }

            if ($filter['filter_end_month']!='' && $filter['filter_end_year']!='') {

                $year_month = $filter['filter_end_year'].'-'.$filter['filter_end_month'];
                $Query = $Query->where(function ($query) use ($filter) {
                        $query->whereRaw("CONCAT(inform_year,'-',inform_month) <= '$year_month'");
                });

                $month_end = \HP::MonthShortConvertList($filter['filter_end_month']);
                $year_end = $filter['filter_end_year'] + 543;
            } else {
                $month_end = '-';
                $year_end = '';
            }

            if ($filter['filter_elicense_detail'] != '') {
                $data = explode(',', $filter['filter_elicense_detail']);
                foreach ($data as $list_data) {
                    $e_detail[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->pluck('a.id');
                    $check[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail', 'like', '%' . $list_data . '%')
                        ->first('a.id');
                }
                $temp_check = 0;
                foreach ($check as $check_error) {
                    if ($check_error == null) {
                        $temp_check += 1;
                    }
                }
                if ($temp_check > 0) {
                    $Query = $Query->where('elicense_detail_id', 0);
                } else {
                    foreach ($e_detail as $list_e_detail) {
                        $Query = $Query->wherein('elicense_detail_id', $list_e_detail);
                    }
                }
                $elicese_detail = $filter['filter_elicense_detail'];
            } else {
                $elicese_detail = '-';
            }

            $report_volume = $Query->sortable()->paginate($filter['perPage']);
            // dd($report_volume);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $sheet->setCellValue('A1', 'รายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'ตามเงื่อนไข ผู้ประกอบการ : ' . $name_create . '     มอก : ' . $tis_name . '      วันที่ผลิต : ' . $month_start . ' ' . $year_start . ' ถึง ' . $month_end . ' ' . $year_end . '     รายละเอียดผลิตภัณฑ์ : ' . $elicese_detail);
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N2:O2');
            $sheet->setCellValue('N2', 'ข้อมูล ณ วันที่ ' . \HP::DateTimeFullThai(date('Y-m-d H:i:s')));
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A3:A4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('B3:B4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('C3:C4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('D3:D4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('E3:E4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('F3:F4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('G3:G4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('H3:H4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('I3:I4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('J3:J4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('K3:K4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('L3:L4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('M3:M4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N3:N4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('O3:O4');

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);

            $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('CCE2F8');

            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue('A3', 'ผู้ประกอบการ');
            $sheet->setCellValue('B3', 'เลข มอก.');
            $sheet->setCellValue('C3', 'ชื่อ มอก.');
            $sheet->setCellValue('D3', 'เลขที่ใบอนุญาต');
            $sheet->setCellValue('E3', 'วันที่แจ้ง');
            $sheet->setCellValue('F3', 'เดือนที่ยื่น');
            $sheet->setCellValue('G3', 'ปีที่ยื่น');
            $sheet->setCellValue('H3', 'จำนวนผลิต(แสดง)');
            $sheet->setCellValue('I3', 'จำนวนผลิต(ไม่แสดง)');
            $sheet->setCellValue('J3', 'รวม(จำนวนผลิต)');
            $sheet->setCellValue('K3', 'หน่วย');
            $sheet->setCellValue('L3', 'ผู้บันทึก');
            $sheet->setCellValue('M3', 'เบอร์โทร');
            $sheet->setCellValue('N3', 'E-Mail');
            $sheet->setCellValue('O3', 'เจ้าหน้าที่รับเรื่อง');
            $row = 5;
            $total_volume1 = 0;
            $total_volume3 = 0;
            $total_sum = 0;
            foreach ($report_volume as $list) {
                $sheet->setCellValue('A' . $row, \HP::get_Create_name($list->inform_volume_license_id));
                $sheet->setCellValue('B' . $row, \HP::get_tb3_Tisno($list->inform_volume_license_id));
                $sheet->setCellValue('C' . $row, \HP::get_tb3_TisThainame($list->inform_volume_license_id));
                $sheet->setCellValue('D' . $row, $list->LicenseNo);
                $sheet->setCellValue('E' . $row, \HP::DateThai(\HP::get_created_at($list->inform_volume_license_id)));
                $sheet->setCellValue('F' . $row, \HP::MonthConvertList(\HP::get_inform_month($list->inform_volume_license_id)));
                $sheet->setCellValue('G' . $row, \HP::get_inform_year($list->inform_volume_license_id) + 543);
                if ($list->volume1 != null) {
                    $sheet->setCellValue('H' . $row, $list->volume1);
                } elseif ($list->volume2 != null) {
                    $sheet->setCellValue('H' . $row, $list->volume2);
                }
                $sheet->setCellValue('I' . $row, $list->volume3);
                if ($list->volume1 != null) {
                    $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume1, $list->volume3));
                } elseif ($list->volume2 != null) {
                    $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume2, $list->volume3));
                }
                $sheet->setCellValue('K' . $row, \HP::get_unit_for_report_volume($list->unit));
                $sheet->setCellValue('L' . $row, \HP::get_applicant_name($list->inform_volume_license_id));
                $sheet->setCellValue('M' . $row, \HP::get_tel($list->inform_volume_license_id));
                $sheet->setCellValue('N' . $row, \HP::get_email($list->inform_volume_license_id));
                $sheet->setCellValue('O' . $row, \HP::get_consider_name(\HP::get_consider($list->inform_volume_license_id)));

                if ($list->volume1 != null) {
                    $total_volume1 += (int) $list->volume1;
                } elseif ($list->volume2 != null) {
                    $total_volume1 += (int) $list->volume2;
                }
                $total_volume3 += (int) $list->volume3;
                if ($list->volume1 != null) {
                    $total_sum += (int) \HP::get_sum_row_volume($list->volume1, $list->volume3);
                } elseif ($list->volume2 != null) {
                    $total_sum += (int) \HP::get_sum_row_volume($list->volume2, $list->volume3);
                }
                $row++;
            }
            $row_table = $row - 1;
            if ($row != 5) {
                $sheet->getStyle('A3:A' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A3:O3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:O4')->applyFromArray($styleArray);
                $sheet->getStyle('B3:B' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('C3:C' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('D3:D' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('E3:E' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('F3:F' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('G3:G' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('H3:H' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('I3:I' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('J3:J' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('K3:K' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('L3:L' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('M3:M' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('N3:N' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('O3:O' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A' . $row . ':' . 'K' . $row)->applyFromArray($styleArray);
                $sheet = $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':' . 'G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'รวมปริมาณการผลิต');
                $sheet->setCellValue('H' . $row, $total_volume1);
                $sheet->setCellValue('I' . $row, $total_volume3);
                $sheet->setCellValue('J' . $row, $total_sum);
                $sheet->setCellValue('K' . $row, '');

                $sheet->getStyle('D5:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5:G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('K5:K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K5:K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('H5:H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H5:H' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('I5:I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I5:I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('J5:J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J5:J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            $writer = new Xlsx($spreadsheet);
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            $path = 'documents/report/';
            $file = Storage::disk('public')->put($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx", $content);
            return Storage::disk('public')->download($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx");
        }
    }

    public function export_excel_old(Request $request)
    {
        $model = str_slug('receive_volume','-');
        if(auth()->user()->can('view-'.$model)) {
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_elicense_detail'] = $request->get('filter_elicense_detail', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new ReceiveVolumeLicenseDetail;
            $tis_filter_state = receive_volume::select()->where('state', 2)->pluck('id');
            $tis_no_filter_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id', $tis_filter_state)->pluck('id');
            $Query = $Query->wherein('inform_volume_license_id', $tis_no_filter_id);

            if ($filter['filter_created_by']!='') {
                $created_by_check = DB::table('esurv_inform_volumes')->select()->where('created_by',$filter['filter_created_by'])->where('state', 2)->first();
                $q_name = SSO_User::where('id', $filter['filter_created_by'])->value('name');
                if ($created_by_check==null){
                    $Query = $Query->where('id', 0);
                    $name_create = $q_name;
                }else{
                    $created_by = receive_volume::select()->where('created_by',$filter['filter_created_by'])->where('state', 2)->pluck('id');
                    $created_by_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id',$created_by)->pluck('id');
                    $Query = $Query->wherein('inform_volume_license_id', $created_by_id);
                    $name_create = $q_name;
                }
            }else{
                $filter['filter_created_by'] = '-';
                $name_create = '-';
            }

            $date_main = new ReceiveVolume;
            $date_main = $date_main->where('state', 2);

            if ($filter['filter_start_month']!='') {
                $date_main = $date_main->where('inform_month', '>=', $filter['filter_start_month']);
            }
            if ($filter['filter_start_year']!='') {
                $date_main = $date_main->where('inform_year', '>=', $filter['filter_start_year']);
            }
            if ($filter['filter_end_month']!='') {
                $date_main = $date_main->where('inform_month', '<=', $filter['filter_end_month']);
            }
            if ($filter['filter_end_year']!='') {
                $date_main = $date_main->where('inform_year', '<=', $filter['filter_end_year']);
            }
            if ($filter['filter_start_month']!='') {
                $data_main = $date_main->sortable()->pluck('id');
                $data_main_check = $date_main->sortable()->first();
                if ($data_main_check!=null){
                    foreach ($data_main as $list_date){
                        $start_year_id[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->pluck('id');
                        $check_id[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->first();
                        $check_get[] = ReceiveVolumeLicense::select()->where('inform_volume_id',$list_date)->get();
                    }
                    $i=0;
                    foreach ($check_get as $list_start_year){
                        if ($check_id[$i]!=null){
                            for ($j=0;$j<count($check_get);$j++){
                                if ($check_id[$j]!=null) {
                                    if (isset($list_start_year[$j]->id)){
                                        $test[] = $list_start_year[$j]->id;
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                    $Query = $Query->wherein('inform_volume_license_id', $test);
                }else{
                    $Query = $Query->where('id', 0);
                }
                $month_start = \HP::MonthShortConvertList($filter['filter_start_month']);
                $year_start = $filter['filter_start_year']+543;
                $month_end = \HP::MonthShortConvertList($filter['filter_end_month']);
                $year_end = $filter['filter_end_year']+543;
            }else{
                $month_start = '-';
                $year_start = '';
                $month_end = '-';
                $year_end = '';
            }

            if ($filter['filter_tb3_Tisno']!='') {
                $tis_no_check = DB::table('esurv_inform_volumes')->select()->where('tb3_Tisno',$filter['filter_tb3_Tisno'])->where('state', 2)->first();
                $t_name = DB::table('tb3_tis')->select()->where('tb3_Tisno',$filter['filter_tb3_Tisno'])->first(['tb3_TisThainame']);
                if ($tis_no_check==null){
                    $Query = $Query->where('id', 0);
                }else{
                    $tis_no = receive_volume::select()->where('tb3_Tisno',$filter['filter_tb3_Tisno'])->where('state', 2)->pluck('id');
                    $tis_no_id = ReceiveVolumeLicense::select()->wherein('inform_volume_id',$tis_no)->pluck('id');
                    $Query = $Query->wherein('inform_volume_license_id', $tis_no_id);
                }
                $tis_name = $filter['filter_tb3_Tisno'] .' ('. $t_name->tb3_TisThainame . ')';
            }else{
                $tis_name = '-';
            }
            if ($filter['filter_elicense_detail']!='')
            {
                $data = explode(',',$filter['filter_elicense_detail']);
                foreach ($data as $list_data){
                    $e_detail[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail','like','%'.$list_data.'%')
                        ->pluck('a.id');
                    $check[] = DB::table('elicense_detail as a')
                        ->select()
                        ->where('a.standard_detail','like','%'.$list_data.'%')
                        ->first('a.id');
                }
                $temp_check = 0;
                foreach ($check as $check_error){
                    if ($check_error==null){
                        $temp_check +=1;
                    }
                }
                if ($temp_check>0){
                    $Query = $Query->where('elicense_detail_id', 0);
                }else{
                    foreach ($e_detail as $list_e_detail){
                        $Query = $Query->wherein('elicense_detail_id', $list_e_detail);
                    }
                }
                $elicese_detail = $filter['filter_elicense_detail'];
            }else{
                $elicese_detail = '-';
            }
            $report_volume = $Query->sortable()->paginate($filter['perPage']);

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A1:N1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(18);
            $sheet->setCellValue('A1', 'รายงานการแจ้งปริมาณการผลิตตามเงื่อนไขใบอนุญาต');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A2:D2');
            $sheet->setCellValue('A2', 'ตามเงื่อนไข ผู้ประกอบการ : ' . $name_create . '     มอก : ' . $tis_name . '      วันที่ผลิต : ' .$month_start.' '.$year_start . ' ถึง ' . $month_end.' '.$year_end.'     รายละเอียดผลิตภัณฑ์ : '.$elicese_detail);
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N2:O2');
            $sheet->setCellValue('N2', 'ข้อมูล ณ วันที่ ' . \HP::DateTimeFullThai(date('Y-m-d H:i:s')));
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('A3:A4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('B3:B4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('C3:C4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('D3:D4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('E3:E4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('F3:F4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('G3:G4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('H3:H4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('I3:I4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('J3:J4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('K3:K4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('L3:L4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('M3:M4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('N3:N4');
            $sheet = $spreadsheet->getActiveSheet()->mergeCells('O3:O4');

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(25);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(25);

            $spreadsheet->getActiveSheet()->getStyle('C')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('G')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('H3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('I3')->getAlignment()->setWrapText(true);
            $spreadsheet->getActiveSheet()->getStyle('J3')->getAlignment()->setWrapText(true);

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFont()->setBold(true);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Arial');

            $spreadsheet->getActiveSheet()->getStyle('A3:O3')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('CCE2F8');

            $styleArray = [
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                        'color' => ['argb' => '000000'],
                    ],
                ],
            ];

            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('E3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('G3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('I3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('J3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('K3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('L3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('M3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('N3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('O3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->setCellValue('A3', 'ผู้ประกอบการ');
            $sheet->setCellValue('B3', 'เลข มอก.');
            $sheet->setCellValue('C3', 'ชื่อ มอก.');
            $sheet->setCellValue('D3', 'เลขที่ใบอนุญาต');
            $sheet->setCellValue('E3', 'วันที่แจ้ง');
            $sheet->setCellValue('F3', 'เดือนที่ยื่น');
            $sheet->setCellValue('G3', 'ปีที่ยื่น');
            $sheet->setCellValue('H3', 'จำนวนผลิต(แสดง)');
            $sheet->setCellValue('I3', 'จำนวนผลิต(ไม่แสดง)');
            $sheet->setCellValue('J3', 'รวม(จำนวนผลิต)');
            $sheet->setCellValue('K3', 'หน่วย');
            $sheet->setCellValue('L3', 'ผู้บันทึก');
            $sheet->setCellValue('M3', 'เบอร์โทร');
            $sheet->setCellValue('N3', 'E-Mail');
            $sheet->setCellValue('O3', 'เจ้าหน้าที่รับเรื่อง');
            $row = 5;
            $total_volume1 = 0;
            $total_volume3 = 0;
            $total_sum = 0;
            foreach ($report_volume as $list){
                $sheet->setCellValue('A' . $row,  \HP::get_Create_name($list->inform_volume_license_id));
                $sheet->setCellValue('B' . $row, \HP::get_tb3_Tisno($list->inform_volume_license_id));
                $sheet->setCellValue('C' . $row, \HP::get_tb3_TisThainame($list->inform_volume_license_id));
                $sheet->setCellValue('D' . $row, $list->LicenseNo);
                $sheet->setCellValue('E' . $row, \HP::DateThai(\HP::get_created_at($list->inform_volume_license_id)));
                $sheet->setCellValue('F' . $row, \HP::MonthConvertList(\HP::get_inform_month($list->inform_volume_license_id)));
                $sheet->setCellValue('G' . $row, \HP::get_inform_year($list->inform_volume_license_id)+543);
                if ($list->volume1!=null){
                    $sheet->setCellValue('H' . $row, $list->volume1);
                }elseif ($list->volume2!=null){
                    $sheet->setCellValue('H' . $row, $list->volume2);
                }
                $sheet->setCellValue('I' . $row, $list->volume3);
                if ($list->volume1!=null){
                    $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume1,$list->volume3));
                }elseif ($list->volume2!=null){
                    $sheet->setCellValue('J' . $row, \HP::get_sum_row_volume($list->volume2,$list->volume3));
                }
                $sheet->setCellValue('K' . $row, 'แกลลอน');
                $sheet->setCellValue('L' . $row, \HP::get_applicant_name($list->inform_volume_license_id));
                $sheet->setCellValue('M' . $row, \HP::get_tel($list->inform_volume_license_id));
                $sheet->setCellValue('N' . $row, \HP::get_email($list->inform_volume_license_id));
                $sheet->setCellValue('O' . $row, \HP::get_consider_name(\HP::get_consider($list->inform_volume_license_id)));

                if ($list->volume1!=null){
                    $total_volume1 += (int)$list->volume1;
                }elseif ($list->volume2!=null){
                    $total_volume1 += (int)$list->volume2;
                }
                $total_volume3 += (int)$list->volume3;
                if ($list->volume1!=null){
                    $total_sum += (int)\HP::get_sum_row_volume($list->volume1,$list->volume3);
                }elseif ($list->volume2!=null){
                    $total_sum += (int)\HP::get_sum_row_volume($list->volume2,$list->volume3);
                }
                $row++;
            }
            $row_table = $row-1;
            if($row!=5){
                $sheet->getStyle('A3:A' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A3:O3')->applyFromArray($styleArray);
                $sheet->getStyle('A4:O4')->applyFromArray($styleArray);
                $sheet->getStyle('B3:B' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('C3:C' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('D3:D' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('E3:E' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('F3:F' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('G3:G' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('H3:H' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('I3:I' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('J3:J' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('K3:K' . $row)->applyFromArray($styleArray);
                $sheet->getStyle('L3:L' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('M3:M' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('N3:N' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('O3:O' . $row_table)->applyFromArray($styleArray);
                $sheet->getStyle('A' . $row . ':' . 'K' . $row)->applyFromArray($styleArray);
                $sheet = $spreadsheet->getActiveSheet()->mergeCells('A'. $row .':' . 'G' . $row);
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->setCellValue('A' . $row, 'รวมปริมาณการผลิต');
                $sheet->setCellValue('H' . $row, $total_volume1);
                $sheet->setCellValue('I' . $row, $total_volume3);
                $sheet->setCellValue('J' . $row, $total_sum);
                $sheet->setCellValue('K' . $row, 'แกลลอน');

                $sheet->getStyle('D5:G' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('D5:G' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('K5:K' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('K5:K' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('H5:H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('H5:H' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('I5:I' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('I5:I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $sheet->getStyle('J5:J' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('J5:J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            }

            $writer = new Xlsx($spreadsheet);
            ob_start();
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            $path = 'documents/report/';
            $file = Storage::disk('public')->put($path . date('d_m_y') . '_' . 'ReportVolume' . ".xlsx", $content);
            return Storage::disk('public')->download($path.date('d_m_y') . '_' . 'ReportVolume' . ".xlsx");

        }

    }


}
