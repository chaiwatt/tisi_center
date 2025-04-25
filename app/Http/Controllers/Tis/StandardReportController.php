<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\Standard;
use App\report_volume;
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

class StandardReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'esurv_attach/inform_calibrate/';

    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standard_report', '-');
        if (auth()->user()->can('view-' . $model)) {

            $filter = [];
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_year'] = $request->get('filter_year', '');
            $filter['filter_standard_formats'] = $request->get('filter_standard_formats', '');
            $filter['filter_standard_types'] = $request->get('filter_standard_types', '');
            $filter['filter_product_groups'] = $request->get('filter_product_groups', '');
            $filter['filter_set_formats'] = $request->get('filter_set_formats', '');
            $filter['filter_industry_targets'] = $request->get('filter_industry_targets', '');
            $filter['filter_methods'] = $request->get('filter_methods', '');

            $filter['perPage'] = $request->get('perPage', 10);

            $Query = $this->getQuery($request);

            $items = $Query->sortable()
                ->paginate($filter['perPage']);

            $attach_path = $this->attach_path;

            return view('tis.standard_report.index', compact('items', 'filter', 'attach_path'));

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
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
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
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {

    }

    public function export_excel(Request $request)
    {

        $model = str_slug('standard_report', '-');
        if (auth()->user()->can('view-' . $model)) {

            //Data Search
//            $filter_year = $request->get('filter_year', '');
//            $filter_year = !is_null($filter_year) ? $filter_year : '-';
//
//            $filter_standard_formats = $request->get('filter_standard_formats');
//            $filter_standard_formats = !is_null($filter_standard_formats) ? $filter_standard_formats : '-';
//
//            $filter_standard_types = $request->get('filter_standard_types');
//            $filter_standard_types = !is_null($filter_standard_types) ? $filter_standard_types : '-';
//
//            $filter_product_groups = $request->get('filter_product_groups');
//            $filter_product_groups = !is_null($filter_product_groups) ? $filter_product_groups : '-';
//
//            $filter_set_formats = $request->get('filter_set_formats', '');
//            $filter_set_formats = !is_null($filter_set_formats) ? $filter_set_formats : '-';
//
//            $filter_industry_targets = $request->get('filter_industry_targets');
//            $filter_industry_targets = !is_null($filter_industry_targets) ? $filter_industry_targets : '-';
//
//            $filter_methods = $request->get('filter_methods');
//            $filter_methods = !is_null($filter_methods) ? $filter_methods : '-';

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงานข้อมูลมาตรฐานที่เปิดใช้ในปัจจุบัน');
            $sheet->mergeCells('A1:J1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);


            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:J2');
            $sheet->getStyle('A2:J2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'ปีที่เริ่ม');
            $sheet->setCellValue('B3', 'เลขที่ มอก.');
            $sheet->setCellValue('C3', 'ชื่อ มอก.');
            $sheet->setCellValue('D3', 'รูปแบบมาตรฐาน');
            $sheet->setCellValue('E3', 'ประเภท');
            $sheet->setCellValue('F3', 'วันที่ประกาศใช้/วันที่บังคับใช้');
            $sheet->setCellValue('G3', 'กลุ่มผลิตภัณฑ์/สาขา');
            $sheet->setCellValue('H3', 'รูปแบบการกำหนด');
            $sheet->setCellValue('I3', 'อุตสาหกรรมเป้าหมาย');
            $sheet->setCellValue('J3', 'วิธีจัดทำ');
            $sheet->getStyle('A3:J3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A3:J3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('95DCFF');

            //แสดงรายการเนื้อหาที่แจ้งเข้ามา
            $Query = $this->getQuery($request);
            $items = $Query->sortable()
               ->get();

            $row = 3; //start row
            foreach ($items as $item) {

                $row++;
                $sheet->setCellValue('A' . $row, $item->tis_year);
                $sheet->setCellValue('B' . $row, $item->tis_no.!empty($item->tis_book)?"เล่ม".$item->tis_book:''."-".$item->tis_year);
                $sheet->setCellValue('C' . $row, $item->title. '<br>' .$item->title_en);
                $sheet->setCellValue('D' . $row, $item->StandardFormatName);
                $sheet->setCellValue('E' . $row, $item->StandardTypeName);
                $sheet->setCellValue('F' . $row, HP::DateThai($item->issue_date));
                $sheet->setCellValue('G' . $row, $item->ProductGroupName);
                $sheet->setCellValue('H' . $row, $item->SetFormatName);
                $sheet->setCellValue('I' . $row, $item->InductryTargetName);
                $sheet->setCellValue('J' . $row, $item->MethodName);
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
            $sheet->getStyle('A3:' . 'J' . $row)->applyFromArray($styleArray);

            //Set Text Top
            $sheet->getStyle('A3:' . 'J' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

            //Set Column Width
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setWidth(20);
            $sheet->getColumnDimension('F')->setWidth(20);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);

            $filename = 'Standard_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");

            exit;

        }

    }

    private function getQuery($request)
    {

        $filter = [];

        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['filter_status'] = $request->get('filter_status', '');
        $filter['filter_year'] = $request->get('filter_year', '');
        $filter['filter_standard_formats'] = $request->get('filter_standard_formats', '');
        $filter['filter_standard_types'] = $request->get('filter_standard_types', '');
        $filter['filter_product_groups'] = $request->get('filter_product_groups', '');
        $filter['filter_set_formats'] = $request->get('filter_set_formats', '');
        $filter['filter_industry_targets'] = $request->get('filter_industry_targets', '');
        $filter['filter_methods'] = $request->get('filter_methods', '');


        $Query = new Standard();

        if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                $query->where('title', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhere('title_en', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhere('remark', 'LIKE', "%{$filter['filter_search']}%");
                         });
        }

        if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
        }

        if ($filter['filter_year'] != '') {
            $Query = $Query->where('tis_year', $filter['filter_year']);
        }

        if ($filter['filter_standard_formats'] != '') {
            $Query = $Query->where('standard_format_id', $filter['filter_standard_formats']);
        }

        if ($filter['filter_standard_types'] != '') {
            $Query = $Query->where('standard_type_id', $filter['filter_standard_types']);
        }

        if ($filter['filter_product_groups'] != '') {
            $Query = $Query->where('product_group_id', $filter['filter_product_groups']);
        }

        if ($filter['filter_set_formats'] != '') {
            $Query = $Query->where('set_format_id', $filter['filter_set_formats']);
        }

        if ($filter['filter_industry_targets'] != '') {
            $Query = $Query->where('industry_target_id', $filter['filter_industry_targets']);
        }

        if ($filter['filter_methods'] != '') {
            $Query = $Query->where('method_id', $filter['filter_methods']);
        }

        return $Query;

    }

}
