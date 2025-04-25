<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Config\ConfigsFaqs;

class ConfigsFaqController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/configs_faqs/';
        $this->attach_path_crop = 'tis_attach/configs_faqs_crop/';
    }

    public function data_list(Request $request)
    {

        $model = str_slug('configs-faq','-');

        $filter_search = $request->input('filter_system');
        $filter_status = $request->input('filter_status');
        
        $query =  ConfigsFaqs::query()->when($filter_status, function ($query, $filter_status){
                                                if( $filter_status == 1){
                                                    return $query->where('state', $filter_status);
                                                }else{
                                                    return $query->where('state', '<>', 1)->orWhereNull('state');
                                                }
                                            })
                                            ->when($filter_search, function ($query, $filter_search){
                                                $search_full = str_replace(' ', '', $filter_search );
                                                $query->where('title',  'LIKE', "%$search_full%");
                                            });
                                          

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('created_at', function ($item) {
                                return (!empty($item->CreatedName)?$item->CreatedName:null).(!empty($item->created_at)?'<br>'.HP::DateThaiFull($item->created_at):null);
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'config/faqs','Config\\ConfigsFaqController@destroy', 'configs-faq');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_at','title', 'attachment'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-faqs.index');
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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-faqs.create');
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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $result = ConfigsFaqs::create($requestData);

            if( isset($requestData['repeater-attach']) ){

                $repeater = $requestData['repeater-attach'];

                $tax_number = !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000';

                $folder_app = 'ID-'.($result->id).'/';

                foreach(  $repeater AS $iFile ){
                    if( isset($iFile['attach_file']) && !empty($iFile['attach_file']) ){
                        HP::singleFileUpload(
                            $iFile['attach_file'],
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ConfigsFaqs)->getTable() ),
                            $result->id,
                            'file_faqs_configs',
                            null
                        );
                    }
                }

            }

            return redirect('config/faqs/create')->with('success_message', 'เพิ่มเรียบร้อยแล้ว!');
            
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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            $result =  ConfigsFaqs::findOrFail($id);

            return view('config/configs-faqs.show',compact('result'));
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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {
            $result =  ConfigsFaqs::findOrFail($id);

            return view('config/configs-faqs.edit',compact('result'));
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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $result =  ConfigsFaqs::findOrFail($id);
            $result->update($requestData);

            if( isset($requestData['repeater-attach']) ){

                $repeater = $requestData['repeater-attach'];

                $tax_number = !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID) : '0000000000000';

                $folder_app = 'ID-'.($result->id).'/';

                foreach(  $repeater AS $iFile ){
                    if( isset($iFile['attach_file']) && !empty($iFile['attach_file']) ){
                        HP::singleFileUpload(
                            $iFile['attach_file'],
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new ConfigsFaqs)->getTable() ),
                            $result->id,
                            'file_faqs_configs',
                            null
                        );
                    }
                }

            }

            return redirect('config/faqs')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');


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
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('view-'.$model)) {

            ConfigsFaqs::destroy($id);
            return redirect('config/faqs')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result =  ConfigsFaqs::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }
    
    }
    
    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request){
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');
    
        $result =  ConfigsFaqs::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
                echo "not success";
        }
    }
    
    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request){
        $model = str_slug('configs-faq','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db      = new ConfigsFaqs;
            $resulte = ConfigsFaqs::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);
    }

}
