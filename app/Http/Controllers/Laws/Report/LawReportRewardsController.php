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

 
use App\Models\Law\Reward\LawlRewardWithdraws;   
use App\Models\Law\Reward\LawlRewardWithdrawsDetails;   

class LawReportRewardsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function query($request)//ใช่ร่วมกับexcel
    {

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        
        $filter_type             = $request->input('filter_type');
        $filter_case_number      = $request->input('filter_case_number');
        $filter_paid_date_month  = $request->input('filter_paid_date_month');
        $filter_paid_date_year   = $request->input('filter_paid_date_year');
        $filter_paid_date_start  = !empty($request->get('filter_paid_date_start'))?HP::convertDate($request->get('filter_paid_date_start'),true):null;
        $filter_paid_date_end    = !empty($request->get('filter_paid_date_end'))?HP::convertDate($request->get('filter_paid_date_end'),true):null;

        $filter_forerunner       = $request->input('filter_forerunner');

        return LawlRewardWithdrawsDetails::query()    
                                        ->with(['law_reward_withdraws_to','law_cases'])  
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return   $query->whereHas('law_cases', function ($query2) use ($search_full){
                                                                return  $query2->Where('case_number', 'LIKE', '%' . $search_full . '%');
                                                    });
                       
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return   $query->whereHas('law_cases', function ($query2) use ($search_full){
                                                                return  $query2->Where('offend_name', 'LIKE', '%' . $search_full . '%')->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%');
                                                     });
                       
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                   return   $query->whereHas('law_cases', function ($query2) use ($search_full){
                                                                return  $query2->Where('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                        });
                       
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search);
                                                    return   $query->whereHas('law_cases', function ($query2) use ($search_full){
                                                                 return  $query2->Where('case_number', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_name', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_taxid', 'LIKE', '%' . $search_full . '%')
                                                                        ->OrWhere('offend_license_number', 'LIKE', '%' . $search_full . '%');
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return   $query->whereHas('law_reward_withdraws_to', function ($query2) use ($filter_status){
                                                           return  $query2->Where('status',$filter_status);
                                                     });
                                           
                                         })
                                         ->when($filter_type, function ($query, $filter_type) use ($filter_case_number,$filter_paid_date_month,$filter_paid_date_year,$filter_paid_date_start,$filter_paid_date_end){
                                            switch ( $filter_type ):
                                                case "1":
                                                        return  $query->when($filter_case_number, function ($query2, $filter_case_number){
                                                                    if(!empty($filter_case_number)){
                                                                        return $query2->Where('case_number', $filter_case_number);
                                                                    }else{
                                                                        return $query2->WhereNull('id');
                                                                    }
                                                                  });
                                                    break;
                                                case "2":
                                                    return   $query->whereHas('law_reward_withdraws_to', function ($query2) use ($filter_paid_date_month,$filter_paid_date_year){
                                                                            if(!is_null($filter_paid_date_year) && !is_null($filter_paid_date_month)){
                                                                                    return  $query2->whereMonth('created_at',$filter_paid_date_month)->whereYear('created_at', $filter_paid_date_year);
                                                                            }else if(!is_null($filter_paid_date_year)){
                                                                                    return  $query2->whereYear('created_at', $filter_paid_date_year);
                                                                            }else{
                                                                                    return  $query2->whereMonth('created_at',$filter_paid_date_month);
                                                                            }
                                                                 });
                                                    case "3":
                                                        return   $query->whereHas('law_reward_withdraws_to', function ($query2) use ($filter_paid_date_start,$filter_paid_date_end){
                                                                        if(!is_null($filter_paid_date_start) && !is_null($filter_paid_date_end) ){
                                                                            return $query2->whereDate('created_at', '>=', $filter_paid_date_start)
                                                                                            ->whereDate('created_at', '<=', $filter_paid_date_end);
                                                                        }else if(!is_null($filter_paid_date_start) && is_null($filter_paid_date_end)){
                                                                            return  $query2->WhereDate('created_at',$filter_paid_date_start);
                                                                        }
                                                                  });
                                               
                                                     break;
                                                default:
                                                break;
                                            endswitch;
                                        })
                                        ->when($filter_forerunner, function ($query, $filter_forerunner){
                                            return   $query->whereHas('law_reward_withdraws_to', function ($query2) use ($filter_forerunner){
                                                          return   $query2->whereHas('forerunner_created', function ($query3) use ($filter_forerunner){
                                                                    $search  = str_replace(' ', '', $filter_forerunner);
                                                                   return  $query3->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search."%");
                                                              });
                                                     });
                                           
                                         });
    }


    public function data_list(Request $request)
    {
       
    
        return Datatables::of(self::query($request))
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return  !empty($item->case_number) ? $item->case_number : '';
                            })
                            ->addColumn('offend_name', function ($item) {
                                 $text =    !empty($item->law_cases->offend_name) ? $item->law_cases->offend_name : '';
                                 $text .=    !empty($item->law_cases->offend_taxid) ? "<br/>".$item->law_cases->offend_taxid : '';
                                return $text;
                            })
                            ->addColumn('forerunner_name', function ($item) {
                                 $text =    !empty($item->law_reward_withdraws_to->forerunner_created->FullName) ? $item->law_reward_withdraws_to->forerunner_created->FullName : '';
                                 $text .=   !empty($item->law_reward_withdraws_to->created_at) ?  "<br/>".HP::DateThai($item->law_reward_withdraws_to->created_at) : '';
                               return $text;
                           })
                           ->addColumn('status', function ($item) {
                                $text =    !empty($item->law_reward_withdraws_to->StatusHtml) ? $item->law_reward_withdraws_to->StatusHtml : '';
                                // $text .=  !empty($item->law_reward_withdraws_to->reference_no) &&  !empty($item->law_reward_withdraws_to->status) && $item->law_reward_withdraws_to->status == '2'?  '<br/><span class="text-success">('.$item->law_reward_withdraws_to->reference_no.')</span>' : '';
                                 $text .=  !empty($item->law_reward_withdraws_to->approve_at) &&  !empty($item->law_reward_withdraws_to->status) && $item->law_reward_withdraws_to->status == '2'?  '<br/>'.HP::DateThai($item->law_reward_withdraws_to->approve_at) : '';
                               return $text;
                           })
                           ->addColumn('paid_amount', function ($item) {
                                return  !empty($item->law_rewards->paid_amount) ? number_format($item->law_rewards->paid_amount,2) : '0.00';
                           })
                           ->addColumn('government_total', function ($item) {
                               return  !empty($item->law_rewards->government_total) ? number_format($item->law_rewards->government_total,2) : '0.00';
                            })
                            ->addColumn('group_total', function ($item) {
                                return  !empty($item->law_rewards->group_total) ? number_format($item->law_rewards->group_total,2) : '0.00';
                             })
                             ->addColumn('operate_total', function ($item) {
                                return  !empty($item->law_rewards->operate_total) ? number_format($item->law_rewards->operate_total,2) : '0.00';
                             })
                             ->addColumn('bribe_total', function ($item) {
                                return  !empty($item->law_rewards->bribe_total) ? number_format($item->law_rewards->bribe_total,2) : '0.00';
                             })
                             ->addColumn('reward_total', function ($item) {
                                return  !empty($item->law_rewards->reward_total) ? number_format($item->law_rewards->reward_total,2) : '0.00';
                             })
                             ->addColumn('difference', function ($item) {
                                if(!empty($item->law_reward_withdraws_detail_sub_many->where('status','2')->sum('amount')) ){
                                    $amount =  number_format($item->law_reward_withdraws_detail_sub_many->where('status','2')->sum('amount'),2);
                                    return '<span class="difference" data-id="'.$item->id.'">'.$amount.'</span>';
                                }else{
                                    return    '0.00';
                                }
                              
                             })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'case_number', 'offend_name', 'forerunner_name', 'status', 'difference'])
                            ->make(true);
    }

    public function index()
    {
        $model = str_slug('law-report-rewards','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/rewards",  "name" => 'รายงานการเบิกจ่ายเงินสินบนรางวัล' ],
            ];
            return view('laws.report.rewards.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function show($id)
    {
        $model = str_slug('law-report-rewards','-');
        if(auth()->user()->can('view-'.$model)) {

            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/rewards",  "name" => 'รายงานการเบิกจ่ายเงินสินบนรางวัล' ],
                [ "link" => "/law/report/rewards/".$id,  "name" => 'รายละเอียด' ],
            ];
            $withdraws = LawlRewardWithdrawsDetails::findOrFail($id);
            $detail_subs = !empty($withdraws->law_reward_withdraws_detail_sub_many->where('status','2')) ?   $withdraws->law_reward_withdraws_detail_sub_many->where('status','2') : [];
            return view('laws.report.rewards.show',compact('withdraws','detail_subs'));
        }
        abort(403);
    }



    public function export_excel(Request $request)
    {

        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB

        $query  = self::query($request)->get();                         

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงานการเบิกจ่ายเงินสินบนรางวัล');
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
            $sheet->setCellValue('E3', 'ผู้เบิก');
            $sheet->setCellValue('F3', 'สถานะ');
            $sheet->setCellValue('G3', 'ค่าปรับ');
            $sheet->setCellValue('H3', 'หักรายได้แผ่นดิน');
            $sheet->setCellValue('I3', 'ค่าดำเนินการ');
            $sheet->setCellValue('J3', 'เงินสินบน');
            $sheet->setCellValue('K3', 'เงินรางวัล');
            $sheet->setCellValue('L3', 'ส่งรายได้แผ่นดิน');

            $sheet->getStyle('A3:L3')->applyFromArray($styleArray_header);

            $row = 3; //start row
            $amount = 0;
        if(count($query) > 0){
            foreach ($query as $key => $item) {


                $row++;
                $sheet->setCellValue('A' . $row,$key+1);
                $sheet->setCellValue('B' . $row, !empty($item->case_number)?$item->case_number:'');
                $sheet->setCellValue('C' . $row, !empty($item->law_cases->offend_name) ? $item->law_cases->offend_name : '');
                $sheet->setCellValue('D' . $row, !empty($item->law_cases->offend_taxid) ? $item->law_cases->offend_taxid : '');
                $sheet->setCellValue('E' . $row, !empty($item->law_reward_withdraws_to->forerunner_created->FullName) ? $item->law_reward_withdraws_to->forerunner_created->FullName : '');
                $sheet->setCellValue('F' . $row, !empty($item->law_reward_withdraws_to->StatusText) ? $item->law_reward_withdraws_to->StatusText : '');

                $sheet->setCellValue('G' . $row, !empty($item->law_rewards->paid_amount) ?  $item->law_rewards->paid_amount:'0.00');  
                $sheet->getStyle('G' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('H' . $row, !empty($item->law_rewards->government_total) ?  $item->law_rewards->government_total:'0.00');  
                $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('I' . $row, !empty($item->law_rewards->operate_total) ?  $item->law_rewards->operate_total:'0.00');  
                $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('J' . $row, !empty($item->law_rewards->bribe_total) ?  $item->law_rewards->bribe_total:'0.00');  
                $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('K' . $row, !empty($item->law_rewards->reward_total) ?  $item->law_rewards->reward_total:'0.00');  
                $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal('right');

                $sheet->setCellValue('L' . $row, !empty($item->law_reward_withdraws_detail_sub_many->where('status','2')->sum('amount')) ? number_format($item->law_reward_withdraws_detail_sub_many->where('status','2')->sum('amount'),2) : '0.00');  
                $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal('right');
            }
        }
            $last_i = $row;
            $amount = 'G4' . ':G' . $last_i;
            $amount1 = 'H4' . ':H' . $last_i;
            $amount2 = 'I4' . ':I' . $last_i;
            $amount3 = 'J4' . ':J' . $last_i;
            $amount4 = 'K4' . ':K' . $last_i;
            $amount5 = 'L4' . ':L' . $last_i;
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

            $sheet->setCellValue('J'.$row,'=SUM(' . $amount3 . ')');
            $sheet->getStyle('J' . $row)->getAlignment()->setHorizontal('right');

            $sheet->setCellValue('K'.$row,'=SUM(' . $amount4 . ')');
            $sheet->getStyle('K' . $row)->getAlignment()->setHorizontal('right');

            $sheet->setCellValue('L'.$row,'=SUM(' . $amount5 . ')');
            $sheet->getStyle('L' . $row)->getAlignment()->setHorizontal('right');


              //ใส่ขอบดำ
              $style_borders = [
                'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
                ]
            ];
            $sheet->getStyle('A3:L'.$row)->applyFromArray($style_borders);

            $sheet->getStyle('G4:G'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('H4:H'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('I4:I'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('J4:J'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('K4:K'.$row)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('L4:L'.$row)->getNumberFormat()->setFormatCode('#,##0.00');

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
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $filename = 'รายงานการเบิกจ่ายเงินสินบนรางวัล_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }

    public function search_users(Request $request)
    {
        $search_query = $request->get('query');
        $search = str_replace(' ', '', $search_query);
         $data =  DB::table((new User)->getTable().' AS u')
                                            ->select('u.reg_fname','u.reg_lname')
                                            ->Where(DB::raw("CONCAT(REPLACE(u.reg_fname,' ',''),'', REPLACE(u.reg_lname,' ',''))"), 'LIKE', "%".$search."%")
                                            ->Join((new LawlRewardWithdraws)->getTable().' AS w', function($join)
                                            {   
                                                $join->on('w.forerunner_id' , '=', 'u.runrecno')    ;     
                                            })
                                        ->groupBy('u.runrecno')
                                        ->orderby('u.runrecno','desc')
                                        ->get();
     
        foreach( $data as $item ){
            $item->name =   $item->reg_fname.' '.$item->reg_lname; 
        }

        return response()->json($data);

    }



}
