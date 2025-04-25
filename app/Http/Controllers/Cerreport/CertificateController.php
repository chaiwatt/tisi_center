<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use HP;
use DB;
use Yajra\Datatables\Datatables;
use App\Models\Certificate\Tracking;

use App\CertificateExport;
use App\Models\Certify\ApplicantCB\CertiCBExport;
use App\Models\Certify\ApplicantIB\CertiIBExport;

use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\ApplicantIB\CertiIb; 
use App\Models\Certify\ApplicantCB\CertiCb; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class CertificateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $model = str_slug('cerreport-certificate','-');
        if(auth()->user()->can('view-'.$model)) {
 
            return view('cerreport.certificate.index');
        }
        abort(403);
    }

     
    public function data_list(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_certificate_type = $request->input('filter_certificate_type');
      
        $query = Tracking::query()
                            ->select(DB::raw('SUM(IF(`ref_id`>0, 1, 0)) AS sum_ref,ref_id,ref_table,id,created_at,certificate_type'))
                            ->when($filter_search, function ($query, $filter_search){
                                $search_full = str_replace(' ', '', $filter_search ); 
                                $query->where(function ($query2) use($search_full) {
                
                                    $export_labs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                           ->leftJoin((new CertificateExport)->getTable().' AS export_labs', function($join) use ($search_full)
                                                            {   
                                                                $certi_labs = CertiLab::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                                                        ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                                $join->on('tracking.ref_id' , '=', 'export_labs.id')
                                                                      ->Where(DB::raw("REPLACE(export_labs.certificate_no,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_labs.certificate_for',$certi_labs)   ;     
                                                            })
                                                        ->whereNotNull('export_labs.certificate_no') 
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');
 
                                      $export_ibs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                            ->leftJoin((new CertiIBExport)->getTable().' AS export_ibs', function($join) use ($search_full)
                                                            {   
                                                                $certi_ibs = CertiIb::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name_unit,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                                $join->on('tracking.ref_id' , '=', 'export_ibs.id')
                                                                      ->Where(DB::raw("REPLACE(export_ibs.certificate,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_ibs.app_certi_ib_id',$certi_ibs)  ;     
                                                            })
                                                        ->whereNotNull('export_ibs.certificate')  
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');

                                       $export_cbs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                            ->leftJoin((new CertiCBExport)->getTable().' AS export_cbs', function($join) use ($search_full)
                                                            {   
                                                                $certi_ibs = CertiIb::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name_standard,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                                $join->on('tracking.ref_id' , '=', 'export_cbs.id')
                                                                      ->Where(DB::raw("REPLACE(export_cbs.certificate,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_cbs.app_certi_cb_id',$certi_ibs)     ;     
                                                            })
                                                        ->whereNotNull('export_cbs.certificate')
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');               

                                    return  $query2->whereIn('id', $export_labs)->OrwhereIn('id', $export_ibs)->OrwhereIn('id', $export_cbs);
                                });
                             }) 
                            ->when($filter_certificate_type, function ($query, $filter_certificate_type){
                            return  $query->where('certificate_type', $filter_certificate_type);
                            })
                          ->whereIn('status_id',[12])
                          ->groupBy('ref_id')
                          ->groupBy('ref_table')
                          ->orderby('id','desc') ;
                  
      return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                        return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('certificate', function ($item) {
                                        $text = '';
                                    if($item->certificate_type == 1){
                                        $text =   !empty($item->certificate_export_to->certificate)? $item->certificate_export_to->certificate:'';
                                    }else if($item->certificate_type == 2){
                                        $text =   !empty($item->certificate_export_to->certificate)? $item->certificate_export_to->certificate:'';
                                    }else{
                                        $text =   !empty($item->certificate_export_to->certificate_no)? $item->certificate_export_to->certificate_no:'';
                                    }
                                    return $text;
                            })
                           ->addColumn('name', function ($item) {
                                        $text = '';
                                    if($item->certificate_type == 1){
                                        $text =   !empty($item->certificate_export_to->CertiCbTo->name)? $item->certificate_export_to->CertiCbTo->name:'';
                                        $text .=   !empty($item->certificate_export_to->CertiCbTo->tax_id)? '<br/>'.$item->certificate_export_to->CertiCbTo->tax_id:'';
                                    }else if($item->certificate_type == 2){
                                        $text =   !empty($item->certificate_export_to->CertiIBCostTo->name)? $item->certificate_export_to->CertiIBCostTo->name:'';
                                        $text .=   !empty($item->certificate_export_to->CertiIBCostTo->tax_id)? '<br/>'.$item->certificate_export_to->CertiIBCostTo->tax_id:'';
                                    }else{
                                        $text =   !empty($item->certificate_export_to->CertiLabTo->name)? $item->certificate_export_to->CertiLabTo->name:'';
                                        $text .=   !empty($item->certificate_export_to->CertiLabTo->tax_id)? '<br/>'.$item->certificate_export_to->CertiLabTo->tax_id:'';
                                    }
                                    return $text;
                            })
                          ->addColumn('name_unit', function ($item) {
                                        $text = '';
                                    if($item->certificate_type == 1){
                                        $text =   !empty($item->certificate_export_to->CertiCbTo->name_standard)? $item->certificate_export_to->CertiCbTo->name_standard:'';
                                    }else if($item->certificate_type == 2){
                                        $text =   !empty($item->certificate_export_to->CertiIBCostTo->name_unit)? $item->certificate_export_to->CertiIBCostTo->name_unit:'';
                                    }else{
                                        $text =   !empty($item->certificate_export_to->CertiLabTo->lab_name)? $item->certificate_export_to->CertiLabTo->lab_name:'';
                                    }
                                    return $text;
                            })
                          ->addColumn('sum_ref', function ($item) {
                                    return $item->sum_ref ?? 0;
                            })
                            ->addColumn('action', function ($item) {
                                    return HP::buttonAction( $item->id, 'cerreport/certificates','Cerreport\\CertificateController@destroy', 'cerreport-certificate',true,false,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id','desc');
                            })
                            ->rawColumns(['checkbox', 'name','action'])
                            ->make(true); 
                                    
    }

    public function show($id)
    {
        $model = str_slug('cerreport-certificate','-');
        if(auth()->user()->can('view-'.$model)) {
            $tracking = Tracking::findOrFail($id);
      
          $trackings = Tracking::where('ref_id',$tracking->ref_id)
                           ->where('ref_table',$tracking->ref_table)
                           ->whereIn('status_id',[12])
                          ->orderby('id','desc')->get() ;

            return view('cerreport.certificate.show', compact('tracking', 'trackings'));
        }
        return   abort(403);
    }

    public function export_excel(Request $request)
    {

        $filter_search = $request->input('filter_search');
        $filter_certificate_type = $request->input('filter_certificate_type');
        $filter_search = $request->input('filter_search');
        $filter_type = $request->input('filter_type');
        $filter_certify = $request->input('filter_certify');
        $filter_state = $request->input('filter_state');
        $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
        $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB
        $query = Tracking::query()
                            ->select(DB::raw('SUM(IF(`ref_id`>0, 1, 0)) AS sum_ref,ref_id,ref_table,id,created_at,certificate_type'))
                            ->when($filter_search, function ($query, $filter_search){
                                $search_full = str_replace(' ', '', $filter_search ); 
                                $query->where(function ($query2) use($search_full) {
                
                                    $export_labs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                           ->leftJoin((new CertificateExport)->getTable().' AS export_labs', function($join) use ($search_full)
                                                            {   
                                                                $certi_labs = CertiLab::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                                                        ->OrWhere(DB::raw("REPLACE(lab_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                                $join->on('tracking.ref_id' , '=', 'export_labs.id')
                                                                      ->Where(DB::raw("REPLACE(export_labs.certificate_no,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_labs.certificate_for',$certi_labs)   ;     
                                                            })
                                                        ->whereNotNull('export_labs.certificate_no') 
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');
 
                                      $export_ibs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                            ->leftJoin((new CertiIBExport)->getTable().' AS export_ibs', function($join) use ($search_full)
                                                            {   
                                                                $certi_ibs = CertiIb::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name_unit,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                                $join->on('tracking.ref_id' , '=', 'export_ibs.id')
                                                                      ->Where(DB::raw("REPLACE(export_ibs.certificate,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_ibs.app_certi_ib_id',$certi_ibs)  ;     
                                                            })
                                                        ->whereNotNull('export_ibs.certificate')  
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');

                                       $export_cbs =  DB::table((new Tracking)->getTable().' AS tracking')
                                                            ->select(DB::raw('tracking.id'))
                                                            ->leftJoin((new CertiCBExport)->getTable().' AS export_cbs', function($join) use ($search_full)
                                                            {   
                                                                $certi_ibs = CertiIb::select('id')
                                                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                                        ->OrWhere(DB::raw("REPLACE(name_standard,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                                $join->on('tracking.ref_id' , '=', 'export_cbs.id')
                                                                      ->Where(DB::raw("REPLACE(export_cbs.certificate,' ','')"), 'LIKE', "%".$search_full."%")->OrWhereIn('export_cbs.app_certi_cb_id',$certi_ibs)     ;     
                                                            })
                                                        ->whereNotNull('export_cbs.certificate')
                                                        ->groupBy('tracking.ref_id')
                                                        ->groupBy('tracking.ref_table')
                                                        ->orderby('tracking.id','desc');               

                                    $query2->whereIn('id', $export_labs)->OrwhereIn('id', $export_ibs)->OrwhereIn('id', $export_cbs);
                                });
                             }) 
                            ->when($filter_certificate_type, function ($query, $filter_certificate_type){
                            return  $query->where('certificate_type', $filter_certificate_type);
                            })
                          ->whereIn('status_id',[12])
                          ->groupBy('ref_id')
                          ->groupBy('ref_table')
                          ->orderby('id','desc')
                             ->get();
   

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงานติดตามใบรับรอง');
            $sheet->mergeCells('A1:E1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:E2');
            $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'ลำดับ');
            $sheet->setCellValue('B3', 'เลขที่ใบรับรอง');
            $sheet->setCellValue('C3', 'ผู้รับใบรับรอง');
            $sheet->setCellValue('D3', 'ชื่อห้องปฏิบัติการ/ชื่อหน่วยตรวจสอบ/ชื่อหน่วยรับรอง');
            $sheet->setCellValue('E3', 'จำนวน');
            

            $row = 3; //start row
        if(count($query) > 0){
            foreach ($query as $key => $item) {

                    $certificate = '';
                if($item->certificate_type == 1){
                    $certificate =   !empty($item->certificate_export_to->certificate)? $item->certificate_export_to->certificate:'';
                }else if($item->certificate_type == 2){
                    $certificate =   !empty($item->certificate_export_to->certificate)? $item->certificate_export_to->certificate:'';
                }else{
                    $certificate =   !empty($item->certificate_export_to->certificate_no)? $item->certificate_export_to->certificate_no:'';
                }

                    $name = '';
                if($item->certificate_type == 1){
                    $name =   !empty($item->certificate_export_to->CertiCbTo->name)? $item->certificate_export_to->CertiCbTo->name:'';
                    $name .=   !empty($item->certificate_export_to->CertiCbTo->tax_id)?  "\n".$item->certificate_export_to->CertiCbTo->tax_id:'';
                }else if($item->certificate_type == 2){
                    $name =   !empty($item->certificate_export_to->CertiIBCostTo->name)? $item->certificate_export_to->CertiIBCostTo->name:'';
                    $name .=   !empty($item->certificate_export_to->CertiIBCostTo->tax_id)?  "\n".$item->certificate_export_to->CertiIBCostTo->tax_id:'';
                }else{
                    $name =   !empty($item->certificate_export_to->CertiLabTo->name)? $item->certificate_export_to->CertiLabTo->name:'';
                    $name .=   !empty($item->certificate_export_to->CertiLabTo->tax_id)? "\n".$item->certificate_export_to->CertiLabTo->tax_id:'';
                }

                $text = '';
                if($item->certificate_type == 1){
                    $text =   !empty($item->certificate_export_to->CertiCbTo->name_standard)? $item->certificate_export_to->CertiCbTo->name_standard:'';
                }else if($item->certificate_type == 2){
                    $text =   !empty($item->certificate_export_to->CertiIBCostTo->name_unit)? $item->certificate_export_to->CertiIBCostTo->name_unit:'';
                }else{
                    $text =   !empty($item->certificate_export_to->CertiLabTo->lab_name)? $item->certificate_export_to->CertiLabTo->lab_name:'';
                }

                $row++;
                $sheet->setCellValue('A' . $row,$key+1);
                $sheet->setCellValue('B' . $row, $certificate);
                $sheet->setCellValue('C' . $row, $name);
                $sheet->setCellValue('D' . $row, $text);
                $sheet->setCellValue('E' . $row, $item->sum_ref ?? 0);
               
            }
        }
              //ใส่ขอบดำ
              $style_borders = [
                'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                ]
            ];
            $sheet->getStyle('A3:E'.$row)->applyFromArray($style_borders);


            //Set Column Width
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
 
            $filename = 'รายงานติดตามใบรับรอง_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }


 
}
