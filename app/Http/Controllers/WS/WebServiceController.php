<?php

namespace App\Http\Controllers\WS;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\WS\Client as web_service;
use App\Models\Log\LogsSendMail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use HP;
use HP_API;
use stdClass;
use DB;
use App\Mail\WS\WebService;
use Illuminate\Support\Facades\Storage;

class WebServiceController extends Controller
{


    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/web_service/';
        $this->attach_path_crop = 'tis_attach/web_service_crop/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('web_service','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new web_service;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $web_service = $Query->sortable()->with('user_created')
                                             ->with('user_updated')
                                             ->paginate($filter['perPage']);

            return view('ws.web_service.index', compact('web_service', 'filter'));
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
        $model = str_slug('web_service','-');
        if(auth()->user()->can('add-'.$model)) {

            $ListAPI = [''];

            return view('ws.web_service.create', compact('ListAPI'));

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
        $model = str_slug('web_service','-');
        if(auth()->user()->can('add-'.$model)) {

            $table = (new web_service)->getTable();

            $this->validate($request, [
        			'title' => 'required',
                    'app_name' => "required|unique:$table"
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['app_secret'] = md5(uniqid());
            $requestData['ListAPI'] = json_encode($requestData['api']);

            if ($request->hasFile('file')) {

                $single_file = $request->file('file');

                $storagePath = Storage::put($this->attach_path, $single_file);
                $storageName = basename($storagePath); // Extract the filename

                $single_attach = [
                                    'file_name' => $storageName,
                                    'file_client_name' => $single_file->getClientOriginalName()
                                ];

                $requestData['file'] = json_encode($single_attach);

            }

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
        $model = str_slug('web_service','-');
        if(auth()->user()->can('view-'.$model)) {
            $web_service = web_service::findOrFail($id);
            return view('ws.web_service.show', compact('web_service'));
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
        $model = str_slug('web_service','-');
        if(auth()->user()->can('edit-'.$model)) {

            $web_service = web_service::findOrFail($id);

            $ListAPI = json_decode($web_service->ListAPI);
            $ListAPI = count($ListAPI)==0?['']:$ListAPI;

            return view('ws.web_service.edit', compact('web_service', 'ListAPI'));

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
        $model = str_slug('web_service','-');
        if(auth()->user()->can('edit-'.$model)) {

            $table = (new web_service)->getTable();

            $web_service = web_service::findOrFail($id);
            $this->validate($request, [
        			'title' => 'required',
                    'app_name' => ['required', Rule::unique($table)->ignore($id)],
        		]);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['ListAPI'] = json_encode($requestData['api']);

            if(array_key_exists('gen_new_secret', $requestData)){//gen app_secret ใหม่
                $requestData['app_secret'] = md5(uniqid());
            }

            if ($request->hasFile('file')) {

                $single_file = $request->file('file');

                $storagePath = Storage::put($this->attach_path, $single_file);

                $storageName = basename($storagePath); // Extract the filename

                $single_attach = [
                                    'file_name' => $storageName,
                                    'file_client_name' => $single_file->getClientOriginalName()
                                ];

                $requestData['file'] = json_encode($single_attach);

            }


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
        $model = str_slug('web_service','-');
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

        $model = str_slug('web_service','-');
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

    public function send_mail(Request $request)
    {
        $model = str_slug('web_service','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $arr_publish = $requestData['id_publish'];

            $Query =  web_service::whereIn( 'id' , $arr_publish)->get();

            $list =  HP_API::APILists();

            $topic =  'แจ้งรายละเอียดผู้ขอใช้บริการเว็บเซอร์วิส';
            $subject = 'แจ้งรายละเอียดผู้ขอใช้บริการเว็บเซอร์วิส';
            $learn = 'ผู้ขอใช้บริการเว็บเซอร์วิส';

            $list_error = [];

            foreach( $Query AS $item ){

                try {

                    $ListAPI = json_decode($item->ListAPI);
                    $ListAPI = count($ListAPI)==0?['']:$ListAPI;

                    $email = $item->email;

                    // $email = 'nattchai_tc000@hotmail.com';

                    $content = '';
                    $content .= '<table width="100%">';
                    $content .= '<tr>';
                    $content .= '<td colspan="3">';

                    $content .= '<p>ตามที่ท่านได้ยื่นคำขอใช้บริการเว็บเซอร์วิส API มานั้น ทาง สมอ. ได้ดำเนินการเพิ่มผู้ใช้งานให้เรียบร้อยแล้ว ท่านสามารถใช้ข้อมูลเพื่อเชื่อมโยง API ได้ดังนี้ </p>';
                    $content .= '<p>App-Name : '.($item->app_name).' </p>';
                    $content .= '<p>App-Secret : '.($item->app_secret).'</p>';
                    $content .= '<p>รายชื่อบริการที่เปิดให้ใช้งาน</p>';
                    $content .= '</td>';
                    $content .= '</tr>';
                    $content .= '<tr>';
                    $content .= '    <td width="5%">#</td>';
                    $content .= '    <td width="80%">รายชื่อบริการ</td>';
                    $content .= '    <td width="15%">คู่มือ</td>';
                    $content .= '</tr>';

                    $i=0;
                    foreach ($ListAPI as $key => $api){

                        if( array_key_exists( $api , $list ) ){

                            $data_api_name =  $list[  $api ]['detail'];

                            $data_api_manual =  $list[  $api ]['manual'];

                            $link = url('downloads/api_manual/'.$data_api_manual);

                            $url = '<a href="'.($link).'" target="_blank" class="font-22 text-danger">คู่มือ</a>';

                            // if( $key == 0){
                            //
                            //     $content .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; API : '.( $data_api_name ).' &nbsp;&nbsp;  '.$url.'</p>';
                            //
                            // }else{
                            //     $content .= '<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  : '.( $data_api_name ).' &nbsp;&nbsp;  '.$url.'</p>';
                            //
                            // }

                            $content .= '<tr>';
                            $content .= '    <td>'.(++$i).'</td>';
                            $content .= '    <td>'.$data_api_name.'</td>';
                            $content .= '    <td>'.$url.'</td>';
                            $content .= '</tr>';

                        }

                    }

                    $content .= '</table>';

                    $log['title'] = $topic;
                    $log['subject'] = $subject;
                    $log['learn'] = $learn;
                    $log['content'] = $content;
                    $log['total'] =  null;
                    $log['created_by'] = auth()->user()->getKey();
                    $log['tb_ref'] = (new web_service)->getTable();
                    $log['id_ref'] = $item->id;
                    $log['email'] = $email;
                    $log['system_code'] =  'WS';
                    $log['site_code'] =  'center';
                    $logmail = LogsSendMail::create($log);

                    Mail::to($email)->send(new WebService([
                        'topic' => $topic,
                        'subject' => $subject,
                        'learn' => $learn,
                        'content' => $content,
                        'email'=>  $email
                    ]));


                } catch (\Exception $e) {

                    $list_error[] = ($item->email).' : '.($e->getMessage());

                }
            }


            if( count($list_error) == 0 ){
                $result = 'success';
            }else{
                $result = 'error';
            }

            $data = new stdClass;
            $data->msg = $result;
            $data->error = $list_error;

            return response()->json($data);

        }

        abort(403);
    }

}
