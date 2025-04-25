<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Config\ConfigsManual;
use Illuminate\Support\Facades\Storage;

class ConfigsManualController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/config_manual';
        $this->attach_path_crop = 'tis_attach/config_manual_crop/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('config/configs-manual.index');
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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('add-'.$model)) {
            
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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();

            if( !empty($requestData['sytem']) && $requestData['sytem'] == 'tisi-center' ){
                $sytem = 'tisi-center';
                if( isset($requestData['repeater-center']) ){
                    $repeater_data = $requestData['repeater-center'];
                    $this->SaveData(  $repeater_data , $sytem  );
                }else{
                    ConfigsManual::where('site', $sytem )->delete();
                }
            }

            if( !empty($requestData['sytem']) && $requestData['sytem'] == 'tisi-e-acc' ){
                $sytem = 'tisi-e-acc';
                if( isset($requestData['repeater-e-acc']) ){
                    $repeater_data = $requestData['repeater-e-acc'];
                    $this->SaveData(  $repeater_data , $sytem  );
                }else{
                    ConfigsManual::where('site', $sytem )->delete();
                }
            }

            if( !empty($requestData['sytem']) && $requestData['sytem'] == 'tisi-esurv' ){
                $sytem = 'tisi-esurv';
                if( isset($requestData['repeater-esurv']) ){
                    $repeater_data = $requestData['repeater-esurv'];
                    $this->SaveData(  $repeater_data , $sytem  );
                }else{
                    ConfigsManual::where('site', $sytem )->delete();
                }
            }

            if( !empty($requestData['sytem']) && $requestData['sytem'] == 'tisi-sso' ){
                $sytem = 'tisi-sso';
                if( isset($requestData['repeater-sso']) ){
                    $repeater_data = $requestData['repeater-sso'];
                    $this->SaveData(  $repeater_data , $sytem  );
                }else{
                    ConfigsManual::where('site', $sytem )->delete();
                }
            }

            return redirect('config/manual')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');

        }
        abort(403);
    }

    
    public function SaveData( $repeater_list , $sytem )
    {

        $list_ids = [];
        foreach($repeater_list as $item){
            $list_ids[] = $item["id"];
        }
        $list_id = array_diff($list_ids, [null]);

        ConfigsManual::where('site', $sytem )
                        ->when($list_id, function ($query, $list_id){
                            return $query->whereNotIn('id', $list_id);
                        })
                        ->delete();

        // ConfigsManual
        foreach( $repeater_list AS $item ){

            $data = ConfigsManual::where('id',$item["id"])->where('site',  $sytem )->first();
            if(is_null($data)){
                $data = new ConfigsManual;
            }

            $data->title = !empty($item['title'])?$item['title']:null;
            $data->details = !empty($item['details'])?$item['details']:null; 
            $data->site = $sytem; 

            if( isset( $item['upload_file'] ) && !empty($item['upload_file']) ){

                $attach = $item['upload_file'];
  
                $file_extension = $attach->getClientOriginalExtension();
                $storageName = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;

                $storagePath = Storage::putFileAs( $this->attach_path, $attach,  str_replace(" ","",$storageName) );

                $single_attach = [
                                    'file_name' => $storageName,
                                    'file_client_name' => $attach->getClientOriginalName()
                                ];

                $data->file = json_encode($single_attach);
                $data->file_url = $storagePath;

            }
            $data->save();  

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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('view-'.$model)) {

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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('edit-'.$model)) {

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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('edit-'.$model)) {

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
        $model = str_slug('configs-manual','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }



}
