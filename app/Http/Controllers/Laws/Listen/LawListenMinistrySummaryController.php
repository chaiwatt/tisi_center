<?php

namespace App\Http\Controllers\Laws\Listen;

use HP;
use HP_Law;
use App\Http\Requests;
use App\Models\Basic\Tis;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Law\Listen\LawListenMinistry;
use App\Models\Law\Listen\LawListenMinistryResults;
use App\Models\Law\Listen\LawListenMinistryResponse;
use App\Mail\Mail\Law\ListenMinistry\MailListenMinistrySummary;

class LawListenMinistrySummaryController extends Controller
{
    private $attach_path;
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/listen_ministry_summary';
    }


    public function query($request)//ใช่ร่วมกับexcel
    {
        $model = str_slug('law-listen-ministry-summary','-');

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_status           = $request->input('filter_status');
        $filter_search           = $request->input('filter_search');
        $filter_standard         = $request->input('filter_standard');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;


       return LawListenMinistry::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                switch ( $filter_condition_search ):
                                                    case "1":
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('ref_no', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    case "2":
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('title', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    case "3":
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('tis_name', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    default:
                                                        $search_full = str_replace(' ', '', $filter_search);
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('ref_no', 'LIKE', '%'.$search_full.'%')
                                                                       ->Orwhere('title', 'LIKE', '%'.$search_full.'%')
                                                                       ->Orwhere('tis_name', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                endswitch;
                                            })
                                            ->when($filter_status, function ($query, $filter_status){
                                                return $query->where('status_id', $filter_status);
                                            })
                                            ->when($filter_standard, function ($query, $filter_standard){
                                                return $query->where('id', $filter_standard);
                                            })
                                            ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                                if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                    return $query->whereBetween('date_start',[$filter_start_date,$filter_end_date]);
                                                }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                    return $query->whereDate('date_start',$filter_start_date);
                                                }
                                            })
                                            ->when(!auth()->user()->can('view_all-'.$model), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่บันทึก
                                                $query->where('created_by', Auth::user()->getKey());     
                                            });

    }

    public function data_list(Request $request)
    {
   
        return Datatables::of(self::query($request))
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                            })
                            ->addColumn('ref_no', function ($item) {
                                return $item->ref_no.'<div>'.(!empty($item->date_start)?HP::DateThai($item->date_start):null).'</div>';
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return $item->tis_name;
                            })
                            ->addColumn('status', function ($item) {
                                $url_type = '';
                                if(!empty($item->url_type) && $item->url_type == 2){
                                    $url_type = '<div class="text-muted m-b-30"><i>(แบบรับฟังความเห็น ระบุเอง)</i></div>';
                                }
                                return $item->StatusText.$url_type;
                            }) 
                            ->addColumn('comment1', function ($item) {
                                return @$item->law_listen_ministry_response->where('comment_point',1)->count();
                            })
                            ->addColumn('comment2', function ($item) {
                                return @$item->law_listen_ministry_response->where('comment_point',2)->count();
                            })
                            ->addColumn('comment3', function ($item) {
                                return @$item->law_listen_ministry_response->where('comment_point',3)->count();
                            })
                            ->addColumn('comment4', function ($item) {
                                return @$item->law_listen_ministry_response->where('comment_point',4)->count();
                            })
                            ->addColumn('comment_amonut', function ($item) {
                                return @$item->law_listen_ministry_response->whereIn('comment_point',[1,2,3,4])->count();
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'date_start', 'color', 'condition', 'created_at', 'ref_no'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-listen-ministry-summary','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
            ];
            return view('laws.listen.ministry-summary.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    public function save_close(Request $request)
    {
        $requestData  = $request->all();
        $listen_ids   =  !empty( $request->listen_id )?explode(',',($request->listen_id)):[];
        $db    = new LawListenMinistry;
        LawListenMinistry::whereIn($db->getKeyName(), $listen_ids)->update([
                                                                    'status_id' => 3,
                                                                    'updated_by' =>  !auth()->user()->getKey()
                                                                     ]);

  
        foreach($listen_ids as $listen_id){
            $lawlistministry =  LawListenMinistry::where('id', $listen_id )->first();
            HP_Law::InsertLawLogWorking( //ประวัติการดำเนินงาน
                                        3,
                                        ((new LawListenMinistry)->getTable()),
                                        $lawlistministry->id,
                                        $lawlistministry->ref_no,
                                        'สรุปความเห็นร่างกฏกระทรวง',
                                        $lawlistministry->title,
                                        $lawlistministry->StatusText,
                                        $lawlistministry->remark
                                    );
        }
    

        return redirect('law/listen/ministry-summary')->with('flash_message', 'ปิดประกาศรับฟังความเห็นเรียบร้อย');
    
    }

    public function save_result(Request $request){

        $requestData  = $request->all();
        $listen_ids   =  !empty( $request->listen_id )?explode(',',($request->listen_id)):[];

        $db    = new LawListenMinistry;
        LawListenMinistry::whereIn($db->getKeyName(), $listen_ids)->update([
                                                                            'status_id'             => 4,
                                                                            'status_diagnosis'      => $requestData['status_diagnosis'],
                                                                            'mail_list_diagnosis'   => !empty($request->input('mail_list_diagnosis'))? json_encode(explode(',', $request->input('mail_list_diagnosis'))):null,
                                                                            'mail_status_diagnosis' => isset($requestData['mail_status_diagnosis'])?1:0,
                                                                            'date_diagnosis'        => !empty($request->date_diagnosis)?HP::convertDate($request->date_diagnosis,true) : null
                                                                        ]);
                                                           

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        foreach( $listen_ids as $listen_id ){
            $lawlistministry =  LawListenMinistry::where('id', $listen_id )->first();

            if(isset($requestData['file_result'])){

                if ($request->hasFile('file_result')) {
                    //ลบไฟล์เดิม
                    $attachs_listen_ministry = $lawlistministry->AttachFileResult;
                    HP_Law::DeleteLawSingleFile($attachs_listen_ministry);

                    HP::singleFileUploadLaw(
                        $request->file('file_result') ,
                        $this->attach_path,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawListenMinistry)->getTable() ),
                        $listen_id,
                        'file_result',
                        'หนังสือแจ้งผล'
                    );
        
                }
            }

            //ประวัติการดำเนินงาน
            HP_Law::InsertLawLogWorking( 
                                            3,
                                            ((new LawListenMinistry)->getTable()),
                                            $lawlistministry->id,
                                            $lawlistministry->ref_no,
                                            'สรุปความเห็นร่างกฏกระทรวง',
                                            $lawlistministry->title,
                                            $lawlistministry->StatusText,
                                            $lawlistministry->remark
                                        );

            if( isset($requestData['mail_status_diagnosis']) && $requestData['mail_status_diagnosis'] == 1 ){//ส่งเมลแจ้งวินิจฉัย
                $this->send_mail($lawlistministry,$request);
            }

        }

        return redirect('law/listen/ministry-summary')->with('flash_message', 'บันทึกแจ้งผลวินิจฉัยเรียบร้อย');
    
    }

    //ส่งเมลแจ้งวินิจฉัย
    public function send_mail($lawlistministry, $request){   

        if(isset($request->mail_list_diagnosis) && count(explode(",",$request->mail_list_diagnosis)) > 0){

            $mail_list = [];
            foreach(explode(",",$request->mail_list_diagnosis)as $email){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$mail_list)){
                    $mail_list[] =  $email;
                }
            }

            if(count($mail_list) > 0){

                $data_app =  [  // ข้อมูล
                                'lawlistministry' => $lawlistministry,
                                'title'           => "การวินิจฉัยการกำหนดให้ $lawlistministry->tis_name ต้องเป็นไปตาม มอก. $lawlistministry->tis_no"
                            ];

                HP_Law::getInsertLawNotifyEmail(3,
                                               ((new LawListenMinistry)->getTable()),
                                               $lawlistministry->id,
                                               'สรุปความเห็นร่างกฏกระทรวง',
                                               "การวินิจฉัยการกำหนดให้ $lawlistministry->tis_name ต้องเป็นไปตาม มอก. $lawlistministry->tis_no",
                                               view('mail.Law.ListenMinistry.listen_ministry_summary', $data_app),
                                               null,  
                                               null,   
                                               json_encode($mail_list)   
                                           );
   
                $html = new MailListenMinistrySummary($data_app);
                Mail::to($mail_list)->send($html);
           }
        }       
    }

    //โหลด email แจ้งวินืจฉัย
    public function select_mail(Request $request){
        $requestData  =  $request->all();
        $listen_id    =  $requestData['listen_id'];
        $email =  LawListenMinistryResponse::whereIn('listen_id',$listen_id)->select('email')->get();

        return response()->json($email);
    }

    //ส่งออกข้อมูล
    public function export_excel(Request $request) {

        //Create Spreadsheet Object
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $query       = self::query($request)->get();

           //หัวรายงาน
           $sheet->setCellValue('A1', 'สรุปความเห็นร่างกฏกระทรวง');
           $sheet->mergeCells('A1:J1');
           $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
           $sheet->getStyle("A1")->getFont()->setSize(18);

           //แสดงวันที่
           $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
           $sheet->mergeCells('A2:J2');
           $sheet->getStyle('A2:J2')->getAlignment()->setHorizontal('center');

           //หัวตาราง
           $sheet->setCellValue('A3', 'ลำดับ');
           $sheet->setCellValue('B3', 'เลขที่อ้างอิง');
           $sheet->setCellValue('C3', 'ชื่อเรื่องประกาศ');
           $sheet->setCellValue('D3', 'สถานะ');
           $sheet->setCellValue('E3', 'วันที่ประกาศ');
           $sheet->setCellValue('F3', 'เห็นชอบให้บังคับตามร่างกฎกระกระทรวงฯ ทุกประการ');
           $sheet->setCellValue('G3', 'ไม่เห็นชอบให้บังคับตามร่างกฎกระกระทรวงฯ และมีความคิดเห็นเพิ่มเติม');
           $sheet->setCellValue('H3', 'เห็นชอบกับการขยายระยะเวลา');
           $sheet->setCellValue('I3', 'ไม่เห็นชอบกับการขยายระยะเวลา');
           $sheet->setCellValue('J3', 'รวม');

           $row = 3; //start row
            if(count($query) > 0){
                foreach ($query as $key => $item) {
                

                $comment_point1 = $item->law_listen_ministry_response->where('comment_point',1)->count();
                $comment_point2 = $item->law_listen_ministry_response->where('comment_point',2)->count();
                $comment_point3 = $item->law_listen_ministry_response->where('comment_point',3)->count();
                $comment_point4 = $item->law_listen_ministry_response->where('comment_point',4)->count();
                $comment_amonut = $item->law_listen_ministry_response->whereIn('comment_point',[1,2,3,4])->count();
                $date_start     =  !empty($item->date_start)?HP::DateThai($item->date_start):'';
                $status         =  !empty($item->StatusText)?$item->StatusText:'';

                    $row++;
                    $sheet->setCellValue('A' . $row,$key+1);
                    $sheet->setCellValue('B' . $row, $item->ref_no);
                    $sheet->setCellValue('C' . $row, $item->title);
                    $sheet->setCellValue('D' . $row, $status);
                    $sheet->setCellValue('E' . $row, $date_start);
                    $sheet->setCellValue('F' . $row, $comment_point1);
                    $sheet->setCellValue('G' . $row, $comment_point2);
                    $sheet->setCellValue('H' . $row, $comment_point3);
                    $sheet->setCellValue('I' . $row, $comment_point4);
                    $sheet->setCellValue('J' . $row, $comment_amonut);
                }
            }
             //ใส่ขอบดำ
             $style_borders = [
               'borders' => [ // กำหนดเส้นขอบ
               'allBorders' => [ // กำหนดเส้นขอบทั้งหม
                   'borderStyle' => Border::BORDER_THIN,
               ],
               ]
           ];
           $sheet->getStyle('A3:J'.$row)->applyFromArray($style_borders);


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

           $filename = 'สรุปความเห็นร่างกฏกระทรวง' . date('Hi_dmY') . '.xlsx';
           header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header('Content-Disposition: attachment; filename="' . $filename . '"');
           $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
           $writer->save("php://output");
           exit;
        }

}
