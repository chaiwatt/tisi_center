<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Config\ConfigsEvidenceSystem;
use App\Models\Config\ConfigsEvidenceGroup;
use App\Models\Config\ConfigsEvidence;

class ConfigsEvidenceGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function data_list(Request $request)
    {

        $model = str_slug('configs-evidence-groups','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        
        $query = ConfigsEvidenceGroup::query()->when($filter_search, function ($query, $filter_search){

                                                        return $query->where(function ($query2) use($filter_search) {
                                                            $search_full = str_replace(' ', '', $filter_search );
                                                            $ids = ConfigsEvidence::where('title',  'LIKE', "%$search_full%")->select('evidence_group_id');
                                                            $query2->where('title',  'LIKE', "%$search_full%")->OrwhereIn('id', $ids);
                                                        });
                                         
                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        if( $filter_status == 1){
                                                            return $query->where('state', $filter_status);
                                                        }else{
                                                            return $query->where('state', '<>', 1)->orWhereNull('state');
                                                        }
                                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return (!empty($item->title)?$item->title:null).(!empty($item->url)?'<br>'.$item->url:null);
                            })
                            ->addColumn('system', function ($item) {
                                return !empty($item->EvidenceSystemName)?$item->EvidenceSystemName:null;
                            })
                            ->addColumn('created_at', function ($item) {
                                return (!empty($item->CreatedName)?$item->CreatedName:null).(!empty($item->created_at)?'<br>'.HP::DateThaiFull($item->created_at):null);
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('attachment', function ($item) {
                                return !empty($item->AttachmentName)?$item->AttachmentName:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'config/evidence/group','Config\\ConfigsEvidenceGroupController@destroy', 'configs-evidence-groups');
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-evidence-groups.index');
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('config/configs-evidence-groups.create');
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $result = ConfigsEvidenceGroup::create($requestData);

            $condition = isset($requestData['condition'])?$requestData['condition']:null;
            if( $condition == "2" ){

                if(  isset($requestData['section_box']) ){

                    $section_box = $requestData['section_box'];

                    foreach( $section_box AS $box ){

                        if(  isset($requestData['repeater-group-'.$box ]) ){

                            $repeater_file = $requestData['repeater-group-'.$box];
            
                            foreach( $repeater_file as $file ){
            
                                $evdence = ConfigsEvidence::where('id',$file["file_id"])->first();
                                if(is_null($evdence)){
                                    $evdence = new ConfigsEvidence;
                                }
                                $evdence->evidence_group_id = $result->id;
                                $evdence->title = !empty($file['file_title'])?$file['file_title']:null;
                                $evdence->size = !empty($file['size'])?$file['size']:null;
                                $evdence->bytes = !empty($file['size'])?$this->ConvertBytes($file['size']):null;
                                $evdence->ordering = !empty($file['ordering'])?$file['ordering']:null;
                                $evdence->file_properties = !empty($file['file_properties'])?json_encode($file['file_properties']):null;
                                $evdence->required = isset($file['required'])?1:null;
                                $evdence->state = isset($file['state'])?1:null;
                                $evdence->created_by = auth()->user()->getKey();
                                $evdence->section = $box;
                                $evdence->save();
            
                            }
                        }

                    }

                }

            }else{

                if(  isset($requestData['repeater-file']) ){

                    $repeater_file = $requestData['repeater-file'];
    
                    foreach( $repeater_file as $file ){
    
                        $evdence = ConfigsEvidence::where('id',$file["file_id"])->first();
                        if(is_null($evdence)){
                            $evdence = new ConfigsEvidence;
                        }
                        $evdence->evidence_group_id = $result->id;
                        $evdence->title = !empty($file['file_title'])?$file['file_title']:null;
                        $evdence->size = !empty($file['size'])?$file['size']:null;
                        $evdence->bytes = !empty($file['size'])?$this->ConvertBytes($file['size']):null;
                        $evdence->ordering = !empty($file['ordering'])?$file['ordering']:null;
                        $evdence->file_properties = !empty($file['file_properties'])?json_encode($file['file_properties']):null;
                        $evdence->required = isset($file['required'])?1:null;
                        $evdence->state = isset($file['state'])?1:null;
                        $evdence->created_by = auth()->user()->getKey();
                        $evdence->save();
    
                    }
                }

            }

            return redirect('config/evidence/group')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('view-'.$model)) {

            $result = ConfigsEvidenceGroup::findOrFail($id);

            return view('config/configs-evidence-groups.show',compact('result'));
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('edit-'.$model)) {

            $result = ConfigsEvidenceGroup::findOrFail($id);

            return view('config/configs-evidence-groups.edit',compact('result'));
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $result = ConfigsEvidenceGroup::findOrFail($id);
            $result->update($requestData);
 
            $condition = isset($requestData['condition'])?$requestData['condition']:null;
            if( $condition == "2" ){

                if(  isset($requestData['section_box']) ){

                    $section_box = $requestData['section_box'];

                    $section_ids = [];
                    foreach( $section_box AS $box ){
                        $section_ids[$box] = $box;
                    }

                    $section_id = array_diff($section_ids, [null]);

                    ConfigsEvidence::where('evidence_group_id', $result->id)
                                    ->whereNotNull('section')
                                    ->when($section_id, function ($query, $section_id){
                                        return $query->whereNotIn('section', $section_id);
                                    })
                                    ->delete();

                    foreach( $section_box AS $box ){

                        if(  isset($requestData['repeater-group-'.$box ]) ){

                            $repeater_file = $requestData['repeater-group-'.$box];

                            $list_ids = [];
                            foreach($repeater_file as $item){
                                $list_ids[] = $item["file_id"];
                            }
                            $list_id = array_diff($list_ids, [null]);
                
                            ConfigsEvidence::where('evidence_group_id', $result->id)
                                            ->where('section', $box )
                                            ->when($list_id, function ($query, $list_id){
                                                return $query->whereNotIn('id', $list_id);
                                            })
                                            ->delete();
            
            
                            foreach( $repeater_file as $file ){
            
                                $evdence = ConfigsEvidence::where('id',$file["file_id"])->first();
                                if(is_null($evdence)){
                                    $evdence = new ConfigsEvidence;
                                }
                                $evdence->evidence_group_id = $result->id;
                                $evdence->title = !empty($file['file_title'])?$file['file_title']:null;
                                $evdence->size = !empty($file['size'])?$file['size']:null;
                                $evdence->bytes = !empty($file['size'])?$this->ConvertBytes($file['size']):null;
                                $evdence->ordering = !empty($file['ordering'])?$file['ordering']:null;
                                $evdence->file_properties = !empty($file['file_properties'])?json_encode($file['file_properties']):null;
                                $evdence->required = isset($file['required'])?1:null;
                                $evdence->state = isset($file['state'])?1:null;
                                $evdence->created_by = auth()->user()->getKey();
                                $evdence->section = $box;
                                $evdence->save();
            
                            }
                        }

                    }

                }

            }else{

                if(  isset($requestData['repeater-file']) ){

                    $repeater_file = $requestData['repeater-file'];
    
                    $list_ids = [];
                    foreach($repeater_file as $item){
                        $list_ids[] = $item["file_id"];
                    }
                    $list_id = array_diff($list_ids, [null]);
        
                    ConfigsEvidence::where('evidence_group_id', $result->id)
                                            ->when($list_id, function ($query, $list_id){
                                                return $query->whereNotIn('id', $list_id);
                                            })->delete();
    
    
    
                    foreach( $repeater_file as $file ){
    
                        $evdence = ConfigsEvidence::where('id',$file["file_id"])->first();
                        if(is_null($evdence)){
                            $evdence = new ConfigsEvidence;
                        }
                        $evdence->evidence_group_id = $result->id;
                        $evdence->title = !empty($file['file_title'])?$file['file_title']:null;
                        $evdence->size = !empty($file['size'])?$file['size']:null;
                        $evdence->bytes = !empty($file['size'])?$this->ConvertBytes($file['size']):null;
                        $evdence->ordering = !empty($file['ordering'])?$file['ordering']:null;
                        $evdence->file_properties = !empty($file['file_properties'])?json_encode($file['file_properties']):null;
                        $evdence->required = isset($file['required'])?1:null;
                        $evdence->state = isset($file['state'])?1:null;
                        $evdence->created_by = auth()->user()->getKey();
                        $evdence->save();
    
                    }
                }

            }

            return redirect('config/evidence/group')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
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
        $model = str_slug('configs-evidence-groups','-');
        if(auth()->user()->can('delete-'.$model)) {
            ConfigsEvidenceGroup::destroy($id);
            return redirect('config/evidence/group')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result = ConfigsEvidenceGroup::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }

    }

    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');

        $result = ConfigsEvidenceGroup::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request)
    {
        $id_status = $request->input('id_status');
        $state = $request->input('state');

        $result = ConfigsEvidenceGroup::where('id', $id_status)  ->update(['state' => $state]);
        
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    public function ConvertBytes($invalue)
    {
        if( !empty( $invalue ) && is_numeric($invalue) && $invalue > 0 ){
            $bytevalue = $invalue * 1024 * 1024;
            // $result = round( $bytevalue / 1024 );
            return $bytevalue;
        }else{
            return null;
        }

    }
}
