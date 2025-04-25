<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Config\ConfigsFormatCode;
use App\Models\Config\ConfigsFormatCodeSub;
use App\Models\Config\ConfigsFormatCodeLog;
use Mpdf\Tag\Tr;

class ConfigsFormatCodeController extends Controller
{

    public function data_list(Request $request)
    {

        $model = str_slug('configs-format-code','-');

        $arr = HP::ApplicationSytemConfig();
        $list_arr = [];
        if( array_key_exists( 'ระบบมาตรตรา 5',  $arr ) ){
            $list_arr = array_merge($list_arr, $arr['ระบบมาตรตรา 5'] );
        }

        if( array_key_exists( 'ระบบงานคดี',  $arr ) ){
            $list_arr = array_merge($list_arr, $arr['ระบบงานคดี'] );
        }

        $filter_system = $request->input('filter_system');
        $filter_status = $request->input('filter_status');
        
        $query = ConfigsFormatCode::query()->when($filter_status, function ($query, $filter_status){
                                                if( $filter_status == 1){
                                                    return $query->where('state', $filter_status);
                                                }else{
                                                    return $query->where('state', '<>', 1)->orWhereNull('state');
                                                }
                                            })
                                            ->when($filter_system, function ($query, $filter_system){
                                                return $query->where('system', $filter_system);
                                            });
                                          

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('system', function ($item) use($list_arr) {
                                return array_key_exists( $item->system,  $list_arr )?$list_arr [ $item->system ]:'-';
                            })
                            ->addColumn('created_at', function ($item) {
                                return (!empty($item->CreatedName)?$item->CreatedName:null).(!empty($item->created_at)?'<br>'.HP::DateThaiFull($item->created_at):null);
                            })
                            ->addColumn('format', function ($item) {
                                $log = $item->format_codes_log_last;
                                return !empty($log->data)?$this->GenFormat($log->data):null;
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'config/format-code','Config\\ConfigsFormatCodeController@destroy', 'configs-format-code');
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-format-code.index');
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-format-code.create');
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            $systems = $requestData['system'];

            $check = ConfigsFormatCode::where( 'system', $systems)->where('state', 1)->first();

            if( is_null($check) ){
                $result = ConfigsFormatCode::create($requestData);

                if( isset($requestData['form-fotmat']) ){
    
                    ConfigsFormatCodeSub::where('format_id', $result->id)->delete();
    
                    $form_fotmat = $requestData['form-fotmat'];
    
                    foreach( $form_fotmat AS $item ){
                        $sub = new ConfigsFormatCodeSub;
                        $sub->format_id = $result->id;
                        $sub->format = $item['format'];
                        $sub->data = !empty($item['data'])?$item['data']:null;
                        $sub->sub_data = !empty($item['sub_data'])?$item['sub_data']:null;
                        $sub->save();
                    }
    
                }else{
                    ConfigsFormatCodeSub::where('format_id', $result->id)->delete();
                }
    
                $this->SaveLog( $result );
                return redirect('config/format-code')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');

            }else{
                return redirect('config/format-code/create')->with('error_message', 'เพิ่มเรียบร้อยแล้ว!');
            }
            
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            $result = ConfigsFormatCode::findOrFail($id);

            return view('config/configs-format-code.show',compact('result'));
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {
            $result = ConfigsFormatCode::findOrFail($id);

            return view('config/configs-format-code.edit',compact('result'));
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $result = ConfigsFormatCode::findOrFail($id);
            $result->update($requestData);


            if( isset($requestData['form-fotmat']) ){

                ConfigsFormatCodeSub::where('format_id', $result->id)->delete();

                $form_fotmat = $requestData['form-fotmat'];

                foreach( $form_fotmat AS $item ){

                    // $sub = ConfigsFormatCodeSub::where('format', $item['format'] )->where('format_id', $result->id)->first();
                    // if(is_null($sub)){
                    //     $sub = new ConfigsFormatCodeSub;
                    // }
                    $sub = new ConfigsFormatCodeSub;
                    $sub->format_id = $result->id;
                    $sub->format = $item['format'];
                    $sub->data = !empty($item['data'])?$item['data']:null;
                    $sub->sub_data = !empty($item['sub_data'])?$item['sub_data']:null;
                    $sub->save();
                }

            }else{
                ConfigsFormatCodeSub::where('format_id', $result->id)->delete();
            }

            $this->SaveLog( $result );

            return redirect('config/format-code')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');


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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {


        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result = ConfigsFormatCode::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }
    
    }
    
    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request){
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');
    
        $result = ConfigsFormatCode::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
                echo "not success";
        }
    }
    
    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request){
        $id_status = $request->input('id_status');
        $state = $request->input('state');
    
        $result = ConfigsFormatCode::where('id', $id_status)  ->update(['state' => $state]);
            
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }
    
    }

    public function SaveLog($result)
    {
        $sub_data = ConfigsFormatCodeSub::where('format_id', $result->id)->select( 'format','data','sub_data' )->get();

        if( count( $sub_data ) > 0 ){

            $json =  (count( $sub_data ) > 0 )?json_encode( $sub_data, JSON_UNESCAPED_UNICODE ):null;

            $log_old = ConfigsFormatCodeLog::where('format_id', $result->id )->orderBy('verstion', 'desc')->first();

            if(  is_null($log_old)  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $result->id;
                $log->data = $json;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $result->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $result->system;
                $log->save();
    
            }else if( strpos($log_old->data, $json ) === false  ){

                $log = new ConfigsFormatCodeLog;
                $log->format_id = $result->id;
                $log->data = $json;
                $log->verstion = count( ConfigsFormatCodeLog::where('format_id', $result->id )->get() ) + 1;
                $log->created_by = auth()->user()->getKey();
                $log->start_date = date('Y-m-d H:i:s');
                $log->state = 1;
                $log->system = $result->system;
                $log->save();

                // Set Log เดิม วันที่สิ้นสุดใช่งาน
                $log_old->end_date = date('Y-m-d H:i:s');
                $log_old->state = 0;
                $log_old->save();

            }

        }

    }


    public function data_histrory(Request $request)
    {
        $format_id = $request->input('format_id');
        // dd($format_id);
        $query = ConfigsFormatCodeLog::query()->where('format_id', $format_id);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('created_at', function ($item) {
                                $date = !empty($item->start_date)?HP::DateThaiFull($item->start_date):null;
                                $date .= (!empty($item->end_date)?' - '.HP::DateThaiFull($item->end_date):null);
                                return $date;
                            })
                            ->addColumn('format', function ($item) {
                                return $this->GenFormat($item->data);
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->rawColumns(['status', 'created_at'])

                            ->make(true);
    }

    public function GenFormat($sub_data)
    {

        $today = date('Y-m-d');
        $dates = explode('-', $today);

        $sub = json_decode( $sub_data, true);

        $strNextSeq = '';
        //วนรูปแบบ
        foreach( $sub AS $key => $item ){
            $item = (object)$item;
            $format = $item->format;

            $dataSet = null;

            if( $format == 'character' ){ //อักษรนำ
                $dataSet .= !empty($item->data)?$item->data:null;
            }else if( $format == 'separator' ){ //อักษรคั่น
                $dataSet .= !empty($item->data)?$item->data:null;
            }else if( $format == 'month' ){ //เดือน
                $dataSet .= $dates[1];
            }else if( $format == 'year-be' ){ //ปี พ.ศ.
                if( $item->data == '4'){
                    $dataSet = $dates[0] + 543;
                }else{
                    $dataSet = (substr( ($dates[0] + 543) , 2) );
                }
            }else if( $format == 'year-bf' ){ //ปี พ.ศ.ตามปีงบประมาณ
                $yaer  = ( $dates[0] >= 10 )?($dates[0] + 544):($dates[0] + 543);
                if( $item->data == '4'){
                    $dataSet = $yaer;
                }else{
                    $dataSet = (substr( $yaer , 2) );
                }
            }else if( $format == 'year-ac' ){ //ปี ค.ศ.
                if( $item->data == '4'){
                    $dataSet = $dates[0];
                }else{
                    $dataSet = (substr( ($dates[0]) , 2) );
                }
            }else if( $format == 'no' ){ //เลขรัน
                $numbers = !empty($item->data) ?($item->data):0;
                $number_set = !empty($item->data) && ($item->data >= 2) ?($item->data - 1):0;
                $zero = str_repeat( '0',  $number_set  );
                $dataSet =  substr( $zero .(1),- $numbers,  $numbers ); 
            }else if(  $format == 'tisi_shortnumber' ){
                $dataSet = !empty($tisi_shortnumber)?$tisi_shortnumber:'XXX';
            }else if( $format == 'tisi_number' ){ //รหัสมาตรฐาน
                $dataSet = !empty($tisi_number)?$tisi_number:'XXX-XXXX';
            }else if( $format == 'application_type' ){ //ประเภทใบสมัคร
                $dataSet = 'APPTYPE';
            }

            $strNextSeq .= $dataSet;
                
        }

        return  $strNextSeq;
    }
}
