<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;


use App\Models\Tis\SetStandard;
use App\Models\Tis\SetStandardPlan;
use App\Models\Esurv\ReceiveChange;
use App\Models\Esurv\ReceiveChangeLicense;
use App\Models\Esurv\Tis;

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

class ReportPerformanceController extends Controller
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
    public function index(Request $request)
    {
        $model = str_slug('report_performance','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_year'] = $request->get('filter_year', '');
            $filter['filter_plan'] = $request->get('filter_plan', '');
            $filter['filter_start_quarter'] = $request->get('filter_start_quarter', '');
            $filter['filter_end_quarter'] = $request->get('filter_end_quarter', '');
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_operation'] = $request->get('filter_operation', '');
            $filter['filter_method'] = $request->get('filter_method', '');
            $filter['filter_made'] = $request->get('filter_made', '');
            $filter['filter_standard_format'] = $request->get('filter_standard_format', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = $this->getQuery($filter);

            $items = $Query->sortable()
                           ->paginate($filter['perPage']);

            return view('tis.report_performance.index', compact('items', 'filter'));
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
        $model = str_slug('report_performance','-');
        if(auth()->user()->can('view-'.$model)) {

          //Data Search
          $filter = [];
          $filter['filter_search'] = $request->get('filter_search', '');
          $filter['filter_status'] = $request->get('filter_status', '');
          $filter['filter_year'] = $request->get('filter_year', '');
          $filter['filter_plan'] = $request->get('filter_plan', '');
          $filter['filter_start_quarter'] = $request->get('filter_start_quarter', '');
          $filter['filter_end_quarter'] = $request->get('filter_end_quarter', '');
          $filter['filter_start_month'] = $request->get('filter_start_month', '');
          $filter['filter_end_month'] = $request->get('filter_end_month', '');
          $filter['filter_operation'] = $request->get('filter_operation', '');
          $filter['filter_method'] = $request->get('filter_method', '');
          $filter['filter_made'] = $request->get('filter_made', '');
          $filter['filter_standard_format'] = $request->get('filter_standard_format', '');
          $filter['perPage'] = $request->get('perPage', 10);

          $items = $this->getQuery($filter)
                        ->sortable()
                        ->with('standard_type')
                        ->with('standard_format')
                        ->with('method')
                        ->with('product_group')
                        ->with('set_standard_plan')
                        ->with('set_standard_result')
                        ->with('industry_target')
                        ->with('appoint')
                        ->get();

          //Create Spreadsheet Object
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();

          //หัวรายงาน
          $sheet->setCellValue('A1', 'รายงานแผน – ผลการปฎิบัติงาน (งาน, เงิน)');
          $sheet->mergeCells('A1:R1');
          $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
          $sheet->getStyle("A1")->getFont()->setSize(18);

          //หัวรายงาน 2
          $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::DateTimeFullThai(date('Y-m-d H:i')));
          $sheet->mergeCells('A2:R2');
          $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');
          $sheet->getStyle("A2")->getFont()->setSize(12);

          //หัวตาราง
          $sheet->setCellValue('A4', 'ลำดับ');
          $sheet->setCellValue('B4', 'ปีที่เสนอเข้าแผน');
          $sheet->setCellValue('C4', 'หมายเลขมอก.');
          $sheet->setCellValue('D4', 'ชื่อมาตรฐานภาษาไทย
ภาษาอังกฤษ');
          $sheet->getStyle('D4')->getAlignment()->setWrapText(true);
          $sheet->setCellValue('E4', 'กว./อนุ กว.');
          $sheet->setCellValue('F4', 'ประเภท มอก.');
          $sheet->setCellValue('G4', 'สถานะ (บังคับ/ทั่วไป)');
          $sheet->setCellValue('H4', 'สถานะ (ใหม่/ทบทวน)');
          $sheet->setCellValue('I4', 'สถานภาพ มอก.');
          $sheet->setCellValue('J4', 'สาขา');
          $sheet->setCellValue('K4', 'S-Curve');
          $sheet->setCellValue('L4', 'reference');
          $sheet->setCellValue('M4', 'เลขาฯ');

          //หัวตารางแถวที่ 3
          $sheet->setCellValue('N6', 'แผน/ผล');
          $sheet->setCellValue('O6', 'ค่าเบี้ย/
อาหาร');
          $sheet->getStyle('O6')->getAlignment()->setWrapText(true);
          $sheet->setCellValue('S6', 'ครั้งที่ประชุม');
          $sheet->setCellValue('W6', 'ครั้งที่ประชุม');
          $sheet->setCellValue('AA6', 'ครั้งที่ประชุม');
          $sheet->setCellValue('AE6', 'ครั้งที่ประชุม');

          //หัวตารางชื่อเดือน
          $sheet->setCellValue('P5', 'ต.ค.');
          $sheet->setCellValue('Q5', 'พ.ย.');
          $sheet->setCellValue('R5', 'ธ.ค.');
          $sheet->setCellValue('S5', 'รวม');
          $sheet->setCellValue('T5', 'ม.ค.');
          $sheet->setCellValue('U5', 'ก.พ.');
          $sheet->setCellValue('V5', 'มี.ค.');
          $sheet->setCellValue('W5', 'รวม');
          $sheet->setCellValue('X5', 'เม.ย.');
          $sheet->setCellValue('Y5', 'พ.ค.');
          $sheet->setCellValue('Z5', 'มิ.ย.');
          $sheet->setCellValue('AA5', 'รวม');
          $sheet->setCellValue('AB5', 'ก.ค.');
          $sheet->setCellValue('AC5', 'ส.ค.');
          $sheet->setCellValue('AD5', 'ก.ย.');
          $sheet->setCellValue('AE5', 'รวม');

          //เซตตัวอักษรเป็นแนวตั้ง
          $sheet->getStyle('A4:C4')->getAlignment()->setTextRotation(90);
          $sheet->getStyle('E4:M4')->getAlignment()->setTextRotation(90);

          //เซตความกว้าง
          $sheet->getColumnDimension('A')->setWidth(5);
          $sheet->getColumnDimension('B')->setWidth(5);
          $sheet->getColumnDimension('C')->setWidth(5);
          $sheet->getColumnDimension('D')->setWidth(25);
          $sheet->getColumnDimension('E')->setWidth(5);
          $sheet->getColumnDimension('F')->setWidth(5);
          $sheet->getColumnDimension('G')->setWidth(5);
          $sheet->getColumnDimension('H')->setWidth(5);
          $sheet->getColumnDimension('I')->setWidth(5);
          $sheet->getColumnDimension('J')->setWidth(5);
          $sheet->getColumnDimension('K')->setWidth(5);
          $sheet->getColumnDimension('L')->setWidth(5);
          $sheet->getColumnDimension('M')->setWidth(5);
          $sheet->getColumnDimension('AF')->setWidth(12);

          //เซตความสูง
          $sheet->getRowDimension(4)->setRowHeight(25);
          $sheet->getRowDimension(5)->setRowHeight(25);
          $sheet->getRowDimension(6)->setRowHeight(30);

          //เซตกึ่งกลางแนวตั้ง
          $styleArray = [
                            'alignment' => [
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            ]
                        ];
          $sheet->getStyle('A4:AG6')->applyFromArray($styleArray);

          //เซตกึ่งกลางแนวนอน
          $sheet->getStyle('A4:AG6')->getAlignment()->setHorizontal('center');

          $sheet->setCellValue('N4', 'แผน/ผล');
          $sheet->setCellValue('P4', 'ไตรมาสที่ 1');
          $sheet->setCellValue('T4', 'ไตรมาสที่ 2');
          $sheet->setCellValue('X4', 'ไตรมาสที่ 3');
          $sheet->setCellValue('AB4', 'ไตรมาสที่ 4');
          $sheet->setCellValue('AF4', 'รวมทั้งหมด');
          $sheet->setCellValue('AG4', 'ตัวอย่าง/
ค่าทดสอบ');
          $sheet->getStyle('AG4')->getAlignment()->setWrapText(true);

          //เซตฟอร์น
          $sheet->getStyle("A4:M4")->getFont()->setSize(9);
          $sheet->getStyle("A4:M4")->getFont()->setBold(true);
          $sheet->getStyle("S6")->getFont()->setSize(9);//รวมไตรมาส 1
          $sheet->getStyle("S6")->getFont()->setBold(true);
          $sheet->getStyle("S6")->getFont()->setUnderline(true);
          $sheet->getStyle("W6")->getFont()->setSize(9);//รวมไตรมาส 2
          $sheet->getStyle("W6")->getFont()->setBold(true);
          $sheet->getStyle("W6")->getFont()->setUnderline(true);
          $sheet->getStyle("X6")->getFont()->setSize(9);//รวมไตรมาส 3
          $sheet->getStyle("X6")->getFont()->setBold(true);
          $sheet->getStyle("X6")->getFont()->setUnderline(true);
          $sheet->getStyle("AE6")->getFont()->setSize(9);//รวมไตรมาส 4
          $sheet->getStyle("AE6")->getFont()->setBold(true);
          $sheet->getStyle("AE6")->getFont()->setUnderline(true);
          $sheet->getStyle("AF4")->getFont()->setSize(10);//รวมทั้งหมด
          $sheet->getStyle("AF4")->getFont()->setBold(true);
          $sheet->getStyle("AG4")->getFont()->setSize(10);//ตัวอย่าง/ค่าทดสอบ

          //Merge Cell
          $sheet->mergeCells('A4:A6');
          $sheet->mergeCells('B4:B6');
          $sheet->mergeCells('C4:C6');
          $sheet->mergeCells('D4:D6');
          $sheet->mergeCells('E4:E6');
          $sheet->mergeCells('F4:F6');
          $sheet->mergeCells('G4:G6');
          $sheet->mergeCells('H4:H6');
          $sheet->mergeCells('I4:I6');
          $sheet->mergeCells('J4:J6');
          $sheet->mergeCells('K4:K6');
          $sheet->mergeCells('L4:L6');
          $sheet->mergeCells('M4:M6');
          $sheet->mergeCells('N4:O5');
          $sheet->mergeCells('P4:S4');
          $sheet->mergeCells('T4:W4');
          $sheet->mergeCells('X4:AA4');
          $sheet->mergeCells('AB4:AE4');
          $sheet->mergeCells('AF4:AF6');
          $sheet->mergeCells('AG4:AG6');

          //แสดงเนื้อหา
          $start = 7;
          foreach ($items as $key => $item) {

              $last_row = $start+7;
              $index2 = $start+1;
              $index3 = $start+2;
              $index4 = $start+3;
              $index5 = $start+4;
              $index6 = $start+5;

              if($item->review_status=='1'){
                $review_status = 'กำหนดใหม่';
              }elseif($item->review_status=='2'){
                $review_status = 'ทบทวน';
              }

              //เลขมาตรฐานอ้างอิง
              $refers = json_decode($item->refer);

              //เซตข้อมูล
              $sheet->setCellValue('A'.$start, $key+1);
              $sheet->setCellValue('B'.$start, $item->plan_year??'n/a');
              $sheet->setCellValue('C'.$start, ($item->tis_no).($item->start_year?' - '.$item->start_year:''));
              $sheet->setCellValue('D'.$start, $item->title??'n/a');
              $sheet->setCellValue('E'.$start, $item->appoint->board_position??'n/a');
              $sheet->setCellValue('F'.$start, $item->standard_type->title??'n/a');
              $sheet->setCellValue('G'.$start, $item->standard_format->title??'n/a');
              $sheet->setCellValue('H'.$start, $review_status??'n/a');
              $sheet->setCellValue('I'.$start, ($item->method->title??'n/a').(" ".$item->MethodDetailName));
              $sheet->setCellValue('J'.$start, $item->product_group->title??'n/a');
              $sheet->setCellValue('K'.$start, $item->industry_target->title??'n/a');
              $sheet->setCellValue('L'.$start, !empty($refers)?implode(',', $refers):'n/a');
              $sheet->setCellValue('M'.$start, $item->secretary);

              //ข้อมูลคอลัมภ์ส่วนแผน/ผล
              $sheet->setCellValue('N'.$start, 'แผน');
              $sheet->setCellValue('N'.($start+1), 'ผล');
              $sheet->setCellValue('N'.($start+2), 'แผนเงิน');
              $sheet->setCellValue('O'.($start+2), 'เบี้ยประชุม');
              $sheet->setCellValue('O'.($start+3), 'ค่าอาหารว่าง');
              $sheet->setCellValue('N'.($start+4), 'แผนผล');
              $sheet->setCellValue('O'.($start+4), 'เบี้ยประชุม');
              $sheet->setCellValue('O'.($start+5), 'ค่าอาหารว่าง');
              $sheet->setCellValue('N'.($start+6), 'หมายเหตุ');
              $sheet->setCellValue('N'.($start+7), 'ค่าใช้จ่ายที่เหลือ');

              // ------------------ ข้อมูลที่จะโชว์ในเดือนต่าง ๆ -----------------//

              //ผลรวมตามไตรมาส
              $plan_allowance = $plan_food = $result_allowance = $result_food = [1=>0, 2=>0, 3=>0, 4=>0];

              //แผน
              $plans = [];
              foreach ($item->set_standard_plan as $plan) {
                $plans[$plan->quarter][] = $plan;
              }

              //ผล
              $results = [];
              foreach ($item->set_standard_result as $result) {
                $results[$result->quarter][] = $result;
              }

              //แผน ไตรมาส 1
              $plans1 = $this->formatPlan($plans, 1, 10, 11, 12);
              $sheet->setCellValue('P'.$start, implode(', ', $plans1['operations'][10]));
              $sheet->setCellValue('Q'.$start, implode(', ', $plans1['operations'][11]));
              $sheet->setCellValue('R'.$start, implode(', ', $plans1['operations'][12]));
              $sheet->setCellValue('S'.$start, $plans1['meeting']);

              //แผน ไตรมาส 2
              $plans2 = $this->formatPlan($plans, 2, 1, 2, 3);
              $sheet->setCellValue('T'.$start, implode(', ', $plans2['operations'][1]));
              $sheet->setCellValue('U'.$start, implode(', ', $plans2['operations'][2]));
              $sheet->setCellValue('V'.$start, implode(', ', $plans2['operations'][3]));
              $sheet->setCellValue('W'.$start, $plans2['meeting']);

              //แผน ไตรมาส 3
              $plans3 = $this->formatPlan($plans, 3, 4, 5, 6);
              $sheet->setCellValue('X'.$start, implode(', ', $plans3['operations'][4]));
              $sheet->setCellValue('Y'.$start, implode(', ', $plans3['operations'][5]));
              $sheet->setCellValue('Z'.$start, implode(', ', $plans3['operations'][6]));
              $sheet->setCellValue('AA'.$start, $plans3['meeting']);

              //แผน ไตรมาส 4
              $plans4 = $this->formatPlan($plans, 4, 7, 8, 9);
              $sheet->setCellValue('AB'.$start, implode(', ', $plans4['operations'][7]));
              $sheet->setCellValue('AC'.$start, implode(', ', $plans4['operations'][8]));
              $sheet->setCellValue('AD'.$start, implode(', ', $plans4['operations'][9]));
              $sheet->setCellValue('AE'.$start, $plans4['meeting']);

              //ผล ไตรมาส 1
              $results1 = $this->formatResult($results, 1, 10, 11, 12);
              $sheet->setCellValue('P'.$index2, implode(', ', $results1['operations'][10]));
              $sheet->setCellValue('Q'.$index2, implode(', ', $results1['operations'][11]));
              $sheet->setCellValue('R'.$index2, implode(', ', $results1['operations'][12]));
              $sheet->setCellValue('S'.$index2, $results1['meeting']);

              //ผล ไตรมาส 2
              $results2 = $this->formatResult($results, 2, 1, 2, 3);
              $sheet->setCellValue('T'.$index2, implode(', ', $results2['operations'][1]));
              $sheet->setCellValue('U'.$index2, implode(', ', $results2['operations'][2]));
              $sheet->setCellValue('V'.$index2, implode(', ', $results2['operations'][3]));
              $sheet->setCellValue('W'.$index2, $results2['meeting']);

              //ผล ไตรมาส 3
              $results3 = $this->formatResult($results, 3, 4, 5, 6);
              $sheet->setCellValue('X'.$index2, implode(', ', $results3['operations'][4]));
              $sheet->setCellValue('Y'.$index2, implode(', ', $results3['operations'][5]));
              $sheet->setCellValue('Z'.$index2, implode(', ', $results3['operations'][6]));
              $sheet->setCellValue('AA'.$index2, $results3['meeting']);

              //ผล ไตรมาส 4
              $results4 = $this->formatResult($results, 4, 7, 8, 9);
              $sheet->setCellValue('AB'.$index2, implode(', ', $results4['operations'][7]));
              $sheet->setCellValue('AC'.$index2, implode(', ', $results4['operations'][8]));
              $sheet->setCellValue('AD'.$index2, implode(', ', $results4['operations'][9]));
              $sheet->setCellValue('AE'.$index2, $results4['meeting']);

              //แผนเงิน เบี้ยประชุม ไตรมาสที่ 1
              $sheet->setCellValue('P'.$index3, count($plans1['plan_allowances'][10])>0 ? array_sum($plans1['plan_allowances'][10]) : '' );
              $sheet->setCellValue('Q'.$index3, count($plans1['plan_allowances'][11])>0 ? array_sum($plans1['plan_allowances'][11]) : '' );
              $sheet->setCellValue('R'.$index3, count($plans1['plan_allowances'][12])>0 ? array_sum($plans1['plan_allowances'][12]) : '' );
              $sheet->setCellValue('S'.$index3, "=SUM(P$index3:R$index3)");

              //แผนเงิน เบี้ยประชุม ไตรมาสที่ 2
              $sheet->setCellValue('T'.$index3, count($plans2['plan_allowances'][1])>0 ? array_sum($plans2['plan_allowances'][1]) : '' );
              $sheet->setCellValue('U'.$index3, count($plans2['plan_allowances'][2])>0 ? array_sum($plans2['plan_allowances'][2]) : '' );
              $sheet->setCellValue('V'.$index3, count($plans2['plan_allowances'][3])>0 ? array_sum($plans2['plan_allowances'][3]) : '' );
              $sheet->setCellValue('W'.$index3, "=SUM(T$index3:V$index3)");

              //แผนเงิน เบี้ยประชุม ไตรมาสที่ 3
              $sheet->setCellValue('X'.$index3, count($plans3['plan_allowances'][4])>0 ? array_sum($plans3['plan_allowances'][4]) : '' );
              $sheet->setCellValue('Y'.$index3, count($plans3['plan_allowances'][5])>0 ? array_sum($plans3['plan_allowances'][5]) : '' );
              $sheet->setCellValue('Z'.$index3, count($plans3['plan_allowances'][6])>0 ? array_sum($plans3['plan_allowances'][6]) : '' );
              $sheet->setCellValue('AA'.$index3, "=SUM(X$index3:Z$index3)");

              //แผนเงิน เบี้ยประชุม ไตรมาสที่ 4
              $sheet->setCellValue('AB'.$index3, count($plans4['plan_allowances'][7])>0 ? array_sum($plans4['plan_allowances'][7]) : '' );
              $sheet->setCellValue('AC'.$index3, count($plans4['plan_allowances'][8])>0 ? array_sum($plans4['plan_allowances'][8]) : '' );
              $sheet->setCellValue('AD'.$index3, count($plans4['plan_allowances'][9])>0 ? array_sum($plans4['plan_allowances'][9]) : '' );
              $sheet->setCellValue('AE'.$index3, "=SUM(AB$index3:AD$index3)");

              //แผนเงิน ค่าอาหารว่าง ไตรมาสที่ 1
              $sheet->setCellValue('P'.$index4, count($plans1['plan_foods'][10])>0 ? array_sum($plans1['plan_foods'][10]) : '' );
              $sheet->setCellValue('Q'.$index4, count($plans1['plan_foods'][11])>0 ? array_sum($plans1['plan_foods'][11]) : '' );
              $sheet->setCellValue('R'.$index4, count($plans1['plan_foods'][12])>0 ? array_sum($plans1['plan_foods'][12]) : '' );
              $sheet->setCellValue('S'.$index4, "=SUM(P$index4:R$index4)");

              //แผนเงิน ค่าอาหารว่าง ไตรมาสที่ 2
              $sheet->setCellValue('T'.$index4, count($plans2['plan_foods'][1])>0 ? array_sum($plans2['plan_foods'][1]) : '' );
              $sheet->setCellValue('U'.$index4, count($plans2['plan_foods'][2])>0 ? array_sum($plans2['plan_foods'][2]) : '' );
              $sheet->setCellValue('V'.$index4, count($plans2['plan_foods'][3])>0 ? array_sum($plans2['plan_foods'][3]) : '' );
              $sheet->setCellValue('W'.$index4, "=SUM(T$index4:V$index4)");

              //แผนเงิน ค่าอาหารว่าง ไตรมาสที่ 3
              $sheet->setCellValue('X'.$index4, count($plans3['plan_foods'][4])>0 ? array_sum($plans3['plan_foods'][4]) : '' );
              $sheet->setCellValue('Y'.$index4, count($plans3['plan_foods'][5])>0 ? array_sum($plans3['plan_foods'][5]) : '' );
              $sheet->setCellValue('Z'.$index4, count($plans3['plan_foods'][6])>0 ? array_sum($plans3['plan_foods'][6]) : '' );
              $sheet->setCellValue('AA'.$index4, "=SUM(X$index4:Z$index4)");

              //แผนเงิน ค่าอาหารว่าง ไตรมาสที่ 4
              $sheet->setCellValue('AB'.$index4, count($plans4['plan_foods'][7])>0 ? array_sum($plans4['plan_foods'][7]) : '' );
              $sheet->setCellValue('AC'.$index4, count($plans4['plan_foods'][8])>0 ? array_sum($plans4['plan_foods'][8]) : '' );
              $sheet->setCellValue('AD'.$index4, count($plans4['plan_foods'][9])>0 ? array_sum($plans4['plan_foods'][9]) : '' );
              $sheet->setCellValue('AE'.$index4, "=SUM(AB$index4:AD$index4)");

              //ผลเงิน เบี้ยประชุม ไตรมาสที่ 1
              $sheet->setCellValue('P'.$index5, count($results1['allowances'][10])>0 ? array_sum($results1['allowances'][10]) : '' );
              $sheet->setCellValue('Q'.$index5, count($results1['allowances'][11])>0 ? array_sum($results1['allowances'][11]) : '' );
              $sheet->setCellValue('R'.$index5, count($results1['allowances'][12])>0 ? array_sum($results1['allowances'][12]) : '' );
              $sheet->setCellValue('S'.$index5, "=SUM(P$index5:R$index5)");

              //ผลเงิน เบี้ยประชุม ไตรมาสที่ 2
              $sheet->setCellValue('T'.$index5, count($results2['allowances'][1])>0 ? array_sum($results2['allowances'][1]) : '' );
              $sheet->setCellValue('U'.$index5, count($results2['allowances'][2])>0 ? array_sum($results2['allowances'][2]) : '' );
              $sheet->setCellValue('V'.$index5, count($results2['allowances'][3])>0 ? array_sum($results2['allowances'][3]) : '' );
              $sheet->setCellValue('W'.$index5, "=SUM(T$index5:V$index5)");

              //ผลเงิน เบี้ยประชุม ไตรมาสที่ 3
              $sheet->setCellValue('X'.$index5, count($results3['allowances'][4])>0 ? array_sum($results3['allowances'][4]) : '' );
              $sheet->setCellValue('Y'.$index5, count($results3['allowances'][5])>0 ? array_sum($results3['allowances'][5]) : '' );
              $sheet->setCellValue('Z'.$index5, count($results3['allowances'][6])>0 ? array_sum($results3['allowances'][6]) : '' );
              $sheet->setCellValue('AA'.$index5, "=SUM(X$index5:Z$index5)");

              //ผลเงิน เบี้ยประชุม ไตรมาสที่ 4
              $sheet->setCellValue('AB'.$index5, count($results4['allowances'][7])>0 ? array_sum($results4['allowances'][7]) : '' );
              $sheet->setCellValue('AC'.$index5, count($results4['allowances'][8])>0 ? array_sum($results4['allowances'][8]) : '' );
              $sheet->setCellValue('AD'.$index5, count($results4['allowances'][9])>0 ? array_sum($results4['allowances'][9]) : '' );
              $sheet->setCellValue('AE'.$index5, "=SUM(AB$index5:AD$index5)");

              //ผลเงิน อาหาร ไตรมาสที่ 1
              $sheet->setCellValue('P'.$index6, count($results1['foods'][10])>0 ? array_sum($results1['foods'][10]) : '' );
              $sheet->setCellValue('Q'.$index6, count($results1['foods'][11])>0 ? array_sum($results1['foods'][11]) : '' );
              $sheet->setCellValue('R'.$index6, count($results1['foods'][12])>0 ? array_sum($results1['foods'][12]) : '' );
              $sheet->setCellValue('S'.$index6, "=SUM(P$index6:R$index6)");

              //ผลเงิน อาหาร ไตรมาสที่ 2
              $sheet->setCellValue('T'.$index6, count($results2['foods'][1])>0 ? array_sum($results2['foods'][1]) : '' );
              $sheet->setCellValue('U'.$index6, count($results2['foods'][2])>0 ? array_sum($results2['foods'][2]) : '' );
              $sheet->setCellValue('V'.$index6, count($results2['foods'][3])>0 ? array_sum($results2['foods'][3]) : '' );
              $sheet->setCellValue('W'.$index6, "=SUM(T$index6:V$index6)");

              //ผลเงิน อาหาร ไตรมาสที่ 3
              $sheet->setCellValue('X'.$index6, count($results3['foods'][4])>0 ? array_sum($results3['foods'][4]) : '' );
              $sheet->setCellValue('Y'.$index6, count($results3['foods'][5])>0 ? array_sum($results3['foods'][5]) : '' );
              $sheet->setCellValue('Z'.$index6, count($results3['foods'][6])>0 ? array_sum($results3['foods'][6]) : '' );
              $sheet->setCellValue('AA'.$index6, "=SUM(X$index6:Z$index6)");

              //ผลเงิน อาหาร ไตรมาสที่ 4
              $sheet->setCellValue('AB'.$index6, count($results4['foods'][7])>0 ? array_sum($results4['foods'][7]) : '' );
              $sheet->setCellValue('AC'.$index6, count($results4['foods'][8])>0 ? array_sum($results4['foods'][8]) : '' );
              $sheet->setCellValue('AD'.$index6, count($results4['foods'][9])>0 ? array_sum($results4['foods'][9]) : '' );
              $sheet->setCellValue('AE'.$index6, "=SUM(AB$index6:AD$index6)");

              //รวมทั้งหมดช่องขวาสุด
              $sheet->setCellValue('AF'.$start, "=S$start+W$start+AA$start+AE$start");
              $sheet->setCellValue('AF'.$index2, "=S$index2+W$index2+AA$index2+AE$index2");
              $sheet->setCellValue('AF'.$index3, "=S$index3+W$index3+AA$index3+AE$index3");
              $sheet->setCellValue('AF'.$index4, "=S$index4+W$index4+AA$index4+AE$index4");
              $sheet->setCellValue('AF'.$index5, "=S$index5+W$index5+AA$index5+AE$index5");
              $sheet->setCellValue('AF'.$index6, "=S$index6+W$index6+AA$index6+AE$index6");

              //ค่าใช้จ่ายที่เหลือ $last_row
              $sheet->setCellValue('P'.$last_row, "=(P$index3+P$index4)-(P$index5+P$index6)");
              $sheet->setCellValue('Q'.$last_row, "=(Q$index3+Q$index4)-(Q$index5+Q$index6)");
              $sheet->setCellValue('R'.$last_row, "=(R$index3+R$index4)-(R$index5+R$index6)");
              $sheet->setCellValue('S'.$last_row, "=(S$index3+S$index4)-(S$index5+S$index6)");
              $sheet->setCellValue('T'.$last_row, "=(T$index3+T$index4)-(T$index5+T$index6)");
              $sheet->setCellValue('U'.$last_row, "=(U$index3+U$index4)-(U$index5+U$index6)");
              $sheet->setCellValue('V'.$last_row, "=(V$index3+V$index4)-(V$index5+V$index6)");
              $sheet->setCellValue('W'.$last_row, "=(W$index3+W$index4)-(W$index5+W$index6)");
              $sheet->setCellValue('X'.$last_row, "=(X$index3+X$index4)-(X$index5+X$index6)");
              $sheet->setCellValue('Y'.$last_row, "=(Y$index3+Y$index4)-(Y$index5+Y$index6)");
              $sheet->setCellValue('Z'.$last_row, "=(Z$index3+Z$index4)-(Z$index5+Z$index6)");
              $sheet->setCellValue('AA'.$last_row, "=(AA$index3+AA$index4)-(AA$index5+AA$index6)");
              $sheet->setCellValue('AB'.$last_row, "=(AB$index3+AB$index4)-(AB$index5+AB$index6)");
              $sheet->setCellValue('AC'.$last_row, "=(AC$index3+AC$index4)-(AC$index5+AC$index6)");
              $sheet->setCellValue('AD'.$last_row, "=(AD$index3+AD$index4)-(AD$index5+AD$index6)");
              $sheet->setCellValue('AE'.$last_row, "=(AE$index3+AE$index4)-(AE$index5+AE$index6)");
              $sheet->setCellValue('AF'.$last_row, "=(AF$index3+AF$index4)-(AF$index5+AF$index6)");

              //merge cell
              $sheet->mergeCells('A'.$start.':A'.$last_row);
              $sheet->mergeCells('B'.$start.':B'.$last_row);
              $sheet->mergeCells('C'.$start.':C'.$last_row);
              $sheet->mergeCells('D'.$start.':D'.$last_row);
              $sheet->mergeCells('E'.$start.':E'.$last_row);
              $sheet->mergeCells('F'.$start.':F'.$last_row);
              $sheet->mergeCells('G'.$start.':G'.$last_row);
              $sheet->mergeCells('H'.$start.':H'.$last_row);
              $sheet->mergeCells('I'.$start.':I'.$last_row);
              $sheet->mergeCells('J'.$start.':J'.$last_row);
              $sheet->mergeCells('K'.$start.':K'.$last_row);
              $sheet->mergeCells('L'.$start.':L'.$last_row);
              $sheet->mergeCells('M'.$start.':M'.$last_row);
              $sheet->mergeCells('N'.$start.':O'.$start);//แผน
              $sheet->mergeCells('N'.($start+1).':O'.($start+1));//ผล
              $sheet->mergeCells('N'.($start+2).':N'.($start+3));//แผนเงิน
              $sheet->mergeCells('N'.($start+4).':N'.($start+5));//แผนผล
              $sheet->mergeCells('N'.($start+6).':O'.($start+6));//หมายเหตุ
              $sheet->mergeCells('N'.($start+7).':O'.($start+7));//ค่าใช้จ่ายที่เหลือ

              //เซตตัวอักษรเป็นแนวตั้ง
              $sheet->getStyle('A'.$start.':C'.$start)->getAlignment()->setTextRotation(90);
              $sheet->getStyle('E'.$start.':M'.$start)->getAlignment()->setTextRotation(90);

              //เซตกึ่งกลางแนวตั้ง
              $sheet->getStyle('A'.$start.':M'.$start)->applyFromArray($styleArray);

              //เซตกึ่งกลางแนวนอน
              $sheet->getStyle("N$start:O$last_row")->getAlignment()->setHorizontal('center');
              $sheet->getStyle("P$start:R$index2")->getAlignment()->setHorizontal('center');//ไตรมาสที่ 1
              $sheet->getStyle("T$start:V$index2")->getAlignment()->setHorizontal('center');//ไตรมาสที่ 2
              $sheet->getStyle("X$start:Z$index2")->getAlignment()->setHorizontal('center');//ไตรมาสที่ 3
              $sheet->getStyle("AB$start:AD$index2")->getAlignment()->setHorizontal('center');//ไตรมาสที่ 4

              //เซตฟอร์น
              $sheet->getStyle("O$index3:O$index6")->getFont()->setSize(9);

              //เซตรูปแบบตัวเลข
              $sheet->getStyle("P$index3:AF$last_row")->getNumberFormat()->setFormatCode("#,##0");

              //เซตตัวหนา ขีดเส้นใต้
              $sheet->getStyle("AF$start:AF$last_row")->getFont()->setBold(true)->setUnderline(true);

              $start += 8;
          }

          $filename = 'Performance_'.date('Hi_dmY').'.xlsx';
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment; filename="'.$filename.'"');
          $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
          $writer->save("php://output");

          exit;

        }

    }

    //จัดการแยกรายละเอียดข้อมูล แผน
    public function formatPlan($plans, $quarter, $month1, $month2, $month3){

        $operations = $plan_allowances = $plan_foods = [$month1=>[], $month2=>[], $month3=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 1 2 3 ของไตรมาส
        $meeting = '';
        if(array_key_exists($quarter, $plans)){

          foreach ($plans[$quarter] as $key_plan => $plan) {

            $status_operation = $plan->status_operation;//สถานะการดำเนินงาน

            $startmonth = explode('-', $plan->startdate)[1];//เดือนเริ่ม
            $endmonth = explode('-', $plan->enddate)[1];//เดือนสิ้นสุด

            //เก็บอักษรย่อของกิจกรรม
            if($startmonth == $month1){//ถ้าคาบเกี่ยวเดือนที่ 1 ของไตรมาส
              $operations[$month1][] = $status_operation->acronym;
            }

            if($startmonth == $month2 || ($startmonth == $month1 && $endmonth >= $month2)){//ถ้าคาบเกี่ยวเดือนที่ 2 ของไตรมาส
              $operations[$month2][] = $status_operation->acronym;
            }

            if($endmonth == $month3){//ถ้าคาบเกี่ยวเดือนที่ 3 ของไตรมาส
              $operations[$month3][] = $status_operation->acronym;
            }

            //เก็บค่าอาหาร ค่าเบี้ยประชุม
            if($startmonth == $month1){//ถ้ามีกิจกรรมเดือน 1 ด้วย

              $plan_allowances[$month1][] = $plan->sum_g + $plan->sum_subg;
              $plan_foods[$month1][] = $plan->sum_attendees;

            }elseif($startmonth == $month2 || ($startmonth == $month1 && $endmonth >= $month2)){//ถ้ามีกิจกรรมเดือน 2 ด้วย

              $plan_allowances[$month2][] = $plan->sum_g + $plan->sum_subg;
              $plan_foods[$month2][] = $plan->sum_attendees;

            }elseif($endmonth == $month3){//ถ้ามีกิจกรรมเดือน 3 ด้วย

              $plan_allowances[$month3][] = $plan->sum_g + $plan->sum_subg;
              $plan_foods[$month3][] = $plan->sum_attendees;

            }

            //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
            if($status_operation->budget_state=='1'){
              $meeting++;
            }

          }

        }

        return array('operations' => $operations,
                     'plan_allowances' => $plan_allowances,
                     'plan_foods' => $plan_foods,
                     'meeting' => $meeting
                    );

    }

    //จัดการแยกรายละเอียดข้อมูล ผล
    public function formatResult($results, $quarter, $month1, $month2, $month3){

        $operations = $result_allowances = $result_foods = [$month1=>[], $month2=>[], $month3=>[]]; //สถานะการดำเนินกิจกรรมเดือนที่ 10 11 12
        $meeting = '';
        if(array_key_exists($quarter, $results)){

          foreach ($results[$quarter] as $key_result => $result) {

            $status_operation = $result->status_operation;//สถานะการดำเนินงาน

            $startmonth = explode('-', $result->startdate)[1];//เดือนเริ่ม
            $endmonth = explode('-', $result->enddate)[1];//เดือนสิ้นสุด

            //เก็บอักษรย่อของกิจกรรม
            if($startmonth == $month1){//ถ้าคาบเกี่ยวเดือนที่ 10
              $operations[$month1][] = $status_operation->acronym;
            }

            if($startmonth == $month2 || ($startmonth == $month1 && $endmonth >= $month2)){//ถ้าคาบเกี่ยวเดือนที่ 11
              $operations[$month2][] = $status_operation->acronym;
            }

            if($endmonth == $month3){//ถ้าคาบเกี่ยวเดือนที่ 12
              $operations[$month3][] = $status_operation->acronym;
            }

            //เก็บค่าอาหาร ค่าเบี้ยประชุม
            if($startmonth == $month1){//ถ้ามีกิจกรรมเดือน 10 ด้วย

              $result_allowances[$month1][] = $result->sum_g + $result->sum_subg;
              $result_foods[$month1][] = $result->sum_attendees;

            }elseif($startmonth == $month2 || ($startmonth == $month1 && $endmonth >= $month2)){//ถ้ามีกิจกรรมเดือน 11 ด้วย

              $result_allowances[$month2][] = $result->sum_g + $result->sum_subg;
              $result_foods[$month2][] = $result->sum_attendees;

            }elseif($endmonth == $month3){//ถ้ามีกิจกรรมเดือน 12 ด้วย

              $result_allowances[$month3][] = $result->sum_g + $result->sum_subg;
              $result_foods[$month3][] = $result->sum_attendees;

            }

            //นับจำนวนครั้งที่ประชุมเฉพาะที่เปิดสถานะงบประมาณ
            if($status_operation->budget_state=='1'){
              $meeting++;
            }

          }

        }

        return array('operations' => $operations,
                     'allowances' => $result_allowances,
                     'foods' => $result_foods,
                     'meeting' => $meeting
                    );

    }

    private function getQuery($filter){

        $Query = new SetStandard;

        if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                $query->where('title', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhere('title_en', 'LIKE', "%{$filter['filter_search']}%");
                         });
        }

        if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
        }

        // if ($filter['filter_year']!='') {
        //     $id_tis_set_standards = SetStandardPlan::where('year', $filter['filter_year'])->pluck('id_tis_set_standards');
        //     $Query = $Query->whereIn('id', $id_tis_set_standards);
        // }

        if ($filter['filter_year']!='') {
            $Query = $Query->where('start_year', $filter['filter_year']);
        }

        if ($filter['filter_start_quarter']!='') {
            $id_tis_set_standards = SetStandardPlan::where('quarter', '>=', $filter['filter_start_quarter'])->pluck('id_tis_set_standards');
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_end_quarter']!='') {
            $id_tis_set_standards = SetStandardPlan::where('quarter', '<=', $filter['filter_end_quarter'])->pluck('id_tis_set_standards');
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_start_month']!='' && $filter['filter_end_month']=='') {
            $id_tis_set_standards = SetStandardPlan::select('id_tis_set_standards')->whereMonth('startdate', '>=', $filter['filter_start_month']);
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_start_month']!='' && $filter['filter_end_month']!='') {
            $id_tis_set_standards = SetStandardPlan::select('id_tis_set_standards')->whereMonth('startdate', '>=', $filter['filter_start_month'])->whereMonth('startdate', '<=', $filter['filter_end_month']);
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_end_month']!='' && $filter['filter_start_month']=='') {
            $id_tis_set_standards = SetStandardPlan::select('id_tis_set_standards')->whereMonth('startdate', '<=', $filter['filter_end_month']);
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_operation']!='') {
            $id_tis_set_standards = SetStandardPlan::where('statusOperation_id', $filter['filter_operation'])->pluck('id_tis_set_standards');
            $Query = $Query->whereIn('id', $id_tis_set_standards);
        }

        if ($filter['filter_made']!='') {
            $Query = $Query->where('made_by', $filter['filter_made']);
        }

        if ($filter['filter_standard_format']!='') {
            $Query = $Query->where('standard_format_id', $filter['filter_standard_format']);
        }

        if ($filter['filter_plan']!='') {
            $Query = $Query->where('plan_year', $filter['filter_plan']);
        }

        if ($filter['filter_method']!='') {
            $Query = $Query->where('method_id', $filter['filter_method']);
        }

        return $Query;

    }


}
