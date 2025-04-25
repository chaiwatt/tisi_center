<?php

namespace App\Http\Controllers\Laws\Report;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Yajra\Datatables\Datatables;
use HP;
use DB;
use HP_Law;
use stdClass;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;

use App\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

 
use App\Models\Law\Cases\LawCasesForm;  

class LawPaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_payments_detail  = $request->input('filter_payments_detail'); 
        $filter_lawyer_by  = $request->input('filter_lawyer_by'); 
 
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;
        $filter_paid_start_date       = !empty($request->input('filter_paid_start_date'))? HP::convertDate($request->input('filter_paid_start_date'),true):null;
        $filter_paid_end_date         = !empty($request->input('filter_paid_end_date'))? HP::convertDate($request->input('filter_paid_end_date'),true):null;

        $filter_users     = $request->input('filter_users');
        $model                   = str_slug('law-cases-payment','-');
 
        $query =  LawCasesForm::query()
                                        ->where(function($query){
                                            $query->whereIn('status',['5','7','8','9','10','11','12','13','14','15']);
                                        })
                                        ->with(array('law_cases_payments_many' => function($query2) {
                                                return  $query2->orderBy('id', 'DESC');
                                        }))  
                                        ->whereHas('law_cases_payments_many', function ($query2) {
                                            return  $query2->WhereNotNull('ref_id');
                                        })
                                        ->with(['law_cases_compare_to'])  
                                        ->whereHas('law_cases_compare_to', function ($query2) {
                                            return  $query2->WhereNotNull('law_cases_id');
                                        })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%");  
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");  
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                        ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_status)  {
                                                       return  $query2->Where('paid_status',$filter_status);
                                                 });  
                                        })
                                        ->when($filter_lawyer_by, function ($query, $filter_lawyer_by){
                                                return $query->Where('lawyer_by',$filter_lawyer_by);
                                        })
                                        ->when($filter_payments_detail, function ($query, $filter_payments_detail){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_payments_detail) {
                                                      return   $query2->whereHas('law_cases_payments_detail_to', function ($query3)  use($filter_payments_detail) {
                                                               return  $query3->Where('fee_name',$filter_payments_detail);
                                                         });  
                                                      });  
                                            
                                         })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                              return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date,$filter_end_date) {
                                                       return $query2->whereBetween('end_date',[$filter_start_date,$filter_end_date]);
                                                    });  
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date) {
                                                            return $query2->whereDate('end_date',$filter_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_paid_start_date, function ($query, $filter_paid_start_date) use($filter_paid_end_date){
                                            if(!is_null($filter_paid_start_date) && !is_null($filter_paid_end_date) ){
                                              return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_paid_start_date,$filter_paid_end_date) {
                                                         return $query2->whereDate('paid_date', '>=', $filter_paid_start_date)
                                                                       ->whereDate('paid_date', '<=', $filter_paid_end_date)
                                                                       ->Where('paid_status','2');
                                                    });  
                                            }else if(!is_null($filter_paid_start_date) && is_null($filter_paid_end_date)){
                                                return  $query->with(['law_cases_payments_many'])  
                                                        ->whereHas('law_cases_payments_many', function ($query2)  use($filter_paid_start_date) {
                                                        return $query2->whereDate('paid_date',$filter_paid_start_date)
                                                        ->Where('paid_status','2');
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_users, function ($query, $filter_users){
                                            return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                          ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_users)  {
                                                            if($filter_users == 'null'){
                                                                return  $query2->WhereNull('updated_by')->Where('paid_status','2');
                                                            }else{
                                                                return  $query2->Where('updated_by',$filter_users);
                                                            }
                                                       
                                                        });  
                                        }) ;
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number) ? $item->case_number : '';
                            })
                            ->addColumn('offend_name', function ($item) {
                                    $text  = !empty($item->offend_name) ? $item->offend_name : '';
                                    $text  .= !empty($item->offend_taxid) ? '<br/>'.$item->offend_taxid : '';
                                return $text;
                            })
                            ->addColumn('fee_name', function ($item) {
                                return  !empty($item->law_cases_payments_to->law_cases_payments_detail_to->fee_name) ?  $item->law_cases_payments_to->law_cases_payments_detail_to->fee_name : ''; 
                            })  
                            ->addColumn('amount', function ($item) {
                                return  !empty($item->law_cases_payments_to->law_cases_payments_detail_to->amount) ?   number_format($item->law_cases_payments_to->law_cases_payments_detail_to->amount,2) : ''; 
                            })
                            ->addColumn('offend_power', function ($item) {
                                return (!empty($item->offend_power) && is_array($item->offend_power)) ? implode(" ",$item->offend_power) : null;
                            })
                            ->addColumn('punish', function ($item) {
                                return !empty($item->law_cases_result_to->PunishNumber)? $item->law_cases_result_to->PunishNumber:null;
                            })
                            ->addColumn('end_date', function ($item) {
                                $text = '';
                                if( !empty($item->law_cases_payments_to)){
                                    if($item->law_cases_payments_to->paid_status == '1'){
                                       $text =  !empty($item->law_cases_payments_to->NumberOfDaysHtml) ?  '<br/>'.$item->law_cases_payments_to->NumberOfDaysHtml  :  ''; 
                                    }
                                }
                                return !empty($item->law_cases_payments_to->end_date) ?   HP::DateThai($item->law_cases_payments_to->end_date).$text  : '';
                            })
                            ->addColumn('paid_date', function ($item) {
                                  if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                       return  !empty($item->law_cases_payments_to->paid_date) ? HP::DateThai($item->law_cases_payments_to->paid_date)  :  '';
                                 } else{
                                       return  '';
                                }
                            })
                            ->addColumn('status', function ($item) { 
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                    return  '<span class="text-success">ชำระเงินแล้ว</span>';
                                 } else{
                                    return  '<span class="text-danger">ยังไม่ชำระเงิน</span>';
                                }
                            })  
                            ->addColumn('user_updated', function ($item) {
                                $text = '';
                                if(!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2' ){
                                     $text  .=  !empty($item->law_cases_payments_to->user_updated->FullName) ? $item->law_cases_payments_to->user_updated->FullName : "e-Payment";
                                }else{
                                    $text  .=   '-';
                                }
                                return $text;
                            })
                            ->addColumn('lawyer_by', function ($item) {
                                return !empty($item->LawyerName)? $item->LawyerName:null;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'offend_name', 'status', 'end_date', 'user_updated'])
                            ->make(true);
    }


    public function index()
    {
        $model = str_slug('law-report-payments','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/payments",  "name" => 'รายงานการชำระเงินค่าปรับ' ],
            ];
            return view('laws.report.payments.index',compact('breadcrumbs'));
        }
        abort(403);
    }

 
    public function export_excel(Request $request)
    {

        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_payments_detail  = $request->input('filter_payments_detail'); 
        $filter_lawyer_by        = $request->input('filter_lawyer_by'); 
 
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;
        $filter_paid_start_date       = !empty($request->input('filter_paid_start_date'))? HP::convertDate($request->input('filter_paid_start_date'),true):null;
        $filter_paid_end_date         = !empty($request->input('filter_paid_end_date'))? HP::convertDate($request->input('filter_paid_end_date'),true):null;

        $filter_users     = $request->input('filter_users');
        $model                   = str_slug('law-cases-payment','-');
 
        $query =  LawCasesForm::query()
                                        ->where(function($query){
                                            $query->whereIn('status',['5','7','8','9','10','11','12','13','14','15']);
                                        })
                                        ->with(array('law_cases_payments_many' => function($query2) {
                                                return  $query2->orderBy('id', 'DESC');
                                        }))  
                                        ->whereHas('law_cases_payments_many', function ($query2) {
                                            return  $query2->WhereNotNull('ref_id');
                                        })
                                        ->with(['law_cases_compare_to'])  
                                        ->whereHas('law_cases_compare_to', function ($query2) {
                                            return  $query2->WhereNotNull('law_cases_id');
                                        })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%");  
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");  
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                               return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                           ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_status)  {
                                                          return  $query2->Where('paid_status',$filter_status);
                                                    });  
                                        })
                                        ->when($filter_lawyer_by, function ($query, $filter_lawyer_by){
                                                return $query->Where('lawyer_by',$filter_lawyer_by);
                                        })
                                        ->when($filter_payments_detail, function ($query, $filter_payments_detail){
                                            return  $query->whereHas('law_cases_payments_many', function ($query2)  use($filter_payments_detail) {
                                                      return   $query2->whereHas('law_cases_payments_detail_to', function ($query3)  use($filter_payments_detail) {
                                                               return  $query3->Where('fee_name',$filter_payments_detail);
                                                         });  
                                                      });  
                                            
                                         })
                                        ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                            if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                              return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date,$filter_end_date) {
                                                       return $query2->whereBetween('end_date',[$filter_start_date,$filter_end_date]);
                                                    });  
                                            }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_start_date) {
                                                            return $query2->whereDate('end_date',$filter_start_date);
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_paid_start_date, function ($query, $filter_paid_start_date) use($filter_paid_end_date){
                                            if(!is_null($filter_paid_start_date) && !is_null($filter_paid_end_date) ){
                                              return  $query->with(['law_cases_payments_many'])  
                                                            ->whereHas('law_cases_payments_many', function ($query2)  use($filter_paid_start_date,$filter_paid_end_date) {
                                                         return $query2->whereDate('paid_date', '>=', $filter_paid_start_date)
                                                                       ->whereDate('paid_date', '<=', $filter_paid_end_date)
                                                                       ->Where('paid_status','2');
                                                    });  
                                            }else if(!is_null($filter_paid_start_date) && is_null($filter_paid_end_date)){
                                                return  $query->with(['law_cases_payments_many'])  
                                                        ->whereHas('law_cases_payments_many', function ($query2)  use($filter_paid_start_date) {
                                                        return $query2->whereDate('paid_date',$filter_paid_start_date)
                                                        ->Where('paid_status','2');
                                                 });  
                                           
                                            }
                                        })
                                        ->when($filter_users, function ($query, $filter_users){
                                            return  $query->with(['law_cases_payments_cancel_status_to'])  
                                                          ->whereHas('law_cases_payments_cancel_status_to', function ($query2) use ($filter_users)  {
                                                            if($filter_users == 'null'){
                                                                return  $query2->WhereNull('updated_by')->Where('paid_status','2');
                                                            }else{
                                                                return  $query2->Where('updated_by',$filter_users);
                                                            }
                                                       
                                                        });  
                                        }) 
                                         ->orderBy('id', 'DESC')
                                         ->get();

                                     

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงานการชำระเงินค่าปรับ');
            $sheet->mergeCells('A1:L1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(16);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:L2');
            $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

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

            //หัวตาราง
            $sheet->setCellValue('A3', 'ลำดับ');
            $sheet->setCellValue('B3', 'เลขคดี');
            $sheet->setCellValue('C3', 'ผู้ประกอบการ');
            $sheet->setCellValue('D3', 'TAXID');
            $sheet->setCellValue('E3', 'ชื่อรายการ');
            $sheet->setCellValue('F3', 'จำนวนเงิน');
            $sheet->setCellValue('G3', 'วันครบกำหนดชำระ');
            $sheet->setCellValue('H3', 'วันที่ชำระเงิน');
            $sheet->setCellValue('I3', 'สถานะ');
            $sheet->setCellValue('J3', 'ผู้ตรวจชำระ');
            $sheet->setCellValue('K3', 'นิติกร');
            $sheet->getStyle('A3:K3')->applyFromArray($styleArray_header);

            $row = 3; //start row
            $amount = 0;
        if(count($query) > 0){
            foreach ($query as $key => $item) {
                $row++;
                $sheet->setCellValue('A' . $row,$key+1);
                $sheet->setCellValue('B' . $row, !empty($item->case_number)?$item->case_number:'');
                $sheet->setCellValue('C' . $row, !empty($item->offend_name) ? $item->offend_name : '');
                $sheet->setCellValue('D' . $row, !empty($item->offend_taxid) ? $item->offend_taxid : '');
                $sheet->getStyle('D'.$row)
                                    ->getNumberFormat()
                                    ->setFormatCode(
                                        \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                                        );
                $sheet->setCellValue('E' . $row, !empty($item->law_cases_payments_to->law_cases_payments_detail_to->fee_name) ?  $item->law_cases_payments_to->law_cases_payments_detail_to->fee_name : '');
                $sheet->setCellValue('F' . $row,  !empty($item->law_cases_payments_to->law_cases_payments_detail_to->amount) ?  $item->law_cases_payments_to->law_cases_payments_detail_to->amount : '');  
                $sheet->setCellValue('G' . $row, !empty($item->law_cases_payments_to->end_date) ?   HP::DateThai($item->law_cases_payments_to->end_date)  : '');  
                $sheet->setCellValue('H' . $row, !empty($item->law_cases_payments_to->paid_date) ?   HP::DateThai($item->law_cases_payments_to->paid_date)  : '');  
                $sheet->setCellValue('I' . $row, (!empty($item->law_cases_payments_to->paid_status)  && $item->law_cases_payments_to->paid_status == '2') ? 'ชำระเงินแล้ว' : 'ยังไม่ชำระเงิน');
                $sheet->setCellValue('J' . $row,  !empty($item->law_cases_payments_to->user_updated->FullName) ? $item->law_cases_payments_to->user_updated->FullName : "e-Payment");  
                $sheet->setCellValue('K' . $row, !empty($item->LawyerName)? $item->LawyerName:'');

            }
        }
            $last_i = $row;
            $amount = 'F4' . ':F' . $last_i; 
            $row++;
   
            $sheet->setCellValue('A'.$row, 'รวม');
            $sheet->mergeCells('A'.$row.':E'.$row);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('right');

       

            $sheet->setCellValue('F'.$row,'=SUM(' . $amount . ')');
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal('right');

 
              //ใส่ขอบดำ
              $style_borders = [
                'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                ]
            ];
            $sheet->getStyle('A3:K'.$row)->applyFromArray($style_borders);

            $sheet->getStyle('F4:F'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
 

            //Set Column Width
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
    
            $filename = 'รายงานการชำระเงินค่าปรับ_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }
}
