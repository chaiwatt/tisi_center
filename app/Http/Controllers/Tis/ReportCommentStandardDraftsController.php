<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\CommentStandardDraft;
use App\Models\Tis\CommentStandardDraftDetail;
use App\report_volume;
use Illuminate\Http\Request;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Storage;
use HP;

class ReportCommentStandardDraftsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/comment_standard_draft/';

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

            $filter['filter_year'] = $request->get('filter_year', '');
            $filter['filter_standard_formats'] = $request->get('filter_standard_formats', '');
            $filter['filter_standard_types'] = $request->get('filter_standard_types', '');
            $filter['filter_product_groups'] = $request->get('filter_product_groups', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_industry_targets'] = $request->get('filter_industry_targets', '');
            $filter['filter_comment'] = $request->get('filter_comment', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = $this->getQuery($request);

            $items = $Query->sortable()
                ->paginate($filter['perPage']);

            $attach_path = $this->attach_path;

            return view('tis.report_comment_standard_drafts.index', compact('items', 'filter', 'attach_path'));

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
        $model = str_slug('report_comment_standard_drafts','-');
        if(auth()->user()->can('view-'.$model)) {
            $comment_standard_draft = CommentStandardDraft::findOrFail($id);
            $comment_standard_draft_detail = CommentStandardDraftDetail::where('comment_standard_draft_id',$comment_standard_draft->id)->get();
            //ไฟล์แนบ
            $attachs = json_decode($comment_standard_draft['attach']);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];

            $attach_path = $this->attach_path;



            return view('tis/report_comment_standard_drafts/show', compact('comment_standard_draft', 'comment_standard_draft_detail', 'attachs', 'attach_path'));
        }
        abort(403);
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

        $model = str_slug('report_comment_standard_drafts', '-');
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
            $sheet->setCellValue('A1', 'ระบบรายงานข้อคิดเห็นต่อร่างมาตรฐาน');
            $sheet->mergeCells('A1:I1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:I2');
            $sheet->getStyle('A2:I2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'วันที่');
            $sheet->setCellValue('B3', 'ผู้ให้ข้อคิดเห็น');
            $sheet->setCellValue('C3', 'หน่วยงาน');
            $sheet->setCellValue('D3', 'เลข มอก.');
            $sheet->setCellValue('E3', 'ชื่อมาตรฐาน');
            $sheet->setCellValue('F3', 'กลุ่มผลิตภัณฑ์/สาขา');
            $sheet->setCellValue('G3', 'เบอร์โทร');
            $sheet->setCellValue('H3', 'ความคิดเห็น');
            $sheet->setCellValue('I3', 'E-mail');
            $sheet->getStyle('A3:I3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A3:I3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('95DCFF');

            //แสดงรายการเนื้อหาที่แจ้งเข้ามา
            $Query = $this->getQuery($request);
            $items = $Query->sortable()
               ->get();

            $row = 3; //start row
            foreach ($items as $item) {

                $row++;
                $sheet->setCellValue('A' . $row, HP::DateThai($item->created_at));
                $sheet->setCellValue('B' . $row, $item->name);
                $sheet->setCellValue('C' . $row, $item->departmentName);
                $sheet->setCellValue('D' . $row, $item->StandardFormatName);
                $sheet->setCellValue('E' . $row, $item->StandardTypeName);
                $sheet->setCellValue('F' . $row, $item->tel);
                $sheet->setCellValue('G' . $row, $item->tel);
                $sheet->setCellValue('H' . $row, $item->CommentName);
                $sheet->setCellValue('I' . $row, $item->email);
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
            $sheet->getStyle('A3:' . 'I' . $row)->applyFromArray($styleArray);

            //Set Text Top
            $sheet->getStyle('A3:' . 'I' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

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

            $filename = 'Comment-standard_' . date('Hi_dmY') . '.xlsx';
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

        $filter['filter_year'] = $request->get('filter_year', '');
        $filter['filter_standard_formats'] = $request->get('filter_standard_formats', '');
        $filter['filter_standard_types'] = $request->get('filter_standard_types', '');
        $filter['filter_product_groups'] = $request->get('filter_product_groups', '');
        $filter['filter_department'] = $request->get('filter_department', '');
        $filter['filter_industry_targets'] = $request->get('filter_industry_targets', '');
        $filter['filter_comment'] = $request->get('filter_comment', '');
        $filter['filter_status'] = $request->get('filter_status', '');
        $filter['filter_search'] = $request->get('filter_search', '');


        $Query = new CommentStandardDraft();

        if ($filter['filter_search'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_search'];
                                    $query->where('name', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('tel', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('email', 'LIKE', "%{$search_text}%");
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

        if ($filter['filter_department'] != '') {
            $Query = $Query->where('department_id', $filter['filter_department']);
        }

        if ($filter['filter_industry_targets'] != '') {
            $Query = $Query->where('industry_target_id', $filter['filter_industry_targets']);
        }

        if ($filter['filter_comment'] != '') {
            $Query = $Query->where('comment', $filter['filter_comment']);
        }

        return $Query;

    }

}
