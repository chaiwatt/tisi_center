<?php

namespace App\Http\Controllers\Laws\Track;

use HP;
use HP_Law;

use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Law\Track\LawTrackReceive;
use App\Models\Law\Track\LawTrackReceiveAssign;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Track\MailTrackReceive;

class LawTrackReceiveController extends Controller
{
    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/track/receives/';
        $this->permission = str_slug('law-track-receive','-');

    }

    public function data_list(Request $request)
    { 
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search      = $request->input('filter_search');
        $filter_status      = $request->input('filter_status');

        $filter_law_job_type_id    = $request->input('filter_law_job_type_id');
        $filter_deperment_type   = $request->input('filter_deperment_type');
        $filter_bs_deperment_id = $request->input('filter_bs_deperment_id');      
        $filter_department_id   = $request->input('filter_department_id');
        $filter_sub_departments_id = $request->input('filter_sub_departments_id');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_lawyer_start_date  = $request->input('filter_lawyer_start_date');
        $filter_lawyer_end_date    = $request->input('filter_lawyer_end_date');

        $query = LawTrackReceive::query()
                                ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                    switch ( $filter_condition_search ):
                                        case "1":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return  $query->where(function ($query2) use($search_full) {
                                                    $query2->where('reference_no', 'LIKE', '%'.$search_full.'%');
                                                    });
                                            break;
                                        case "2":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return  $query->where(function ($query2) use($search_full) {
                                                    $query2->where('book_no', 'LIKE', '%'.$search_full.'%');
                                                    });
                                            break;
                                        case "3":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return  $query->where(function ($query2) use($search_full) {
                                                    $query2->where('title', 'LIKE', '%'.$search_full.'%');
                                                    });
                                            break;
                                        case "4":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return $query->whereHas('user_assign_to', function($query) use ($search_full){
                                                   $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                            });
                                            break;
                                        case "5":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            return $query->whereHas('user_lawyer_to', function($query) use ($search_full){
                                                   $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                         });
                                            break;
                                        default:
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where( function($query) use($search_full) {
                                                $query->where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                                        ->OrWhere(DB::raw("REPLACE(reference_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->Orwhere('book_no', 'LIKE', '%'.$search_full.'%')
                                                        ->OrWhere(DB::raw("REPLACE(receive_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrwhereHas('user_assign_to', function($query) use ($search_full){
                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                        })
                                                        ->OrwhereHas('user_lawyer_to', function($query) use ($search_full){
                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                        })
                                                        ->OrwhereHas('law_deparment', function($query) use ($search_full){
                                                            $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                        })
                                                        ->OrwhereHas('sub_deparment', function($query) use ($search_full){
                                                            $query->Where(DB::raw("REPLACE(sub_departname,' ','')"), 'LIKE', "%".$search_full."%");
                                                        });
                                            });
                                            break;
                                    endswitch;
                                })

                                ->when($filter_status, function ($query, $filter_status){
                                    return $query->where('status_job_track_id', $filter_status);
                                })
                                ->when($filter_start_date, function ($query, $filter_start_date){
                                    $filter_start_date = HP::convertDate($filter_start_date, true);
                                    return $query->where('created_at', '>=', $filter_start_date);
                                })
                                ->when($filter_end_date, function ($query, $filter_end_date){
                                    $filter_end_date = HP::convertDate($filter_end_date, true);
                                    return $query->where('created_at', '<=', $filter_end_date);
                                })
                                ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                    $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                    return $query->where('assign_at', '>=', $filter_assign_start_date);
                                })
                                ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                    $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                    return $query->where('assign_at', '<=', $filter_assign_end_date);
                                })
                                ->when($filter_lawyer_start_date, function ($query, $filter_lawyer_start_date){
                                    $filter_lawyer_start_date = HP::convertDate($filter_lawyer_start_date, true);
                                    return $query->where('lawyer_at', '>=', $filter_lawyer_start_date);
                                })
                                ->when($filter_lawyer_end_date, function ($query, $filter_lawyer_end_date){
                                    $filter_lawyer_end_date = HP::convertDate($filter_lawyer_end_date, true);
                                    return $query->where('lawyer_at', '<=', $filter_lawyer_end_date);
                                })
                                ->when($filter_law_job_type_id, function ($query, $filter_law_job_type_id){
                                    $query->where('law_bs_job_type_id', $filter_law_job_type_id);
                                })
                                ->when($filter_deperment_type, function ($query, $filter_deperment_type){
                                    $query->where('law_deperment_type', $filter_deperment_type);
                                })
                                ->when($filter_bs_deperment_id, function ($query, $filter_bs_deperment_id){
                                    $query->where('law_bs_deperment_id', $filter_bs_deperment_id);
                                })
                                ->when($filter_department_id, function ($query, $filter_department_id){
                                    $query->where('department_id', $filter_department_id);
                                })
                                ->when($filter_sub_departments_id, function ($query, $filter_sub_departments_id){
                                    $query->where('sub_departments_id', $filter_sub_departments_id);
                                })
                                ->with(['users_assign' => function($query){
                                    $query->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name"));
                                }])
                                ->with(['users_lawyer' => function($query){
                                    $query->select(DB::raw("CONCAT( reg_fname,' ',reg_lname ) AS name_lawyer"));
                                }])
                                ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                    $query->where(function($query){
                                        $query->where('created_by', Auth::user()->getKey())
                                              ->Orwhere('lawyer_by', Auth::user()->getKey())
                                              ->Orwhere('assign_by', Auth::user()->getKey());
                                    });            
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {

                                if( is_null($item->cancel_status) || !in_array($item->cancel_status, [1]) ){

                                    $data_input =  'data-ref_no="'.$item->reference_no.'"';
                                    $data_input .= 'data-book_no="'.$item->book_no.'"'; 
                                    $data_input .= 'data-title="'.$item->title.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_trackreceives_assign_to->sub_department_id) ? $item->law_trackreceives_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" '.( $data_input ).' value="'. $item->id .'">';
                                }
                            })
                            ->addColumn('reference_no', function ($item) {
                                return !empty($item->reference_no)?$item->reference_no:null;
                            })   
                            ->addColumn('book_no', function ($item) {
                                $book_no = '';
                                $book_no .= !empty($item->book_no)?$item->book_no:null;
                                $book_no .= !is_null($item->law_job_types) &&!empty($item->law_job_types->title)? '<br>'.$item->law_job_types->title:null;
                                return $book_no;

                            })             
                            ->addColumn('receive_date', function ($item) {
                                $startDate = Carbon::parse( $item->receive_date )->format('Y-m-d');
                                $endDate   = Carbon::parse( !empty($item->close_date)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);

                                $html = '<div><span class="text-'.( empty($item->close_date)?'warning':'success' ).'">จำนวน '.(count($lits)).' วัน</span></div>';
                                return  (!empty($item->receive_date)?HP::revertDate($item->receive_date,true):null). $html ;
                            }) 
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })   
                            ->addColumn('law_deparment', function ($item) {
                                $deperment = '';
                                if($item->law_deperment_type=='1'){
                                  $deperment =  $item->DeparmentName;
                                }else if($item->law_deperment_type=='2'){
                                  $deperment =  $item->LawDeparmentName;
                                }
                                return ($deperment).('<div class="text-muted">('.(@$item->DeparmentTypeName).')</div>');
                            })
                            ->addColumn('lawyer', function ($item) {
                                if( !empty($item->user_lawyer_to) ){//มอบหมายแล้ว

                                    $startDate = Carbon::parse( $item->lawyer_at )->format('Y-m-d');
                                    $endDate   = Carbon::parse( !empty($item->close_date)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                    $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                    $title =  'วันที่ได้รับมอบหมาย : '.(!empty($item->lawyer_at) ?  HP::DateThai( $item->lawyer_at ):' - ');
                                    $title .= "\nวันที่ปิดงาน : ".(!empty($item->close_date) ? HP::DateThai( $item->close_date ):' - ');
                                    $html = '<div><span class="text-'.( empty($item->close_date)?'warning':'success' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</span></div>';
                                    return $item->user_lawyer_to->FullName.$html;

                                }else{//ยังไม่ได้มอบหมาย

                                    $data_input =  'data-id="'.$item->id.'"';
                                    $data_input .= 'data-ref_no="'.$item->reference_no.'"';
                                    $data_input .= 'data-book_no="'.$item->book_no.'"'; 
                                    $data_input .= 'data-title="'.$item->title.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_trackreceives_assign_to->sub_department_id) ? $item->law_trackreceives_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<a  href="javascript:void(0)" class="text-muted single_assign"  '.( $data_input ).'  ><u>(รอมอบหมาย)</u></a>';
                                }
                            
                            }) 

                            ->addColumn('assing', function ($item) {
                                if( !empty($item->user_assign_to) ){//มอบหมายแล้ว

                                    $startDate = Carbon::parse( $item->assign_at )->format('Y-m-d');
                                    $endDate   = Carbon::parse( !empty($item->close_date)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                    $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                    $title =  'วันที่มอบหมาย : '.(!empty($item->assign_at) ?  HP::DateThai( $item->assign_at ):' - ');
                                    $title .= "\nวันที่ปิดงาน : ".(!empty($item->close_date) ? HP::DateThai( $item->close_date ):' - ');
                                    $html = '<div><span class="text-'.( empty($item->close_date)?'warning':'success' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</span></div>';
                                    return  $item->user_assign_to->FullName.$html;
                                }else{//ยังไม่ได้มอบหมาย

                                    $data_input =  'data-id="'.$item->id.'"';
                                    $data_input .= 'data-ref_no="'.$item->reference_no.'"';
                                    $data_input .= 'data-book_no="'.$item->book_no.'"'; 
                                    $data_input .= 'data-title="'.$item->title.'"';
                                    $data_input .= 'data-sub_department_id="'.( !empty($item->law_trackreceives_assign_to->sub_department_id) ? $item->law_trackreceives_assign_to->sub_department_id : '').'" ';
                                    $data_input .= 'data-assign_by="'.$item->assign_by.'"';
                                    $data_input .= 'data-lawyer_by="'.$item->lawyer_by.'"';

                                    return '<a  href="javascript:void(0)" class="text-muted single_assign"  '.( $data_input ).'  ><u>(รอมอบหมาย)</u></a>';
                                }
                            })
             
                            ->addColumn('status', function ($item) {
                                if($item->cancel_status=='1'){
                                    $html = '<button type="button" class="cancel_modal" style="border: none;background-color: #ffffff;"  
                                                data-id="'.$item->id.'"     
                                                data-cancel_remark="'.$item->cancel_remark.'"   
                                                data-cancel_at="'.(!empty($item->cancel_at)?HP::DateThai($item->cancel_at):null).'">
                                                <i class="fa fa-close text-danger"></i> ยกเลิก
                                            </button>';
                                    return $html;
                                }else{
                                    return  !is_null($item->law_status_job_tracks) && !empty($item->law_status_job_tracks->title)?$item->law_status_job_tracks->title:null;

                                }
                            }) 
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.HP::DateTimeThaiPipe($item->created_at):null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                    $disabled = (!empty($item->cancel_status) && $item->cancel_status==1 || $item->status_job_track_id !=1)?'disabled':'';
                                    $cancel_status = (!empty($item->cancel_status) && $item->cancel_status==1 )? false  : true ;
                                    $btn_cancel = ' <button '. $disabled .' type="button" class="btn btn-icon btn-circle btn-light-danger " data-id="'.$item->id.'"  onclick="return confirm_cancel('.$item->id.')" title="ยกเลิก" ><i class="fa fa-close" style="font-size: 1.5em;" aria-hidden="true"></i></button>';
                                return HP::buttonActionLaw( $item->id, 'law/track/receive','Laws\Track\\LawTrackReceiveController@destroy', 'law-track-receive',true ,$cancel_status,false).$btn_cancel;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at','assing','law_deparment','book_no','receive_date','lawyer'])
                            ->make(true);
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
                [ "link" => "/law/track/receive",  "name" => ' แจ้งงานเข้ากองกฎหมาย' ],
            ];


            return view('laws.track.receive.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->can('add-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/receive",  "name" => ' แจ้งงานเข้ากองกฎหมาย' ],
                [ "link" => "/law/track/receive/create",  "name" => 'เพิ่ม' ],
            ];

            return view('laws.track.receive.create',compact('breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->can('add-'.$this->permission)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $running_no =  HP::ConfigFormat( 'LawTrackReceive' , (new LawTrackReceive)->getTable()  , 'reference_no', null , null,null );
            $check = LawTrackReceive::where('reference_no', $running_no)->first();
            if(!is_null($check)){
                $running_no =  HP::ConfigFormat( 'LawTrackReceive' , (new LawTrackReceive)->getTable()  , 'reference_no', null , null,null );
            }
            $requestData['reference_no'] = $running_no;
            $requestData['receive_date']  = !empty(  $requestData['receive_date'] ) ?  HP::convertDate( $requestData['receive_date'],true) : null;

            $requestData['law_bs_deperment_id'] = !empty(  $requestData['law_bs_deperment_id'] ) ? $requestData['law_bs_deperment_id'] : null;
            $requestData['sub_departments_id']  = !empty(  $requestData['sub_departments_id'] ) ? $requestData['sub_departments_id'] : null;

            $requestData['status_job_track_id']  = 1;

            $lawtrackreceive = LawTrackReceive::create($requestData);

            $this->SaveFile( $lawtrackreceive , $requestData  );

            return redirect('law/track/receive')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }


    public function SaveFile($lawtrackreceive , $requestData )
    {
    
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        if( isset($requestData['repeater-attach']) ){

            $attachs = $requestData['repeater-attach'];

            $folder_app = ( str_replace( '/', '-' ,$lawtrackreceive->reference_no) ).'/';

            foreach( $attachs as $file ){
                if( isset($file['attach_file']) && !empty($file['attach_file']) ){
                    HP::singleFileUploadLaw(
                        $file['attach_file'],
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawTrackReceive)->getTable() ),
                        $lawtrackreceive->id,
                        'file_law_track_receives',
                        !empty($file['attach_description'])?$file['attach_description']:null
                    );
                }
            }

        }

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {

            $lawtrackreceive = LawTrackReceive::findOrFail($id);

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/receive",  "name" => ' แจ้งงานเข้ากองกฎหมาย' ],
                [ "link" => "/law/track/receive/".$id,  "name" => 'รายละเอียด' ],
            ];

            return view('laws.track.receive.show',compact('breadcrumbs','lawtrackreceive'));

        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $lawtrackreceive = LawTrackReceive::findOrFail($id);

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/receive",  "name" => ' แจ้งงานเข้ากองกฎหมาย' ],
                [ "link" => "/law/track/receive/".$id."/edit",  "name" => 'แก้ไข' ],
            ];


            if(!empty($lawtrackreceive->cancel_status) && $lawtrackreceive->cancel_status==1){
                return view('laws.track.receive.show',compact('breadcrumbs','lawtrackreceive'));
            }else{
                return view('laws.track.receive.edit',compact('breadcrumbs','lawtrackreceive'));
            }

        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $lawtrackreceive                    = LawTrackReceive::findOrFail($id);
            $requestData                        = $request->all();
            $requestData['receive_date']        = !empty(  $requestData['receive_date'] ) ?  HP::convertDate( $requestData['receive_date'],true) : null;
            $requestData['updated_by']          =  auth()->user()->getKey();
            $requestData['law_bs_deperment_id'] = !empty(  $requestData['law_bs_deperment_id'] ) ? $requestData['law_bs_deperment_id'] : null;
            $requestData['sub_departments_id']  = !empty(  $requestData['sub_departments_id'] ) ? $requestData['sub_departments_id'] : null;

            $lawtrackreceive->update($requestData);

            $this->SaveFile( $lawtrackreceive , $requestData  );

            return redirect('law/track/receive')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');

        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->can('delete-'.$this->permission)) {
            LawTrackReceive::destroy($id);
            return redirect('law/track/receive')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function save_assign(Request $request)
    {      
        $message = false;

        if(!empty($request->ids) && count($request->ids) > 0){

            foreach($request->ids as $id){
                $track = LawTrackReceive::findOrFail($id);
                if(!is_null($track)){
                    $user_ids = [];
                    if($track->assign_id  !=  $request->assign_id){
                        $user_ids[$request->assign_id] =  $request->assign_id;
                    }

                    if(!empty($request->lawyer_ids)){
                       $user_ids[$request->lawyer_ids] =  $request->lawyer_ids;
                    }
                   
                    $requestData['assign_by']                 =  $request->assign_id ?? null;
                    $requestData['lawyer_by']                 =  $request->lawyer_ids ?? null;
                    $requestData['lawyer_check']              =  $request->lawyer_check ?? null;

                    if(!empty($track->status) && $track->status <= '2'){ //ห้ามย้อนสถานะ
                        $requestData['status']  = 2;
                    }
                    if(!empty($requestData['assign_by'] && !in_array($requestData['assign_by'],[$track->assign_by]))){
                        $requestData['assign_at']  =  date('Y-m-d'); 
                    }
                    if(!empty($requestData['lawyer_by'])){
                        $requestData['lawyer_at']   =  date('Y-m-d'); 
                    }
                    $track->update($requestData); 

                    $log = new LawTrackReceiveAssign;
                    $log->law_track_receives_id   = $id;
                    $log->sub_department_id       = $request->sub_department_id ?? null;
                    $log->user_id                 = $request->assign_id ?? null;
                    $log->lawyer_by               = $request->lawyer_ids ?? null;
                    $log->lawyer_check            = $request->lawyer_check ?? null;
                    $log->created_by              = auth()->user()->getKey();
                    $log->save();

                    $other = (!empty($track->law_deparment->other) && $track->law_deparment->other == 1) ? ' | '.$track->law_bs_deperment_other:' ';
                    $law_deparment = !empty($track->law_deparment->title) ? $track->law_deparment->title.$other:null;
                    
                    $sub_deparment = !empty($track->sub_deparment) ? ' | '.$track->sub_deparment->sub_depart_shortname :' ';
                    $deparment =  (!empty($track->deparment->depart_name))?$track->deparment->depart_name.$sub_deparment:' ';
                    
                    $deperment_name = '';
                    if($track->law_deperment_type=='1'){
                      $deperment_name =  $deparment.' ('.(!empty($track->DeparmentTypeName) ? $track->DeparmentTypeName:'').') ';
                    }else if($track->law_deperment_type=='2'){
                      $deperment_name =  $law_deparment.' ('.(!empty($track->DeparmentTypeName) ? $track->DeparmentTypeName:'').') ';
                    }

                    if( count($user_ids) > 0 ){
                        foreach($user_ids as $user_id){
                               $user = User::findOrFail($user_id);
                               if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                                   $data_app = [
                                                   'track'          => $track,
                                                   'assign'         => $log,
                                                   'user'           => $user,
                                                   'deperment_name' => $deperment_name,
                                                   'title'          => "แจ้งมอบหมายรับงานเข้ากอง  $track->title เรื่อง $track->title"
                                            ]; 
                                    HP_Law::getInsertLawNotifyEmail(5, //ระบบติดตามงานต่างๆ
                                                                           ((new LawTrackReceive)->getTable()),
                                                                           $log->id,
                                                                           'แจ้งงานเข้ากองกฎหมาย',
                                                                           "แจ้งมอบหมายรับงานเข้ากอง  $track->title เรื่อง $track->title",
                                                                           view('mail.Law.Track.assigns', $data_app),
                                                                           null,  
                                                                           null,  
                                                                           $user->reg_email
                                                                           );
                                    $html = new MailTrackReceive($data_app);
                                    Mail::to($user->reg_email)->send($html);
                               }
                        }        
                   }

 
                }
            }
            $message = true;

        }else if(!empty($request->id)){//มอบหมายจาก record

            $track = LawTrackReceive::findOrFail($request->id);
            if(!is_null($track)){
                $user_ids = [];
                if($track->assign_id  !=  $request->assign_id){
                    $user_ids[$request->assign_id] =  $request->assign_id;
                }

                if(!empty($request->lawyer_ids)){
                   $user_ids[$request->lawyer_ids] =  $request->lawyer_ids;
                }
               
                $requestData['assign_by']                 =  $request->assign_id ?? null;
                $requestData['lawyer_by']                 =  $request->lawyer_ids ?? null;

                if(!empty($track->status) && $track->status <= '2'){ //ห้ามย้อนสถานะ
                    $requestData['status']  = 2;
                }
                if(!empty($requestData['assign_by'] && !in_array($requestData['assign_by'],[$track->assign_by]))){
                    $requestData['assign_at']  =  date('Y-m-d'); 
                }
                if(!empty($requestData['lawyer_by'])){
                    $requestData['lawyer_at']   =  date('Y-m-d'); 
                }
                $track->update($requestData); 

                $log = new LawTrackReceiveAssign;
                $log->law_track_receives_id   = $request->id;
                $log->sub_department_id       = $request->sub_department_id ?? null;
                $log->user_id                 = $request->assign_id ?? null;
                $log->lawyer_by               = $request->lawyer_ids ?? null;
                $log->created_by              = auth()->user()->getKey();
                $log->save();

                $other = (!empty($track->law_deparment->other) && $track->law_deparment->other == 1) ? ' | '.$track->law_bs_deperment_other:' ';
                $law_deparment = !is_null($track->law_deparment) && !empty($track->law_deparment->title_short)?(($track->law_deparment->title_short != '-')?$track->law_deparment->title_short:$track->law_deparment->title).$other:null;
            
                $sub_deparment = !empty($track->sub_deparment) ? ' | '.$track->sub_deparment->sub_depart_shortname :' ';
                $deparment =  (!is_null($track->deparment) && !empty($track->deparment->depart_nameShort))?$track->deparment->depart_nameShort.$sub_deparment:' ';
                
                $deperment_name = '';
                if($track->law_deperment_type=='1'){
                  $deperment_name =  $deparment;
                }else if($track->law_deperment_type=='2'){
                  $deperment_name =  $law_deparment;
                }

                if( count($user_ids) > 0 ){
                    foreach($user_ids as $user_id){
                           $user = User::findOrFail($user_id);
                           if(!empty($user)  &&  (!empty($user->reg_email) &&  filter_var($user->reg_email, FILTER_VALIDATE_EMAIL))  ){
                               $data_app = [
                                               'track'          => $track,
                                               'assign'         => $log,
                                               'user'           => $user,
                                               'deperment_name' => $deperment_name,
                                               'title'          => "แจ้งมอบหมายรับงานเข้ากอง  $track->title เรื่อง $track->title"
                                        ]; 
                                HP_Law::getInsertLawNotifyEmail(5, //ระบบติดตามงานต่างๆ
                                                                       ((new LawTrackReceive)->getTable()),
                                                                       $log->id,
                                                                       'แจ้งงานเข้ากองกฎหมาย',
                                                                       "แจ้งมอบหมายรับงานเข้ากอง  $track->title เรื่อง $track->title",
                                                                       view('mail.Law.Track.assigns', $data_app),
                                                                       null,  
                                                                       null,  
                                                                       $user->reg_email
                                                                       );
                                $html = new MailTrackReceive($data_app);
                                Mail::to($user->reg_email)->send($html);
                           }
                    }        
               }

            }
              $message = true;
        }


        return response()->json(['message' => $message ]);
    }

    public function save_cancel(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $message = 'unsuccess';
        $ref_no = '-';
        if ($id_publish) {
            $lawtrackreceive = LawTrackReceive::findOrFail($id_publish);
            if (!is_null($lawtrackreceive)) {
                $lawtrackreceive->cancel_status  =  '1'; // ยกเลิก
                $lawtrackreceive->cancel_remark = !empty($request->cancel_remark) ? $request->cancel_remark : null;
                $lawtrackreceive->cancel_by     =  auth()->user()->getKey();
                $lawtrackreceive->cancel_at     =  date('Y-m-d H:i:s');
                $lawtrackreceive->save();
            }
            $message = 'success';
            $ref_no = $lawtrackreceive->reference_no;
        }
        return response()->json(['msg' => $message, 'ref_no' => $ref_no]);
    }
}
