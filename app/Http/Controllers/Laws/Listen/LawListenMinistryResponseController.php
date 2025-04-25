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
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Models\Law\Listen\LawListenMinistry;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Models\Law\Listen\LawListenMinistryResponse;

class LawListenMinistryResponseController extends Controller
{
    private $permission;
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-listen-ministry-response','-');
        $this->attach_path = 'law_attach/listen_ministry_response';
    }

    public function query($request)//ใช่ร่วมกับexcel
    {

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_status           = $request->input('filter_status');
        $filter_search           = $request->input('filter_search');
        $filter_standard         = $request->input('filter_standard');
        $filter_comment           = $request->input('filter_comment');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;

        return  LawListenMinistryResponse::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                        switch ( $filter_condition_search ):
                                                            case "1":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                $lawlistministry =  LawListenMinistry::where('ref_no', 'LIKE', '%'.$search_full.'%')->select('id');
                                                                return  $query->whereIn('listen_id',$lawlistministry);  
                                                            break;
                                                            case "2":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                $lawlistministry =  LawListenMinistry::where('title', 'LIKE', '%'.$search_full.'%')->select('id');
                                                                return  $query->whereIn('listen_id',$lawlistministry);  
                                                            break;
                                                            case "3":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('name', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                            break;
                                                            case "4":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('tel', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                            break;
                                                            case "5":
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                return  $query->where(function ($query2) use($search_full) {
                                                                        $query2->where('email', 'LIKE', '%'.$search_full.'%');
                                                                        });
                                                            break;
                                                            default:
                                                                $search_full = str_replace(' ', '', $filter_search);
                                                                $lawlistministry =  LawListenMinistry::where('title', 'LIKE', '%'.$search_full.'%')
                                                                                                     ->orwhere('ref_no', 'LIKE', '%'.$search_full.'%')
                                                                                                     ->orwhere('ref_no', 'LIKE', '%'.$search_full.'%')
                                                                                                     ->select('id');

                                                                return $query->whereIn('listen_id',$lawlistministry)
                                                                             ->Orwhere('name', 'LIKE', '%'.$search_full.'%')
                                                                             ->Orwhere('email', 'LIKE', '%'.$search_full.'%')
                                                                             ->Orwhere('tel', 'LIKE', '%'.$search_full.'%');
                                                
                                                                break;
                                                        endswitch;
                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        $lawlistministry =  LawListenMinistry::where('status_id',$filter_status)->select('id');
                                                        return $query->whereIn('listen_id', $lawlistministry);
                                                    })
                                                    ->when($filter_standard, function ($query, $filter_standard){
                                                        return $query->where('listen_id', $filter_standard);
                                                    })
                                                    ->when($filter_comment, function ($query, $filter_comment){
                                                        return $query->where('comment_point', $filter_comment);
                                                    })
                                                    ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                                        if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                            return $query->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                                        }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                            return $query->whereDate('created_at',$filter_start_date);
                                                        }
                                                    })
                                                    ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่บันทึก
                                                        $lawlistministry =  LawListenMinistry::where('created_by', Auth::user()->getKey())->select('id');
                                                        $query->whereIn('listen_id',$lawlistministry);     
                                                    });

    }


    public function data_list(Request $request)
    {
        return Datatables::of(self::query($request))
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('ref_no', function ($item) {
                                $url       = url('/law/listen/ministry/accept/'. base64_encode($item->listen_id));
                                $group_ref_no = '';
                                $group_ref_no .= !empty($item->RefNo)?$item->RefNo:'';
                                $group_ref_no .= !empty($item->TisName)? ' - '.$item->TisName:'';
                                $group_ref_no .= !empty($item->TisNo)? ' มอก. '.$item->TisNo:'';
                                $group_ref_no .= !empty($item->DateStart)? ' ประกาศเมื่อ ('.HP::DateThai($item->DateStart).')':'';

                                return '<a  href="'.$url.'" target="_blank">'.$group_ref_no.'</a>';
                            })
                            ->addColumn('name', function ($item) {
                                $name = '';
                                $name .= $item->name;
                                $name .= !empty($item->created_by)? '<div><span class="text-muted m-b-30 font-12"><i>(บันทึกโดย :'.$item->CreatedName.')</i></span></div>':null;
                                return $name;
                            })
                            ->addColumn('contact', function ($item) {
                                return $item->Contact;
                            })
                            ->addColumn('agency', function ($item) {
                                return $item->agency;
                            })
                            ->addColumn('comment_point', function ($item) {
                                return $item->Comment;
                            })
                            ->addColumn('comment_more', function ($item) {
                                $comment_more = mb_strimwidth($item->comment_more, 0, 40, '......' );
                                return '<a  href="javascript:void(0)" class="show_comment_more"  data-comment_more="'.( $item->comment_more ).'" >'.$comment_more.'</a>';

                            })
                            ->addColumn('created_at', function ($item) {
                                return  !empty($item->created_at)?HP::DateThai($item->created_at):null;         
                            })
                            ->addColumn('date_start', function ($item) {
                                return  !empty($item->DateStart)?HP::DateThai($item->DateStart):null;
                            })
                            ->addColumn('action', function ($item) {
                                $status_diagnosis =  !empty($item->listen_ministry->status_diagnosis)?false:true; //แจ้งผลวินิจฉัยห้ามแก้ไข
            
                                $btn = '';
                                if(!empty($item->created_by) && $item->created_by == Auth::user()->getKey()){
                                    $btn .= '<form action="' . action('Laws\Listen\\LawListenMinistryResponseController@destroy', ['id' => $item->id]) . '" method="POST" style="display:inline" id="form_ministry_response_delete">
                                    ' . csrf_field() . method_field('DELETE') . '
                                    <button type="button" class="btn btn-icon btn-circle btn-light-danger ministry_response_confirm_delete" data-name="'.$item->name.'" title="ลบ"><i class="fa fa-trash-o" style="font-size: 1.5em;" aria-hidden="true"></i></button>
                                    </form>';
    
                                }
             
                                return HP::buttonActionLaw( $item->id, 'law/listen/ministry-response','Laws\Listen\\LawListenMinistryResponseController@destroy', 'law-listen-ministry-response',true,$status_diagnosis,false).$btn;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'name', 'action', 'contact', 'created_by', 'comment_point', 'created_at','comment_more','ref_no'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-response",  "name" => 'ตรวจสอบข้อมูลความเห็น' ],
            ];
            return view('laws.listen.ministry-response.index',compact('breadcrumbs'));
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
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-response",  "name" => 'ตรวจสอบข้อมูลความเห็น' ],
                [ "link" => "/law/listen/ministry-response/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.listen.ministry-response.create',compact('breadcrumbs'));
        }
        return response(view('403'), 403);

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
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData  = $request->all();
            $requestData['created_by']   =  auth()->user()->getKey();
            
            $lawlistministryrsponse = LawListenMinistryResponse::create($requestData);
            $this->upload_file($lawlistministryrsponse,$request);

            $remark = '';
            $remark .= ' ผู้แสดงความเห็น : '.$requestData['name'];
            $remark .= ' เบอร์ : '.$requestData['tel'];
            $remark .= ' อีเมล : '.$requestData['email'];

            HP_Law::InsertLawLogWorking(         
                3,
                ((new LawListenMinistryResponse)->getTable()),
                $lawlistministryrsponse->id,
                $lawlistministryrsponse->RefNo,
                'ตรวจสอบข้อมูลความเห็น',
                'แสดงความคิดเห็นร่างกฏกระทรวง',
                'บันทึกความเห็น',
                $remark
            );


            return redirect('law/listen/ministry-response')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);
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
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('view-'.$model)) {
            $lawlistministryrsponse = LawListenMinistryResponse::findOrFail($id);
            $lawlistministry  =  LawListenMinistry::where('id',$lawlistministryrsponse->listen_id)->first();      //ตารางประกาศ

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-response",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
                [ "link" => "/law/listen/ministry-response/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('laws.listen.ministry-response.show', compact('lawlistministryrsponse','lawlistministry','breadcrumbs'));
        }
        return response(view('403'), 403);
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
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('edit-'.$model)) {

            $lawlistministryrsponse    =  LawListenMinistryResponse::findOrFail($id);
            $lawlistministry  =  LawListenMinistry::where('id',$lawlistministryrsponse->listen_id)->first();      //ตารางประกาศ

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-response",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
                [ "link" => "/law/listen/ministry-response/$id/edit",  "name" => 'แก้ไข' ],

            ];
            return view('laws.listen.ministry-response.edit', compact('lawlistministryrsponse','lawlistministry','breadcrumbs'));
        }
        return response(view('403'), 403);
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
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('edit-'.$model)) {
          
            $lawlistministryrsponse = LawListenMinistryResponse::findOrFail($id);
            $requestData = $request->all();
            $requestData['updated_by']   =  auth()->user()->getKey();

            $lawlistministryrsponse->update($requestData);
            $this->upload_file($lawlistministryrsponse,$request);

            return redirect('law/listen/ministry-response')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);

    }

 public function upload_file($lawlistministryrsponse, $request){
        $requestData = $request->all();
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        if(isset($requestData['file_response'])){
            if ($request->hasFile('file_response')) {
                HP::singleFileUploadLaw(
                    $request->file('file_response') ,
                    $this->attach_path,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawListenMinistryResponse)->getTable() ),
                    $lawlistministryrsponse->id,
                    'file_response',
                    'ไฟล์แนบความคิดเห็น'
                );
            }
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawListenMinistryResponse::destroy($id);
            return redirect('law/listen/ministry-response')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-listen-ministry-response','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawListenMinistryResponse;
            $resulte = LawListenMinistryResponse::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result = LawListenMinistryResponse::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }


    public function data_ministry($id) {
        $lawlistministry =  LawListenMinistry::where('id',$id)->first();

        $file_listen_ministry = '';
        if(!empty($lawlistministry->AttachFileListenMinistry)){
            $attachs_listen_ministry = $lawlistministry->AttachFileListenMinistry;
    
                $file_listen_ministry .= '<p>';
                $file_listen_ministry .= '<a href="'.HP::getFileStorage($attachs_listen_ministry->url).'" target="_blank">';
                $file_listen_ministry .=  '<u>'.(!empty($attachs_listen_ministry->filename) ? $attachs_listen_ministry->filename : '').'</u>';
                $file_listen_ministry .=  HP::FileExtension($attachs_listen_ministry->filename) ;
                $file_listen_ministry .= '<a>';
                $file_listen_ministry .= '</p>';
        }

        $file_draft_ministerial = '';
        if(!empty($lawlistministry->AttachFileDraftMinisterial)){
            $attachs_draft_ministerial = $lawlistministry->AttachFileDraftMinisterial;
    
                $file_draft_ministerial .= '<p>';
                $file_draft_ministerial .= '<a href="'.HP::getFileStorage($attachs_draft_ministerial->url).'" target="_blank">';
                $file_draft_ministerial .=  '<u>'.(!empty($attachs_draft_ministerial->filename) ? $attachs_draft_ministerial->filename : '').'</u>';
                $file_draft_ministerial .=  HP::FileExtension($attachs_draft_ministerial->filename) ;
                $file_draft_ministerial .= '<a>';
                $file_draft_ministerial .= '</p>';
        }

        $file_draft_standard = '';
        if(!empty($lawlistministry->AttachFileDraftStandard)){
            $attachs_draft_standard = $lawlistministry->AttachFileDraftStandard;
    
                $file_draft_standard .= '<p>';
                $file_draft_standard .= '<a href="'.HP::getFileStorage($attachs_draft_standard->url).'" target="_blank">';
                $file_draft_standard .=  '<u>'.(!empty($attachs_draft_standard->filename) ? $attachs_draft_standard->filename : '').'</u>';
                $file_draft_standard .=  HP::FileExtension($attachs_draft_standard->filename) ;
                $file_draft_standard .= '<a>';
                $file_draft_standard .= '</p>';
        }

        $file_other = '';
        if(!empty($lawlistministry->AttachFileOther)){
            $attachs = $lawlistministry->AttachFileOther;

            if (!empty($attachs) && count($attachs) > 0){
                foreach($attachs as $attach){                 
                        $file_other .= '<div class="form-group  required">';
                        $file_other .= '<label for="file_other" class="col-md-3 text-right">ไฟล์เเนบอื่นๆ :</label>';
                        $file_other .= '<div class="col-md-4">';
                        $file_other .= '<p>';
                        $file_other .= '<a href="'.HP::getFileStorage($attach->url).'" target="_blank">';
                        $file_other .= '<u>'.(!empty($attach->caption) ? $attach->caption : '').'</u>';
                        $file_other .=  HP::FileExtension($attach->filename) ;
                        $file_other .= '<a>';
                        $file_other .= '</p>';
                        $file_other .= '</div>';
                        $file_other .= '</div>';
                }
            }
        }

        $comment_point_list = LawListenMinistryResponse::list_comment_point();
        $responses_types = !empty($lawlistministry->responses_type) ? json_decode($lawlistministry->responses_type):[];

        $comment = '';
        if(count($responses_types) > 0 ){
                foreach($responses_types as $key => $responses_type){      
                       
                        if($key == 0){
                            $checked = ' checked  required ';
                        }else{
                            $checked = '';
                        }
                        
                        $comment .= '<div class="form-group">';
                        $comment .= '<input type="radio" class="check comment_point" value="'.$responses_type.'"  id="comment_point-'.$responses_type.'" name="comment_point"  data-radio="iradio_square-green" '.$checked.'>';
                        $comment .= '<label for="comment_point-'.$responses_type.'"> &nbsp;'. (array_key_exists($responses_type,$comment_point_list)?$comment_point_list[$responses_type]:null).'</label>';
                        $comment .= '</div>';
                }
        }

        $url = '';
        if(!empty($lawlistministry->url)){         
       
            $url .= '<div class="form-group">';
            $url .= '<label for="file_other" class="col-md-3 text-right">แบบรับฟังความคิดเห็น</label>';
            $url .= '<div class="col-md-3">';
            $url .= '<span class="font-medium-6">';
            $url .= '<a href="'.$lawlistministry->url.'" target="_blank"><u>'.$lawlistministry->url.'</u></a>';
            $url .= '</span>';
            $url .= '</div>';
            $url .= '</div>';
        }

        if(!is_null($lawlistministry)){
            return response()->json([
                    'tis_no'=> !empty($lawlistministry->tis_no) ? $lawlistministry->tis_no : '-' ,
                    'tis_name'=> !empty($lawlistministry->tis_name) ? $lawlistministry->tis_name : '-' ,
                    'remark'=> !empty($lawlistministry->remark) ? $lawlistministry->remark : '-' ,
                    'date_due'=>  !empty( $lawlistministry->date_due)?HP::formatDateThaiFull($lawlistministry->date_due,true):null,
                    'file_listen_ministry'=> $file_listen_ministry ,
                    'file_draft_ministerial'=> $file_draft_ministerial ,
                    'file_draft_standard'=> $file_draft_standard ,
                    'file_other'=> $file_other ,
                    'comment'=> $comment ,
                    'url_type'=> $lawlistministry->url_type,
                    'url'=> $url,
                ]);
        }
   
    }

    //ส่งออกข้อมูล
    public function export_excel(Request $request) {

        //Create Spreadsheet Object
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $query       = self::query($request)->get();

           //หัวรายงาน
           $sheet->setCellValue('A1', 'ข้อมูลความคิดเห็น');
           $sheet->mergeCells('A1:P1');
           
           $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
           $sheet->getStyle("A1")->getFont()->setSize(18);

           //แสดงวันที่
           $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
           $sheet->mergeCells('A2:P2');

           $sheet->setCellValue('A3', 'ผู้ส่งออกข้อมูล : '.auth()->user()->FullName);
           $sheet->mergeCells('A3:P3');

           //หัวตาราง
           $sheet->setCellValue('A4', 'ลำดับ');
           $sheet->setCellValue('B4', 'ชื่อผู้ให้ความเห็น');
           $sheet->setCellValue('C4', 'เลขที่บัตรประชาชน/passport');
           $sheet->setCellValue('D4', 'ตำแหน่ง');
           $sheet->setCellValue('E4', 'ประเภทสถานประกอบการ');
           $sheet->setCellValue('F4', 'โทร.');
           $sheet->setCellValue('G4', 'อีเมล.');
           $sheet->setCellValue('H4', 'ที่อยู่');
           $sheet->setCellValue('I4', 'สังกัด/หน่วยงาน');
           $sheet->setCellValue('J4', 'เลขอ้างอิง');
           $sheet->setCellValue('K4', 'ชื่อประกาศ');
           $sheet->setCellValue('L4', 'เลข มอก.');
           $sheet->setCellValue('M4', 'ชื่อ มอก.');
           $sheet->setCellValue('N4', 'ความเห็น');
           $sheet->setCellValue('O4', 'ข้อคิดเห็นเพิ่มเติม');
           $sheet->setCellValue('P4', 'วันที่ให้ความเห็น');
           $row = 4; //start row
            if(count($query) > 0){
                foreach ($query as $key => $item) {

                $created_at = !empty($item->created_at)?HP::DateThai($item->created_at):'';   

                    $row++;
                    $sheet->setCellValue('A' . $row,$key+1);
                    $sheet->setCellValue('B' . $row, $item->name);
                    $sheet->setCellValue('C' . $row, $item->tax_number);
                    $sheet->setCellValue('D' . $row, $item->agency);
                    $sheet->setCellValue('E' . $row, $item->TraderTypeName);
                    $sheet->setCellValue('F' . $row, $item->tel);
                    $sheet->setCellValue('G' . $row, $item->email);
                    $sheet->setCellValue('H' . $row, $item->address);
                    $sheet->setCellValue('I' . $row, $item->agency);
                    $sheet->setCellValue('J' . $row, $item->RefNo);
                    $sheet->setCellValue('K' . $row, $item->Tile);
                    $sheet->setCellValue('L' . $row, $item->TisNo);
                    $sheet->setCellValue('M' . $row, $item->TisName);
                    $sheet->setCellValue('N' . $row, $item->Comment);
                    $sheet->setCellValue('O' . $row, $item->comment_more);
                    $sheet->setCellValue('P' . $row, $created_at);

                    $sheet->getStyle('C' . $row)->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER);
                    $sheet->getStyle('F' . $row)->getNumberFormat()
                          ->setFormatCode(NumberFormat::FORMAT_NUMBER);
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
           $sheet->getStyle('A4:P'.$row)->applyFromArray($style_borders);


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
           $sheet->getColumnDimension('K')->setWidth(50);
           $sheet->getColumnDimension('L')->setAutoSize(true);
           $sheet->getColumnDimension('M')->setWidth(50);
           $sheet->getColumnDimension('N')->setAutoSize(true);
           $sheet->getColumnDimension('O')->setAutoSize(true);
           $sheet->getColumnDimension('P')->setAutoSize(true);


           $filename = 'ข้อมูลความเห็น_' . date('Hi_dmY') . '.xlsx';
           header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header('Content-Disposition: attachment; filename="' . $filename . '"');
           $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
           $writer->save("php://output");
           exit;
        }

}
