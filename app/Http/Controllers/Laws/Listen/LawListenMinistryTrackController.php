<?php

namespace App\Http\Controllers\Laws\Listen;

use HP;
use HP_Law;
use App\Models\Basic\Tis;

use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Law\Listen\LawListenMinistry;
use App\Models\Law\Listen\LawListenMinistryTrack;
use App\Models\Law\Listen\LawListenMinistryResults;

class LawListenMinistryTrackController extends Controller
{
    private $attach_path;
    private $attach_path_results;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path_results = 'law_attach/listen_ministry_results';
        $this->attach_path         = 'law_attach/listen_ministry_track';
        $this->permission          = str_slug('law-listen-ministry-track','-');;

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
                                                $search_full = str_replace(' ', '', $filter_search);
                                                switch ( $filter_condition_search ):
                                                    case "1":
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('ref_no', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    case "2":
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('title', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    case "3":
                                                        return  $query->where(function ($query2) use($search_full) {
                                                                $query2->where('tis_name', 'LIKE', '%'.$search_full.'%');
                                                                });
                                                        break;
                                                    default:
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
                                if($item->status_id!='5'){
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                                }
                            })
                            ->addColumn('ref_no', function ($item) {
                                return $item->ref_no;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return $item->tis_no.' : '.$item->tis_name;
                            })
                            ->addColumn('status', function ($item) {
                                return $item->StatusText;
                            })
                            ->addColumn('state', function ($item) {
                                return $item->StateIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                             return   !empty($item->CreatedName)?$item->CreatedName:'-';
                            
                            })
                            ->addColumn('action', function ($item) {
                                $status_close = ($item->status_close == 1) ? false : true; //ปิดงาน
                                return HP::buttonActionLaw( $item->id, 'law/listen/ministry-track','Laws\Listen\\LawListenMinistryTrackController@destroy', 'law-listen-ministry-track',true,$status_close,false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by', 'state', 'condition', 'created_at'])
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
                [ "link" => "/law/listen/ministry-track",  "name" => 'บันทึกติดตาม/ประกาศราชกิจจา' ],
            ];
            return view('laws.listen.ministry-track.index',compact('breadcrumbs'));
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
                [ "link" => "/law/listen/ministry-track",  "name" => 'บันทึกติดตาม/ประกาศราชกิจจา' ],
                [ "link" => "/law/listen/ministry-track/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.listen.ministry-track.create',compact('breadcrumbs'));
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

            $requestData  =  $request->all();
            $listen_ids   =  !empty( $request->listen_id )?explode(',',($request->listen_id)):null;

            // อัพเดทสถานะปิดงาน/ประกาศราชกิจจา
            $db = new LawListenMinistry;
            LawListenMinistry::whereIn($db->getKeyName(), $listen_ids)->update([
                                                                                'status_id'    => 5,
                                                                                'status_close' => isset($request->status_close)?1:0,
                                                                                'close_date'   => isset($request->status_close)? date('Y-m-d'):null,
                                                                                'close_by'     => isset($request->status_close)?auth()->user()->getKey():null
                                                                               ]);

            foreach( $listen_ids as $listen_id ){

                $listenresults   =  LawListenMinistryResults::where('listen_id', $listen_id )->first();
                
                if(is_null($listenresults)){
                    $listenresults             = new LawListenMinistryResults;
                    $listenresults->created_by = auth()->user()->getKey();
                }else{
                    //ลบไฟล์เดิม
                    $attachs_listen_ministry_rsults = $listenresults->AttachFileGazette;
                    HP_Law::DeleteLawSingleFile($attachs_listen_ministry_rsults);

                    $listenresults->updated_by = auth()->user()->getKey();
                }

                $listenresults->listen_id             = $listen_id;
                $listenresults->date_on               = !empty( $request->date_on) ?  HP::convertDate($request->date_on,true) : null;
                $listenresults->date_announcement     = !empty( $request->date_announcement) ?  HP::convertDate($request->date_announcement,true) : null;
                $listenresults->date_effective        = !empty( $request->date_effective) ?  HP::convertDate($request->date_effective,true) : null;
                $listenresults->amount                = !empty( $request->amount) ? $request->amount:null;
                $listenresults->book                  = !empty( $request->book) ? $request->book:null;
                $listenresults->section               = !empty( $request->section) ? $request->section:null;
                $listenresults->detail                = !empty( $request->detail) ? $request->detail:null;
                $listenresults->save();

    
                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                if(isset($requestData['file_listen_ministry_result'])){
                    if ($request->hasFile('file_listen_ministry_result')) {
                        HP::singleFileUploadLaw(
                            $request->file('file_listen_ministry_result') ,
                            $this->attach_path_results,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            (  (new LawListenMinistryResults)->getTable() ),
                            $listenresults->id,
                            'file_listen_ministry_result',
                            'ไฟล์ประกาศราชกิจจานุเบกษา'
                        );
                    }
                }

                $lawlistministry =  $listenresults->listen_ministry;
                HP_Law::InsertLawLogWorking( //ประวัติการดำเนินงาน
                                            3,
                                            ((new LawListenMinistry)->getTable()),
                                            $lawlistministry->id,
                                            $lawlistministry->ref_no,
                                            'บันทึกติดตาม/ประกาศราชกิจจา',
                                            $lawlistministry->title,
                                            $lawlistministry->StatusText,
                                            $lawlistministry->remark
                                        );
            }

            return redirect('law/listen/ministry-track')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
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
     
            $lawlistministry->mail_list             =  !empty( $lawlistministry->mail_list )?implode(',',json_decode($lawlistministry->mail_list,true)):null;
            $lawlistministry->date_due              =  !empty( $lawlistministry->date_due)?HP::revertDate($lawlistministry->date_due,true):null;
            $lawlistministry->date_start            =  !empty( $lawlistministry->date_start)?HP::revertDate($lawlistministry->date_start,true):HP::revertDate($lawlistministry->book_date,true);
            $lawlistministry->date_diagnosis        =  !empty( $lawlistministry->date_diagnosis)?HP::revertDate($lawlistministry->date_diagnosis,true):null;
            $lawlistministry->date_end              =  !empty( $lawlistministry->date_end)?HP::revertDate($lawlistministry->date_end,true):null;
            
            $listenresults = $lawlistministry->law_listen_ministry_results; //ประกาศราชกิจจา
            $lawlistministry->date_on               = !empty( $listenresults->date_on) ?  HP::revertDate($listenresults->date_on,true) : null;
            $lawlistministry->date_announcement     = !empty( $listenresults->date_announcement) ?  HP::revertDate($listenresults->date_announcement,true) : null;
            $lawlistministry->date_effective        = !empty( $listenresults->date_effective) ?  HP::revertDate($listenresults->date_effective,true) : null;
            $lawlistministry->book                  = !empty( $listenresults->book) ? $listenresults->book:null;
            $lawlistministry->section               = !empty( $listenresults->section) ? $listenresults->section:null;
            $lawlistministry->detail                = !empty( $listenresults->detail) ? $listenresults->detail:null;

            $signer    = Signer::where('id', $lawlistministry->sign_id)->first();
            $url       = url('/law/listen/ministry/accept/'. base64_encode($lawlistministry->id));
            $image_qr  = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                          ->size(500)->errorCorrection('H')
                          ->generate($url);  
            $lawlistministry->url  =  !empty($lawlistministry->url)?$lawlistministry->url:$url;
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-track",  "name" => 'บันทึกติดตาม/ประกาศราชกิจจา' ],
                [ "link" => "/law/listen/ministry-track/$id",  "name" => 'รายละเอียด' ],

            ];
            return view('laws.listen.ministry-track.show', compact('lawlistministry','breadcrumbs','image_qr','url','signer','listenresults'));
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
            $lawlistministry->date_due           =  !empty( $lawlistministry->date_due)?HP::revertDate($lawlistministry->date_due,true):null;
            $lawlistministry->date_start         =  !empty( $lawlistministry->date_start)?HP::revertDate($lawlistministry->date_start,true):null;
            $lawlistministry->date_end           =  !empty( $lawlistministry->date_end)?HP::revertDate($lawlistministry->date_end,true):null;
            $listenresults =  LawListenMinistryResults::where('listen_id', $id )->first();

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/listen/ministry-track",  "name" => 'บันทึกติดตาม/ประกาศราชกิจจา' ],
                [ "link" => "/law/listen/ministry-track/$id/edit",  "name" => 'แก้ไข' ],

            ];
            return view('laws.listen.ministry-track.edit', compact('lawlistministry','breadcrumbs','listenresults'));
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
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            if( isset($requestData['repeater-operation']) ){

                $list = $requestData['repeater-operation'];
                $list_id_data = [];
                foreach($list as $lists){
                    if(isset($lists['listentrack_id'])){
                        $list_id_data[] = $lists['listentrack_id'];
                    }
                }
                $lists_id = array_diff($list_id_data, [null]);
    
                $lawlistministrytrack_old =  LawListenMinistryTrack::where('listen_id',$lawlistministry->id)
                                            ->when($lists_id, function ($query, $lists_id){
                                                return $query->whereNotIn('id', $lists_id);
                                            });
        
                if(!empty($lawlistministrytrack_old->get()) && count($lawlistministrytrack_old->get()) > 0){
                    foreach(  $lawlistministrytrack_old->get()  AS $item ){
                        //ลบไฟล์เดิม
                        $attachs_listen_ministry_track = $item->AttachFileTrack;
                        HP_Law::DeleteLawSingleFile($attachs_listen_ministry_track);
                    }
                    //ลบข้อมูลเดิม
                    $lawlistministrytrack_old->delete();
                }
      
                foreach( $list as $item ){
                    $lawlistministrytrack =  LawListenMinistryTrack::where('id',$item['listentrack_id'])->first();
                    if(is_null($lawlistministrytrack)){
                        $lawlistministrytrack             = new LawListenMinistryTrack;
                        $lawlistministrytrack->created_by = auth()->user()->getKey();
                    }else{
                        $lawlistministrytrack->updated_by = auth()->user()->getKey();
                    }
    
                    $lawlistministrytrack->listen_id    = $lawlistministry->id;
                    $lawlistministrytrack->date_track   = !empty( $item['date_track'] )?HP::convertDate($item['date_track'],true) : null;
                    $lawlistministrytrack->date_due     = !empty( $item['date_due'] )?HP::convertDate($item['date_due'],true) : null;
                    $lawlistministrytrack->status_id    = !empty( $item['status_id'])?$item['status_id']:null;
                    $lawlistministrytrack->detail       = !empty( $item['detail'])?$item['detail']:null;
                    $lawlistministrytrack->save();

                    //อัพโหลดไฟล์
                    if( isset($item['file_law_listministry_track']) && !empty($item['file_law_listministry_track']) ){
                        HP::singleFileUploadLaw(
                            $item['file_law_listministry_track'],
                            $this->attach_path,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            (  (new LawListenMinistryTrack)->getTable() ),
                            $lawlistministrytrack->id,
                            'file_law_listministry_track',
                            'ไฟล์แนบบันทึกติดตาม/ประกาศราชกิจจา'
                        );
                    }
                }
    
            }
    
            return redirect('law/listen/ministry-track')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);

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
            return redirect('law/listen/ministry-track')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function data_list_ministry_summary(Request $request)
    {
        $ministry_id     = $request->input('ministry_id');
        $query = LawListenMinistry::query()->when($ministry_id, function ($query, $ministry_id){
                                                                                    $query->where('id',$ministry_id);       
                                                                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('ref_no', function ($item) {
                                return $item->ref_no;
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('tis_name', function ($item) {
                                return $item->tis_name;
                            })
                            ->addColumn('status', function ($item) {
                                return $item->StatusText;
                            })
                            ->addColumn('date_start', function ($item) {
                                return  !empty($item->date_start)?HP::DateThai($item->date_start):null;        
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
                            ->rawColumns(['checkbox', 'action', 'status', 'date_start', 'color', 'condition', 'created_at'])
                            ->make(true);
    }


}
