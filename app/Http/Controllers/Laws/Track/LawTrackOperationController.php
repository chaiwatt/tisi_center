<?php

namespace App\Http\Controllers\Laws\Track;

use HP;
use HP_Law;
use Carbon\Carbon;

use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Law\File\AttachFileLaw;

use Illuminate\Support\Facades\Storage;
use App\Models\Law\Track\LawTrackReceive;
use App\Models\Law\Track\LawTrackOperation;

class LawTrackOperationController extends Controller
{

    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/track/operation/';
        $this->permission = str_slug('law-track-operation','-');

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
                                                            ->OrWhere(DB::raw("REPLACE(book_no,' ','')"), 'LIKE', "%".$search_full."%")
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
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'" data-ref_no="'.($item->reference_no).'"  data-book_no="'.($item->book_no).'"  data-title="'.($item->title).'" >';
                            })
                            ->addColumn('reference_no', function ($item) {
                                return !empty($item->reference_no)?$item->reference_no:null;
                            })   
                            ->addColumn('book_no', function ($item) {
                                $html = !empty($item->book_no)?$item->book_no:null;
                                $html .= '<div class="text-muted"><em>('.(!empty($item->receive_no)?$item->receive_no:null).')</em></div>';
                                return  $html ;
                            })             
                            ->addColumn('law_job_types', function ($item) {
                                return  !is_null($item->law_job_types) && !empty($item->law_job_types->title)?$item->law_job_types->title:null;
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
                            ->addColumn('assing', function ($item) {
                                $startDate = Carbon::parse( $item->assign_at )->format('Y-m-d');
                                $endDate   = Carbon::parse( !empty($item->close_date)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                $title =  'วันที่มอบหมาย : '.(!empty($item->assign_at) ?  HP::DateThai( $item->assign_at ):' - ');
                                $title .= "\nวันที่ปิดงาน : ".(!empty($item->close_date) ? HP::DateThai( $item->close_date ):' - ');
                           
                                $html = '<div><span class="text-'.( empty($item->close_date)?'warning':'success' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</span></div>';

                                return !empty($item->user_assign_to->FullName) ? $item->user_assign_to->FullName.$html : '<span class="text-muted">(รอมอบหมาย)</span>';
                            }) 
                            ->addColumn('lawyer', function ($item) {
                                $startDate = Carbon::parse( $item->lawyer_at )->format('Y-m-d');
                                $endDate   = Carbon::parse( !empty($item->close_date)?$item->close_date:date('Y-m-d') )->format('Y-m-d');
                                $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);
                                $title =  'วันที่ได้รับมอบหมาย : '.(!empty($item->lawyer_at) ?  HP::DateThai( $item->lawyer_at ):' - ');
                                $title .= "\nวันที่ปิดงาน : ".(!empty($item->close_date) ? HP::DateThai( $item->close_date ):' - ');
                           
                                $html = '<div><span class="text-'.( empty($item->close_date)?'warning':'success' ).'" title="'.$title.'">(จำนวน '.(count($lits)).' วัน)</span></div>';
                                return !empty($item->user_lawyer_to->FullName)   ? $item->user_lawyer_to->FullName.$html: '<span class="text-muted">(รอมอบหมาย)</span>';
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
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                $cancel_status = (!empty($item->cancel_status) && $item->cancel_status==1 )? false  : true ;
                                return HP::buttonActionLaw( $item->id, 'law/track/operation','Laws\Track\\LawTrackOperationController@destroy', 'law-track-operation',true ,$cancel_status,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','title', 'created_at','assing', 'book_no','law_deparment','lawyer'])
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
                [ "link" => "/law/track/operation",  "name" => ' บันทึกผลการดำเนินการ' ],
            ];

            return view('laws.track.operation.index',compact('breadcrumbs'));
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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/operation",  "name" => ' บันทึกผลการดำเนินการ' ],
            ];


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
        if(auth()->user()->can('view-'.$this->permission)) {



        }
        abort(403);
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

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/operation",  "name" => ' บันทึกผลการดำเนินการ' ],
            ];

            $lawtrackreceive = LawTrackReceive::findOrFail($id);

            return view('laws.track.operation.show',compact('breadcrumbs','lawtrackreceive'));


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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/track/operation",  "name" => ' บันทึกผลการดำเนินการ' ],
            ];

            $lawtrackreceive = LawTrackReceive::findOrFail($id);
            
            if(!empty($lawtrackreceive->cancel_status) && $lawtrackreceive->cancel_status==1){
                return view('laws.track.operation.show',compact('breadcrumbs','lawtrackreceive'));
            }else{
                return view('laws.track.operation.edit',compact('breadcrumbs','lawtrackreceive'));
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
        if(auth()->user()->can('view-'.$this->permission)) {

            $requestData = $request->all();

            $lawtrackreceive = LawTrackReceive::findOrFail($id);

            $requestData['send_mail_status'] = !empty( $requestData['send_mail_status'])?$requestData['send_mail_status']:null;
            $requestData['noti_email']       = !empty( $requestData['noti_email'])?explode(',',$requestData['noti_email']):null;

            if( $requestData['status_job_track_id'] == 4){
                $requestData['close_by']    =  auth()->user()->getKey();
                $requestData['close_date']  = date('Y-m-d H:i:s');
            }
            $lawtrackreceive->update($requestData);

            $this->SaveOperattion( $lawtrackreceive , $requestData  );
 
            return redirect('law/track/operation')->with('flash_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');

        }
        abort(403);
    }

    public function SaveOperattion($lawtrackreceive, $requestData )
    {
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        if( isset($requestData['repeater-operation']) ){

            $list = $requestData['repeater-operation'];

            $list_id_data = [];
            foreach($list as $lists){
                if(isset($lists['operation_id'])){
                    $list_id_data[] = $lists['operation_id'];
                }
            }
            $lists_id = array_diff($list_id_data, [null]);

            //ลบไฟล์
            $old =  LawTrackOperation::where('law_track_receives_id',$lawtrackreceive->id)
                                        ->when($lists_id, function ($query, $lists_id){
                                            return $query->whereNotIn('id', $lists_id);
                                        })
                                        ->get();
            foreach(  $old  AS $item ){
                if(  !empty($item->attach_file)  ){
                    $attach =  AttachFileLaw::find($item->attach_file->id);
                    if( !empty($attach) && !empty($attach->url) ){    
                        if( HP::checkFileStorage( '/'.$attach->url) ){
                            Storage::delete( '/'.$attach->url );
                            $attach->delete();
                        }
                    }
                }
            }

            //ลบข้อมูลเดิม
            LawTrackOperation::where('law_track_receives_id',$lawtrackreceive->id)
                                ->when($lists_id, function ($query, $lists_id){
                                    return $query->whereNotIn('id', $lists_id);
                                })
                                ->delete();

            foreach( $list as $item ){

                $operation =  LawTrackOperation::where('id', $item['operation_id'] )->first();
                if(is_null($operation)){
                    $operation             = new LawTrackOperation;
                    $operation->created_by = auth()->user()->getKey();
                }else{
                    $operation->updated_by = auth()->user()->getKey();
                }

                $operation->law_track_receives_id = $lawtrackreceive->id;
                $operation->operation_date        = !empty( $item['operation_date'] ) ?  HP::convertDate( $item['operation_date'],true) : null;
                $operation->due_date              = !empty( $item['due_date'] ) ?  HP::convertDate( $item['due_date'],true) : null;
                $operation->status_job_track_id   = !empty( $item['status_job_track_id'])?$item['status_job_track_id']:null;
                $operation->detail                = !empty( $item['detail'])?$item['detail']:null;
                $operation->save();

                $folder_app = ($lawtrackreceive->reference_no).'/';

                if( isset($item['attach_file']) && !empty($item['attach_file']) ){
                    HP::singleFileUploadLaw(
                        $item['attach_file'],
                      $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawTrackOperation)->getTable() ),
                        $operation->id,
                        'file_law_operator',
                        'ไฟล์แนบการดำเนินการ'
                    );
                }
            }

        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {



        }
        abort(403);
    }
}
