<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Config as config;
use App\Models\Basic\ConfigRoles as config_roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\AttachFile;
use HP;

class ConfigController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('config','-');
        if(auth()->user()->can('view-'.$model)) {

            $config = HP::getConfig(false);

            $esurv = config_roles::select('role_id')->where('group_type',1)->get()->pluck('role_id');
            if(count($esurv) == 0){
                $esurv = [];
            }
            $certify = config_roles::select('role_id')->where('group_type',2)->get()->pluck('role_id');
            if(count($certify) == 0){
                $certify = [];
            }
            return view('basic.config.index', compact('config','esurv','certify'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('basic.config_term.create');
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();

            foreach ($requestData as $key => $value) {
              $config = config::where('variable', $key)->first();

              if(!is_null($config)){
                $config->data = $value;
                $config->save();
              }

            }
           
            $config = config::where('variable', 'check_electronic_certificate')->first();
            if(!is_null($config)){
                    if(isset($requestData['check_electronic_certificate'])){  //  เปิดใช้งานใบรับรองระบบงานแบบดิจิทัล
                        $config->data = 1; 
                    }else{
                        $config->data = 0;
                    }
                 $config->save();
             }

            config_roles::where('group_type',1)->delete();
            if(!empty($request->esurv) && count($request->esurv) > 0){
              foreach ($request->esurv as $key => $value) {
                   $esurv             =  new config_roles;
                   $esurv->group_type = 1;
                   $esurv->role_id    = $value;
                   $esurv->created_by   =  auth()->user()->getKey();
                   $esurv->save();
               }
            }
            config_roles::where('group_type',2)->delete();
            if(!empty($request->certify) && count($request->certify) > 0){
            foreach ($request->certify as $key => $value) {
                 $certify                =  new config_roles;
                 $certify->group_type    = 2;
                 $certify->role_id       = $value;
                 $certify->created_by    =  auth()->user()->getKey();
                 $certify->save();
             }
          }

            return redirect('basic/config')->with('flash_message', 'แก้ไขการตั้งค่าระบบเรียบร้อยแล้ว');

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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('view-'.$model)) {
            $config_term = config_term::findOrFail($id);
            return view('basic.config_term.show', compact('config_term'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('edit-'.$model)) {
            $config_term = config_term::findOrFail($id);
            return view('basic.config_term.edit', compact('config_term'));
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $config_term = config_term::findOrFail($id);
            $config_term->update($requestData);

            return redirect('basic/config_term')->with('flash_message', 'แก้ไข config_term เรียบร้อยแล้ว!');
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
        $model = str_slug('config_term','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new config_term;
            config_term::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            config_term::destroy($id);
          }

          return redirect('basic/config_term')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('config_term','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new config_term;
          config_term::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/config_term')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function upload_file(Request $request)
    {
        $requestData = $request->all();

        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
        $folder_app = 'tis_attach/config_file';

        if(isset($requestData['file'])){
            if ($request->hasFile('file')) {
                HP::singleFileUpload(
                    $request->file('file') ,
                    $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Center',
                    (  (new Config)->getTable() ),
                    null,
                    'config_file',
                    null
                );
            }
        }

        echo 'success';

    }

    public function get_file(Request $request){
        return view('basic.config.file');
    }

    public function delete_file($id)
    {
        $resulte = 'error';
        $attach =  AttachFile::findOrFail($id);
        if( !empty($attach) && !empty($attach->url) ){
    
            if( HP::checkFileStorage( '/'.$attach->url) ){
                Storage::delete( '/'.$attach->url );
                $attach->delete();
            }
    
            $resulte = 'success';
    
        }

        echo $resulte;
    }

}
