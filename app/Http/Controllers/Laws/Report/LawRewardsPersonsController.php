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

 
use App\Models\Law\Reward\LawlRewardStaffLists;  
class LawRewardsPersonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_reward_group           = $request->input('filter_reward_group');
        
        $filter_type             = $request->input('filter_type');
        $filter_law_arrest       = $request->input('filter_law_arrest');
        $filter_case_number      = $request->input('filter_case_number');
        $filter_paid_date_month  = $request->input('filter_paid_date_month');
        $filter_paid_date_year   = $request->input('filter_paid_date_year');
        $filter_paid_date_start  = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end    = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;
        $filter_recepts_date_start  = !empty($request->get('filter_recepts_date_start'))?HP::convertDate($request->get('filter_recepts_date_start'),true):null;
        $filter_recepts_date_end    = !empty($request->get('filter_recepts_date_end'))?HP::convertDate($request->get('filter_recepts_date_end'),true):null;
      
        $query =  LawlRewardStaffLists::query()
                                        ->with(['law_reward_recepts_detail_to','law_cases_payments_to']) 
                                        ->whereHas('law_reward_recepts_detail_to', function ($query2) {
                                            return  $query2->WhereNotNull('created_by');
                                        }) 
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                              case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->Where(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                    break;
                                            endswitch;
                                        })  
                                        ->when($filter_reward_group, function ($query, $filter_reward_group){
                                            return   $query->where('basic_reward_group_id', $filter_reward_group);
                                           
                                         })
                                         ->when($filter_type, function ($query, $filter_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                            switch ( $filter_type ):
                                                case "1":
                                                    return $query->Where('case_number', 'LIKE', '%' . $filter_case_number . '%');
                                                    break;
                                                case "2":
                                                    return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                        if(!is_null($filter_paid_date_year)){
                                                                             return  $query2->whereMonth('paid_date',$filter_paid_date_month)->whereYear('paid_date',$filter_paid_date_year);
                                                                        }else{
                                                                            return  $query2->whereMonth('paid_date',$filter_paid_date_month);
                                                                        }
                                                                });
                                                    break;
                                                    case "3":
                                                        return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                      if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                            return $query2->whereDate('paid_date', '>=', $filter_paid_date_start)
                                                                                            ->whereDate('paid_date', '<=', $filter_paid_date_end);
                                                                        }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                            return  $query2->WhereDate('paid_date',$filter_paid_date_start);
                                                                        }
                                                                 });
                                               
                                                     break;
                                                default:
                                                break;
                                            endswitch;
                                        })
                                        ->when($filter_law_arrest, function ($query, $filter_law_arrest){
                                            return    $query->with(['law_case_to']) 
                                                            ->whereHas('law_case_to', function ($query2) use ($filter_law_arrest){
                                                                    return  $query2->where('law_basic_arrest_id', $filter_law_arrest);
                                                            });  
                                         })
                                        ->whereHas('law_reward_recepts_detail_to', function ($query2) use ($filter_recepts_date_start,$filter_recepts_date_end){
                                            if(!is_null($filter_recepts_date_start) && !is_null($filter_recepts_date_end) ){
                                                return $query2->whereDate('created_at', '>=', $filter_recepts_date_start)
                                                                ->whereDate('created_at', '<=', $filter_recepts_date_end);
                                            }else if(!is_null($filter_recepts_date_start) && is_null($filter_recepts_date_end)){
                                                return  $query2->WhereDate('created_at',$filter_recepts_date_start);
                                            }
                                      });


 
        $model =   str_slug('law-report-rewards-persons','-');
        
           
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('name', function ($item) {
                                 $text =    !empty($item->name) ? $item->name : '';
                                 $text .=    !empty($item->taxid) ? "<br/>".$item->taxid : '';
                                return $text;
                            })
                            ->addColumn('arrest', function ($item) {
                                 $text =    !empty($item->law_case_to->law_basic_arrest_to->title) ? $item->law_case_to->law_basic_arrest_to->title : '';
                                 $text .=   !empty($item->case_number) ?  "<br/>".$item->case_number : '';
                               return $text;
                           })
                  
                           ->addColumn('awardees', function ($item) {
                                return  !empty($item->law_reward_group_to->title) ? $item->law_reward_group_to->title : '';
                            })
                            ->addColumn('division', function ($item) {
                               
                                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                    if( !empty($item->law_calculation2_to->average) &&  $item->law_calculation2_to->average > 1){
                                        return  !empty($item->law_calculation2_to->division) ? HP::number_format(($item->law_calculation2_to->division / $item->law_calculation2_to->average),2).'%' : '';
                                     }else{
                                       return  !empty($item->law_calculation2_to->division) ? HP::number_format($item->law_calculation2_to->division,2).'%' : '';
                                     }
                                }else{
                                    if( !empty($item->law_calculation3_to->average) &&  $item->law_calculation3_to->average > 1){
                                        return  !empty($item->law_calculation3_to->division) ? HP::number_format(($item->law_calculation3_to->division / $item->law_calculation3_to->average),2).'%' : '';
                                     }else{
                                       return  !empty($item->law_calculation3_to->division) ? HP::number_format($item->law_calculation3_to->division,2).'%' : '';
                                     }
                                }
                            })
                            ->addColumn('total', function ($item) {
                                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                                       return  !empty($item->law_calculation2_to->total) ?  number_format($item->law_calculation2_to->total,2)  : '0.00';
                                 }else{
                                       return  !empty($item->law_calculation3_to->total) ? number_format($item->law_calculation3_to->total,2) : '0.00';
                                 }
                                // return  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->total) ? number_format($item->law_reward_recepts_detail_to->law_reward_recepts_to->total,2) : '0.00';
                            })
                            ->addColumn('deduct_amount', function ($item) {
                                $deduct_amount =  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount : '0.00';
                                $deduct_amount +=  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount : '0.00';
                                return number_format($deduct_amount,2);
                            })
                            ->addColumn('amount', function ($item) {
                                return  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->amount) ? number_format($item->law_reward_recepts_detail_to->law_reward_recepts_to->amount,2) : '0.00';
                            })
                            ->addColumn('created_name', function ($item) {
                              return  !empty($item->law_reward_recepts_detail_to->created_at) ? HP::DateThai($item->law_reward_recepts_detail_to->created_at) : '';
                             })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'name', 'arrest'])
                            ->make(true);
    }

    public function index()
    {
        $model = str_slug('law-report-rewards-persons','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/rewards_persons",  "name" => 'รายงานผู้มีสิทธิ์ได้รับเงินรางวัล จำแนกตามบุคคล' ],
            ];
            return view('laws.report.rewards_persons.index',compact('breadcrumbs'));
        }
        abort(403);
    }

 
    public function export_excel(Request $request)
    {

        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_reward_group           = $request->input('filter_reward_group');
        
        $filter_type             = $request->input('filter_type');
        $filter_law_arrest       = $request->input('filter_law_arrest');
        $filter_case_number      = $request->input('filter_case_number');
        $filter_paid_date_month  = $request->input('filter_paid_date_month');
        $filter_paid_date_year   = $request->input('filter_paid_date_year');
        $filter_paid_date_start  = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end    = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;
        $filter_recepts_date_start  = !empty($request->get('filter_recepts_date_start'))?HP::convertDate($request->get('filter_recepts_date_start'),true):null;
        $filter_recepts_date_end    = !empty($request->get('filter_recepts_date_end'))?HP::convertDate($request->get('filter_recepts_date_end'),true):null;
      

        $query =  LawlRewardStaffLists::query()
                                        ->with(['law_reward_recepts_detail_to','law_cases_payments_to']) 
                                        ->whereHas('law_reward_recepts_detail_to', function ($query2) {
                                            return  $query2->WhereNotNull('created_by');
                                        }) 
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                              case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->Where(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return  $query->where(function ($query2) use($search_full) {
                                                               $query2->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_reward_group, function ($query, $filter_reward_group){
                                            return   $query->where('basic_reward_group_id', $filter_reward_group);
                                           
                                         })
                                         ->when($filter_type, function ($query, $filter_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                            switch ( $filter_type ):
                                                case "1":
                                                    return $query->Where('case_number', 'LIKE', '%' . $filter_case_number . '%');
                                                    break;
                                                case "2":
                                                    return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                        if(!is_null($filter_paid_date_year)){
                                                                             return  $query2->whereMonth('paid_date',$filter_paid_date_month)->whereYear('paid_date',$filter_paid_date_year);
                                                                        }else{
                                                                            return  $query2->whereMonth('paid_date',$filter_paid_date_month);
                                                                        }
                                                                });
                                                    break;
                                                    case "3":
                                                        return   $query->whereHas('law_cases_payments_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                      if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                            return $query2->whereDate('paid_date', '>=', $filter_paid_date_start)
                                                                                            ->whereDate('paid_date', '<=', $filter_paid_date_end);
                                                                        }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                            return  $query2->WhereDate('paid_date',$filter_paid_date_start);
                                                                        }
                                                                 });
                                               
                                                     break;
                                                default:
                                                break;
                                            endswitch;
                                        })
                                        ->when($filter_law_arrest, function ($query, $filter_law_arrest){
                                            return    $query->with(['law_case_to']) 
                                                            ->whereHas('law_case_to', function ($query2) use ($filter_law_arrest){
                                                                    return  $query2->where('law_basic_arrest_id', $filter_law_arrest);
                                                            });  
                                         })
                                        ->whereHas('law_reward_recepts_detail_to', function ($query2) use ($filter_recepts_date_start,$filter_recepts_date_end){
                                            if(!is_null($filter_recepts_date_start) && !is_null($filter_recepts_date_end) ){
                                                return $query2->whereDate('created_at', '>=', $filter_recepts_date_start)
                                                                ->whereDate('created_at', '<=', $filter_recepts_date_end);
                                            }else if(!is_null($filter_recepts_date_start) && is_null($filter_recepts_date_end)){
                                                return  $query2->WhereDate('created_at',$filter_recepts_date_start);
                                            }
                                         })
                                         ->orderBy('id', 'DESC')
                                         ->get();

                                     

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงานผู้มีสิทธิ์ได้รับเงินรางวัล จำแนกตามบุคคล');
            $sheet->mergeCells('A1:J1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(16);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:J2');
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
            $sheet->setCellValue('C3', 'ชื่อ-สกุลผู้มีสิทธิ์');
            $sheet->setCellValue('D3', 'TAXID');
            $sheet->setCellValue('E3', 'การจับกุม');
            $sheet->setCellValue('F3', 'ร้อยละ');
            $sheet->setCellValue('G3', 'จำนวนเงิน');
            $sheet->setCellValue('H3', 'หักไว้');
            $sheet->setCellValue('I3', 'คงเหลือ');

            $sheet->setCellValue('J3', 'วันที่ออกใบสำคัญรับเงิน');

            $sheet->getStyle('A3:J3')->applyFromArray($styleArray_header);

            $row = 3; //start row
            $amount = 0;
        if(count($query) > 0){
            foreach ($query as $key => $item) {
                $row++;
                $sheet->setCellValue('A' . $row,$key+1);
                $sheet->setCellValue('B' . $row, !empty($item->case_number)?$item->case_number:'');
                $sheet->setCellValue('C' . $row, !empty($item->name) ? $item->name : '');
                $sheet->setCellValue('D' . $row, !empty($item->taxid) ? $item->taxid : '');
                $sheet->getStyle('D'.$row)
                                    ->getNumberFormat()
                                    ->setFormatCode(
                                        \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                                        );
                
                $sheet->setCellValue('E' . $row, !empty($item->law_case_to->law_basic_arrest_to->title) ? $item->law_case_to->law_basic_arrest_to->title : '');

                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                    if( !empty($item->law_calculation2_to->average) &&  $item->law_calculation2_to->average > 1){
                        $sheet->setCellValue('F' . $row, !empty($item->law_calculation2_to->division) ?  HP::number_format(($item->law_calculation2_to->division / $item->law_calculation2_to->average),2).'%' : '');
                     }else{
                       $sheet->setCellValue('F' . $row, !empty($item->law_calculation2_to->division) ?  HP::number_format($item->law_calculation2_to->division,2).'%' : '');
                     }
                     $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal('center');
                }else{
                    if( !empty($item->law_calculation3_to->average) &&  $item->law_calculation3_to->average > 1){
                        $sheet->setCellValue('F' . $row,  !empty($item->law_calculation3_to->division) ?  HP::number_format(($item->law_calculation3_to->division / $item->law_calculation3_to->average),2).'%' : '');
                     }else{
                       $sheet->setCellValue('F' . $row, !empty($item->law_calculation3_to->division) ?  HP::number_format($item->law_calculation3_to->division,2).'%' : '');
                     }
                     $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal('center');

                }
 
                if($item->basic_reward_group_id == '9'){  // ผู้แจ้งเบาะแส
                    $sheet->setCellValue('G' . $row, !empty($item->law_calculation2_to->total) ?  $item->law_calculation2_to->total:'0.00');  
                }else{
                    $sheet->setCellValue('G' . $row, !empty($item->law_calculation3_to->total) ?  $item->law_calculation3_to->total:'0.00');  
                }
                    $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('right');

                $deduct_amount =  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_amount : '0.00';
                $deduct_amount +=  !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount) ? $item->law_reward_recepts_detail_to->law_reward_recepts_to->deduct_vat_amount : '0.00';

                $sheet->setCellValue('H' . $row, !empty($deduct_amount) ?  $deduct_amount :'0.00');  
                $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('I' . $row, !empty($item->law_reward_recepts_detail_to->law_reward_recepts_to->amount) ?  $item->law_reward_recepts_detail_to->law_reward_recepts_to->amount:'0.00');  
                $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal('right');

                  $sheet->setCellValue('J' . $row,  !empty($item->law_reward_recepts_detail_to->created_at) ? HP::DateThai($item->law_reward_recepts_detail_to->created_at) : '');  
 
            }
        }

           $sheet->getStyle('A4:A' . $row)->getAlignment()->setHorizontal('center');
           $sheet->getStyle('B4:B' . $row)->getAlignment()->setHorizontal('left');
           $sheet->getStyle('C4:C' . $row)->getAlignment()->setHorizontal('left');
           $sheet->getStyle('D4:D' . $row)->getAlignment()->setHorizontal('left');
           $sheet->getStyle('E4:E' . $row)->getAlignment()->setHorizontal('left');
           
            $last_i = $row;
            $amount = 'G4' . ':G' . $last_i; 
            $amount1 = 'H4' . ':H' . $last_i; 
            $amount2 = 'I4' . ':I' . $last_i; 
            
            
            $row++;
   
            $sheet->setCellValue('A'.$row, 'รวม');
            $sheet->mergeCells('A'.$row.':F'.$row);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('right');



            $sheet->setCellValue('G'.$row,'=SUM(' . $amount . ')');
            $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('right');

            $sheet->setCellValue('H'.$row,'=SUM(' . $amount1 . ')');
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');

            $sheet->setCellValue('I'.$row,'=SUM(' . $amount2 . ')');
            $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal('right');
 
              //ใส่ขอบดำ
              $style_borders = [
                'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                ]
            ];
            $sheet->getStyle('A3:J'.$row)->applyFromArray($style_borders);

            $sheet->getStyle('G4:G'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('H4:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('I4:I'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
 

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

            $filename = 'รายงานผู้มีสิทธิ์ได้รับเงินรางวัล_จำแนกตามบุคคล_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }

}
