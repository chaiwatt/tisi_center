<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WS\Client as web_service;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Basic\Config;

use HP_API;
use stdClass;
use DB;
class ApiServiceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request){


        $filter_search      = $request->input('filter_search');
    
        
        $list = HP_API::APILists();

        $collection = [];

        $configs = Config::select('variable', 'data' )->pluck('data', 'variable')->toArray();

        foreach($list as $key => $item){

            $search_full = str_replace(' ', '', $filter_search);

            $detail_full = str_replace(' ', '', $item['detail'] );

            if(  !empty($filter_search) &&( mb_strpos( $detail_full , $search_full ) !== false  ) ){
                $url = !empty($item['domain']) && array_key_exists( $item['domain'] , $configs )?$configs[ $item['domain'] ]:url('/').'/';
                $data = new stdClass;
                $data->detail = $item['detail'];
                $data->url = $item['url'];
                $data->manual = $item['manual'];
                $data->domain_url =  $url;
                $data->domain = $item['domain'];
                $collection[] = $data;
            }else if( empty($filter_search) ){
                $url = !empty($item['domain']) && array_key_exists( $item['domain'] , $configs )?$configs[ $item['domain'] ]:url('/').'/';
                $data = new stdClass;
                $data->detail = $item['detail'];
                $data->url = $item['url'];
                $data->manual = $item['manual'];
                $data->domain_url =  $url;
                $data->domain = $item['domain'];
                $collection[] = $data;
            }


        }
        $query = collect($collection);

        return Datatables::of($query)
                        ->addIndexColumn()
                        ->addColumn('detail', function ($item) {
                            return $item->detail;
                        })
                        ->addColumn('url', function ($item) {
                            return $item->domain_url.$item->url;
                        })
                        ->addColumn('manual', function ($item) {

                            if( !empty($item->manual) && $item->manual != '' ){
                                $url = url('downloads/api_manual').'/'.$item->manual;
                                $btn = '<a href="'.($url).'"  target="_blank" ><i class="fa fa-file-pdf-o" style="font-size:25px; color:red" aria-hidden="true"></i></a>';
                            }else{

                                $btn = '<i class="fa fa-file-pdf-o" style="font-size:25px; color:#cccccc" aria-hidden="true"></i>';
                            }

                            return $btn ;
                        })
                        ->rawColumns(['manual'])
                        ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('api_service','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];

            $api_service = HP_API::APILists();

            return view('ws.api_service.index', compact('api_service', 'filter'));
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
        $model = str_slug('api_service','-');
        if(auth()->user()->can('add-'.$model)) {

            $ListAPI = [''];

            return view('ws.api_service.create', compact('ListAPI'));

        }
        abort(403);

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
        $model = str_slug('api_service','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['app_name'] = uniqid();
            $requestData['app_secret'] = md5(uniqid());
            $requestData['ListAPI'] = json_encode($requestData['api']);

            web_service::create($requestData);
            return redirect('ws/web_service')->with('flash_message', 'เพิ่ม web_service เรียบร้อยแล้ว');
        }
        abort(403);
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
        $model = str_slug('api_service','-');
        if(auth()->user()->can('view-'.$model)) {
            $web_service = web_service::findOrFail($id);
            return view('ws.api_service.show', compact('api_service'));
        }
        abort(403);
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
        $model = str_slug('api_service','-');
        if(auth()->user()->can('edit-'.$model)) {

            $web_service = web_service::findOrFail($id);

            $ListAPI = json_decode($web_service->ListAPI);
            $ListAPI = count($ListAPI)==0?['']:$ListAPI;

            return view('ws.api_service.edit', compact('api_service', 'ListAPI'));

        }
        abort(403);
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
        $model = str_slug('api_service','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request, [
        			'title' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['ListAPI'] = json_encode($requestData['api']);

            if(array_key_exists('gen_new_secret', $requestData)){//gen app_secret ใหม่
                $requestData['app_secret'] = md5(uniqid());
            }

            $web_service = web_service::findOrFail($id);
            $web_service->update($requestData);

            return redirect('ws/web_service')->with('flash_message', 'แก้ไข web_service เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('api_service','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new web_service;
            web_service::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            web_service::destroy($id);
          }

          return redirect('ws/web_service')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('api_service','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new web_service;
          web_service::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('ws/web_service')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
