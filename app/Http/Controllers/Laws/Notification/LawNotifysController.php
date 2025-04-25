<?php

namespace App\Http\Controllers\Laws\Notification;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use App\Models\Law\Log\LawSystemCategory;
use App\Models\Law\Log\LawNotify;
use App\Models\Law\Log\LawNotifyUser;
use HP;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use stdClass;
class LawNotifysController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-notifys','-');
    }

    public function query(Request $request){
        $filter_condition_search   = $request->input('filter_condition_search');
        $filter_search             = $request->input('filter_search');
        $filter_category_id        = $request->input('filter_category_id');
        $filter_state              = $request->input('filter_state');

        $filter_created_at_start   = !empty($request->input('filter_created_at_start'))? HP::convertDate($request->input('filter_created_at_start'),true):null;
        $filter_created_at_end     = !empty($request->input('filter_created_at_end'))? HP::convertDate($request->input('filter_created_at_end'),true):null;

        $query = LawNotify::query() 
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                             ->orWhere(DB::raw("REPLACE(content,' ','')"), 'LIKE', "%".$search_full."%");
                                                break;
                                            case "2":
                                                return $query->whereHas('user_created', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            default:
                                                return $query->where( function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                      ->orWhere(DB::raw("REPLACE(content,' ','')"), 'LIKE', "%".$search_full."%")
                                                                      ->OrwhereHas('user_created', function($query) use ($search_full){
                                                                          $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                      });
                                                            });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_state, function ($query, $filter_state){
                                        if( $filter_state == 1){
                                            return $query->whereHas('notify_user_by_user', function($query){
                                                        $query->where('read_type','1');
                                                    });
                                        }else if($filter_state == 2){
                                            return $query->whereDoesntHave('notify_user_by_user', function($query){
                                                                $query->where('read_type','1')
                                                                ->where('user_register', Auth::user()->getKey() );
                                                            });
                                        }else if($filter_state == 3){
                                            return $query->whereHas('notify_user_by_user', function($query){
                                                            $query->where('marked','1');
                                                        });
                                        }
                                    })
                                    ->when($filter_category_id, function ($query, $filter_category_id){
                                        if( is_numeric( $filter_category_id) ){
                                            return $query->where('law_system_category_id', $filter_category_id);
                                        }
                                    })
                                    ->when($filter_created_at_start, function ($query, $filter_created_at_start){
                                        return $query->whereDate('created_at', '>=', $filter_created_at_start);
                                    })
                                    ->when($filter_created_at_end, function ($query, $filter_created_at_end){
                                        return $query->whereDate('created_at', '<=', $filter_created_at_end);
                                    })
                                    ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ดูได้เฉพาะรายการที่ได้รับ
                                        return $query->where(function($query){
                                                            $query->Where('email', 'LIKE', "%".(Auth::user()->reg_email)."%" );
                                                        });
                                    })
                                    ->where(function($query){
                                        $query->whereHas('category', function($query){
                                                    $query->where('state_notify',1);
                                                });
                                    });
        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('star', function ($item) {
                                return $item->MarkedIcon;
                            })
                            ->addColumn('system', function ($item) {
                                return !empty($item->category->name)?$item->category->name:null;
                            })
                            ->addColumn('title', function ($item) {

                                $notify_user = $item->notify_user_by_user;
                                $read_type = 2;
                                if( !is_null($notify_user) && $notify_user->read_type == 1 ){
                                    $read_type = 1;
                                }
                                $html = '<input type="hidden" name="read_type[]" class="item_read_type" value="'. $read_type .'">';
                                return (!empty($item->title)?'<span class="btn_action_link">'.($item->title).'</span>':null).$html;
                            })
                            ->addColumn('content', function ($item) {
                                $category = $item->category;
                                $html = '<span class="fa fa-circle bg-color m-r-5" data-color="'.( !empty($category->color)?$category->color:'text-muted' ).'"></span>';
                                return  $html.(!empty($item->content)?$item->content:'-');
                            })
                            ->addColumn('created_by', function ($item) {
                                return !empty($item->CreatedName)?$item->CreatedName:'-';
                            })
                            ->addColumn('created_at', function ($item) {
                                $created_at = '';
                                $created_at .= !empty($item->created_at)?HP::DateThai($item->created_at):null;
                                $created_at .= !empty($item->created_at)?' | '.Carbon::parse($item->created_at)->format('H.i').' น.':null;

                                return $created_at;
                            })
                            ->addColumn('action', function ($item) {
                                return '<a href="'.url('law/notifys/'.$item->id).'" class="btn btn-label-info btn-sm btn-circle action_link"><i class="fa fa-eye fa-lg"></i></a>';
                            })
                            ->orderColumns(['title', 'law_system_category_id','created_at'], '-:column $1')

                            ->rawColumns(['checkbox', 'action', 'content','star','title','system' ])
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
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
            ];
            return view('laws.notification.notifys.index',compact('breadcrumbs'));

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
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
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
    public function show(Request $request, $id)
    {
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
            ];

            $notifys = LawNotify::findOrFail($id);

            $resulte = LawNotifyUser::updateOrCreate( 
                                                        ['law_notify_id' => $notifys->id, 'user_register' => auth()->user()->getKey() ], 
                                                        [ 'user_register' => auth()->user()->getKey(), 'name' => auth()->user()->FullName, 'read_type' => 1 ]
                                                    );

            $query = $this->query( $request )->select('id')->get();

            return view('laws.notification.notifys.show',compact('breadcrumbs','notifys', 'query'));

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
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
            ];
        
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
        if(auth()->user()->can('view-'.$this->permission)) {


        }
        abort(403);
    }

    public function update_marked(Request $request){
        if(auth()->user()->can('edit-'.$this->permission) || auth()->user()->can('view-'.$this->permission) ) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawNotify;
            $notify =  LawNotify::whereIn($db->getKeyName(), $id_publish)->get();

            foreach( $notify AS $notifys ){
                $resulte = LawNotifyUser::updateOrCreate( 
                                                            ['law_notify_id' => $notifys->id, 'user_register' => auth()->user()->getKey() ], 
                                                            [ 'user_register' => auth()->user()->getKey(), 'name' => auth()->user()->FullName, 'marked' => $requestData['state'] ] 
                                                        );
            }

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    
    public function update_state(Request $request){

        
        if(auth()->user()->can('edit-'.$this->permission) || auth()->user()->can('view-'.$this->permission) ) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawNotify;
            $notify =  LawNotify::whereIn($db->getKeyName(), $id_publish)->get();

            foreach( $notify AS $notifys ){

                if(  $requestData['state'] == 1){
                    $resulte = LawNotifyUser::updateOrCreate( 
                                                                ['law_notify_id' => $notifys->id, 'user_register' => auth()->user()->getKey() ], 
                                                                [ 'user_register' => auth()->user()->getKey(), 'name' => auth()->user()->FullName, 'read_type' => 1 ]
                                                            );
                }else if(  $requestData['state'] == 2){
                    $resulte = LawNotifyUser::updateOrCreate( 
                                                                ['law_notify_id' => $notifys->id, 'user_register' => auth()->user()->getKey() ], 
                                                                [ 'user_register' => auth()->user()->getKey(), 'name' => auth()->user()->FullName, 'read_type' => 2 ]
                                                            );
                }else if(  $requestData['state'] == 3){
                    $resulte = LawNotifyUser::updateOrCreate( 
                                                                ['law_notify_id' => $notifys->id, 'user_register' => auth()->user()->getKey() ], 
                                                                [ 'user_register' => auth()->user()->getKey(), 'name' => auth()->user()->FullName, 'marked' => 1 ]
                                                            );
                }

            }

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function loadJsonMenu()
    {
        $level = 0;
        $menu  = [];

        //ระบบบันทึกคดีผลิตภัณฑ์อุตสาหกรรม
        if (File::exists(base_path('resources/laravel-admin/new-menu-law.json'))) {
            $laravelMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/new-menu-law.json')));
            $menu[] = $laravelMenuLaw->menus[0];
        }

        $level = 0;
        $list =   $this->LoopItem($menu , $level);
        return $list;
        // return response()->json($list);

    }

    public function LoopItem($menulist , $level)
    {       
        $txt = [];
        $level++;
        $i = 0;

        $menu = [];
        if( isset($menulist[0]->items ) ){
            $menu = $menulist[0]->items;
        }else{
            $menu = $menulist;
        }
        
        foreach ( $menu AS $item ){
            
            $input = '<input type="hidden" name="ordering[]" value="'.$i++.'" >';

            $data       = new stdClass;
            $data->text = ( !empty($item->display)?$item->display:(  !empty($item->title)?$item->title:null ));
            $data->title = !empty($item->title)?$item->title:null;

            if( isset($item->sub_menus) ){
                $result      = $this->LoopItem($item->sub_menus, $level);
                $data->tags  = [ count($result) ];
                $data->nodes =  $result;
            }
            $txt[] = $data;

        }
        return $txt;

    }

    public function menu()
    {
        
        if(auth()->user()->can('view-'.$this->permission)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/notifys",  "name" => 'แจ้งเตือน' ],
            ];

            $menu =  $this->loadJsonMenu();

            return view('laws.notification.notifys.menu',compact('breadcrumbs','menu'));

        }
        abort(403);
    }
}
