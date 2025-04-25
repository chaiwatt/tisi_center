<?php

namespace App\Http\Controllers\Laws\Listen;

use HP;
use HP_Law;
use App\Http\Requests;
use App\Models\Sso\User;
use App\Models\Basic\Tis;
use App\Models\Csurv\Tis4;
use Illuminate\Http\Request;

use App\Models\Besurv\Signer;
use App\LawLogSendmailSuccess;
use Yajra\Datatables\Datatables;
use App\Models\Besurv\Department;
use App\Models\Law\Log\LawNotify;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use PhpOffice\PhpWord\TemplateProcessor;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Law\Listen\LawListenMinistry;
use App\Models\Law\Basic\LawDepartmentStakeholder;
use App\Mail\Mail\Law\ListenMinistry\MailListenMinistry;

class LawListenMinistryController extends Controller
{

    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/listen_ministry';
        $this->permission  = str_slug('law-listen-ministry','-');
    }

    public function data_list(Request $request)
    {

        $filter_condition_search = $request->input('filter_condition_search');
        $filter_status           = $request->input('filter_status');
        $filter_search           = $request->input('filter_search');
        $filter_standard         = $request->input('filter_standard');
        $filter_start_date       = !empty($request->input('filter_start_date'))? HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_end_date         = !empty($request->input('filter_end_date'))? HP::convertDate($request->input('filter_end_date'),true):null;

        $query = LawListenMinistry::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
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
                                            ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่บันทึก
                                                $query->where('created_by', Auth::user()->getKey());     
                                            });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('ref_no', function ($item) {
                                return $item->ref_no;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return ( !empty($item->tis_no)?$item->tis_no.' : ':null ).$item->tis_name;
                            })
                            ->addColumn('status', function ($item) {
                                return $item->StatusText;
                            })
                            ->addColumn('state', function ($item) {
                                $date = '<br>';
                                $date .= !empty($item->amount)?$item->amount.' วัน'.'<br>':null;
                                $date .= !empty($item->date_start)?'('.HP::DateThai($item->date_start):null;
                                $date .= !empty($item->date_end)?'-'.HP::DateThai($item->date_end).')':null;

                                return $item->StateIcon.$date;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                $announce = ($item->status_id == 3) ? false : true; //ปิดประกาศ

                                return self::buttonActionLaw( $item->id, 'law/listen/ministry','Laws\Listen\\LawListenMinistryController@destroy', 'law-listen-ministry', $item, true, $announce, false,true);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by', 'state', 'condition', 'created_at'])
                            ->make(true);
    }



    public static function buttonActionLaw($id, $action_url, $controller_action, $str_slug_name, $item , $show_view = true, $show_edit = true, $show_delete = true,  $show_word = true)
    {
        $form_action = '';
        //พิมพ์
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('printing-' . str_slug($str_slug_name)) && $show_word === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/export-word/' . $id) . '" title="Word ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-primary"><i class="fa fa-file-word-o" aria-hidden="true"style="font-size: 1.5em;"></i></a>';
        endif;
       
        //แก้ไข
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            $class = !empty($item->date_start)?'btn-light-success':'btn-light-warning';
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/edit') . '"   title="ประกาศ ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle '.($class).'"><i class="fa fa-bullhorn"  style="font-size: 1.5em;"></i></a>';

        endif;

        //รายละเอียด
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id) . '"             title="ดูรายละเอียด ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-info"><i class="fa fa-info-circle"  style="font-size: 1.5em;"></i></a>';
            
        endif;

        //ลบ
        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-' . str_slug($str_slug_name)) && $show_delete === true):
            $form_action .= '<form action="' . action($controller_action, ['id' => $id]) . '" method="POST" style="display:inline" id="form_law_delete">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="button" class="btn btn-icon btn-circle btn-light-danger" title="ลบ' . substr($str_slug_name, 0, -1) . '" onclick="return law_confirm_delete()"><i class="fa fa-trash-o" style="font-size: 1.5em;" aria-hidden="true"></i></button>
                            </form>';
        endif;

        return $form_action;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
            ];
            return view('laws.listen.ministry.index',compact('breadcrumbs'));
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
        if(auth()->user()->can('add-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
                [ "link" => "/law/listen/ministry/create",  "name" => 'เพิ่ม' ],
            ];
            return view('laws.listen.ministry.create',compact('breadcrumbs'));
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
        if(auth()->user()->can('add-'.$this->permission)) {

            $requestData = $request->all();
            $sign = Signer::where('id', $request->sign_id)->first();

            //เลขรัน
            $running_no =  HP::ConfigFormat( 'LawListenMinistry' , (new LawListenMinistry)->getTable()  , 'ref_no', null , null,null );
            $check = LawListenMinistry::where('ref_no', $running_no)->first();
            if(!is_null($check)){
                $running_no =  HP::ConfigFormat( 'LawListenMinistry' , (new LawListenMinistry)->getTable()  , 'ref_no', null , null,null );
            }

            $requestData['ref_no']      =  $running_no;
            $requestData['state']       =  isset($request->state)?1:0;
            $requestData['sign_img']    =  isset($request->sign_img)?1:0;
            $requestData['sign_name']   =  !empty($sign->name)?$sign->name:null;
            $requestData['date_due']    =  !empty($request->date_due)?HP::convertDate($request->date_due,true) : null;
            $requestData['book_date']   =  !empty($request->book_date)?HP::convertDate($request->book_date,true) : null;
            $requestData['status_id']   =  1;
            $requestData['created_by']  =  auth()->user()->getKey();

            $lawlistministry = LawListenMinistry::create($requestData);

            //ถ้าเลขที่หนังสือว่าง
            if( empty( $lawlistministry->book_no ) ){
                //เลขรัน
                $running_no =  HP::ConfigFormat('LawListenBookMinistry', (new LawListenMinistry)->getTable(), 'book_no', null, null, null);
                $check = LawListenMinistry::where('book_no', $running_no)->whereNotNull('book_no')->first();
                if(!is_null($check)){
                    $running_no =  HP::ConfigFormat('LawListenBookMinistry', (new LawListenMinistry)->getTable(), 'book_no', null, null, null);
                }
                $requestData['book_no'] = $running_no;

                $lawlistministry->book_no = !empty($requestData['book_no'])?$requestData['book_no']:null;
                $lawlistministry->save();
            }

            HP_Law::InsertLawLogWorking(         
                                            3,
                                            ((new LawListenMinistry)->getTable()),
                                            $lawlistministry->id,
                                            $running_no,
                                            'จัดทำแบบรับฟังความเห็น',
                                            $lawlistministry->title,
                                            $lawlistministry->StatusText,
                                            $lawlistministry->remark
                                        );

            return redirect('law/listen/ministry')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
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
        if(auth()->user()->can('view-'.$this->permission)) {
            $lawlistministry = LawListenMinistry::findOrFail($id);

            //คำนวณวันที่
            $date_amount                         =  (!empty($lawlistministry->book_date) && !empty($lawlistministry->amount)) ?  HP::revertDate(date('Y-m-d', strtotime($lawlistministry->book_date. ' + '.($lawlistministry->amount - 1).' days')),true):null;
            $lawlistministry->mail_list          =  !empty( $lawlistministry->mail_list )?implode(',',json_decode($lawlistministry->mail_list,true)):null;
            $lawlistministry->date_start         =  !empty( $lawlistministry->date_start)?HP::revertDate($lawlistministry->date_start,true):HP::revertDate($lawlistministry->book_date,true);
            $lawlistministry->date_due           =  !empty( $lawlistministry->date_due)?HP::revertDate($lawlistministry->date_due,true):$date_amount;
            $lawlistministry->date_end           =  !empty( $lawlistministry->date_end)?HP::revertDate($lawlistministry->date_end,true):$date_amount;
            
            $signer = Signer::where('id', $lawlistministry->sign_id)->first();
            
            $url       = url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
            $image_qr  = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                          ->size(500)->errorCorrection('H')
                          ->generate($url);  
            
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
                [ "link" => "/law/listen/ministry/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('laws.listen.ministry.show',compact('lawlistministry','breadcrumbs','image_qr','url','signer'));
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
        if(auth()->user()->can('edit-'.$this->permission)) {
            $lawlistministry = LawListenMinistry::findOrFail($id);

            //คำนวณวันที่
            $date_amount                         =  (!empty($lawlistministry->book_date) && !empty($lawlistministry->amount)) ?  HP::revertDate(date('Y-m-d', strtotime($lawlistministry->book_date. ' + '.($lawlistministry->amount - 1).' days')),true):null;
            $lawlistministry->mail_list          =  !empty( $lawlistministry->mail_list )?implode(',',json_decode($lawlistministry->mail_list,true)):null;
            $lawlistministry->date_start         =  !empty( $lawlistministry->date_start)?HP::revertDate($lawlistministry->date_start,true):HP::revertDate($lawlistministry->book_date,true);
            $lawlistministry->date_due           =  !empty( $lawlistministry->date_due)?HP::revertDate($lawlistministry->date_due,true):$date_amount;
            $lawlistministry->date_end           =  !empty( $lawlistministry->date_end)?HP::revertDate($lawlistministry->date_end,true):$date_amount;
            
            $url       =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
            $image_qr  =  QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)->size(500)->errorCorrection('H')->generate($url);  

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry",  "name" => 'จัดทำแบบรับฟังความเห็นฯ' ],
                [ "link" => "/law/listen/ministry/$id/edit",  "name" => 'แก้ไข' ],

            ];
            return view('laws.listen.ministry.edit', compact('lawlistministry','breadcrumbs','image_qr','url'));
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
        if(auth()->user()->can('edit-'.$this->permission)) {

            $lawlistministry = LawListenMinistry::findOrFail($id);
            $requestData = $request->all();
            $requestData['mail_list']    = !empty( $request->input('mail_list') )? json_encode(explode(',', $request->input('mail_list'))):null;
            $requestData['date_due']     = !empty( $request->date_due) ?  HP::convertDate($request->date_due,true) : null;
            $requestData['date_start']   = !empty( $request->date_start) ?  HP::convertDate($request->date_start,true) : null;
            $requestData['date_end']     = !empty( $request->date_end) ?  HP::convertDate($request->date_end,true) : null;
            $requestData['state']        = isset($request->state)?1:0;
            $requestData['status_dear']  = isset($request->status_dear)?1:0;
            $requestData['updated_by']   = auth()->user()->getKey();
            $requestData['status_id']    = 2;
            $requestData['responses_type']    = !empty($requestData['responses_type'])? json_encode($requestData['responses_type']):null;
     
            $lawlistministry->update($requestData);

            //ถ้าเลขที่หนังสือว่าง
            if( empty( $lawlistministry->book_no ) ){
                //เลขรัน
                $running_no =  HP::ConfigFormat('LawListenBookMinistry', (new LawListenMinistry)->getTable(), 'book_no', null, null, null);
                $check = LawListenMinistry::where('book_no', $running_no)->whereNotNull('book_no')->first();
                if(!is_null($check)){
                    $running_no =  HP::ConfigFormat('LawListenBookMinistry', (new LawListenMinistry)->getTable(), 'book_no', null, null, null);
                }
                $requestData['book_no'] = $running_no;

                $lawlistministry->book_no = !empty($requestData['book_no'])?$requestData['book_no']:null;
                $lawlistministry->save();
            }

            $this->upload_file($lawlistministry,$request);
            
            if($lawlistministry->mail_status == 1){//ลบ log เพื่อส่งซ้ำ
               LawLogSendmailSuccess::where('ref_table',(new LawListenMinistry)->getTable())->where('ref_id',$lawlistministry->id)->delete();
               LawNotify::where('law_system_category_id','3')->where('ref_table',(new LawListenMinistry)->getTable())->where('ref_id',$lawlistministry->id)->delete();
            }

            HP_Law::InsertLawLogWorking(         
                                            3,
                                            ((new LawListenMinistry)->getTable()),
                                            $lawlistministry->id,
                                            $lawlistministry->ref_no,
                                            'จัดทำแบบรับฟังความเห็น',
                                            $lawlistministry->title,
                                            $lawlistministry->StatusText,
                                            $lawlistministry->remark
                                        );
    
            return redirect('law/listen/ministry')->with('flash_message', ' บันทึกข้อมูลสำเร็จ');
        }
        return response(view('403'), 403);

    }

    public function upload_file($lawlistministry, $request){
        $requestData = $request->all();
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');


        if(isset($requestData['file_listen_ministry'])){
            if ($request->hasFile('file_listen_ministry')) {
                HP::singleFileUploadLaw(
                    $request->file('file_listen_ministry') ,
                    $this->attach_path,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawListenMinistry)->getTable() ),
                    $lawlistministry->id,
                    'file_listen_ministry',
                    'ประกาศรับฟังความเห็น'
                );
            }
        }

        if(isset($requestData['file_draft_ministerial'])){
            if ($request->hasFile('file_draft_ministerial')) {
                HP::singleFileUploadLaw(
                    $request->file('file_draft_ministerial') ,
                    $this->attach_path,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawListenMinistry)->getTable() ),
                    $lawlistministry->id,
                    'file_draft_ministerial',
                    'ร่างกฎกระทรวง'
                );
            }
        }

        if(isset($requestData['file_draft_standard'])){
            if ($request->hasFile('file_draft_standard')) {
                HP::singleFileUploadLaw(
                    $request->file('file_draft_standard') ,
                    $this->attach_path,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawListenMinistry)->getTable() ),
                    $lawlistministry->id,
                    'file_draft_standard',
                    'ร่างมาตรฐาน'
                );
            }
        }
            if(isset( $requestData['repeater-attach'] ) ){
               $attachs = $requestData['repeater-attach'];
               foreach( $attachs as $file ){
                   if( isset($file['file_other']) && !empty($file['file_other']) ){
                       HP::singleFileUploadLaw(
                           $file['file_other'],
                           $this->attach_path,
                           ( $tax_number),
                           (auth()->user()->FullName ?? null),
                           'Law',
                           (  (new LawListenMinistry)->getTable() ),
                           $lawlistministry->id,
                           'file_other',
                           !empty($file['file_desc'])?$file['file_desc']:null
                       );
                   }
               }
           }

    }

    public function send_mail($lawlistministry, $request){    
        
        if(isset($request->mail_list) && count(explode(",",$request->mail_list)) > 0){

            if(!empty($lawlistministry->status_dear) && $lawlistministry->status_dear == 1){//กรณีไม่แสดงชื่อหน่วยงาน
        
                foreach(explode(",",$request->mail_list)as $email){
                        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                            $url  =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
                            // ข้อมูล
                            $data_app = [
                                        'dear'            => $lawlistministry->dear,
                                        'url'             => $url,
                                        'lawlistministry' => $lawlistministry,
                                        'title'           => "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no"
                                    ];
            
                        HP_Law::getInsertLawNotifyEmail(3,
                                                        ((new LawListenMinistry)->getTable()),
                                                        $lawlistministry->id,
                                                        'จัดทำแบบรับฟังความเห็นฯ',
                                                        "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no",
                                                        view('mail.Law.ListenMinistry.listen_ministry', $data_app),
                                                        null,  
                                                        null,   
                                                        $email
                                                        );
            
                        $html = new MailListenMinistry($data_app);
                        Mail::to($email)->send($html);

                        }
                    }

            }else{//กรณีแสดงชื่อหน่วยงาน
                
                foreach(explode(",",$request->mail_list)as $email){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)){

                        //ค้นหาชื่อหน่วยงานจาก email
                        $user_sso =  User::where('email',$email)->select('tax_number');
                        if(!empty($user_sso)){//ชื่อผู้ได้รับใบอนุญาต
                            $tb4_tisilicense = Tis4::whereIn('tbl_taxpayer',$user_sso)->first();
                        }
                        //ชื่อผู้มีส่วนได้ส่วนเสีย
                        $department_stakeholder = LawDepartmentStakeholder::where('email',$email)->first();

                        if(!empty($tb4_tisilicense)){//ชื่อผู้ได้รับใบอนุญาต
                            $applicanttype_id =  User::where('tax_number',$tb4_tisilicense->tbl_taxpayer)->value('applicanttype_id');
                            $prefix_name =  (!empty($applicanttype_id) && $applicanttype_id == 1) ? 'กรรมการผู้จัดการ ':'';
                            $dear = $prefix_name.$tb4_tisilicense->tbl_tradeName;

                        }else if(!empty($department_stakeholder)){//ชื่อผู้มีส่วนได้ส่วนเสีย
                            $dear = $department_stakeholder->title;
                        }else{ 
                            $dear = 'ผู้มีส่วนได้ส่วนเสีย';
                        }

                        $url  =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
                        // ข้อมูล
                        $data_app = [
                                    'dear'            => $dear,
                                    'url'             => $url,
                                    'lawlistministry' => $lawlistministry,
                                    'title'           => "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no"
                                ];

                        HP_Law::getInsertLawNotifyEmail(3,
                                                        ((new LawListenMinistry)->getTable()),
                                                        $lawlistministry->id,
                                                        'จัดทำแบบรับฟังความเห็นฯ',
                                                        "รับฟังความคิดเห็นเกี่ยวกับการกำหนดให้ผลิตภัณฑ์อุตสาหกรรมต้องเป็นไปตามมาตรฐาน มอก. $lawlistministry->tis_no",
                                                        view('mail.Law.ListenMinistry.listen_ministry', $data_app),
                                                        null,  
                                                        null,   
                                                        $email
                                                        );
  
                        $html = new MailListenMinistry($data_app);
                        Mail::to($email)->send($html);
                        
                    }
                 
                }

            
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
        if(auth()->user()->can('delete-'.$this->permission)) {
            LawListenMinistry::destroy($id);
            return redirect('law/listen/ministry')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){
        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new LawListenMinistry;
            $resulte = LawListenMinistry::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

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
        $result = LawListenMinistry::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function data_department_takeholder(Request $request)
    {
        $filter_standard      = $request->input('filter_standard');

        $query = LawDepartmentStakeholder::query()
                                                ->where('state',1)
                                                ->when($filter_standard, function ($query, $filter_standard){
                                                    return $query->whereJsonContains('tis_id', $filter_standard);
                                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'"data-email="'.(!empty($item->email)?$item->email:'-').'">';
                            })
                            ->addColumn('tisi_no', function ($item) {
                                return $item->TisNo;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('contact', function ($item) {
                                return !is_null($item->Contact) ? $item->Contact : '-' ;
                            })
                            ->addColumn('email', function ($item) {
                                return $item->email;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at'])
                            ->make(true);
    }

    public function data_tb4_tisilicense(Request $request)
    {
        $filter_standard      = $request->input('filter_standard');

          $query = Tis4::query()->where('tbl_licenseStatus',1)
                                ->when($filter_standard, function ($query, $filter_standard){
                                      $data_std =  Tis::where('tb3_TisAutono',$filter_standard)->first();
                                      return $query->where('tbl_tisiNo', $data_std->tb3_Tisno);
                                  });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                $user_sso =  User::where('tax_number',$item->tbl_taxpayer)->first();
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->Autono .'" data-email="'.(!empty($user_sso->email)?$user_sso->email:'-').'" >';
                            })
                            ->addColumn('tisi_no', function ($item) {
                                return $item->tbl_tisiNo;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->tbl_tradeName.'<br>('.$item->tbl_licenseNo.')';
                            })
                            ->addColumn('contact', function ($item) {
                                return $item->tbl_tradeAddress;
                            })
                            ->addColumn('email', function ($item) {
                                if(!empty($item->tbl_taxpayer)){
                                    $user_sso =  User::where('tax_number',$item->tbl_taxpayer)->first();
                                }
                             
                                return !empty($user_sso->email)?$user_sso->email:'-';
                            })
                            ->order(function ($query) {
                                $query->orderBy('Autono', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at'])
                            ->make(true);
    }

    public function signPosition($id) {
        $signer =  Signer::where('id',$id)->first();
        if(!is_null($signer)){

            $attach = !empty($signer->AttachFileAttachTo)?$signer->AttachFileAttachTo:null;             
            if(!empty($attach->url) && HP::getFileStorage($attach->url)){
                $file_signer  = '<img src="'.asset(HP::getFileStorage($attach->url)).'" width="200">' ;
            }else{
                $file_signer  = '<span class="text-muted m-b-30 font-16"><i>ไม่พบภาพถ่ายลายเซ็น</i></span>';
            }

            return response()->json([
                    'sign_position'=> !empty($signer->position) ? $signer->position : ' ' ,
                    'file_signer'=> $file_signer,
                ]);
        }
   
    }

    public function export_word($id)
    {    

        $lawlistministry = LawListenMinistry::findOrFail($id);
        $signer = Signer::where('id', $lawlistministry->sign_id)->first();

        $url  =  url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
        $path = !empty($lawlistministry->url)?$lawlistministry->url:$url;

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/law_listen_ministry.docx'));

        $templateProcessor->setValue('book_no', !empty($lawlistministry->book_no) ? HP::toThaiNumber($lawlistministry->book_no):null);
        $templateProcessor->setValue('book_date', !empty($lawlistministry->book_date)?HP::toThaiNumber(HP::formatDateThaiFull($lawlistministry->book_date)):null);
        $templateProcessor->setValue('title', !empty($lawlistministry->title) ? HP::toThaiNumber($lawlistministry->title):null);
        $templateProcessor->setValue('dear',!empty($lawlistministry->dear) ? HP::toThaiNumber($lawlistministry->dear):null);
        $templateProcessor->setValue('tis_name', !empty($lawlistministry->tis_name) ? HP::toThaiNumber($lawlistministry->tis_name):null);
        $templateProcessor->setValue('tis_no',!empty($lawlistministry->tis_no) ? HP::toThaiNumber($lawlistministry->tis_no):null);
        $templateProcessor->setValue('sign_name', '('.$lawlistministry->sign_name.')');
        $templateProcessor->setValue('sign_position', $lawlistministry->sign_position);
        $templateProcessor->setValue('url', $path);
        $templateProcessor->setValue('amount', !empty($lawlistministry->amount)? HP_LAW::numwordsThai($lawlistministry->amount):'-');
        $templateProcessor->setValue('fullname', !empty($lawlistministry->CreatedName)?HP::toThaiNumber($lawlistministry->CreatedName):'');
        $templateProcessor->setValue('department', !empty($lawlistministry->CreatedSubdepart)?HP::toThaiNumber($lawlistministry->CreatedSubdepart):'-');
        $templateProcessor->setValue('phone', !empty($lawlistministry->CreatedPhone)?HP::toThaiNumber($lawlistministry->CreatedPhone):'-');
        $templateProcessor->setValue('wphone', !empty($lawlistministry->CreatedWPhone)?HP::toThaiNumber($lawlistministry->CreatedWPhone):'-');

        $image_qr  = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                                          ->size(500) 
                                          ->errorCorrection('H')
                                          ->generate($path);
        $templateProcessor->setImageValue('image_qr', array('path' => 'data:image/png;base64,'.base64_encode($image_qr), 'width' => 80, 'height' => 80, 'ratio' => false));

        $attach_signer = !empty($signer->AttachFileAttachTo)?$signer->AttachFileAttachTo:null;      
        if($lawlistministry->sign_img == 1 && !empty($attach_signer) && HP::getFileStoragePath($attach_signer->url)){//แสดงลายเซ็นหรือไม่
            $templateProcessor->setImageValue('image_sign', array('path' => HP::getFileStoragePath($attach_signer->url), 'width' => 70, 'height' => 30, 'ratio' => false));
        }else{
            $templateProcessor->setValue('image_sign', '');
        }


        $title = 'หนังสือรับฟังความเห็น'.date('Ymd_His').'.docx';
        $templateProcessor->saveAs(storage_path($title));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path($title));     
   
    }



}
