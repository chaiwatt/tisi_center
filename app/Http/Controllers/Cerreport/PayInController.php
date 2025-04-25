<?php

namespace App\Http\Controllers\Cerreport;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use HP;
use DB;
use Yajra\Datatables\Datatables;
use App\Models\Certify\PayInAll;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PayInController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

 
    public function index()
    {
        $model = str_slug('cerreport-payins','-');
        if(auth()->user()->can('view-'.$model)) {
 
            return view('cerreport.payins.index');
        }
        abort(403);
    }


    public function data_list(Request $request)
    {
        
        $filter_search = $request->input('filter_search');
        $filter_certify = $request->input('filter_certify');
        $filter_conditional_type = $request->input('filter_conditional_type');
        $query = PayInAll::query()                       
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search ); 
                                    $query->where(function ($query2) use($search_full) {
                                        return   $query2->Where(DB::raw("REPLACE(app_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(auditors_name,' ','')"), 'LIKE', "%".$search_full."%") ;
                                        });
                                    }) 
                                    ->when($filter_conditional_type, function ($query, $filter_conditional_type){
                                        return  $query->where('conditional_type', $filter_conditional_type);
                                     })
                                     ->when($filter_certify, function ($query, $filter_certify){
                                        return  $query->where('certify', $filter_certify);
                                       })
                                    ;
                  
      return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                        return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('app_no', function ($item) {
                                     return $item->app_no ?? null;
                            })
                            ->addColumn('name', function ($item) {
                                    $text =   !empty($item->name)? $item->name:'';
                                    $text .=   !empty($item->tax_id)? '<br/>'.$item->tax_id:'';
                                     return $text;
                            })
                            ->addColumn('conditional_type', function ($item) {
                                    return $item->ConditionalTypeName ?? null;
                           })
                           ->addColumn('start_date', function ($item) {
                                    return  !empty($item->start_date)?HP::DateThai($item->start_date):null;
                           })
                        //    ->addColumn('attach', function ($item) {
                        //         $text= '';
                        //         if(!is_null($item->attach)){
                        //               $attach= json_decode($item->attach); 
                        //              if(!Is_null($attach)){
                                  
                        //                    $text =   '<a href="'. ( url($attach->url.$attach->new_filename.'/'.$attach->filename)).'" target="_blank">
                        //                           '.( HP::FileExtension($attach->filename) ).'
                        //                       </a> ';
                        //              }
                        //         }
                        //         return $text;
                       
                        //  })

                            ->addColumn('action', function ($item) {
                                    return HP::buttonAction( $item->id, 'cerreport/payins','Cerreport\\PayInController@destroy', 'cerreport-payins',true,false,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id','desc');
                            })
                            ->rawColumns(['checkbox', 'name','attach', 'action'])
                            ->make(true); 
                                    
    }
    public function show($id)
    {
        $model = str_slug('cerreport-payins','-');
        if(auth()->user()->can('view-'.$model)) {
            $pay_in = PayInAll::findOrFail($id);
            return view('cerreport.payins.show', compact('pay_in'));
        }
        abort(403);
    }

    public function export_excel(Request $request)
    {
 
        ini_set('max_execution_time', 7200); //120 minutes
        ini_set('memory_limit', '16384M'); //16 GB
        $filter_search = $request->input('filter_search');
        $filter_certify = $request->input('filter_certify');
        $filter_conditional_type = $request->input('filter_conditional_type');
        $query = PayInAll::query()                       
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search ); 
                                    $query->where(function ($query2) use($search_full) {
                                        return   $query2->Where(DB::raw("REPLACE(app_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(tax_id,' ','')"), 'LIKE', "%".$search_full."%") 
                                                        ->OrWhere(DB::raw("REPLACE(auditors_name,' ','')"), 'LIKE', "%".$search_full."%") ;
                                        });
                                    }) 
                                    ->when($filter_conditional_type, function ($query, $filter_conditional_type){
                                        return  $query->where('conditional_type', $filter_conditional_type);
                                     })
                                     ->when($filter_certify, function ($query, $filter_certify){
                                        return  $query->where('certify', $filter_certify);
                                         })
                              ->orderby('id','desc')
                              ->get();
   

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'รายงาน Pay In');
            $sheet->mergeCells('A1:E1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);

            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:E2');
            $sheet->getStyle('A2:E2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'ลำดับ');
            $sheet->setCellValue('B3', 'เลขที่คำขอ');
            $sheet->setCellValue('C3', 'หน่วยงาน');
            $sheet->setCellValue('D3', 'เงื่อนไขการชำระเงิน');
            $sheet->setCellValue('E3', 'วันที่แจ้งชำระ'); 
            

            $row = 3; //start row
        if(count($query) > 0){
            foreach ($query as $key => $item) {
                    $text =   !empty($item->name)? $item->name:'';
                    $text .=   !empty($item->tax_id)?   "\n".$item->tax_id:'';
                 

                $row++;
                $sheet->setCellValue('A' . $row,$key+1); 
                $sheet->setCellValue('B' . $row, $item->app_no ?? "");
                $sheet->setCellValue('C' . $row, $text);
                $sheet->setCellValue('D' . $row, $item->ConditionalTypeName ?? "");
                $sheet->setCellValue('E' . $row, !empty($item->start_date)?HP::DateThai($item->start_date):"");
               
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
            $filename = 'รายงานPayIn' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");
            exit;

    }



} 