<?php

namespace App\Http\Controllers\Rsurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Esurv\ReceiveInspection;
use App\Models\Besurv\Inspector;
use App\Models\Esurv\Tis;
use App\Models\Sso\User AS SSO_User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use HP;

class ReportInspectionController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/inform_inspection/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $model = str_slug('receive_inspection','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];

            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');

            $filter['filter_license'] = $request->get('filter_license', '');

            $filter['filter_inspector'] = $request->get('filter_inspector', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = $this->getQuery($request);

            $items = $Query->sortable()
                           ->with('tis')
                           ->with('trader_created')
                           ->paginate($filter['perPage']);

            $attach_path = $this->attach_path;

            return view('rsurv.report_inspection.index', compact('items', 'filter', 'attach_path'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {

    }

    public function export_excel(Request $request)
    {
        $model = str_slug('receive_change','-');
        if(auth()->user()->can('view-'.$model)) {

          //Data Search
          $filter_tb3_Tisno = $request->get('filter_tb3_Tisno');//เลขที่ มอก.
          $filter_tb3_Tisno = !is_null($filter_tb3_Tisno)?$filter_tb3_Tisno:'-';
          $filter_license = $request->get('filter_license');//เลขที่ใบอนุญาต
          $filter_license = !is_null($filter_license)?$filter_license:'-';

          $filter_created_by = $request->get('filter_created_by');//ผู้ประกอบการ
          $filter_created_by = !is_null($filter_created_by)?SSO_User::find($filter_created_by)->name:'-';

          $filter_inspector = $request->get('filter_inspector');//หน่วยทดสอบ
          if(!is_null($filter_inspector)){
            if($filter_inspector!='NULL'){
              $filter_inspector = Inspector::find($filter_inspector)->title;
            }else{
              $filter_inspector = 'อื่นๆ';
            }
          }else{
            $filter_inspector = '-';
          }

          $filter_start_date = $request->get('filter_start_date', '-');//วันที่แจ้ง
          $filter_end_date = $request->get('filter_end_date');
          if(!is_null($filter_start_date) && !is_null($filter_end_date)){
            if($filter_start_date!=$filter_end_date){
              $filter_date = HP::DateThai(HP::convertDate($filter_start_date)).' ถึง '.HP::DateThai(HP::convertDate($filter_end_date));
            }else{
              $filter_date = HP::DateThai(HP::convertDate($filter_start_date));
            }
          }elseif(!is_null($filter_start_date)){
            $filter_date = 'ตั้งแต่ '.HP::DateThai(HP::convertDate($filter_start_date)).' เป็นต้นไป';
          }elseif(!is_null($filter_end_date)){
            $filter_date = 'ไม่เกิน '.HP::DateThai(HP::convertDate($filter_end_date)).'';
          }else{
            $filter_date = '-';
          }

          //Create Spreadsheet Object
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          //หัวรายงาน
          $sheet->setCellValue('A1', 'รายงานการแจ้งผลการทดสอบผลิตภัณฑ์');
          $sheet->mergeCells('A1:J1');
          $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
          $sheet->getStyle("A1")->getFont()->setSize(18);

          //หัวรายงาน ชื่อบริษัท ชื่อมอก.
          $head_created_by = ($filter_created_by=='-')?'':$filter_created_by;
          $head_tb3_Tisno = ($filter_tb3_Tisno=='-')?'':'มอก.'.$filter_tb3_Tisno.' '.Tis::where('tb3_Tisno', $filter_tb3_Tisno)->first()->tb3_TisThainame;
          $sheet->setCellValue('A2', $head_created_by.'   '.$head_tb3_Tisno);
          $sheet->mergeCells('A2:J2');
          $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

          //แสดงเงื่อนไข
          $sheet->setCellValue('A4', 'ตามเงื่อนไข  ผู้ประกอบการ : '.$filter_created_by.'                    มอก. : '.$filter_tb3_Tisno.'                    ใบอนุญาต : '.$filter_license.'                        วันที่ตรวจ : '.$filter_date.'                      หน่วยทดสอบ  : '.$filter_inspector);
          $sheet->mergeCells('A4:H4');
          $sheet->getStyle("A4")->getFont()->setSize(10);

          //แสดงวันที่
          $sheet->setCellValue('I4', 'ข้อมูล ณ วันที่ '.HP::DateTimeFullThai(date('Y-m-d H:i')));
          $sheet->mergeCells('I4:L4');
          $sheet->getStyle('I4:L4')->getAlignment()->setHorizontal('right');

          //หัวตาราง
          $sheet->setCellValue('A5', 'ผู้ประกอบการ');
          $sheet->setCellValue('B5', 'เลข มอก.');
          $sheet->setCellValue('C5', 'ชื่อมอก.');
          $sheet->setCellValue('D5', 'วันที่แจ้ง');
          $sheet->setCellValue('E5', 'ใบอนุญาต');
          $sheet->setCellValue('F5', 'วันที่ทดสอบ');
          $sheet->setCellValue('G5', 'หน่วยทดสอบ');
          $sheet->setCellValue('H5', 'หมายเหตุ');
          $sheet->setCellValue('I5', 'ผู้บันทึก');
          $sheet->setCellValue('J5', 'เบอร์โทร');
          $sheet->setCellValue('K5', 'e-Mail');
          $sheet->setCellValue('L5', 'เจ้าหน้าที่รับเรื่อง');
          $sheet->getStyle('A5:L5')->getAlignment()->setHorizontal('center');
          $sheet->getStyle('A5:L5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('95DCFF');

          //แสดงรายการเนื้อหาที่แจ้งเข้ามา
          $Query = $this->getQuery($request);
          $items = $Query->sortable()
                         ->with('tis')
                         ->with('trader_created')->get();

          $row = 5;//start row
          foreach ($items as $item) {
            $row++;
            $sheet->setCellValue('A'.$row, $item->CreatedName);
            $sheet->setCellValue('B'.$row, $item->tis->tb3_Tisno);
            $sheet->setCellValue('C'.$row, $item->tis->tb3_TisThainame);
            $sheet->setCellValue('D'.$row, HP::DateThai($item->created_at));
            $sheet->setCellValue('E'.$row, $item->tbl_licenseNo);
            $sheet->setCellValue('F'.$row, HP::DateThai($item->check_date));
            $sheet->setCellValue('G'.$row, !is_null($item->inspector)?$item->inspector_u->title:$item->inspector_other);
            $sheet->setCellValue('H'.$row, $item->detail);
            $sheet->setCellValue('I'.$row, $item->applicant_name);
            $sheet->setCellValue('J'.$row, $item->tel);
            $sheet->setCellValue('K'.$row, $item->email);
            $sheet->setCellValue('L'.$row, HP::get_consider_name($item->consider));
          }

          //Set Border Style
          $styleArray = [
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => '000000'],
                                ],
                            ]
                        ];
          $sheet ->getStyle('A5:'.'L'.$row)->applyFromArray($styleArray);

          //Set Column Width
          $sheet->getColumnDimension('A')->setAutoSize(true);
          $sheet->getColumnDimension('B')->setAutoSize(true);
          $sheet->getColumnDimension('C')->setWidth(20);
          $sheet->getColumnDimension('D')->setAutoSize(true);
          $sheet->getColumnDimension('E')->setWidth(20);
          $sheet->getColumnDimension('F')->setAutoSize(true);
          $sheet->getColumnDimension('G')->setWidth(20);
          $sheet->getColumnDimension('H')->setWidth(20);
          $sheet->getColumnDimension('I')->setAutoSize(true);
          $sheet->getColumnDimension('J')->setAutoSize(true);
          $sheet->getColumnDimension('K')->setAutoSize(true);
          $sheet->getColumnDimension('L')->setAutoSize(true);

          $filename = 'Inspection_'.date('Hi_dmY').'.xlsx';
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="'.$filename.'"');
          $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
          $writer->save("php://output");

          exit;

        }

    }

    private function getQuery($request){

      $filter = [];

      $filter['filter_created_by'] = $request->get('filter_created_by', '');
      $filter['filter_tb3_Tisno'] = $request->get('filter_tb3_Tisno', '');
      $filter['filter_start_date'] = $request->get('filter_start_date', '');
      $filter['filter_end_date'] = $request->get('filter_end_date', '');
      $filter['filter_license'] = $request->get('filter_license', '');
      $filter['filter_inspector'] = $request->get('filter_inspector', '');

      $Query = new ReceiveInspection;
      $Query = $Query->where('state', 2);

      if ($filter['filter_created_by']!='') {
          $Query = $Query->where('created_by', $filter['filter_created_by']);
      }

      if ($filter['filter_tb3_Tisno']!='') {
          $Query = $Query->where('tb3_Tisno', $filter['filter_tb3_Tisno']);
      }

      if ($filter['filter_start_date']!='') {
          $filter_start_date = HP::convertDate($filter['filter_start_date']);
          $Query = $Query->whereDate('check_date', '>=', $filter_start_date);
      }

      if ($filter['filter_end_date']!='') {
          $filter_end_date = HP::convertDate($filter['filter_end_date']);
          $Query = $Query->whereDate('check_date', '<=', $filter_end_date);
      }

      if ($filter['filter_license']!=''){
          $Query = $Query->where('tbl_licenseNo', 'LIKE', '%'.$filter['filter_license'].'%');
      }

      if ($filter['filter_inspector']!=''){
          $filter['filter_inspector'] = $filter['filter_inspector']=='NULL'?null:$filter['filter_inspector'];
          $Query = $Query->where('inspector', $filter['filter_inspector']);
      }

      return $Query;

    }


}
