<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Basic\Feewaiver;
use HP;
use Storage;
class FeewaiverController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
     public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/basic/feewaiver/';
    }

    public function index(Request $request)
    {
        $model = str_slug('feewaiver','-');
        if(auth()->user()->can('view-'.$model)) {
            $attach_path  =  $this->attach_path;
            $feewaiver  =  Feewaiver::where('certify',1)->first();
            $feewaiver_ib  =  Feewaiver::where('certify',2)->first();
            $feewaiver_cb  =  Feewaiver::where('certify',3)->first();
            return view('basic.feewaiver.index',['feewaiver'   => $feewaiver,
                                                'feewaiver_ib' => $feewaiver_ib,
                                                'feewaiver_cb' => $feewaiver_cb,
                                                'attach_path'  => $attach_path
                                                ]);
        }
        abort(403);
    }

    public function store(Request $request)
    {
        $model = str_slug('feewaiver','-');
        if(auth()->user()->can('add-'.$model)) {


            $requestData = [];
            $requestData['payin1_status']                   =   isset($request->payin1_status) ? 1 : null;
            $requestData['payin1_start_date']               =   !empty($request->payin1_start_date) ? HP::convertDate($request->payin1_start_date,true) : null;
            $requestData['payin1_end_date']                 =   !empty($request->payin1_end_date) ? HP::convertDate($request->payin1_end_date,true) : null;
            $requestData['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin1_file')) {
                $requestData['payin1_file']                 =    $this->storeFile($request->payin1_file);
                $requestData['payin1_file_client_name']     =     HP::ConvertCertifyFileName($request->payin1_file->getClientOriginalName());
            }
            $requestData['payin2_status']                   =   isset($request->payin2_status) ? 1 : null;
            $requestData['payin2_start_date']               =   !empty($request->payin2_start_date) ? HP::convertDate($request->payin2_start_date,true) : null;
            $requestData['payin2_end_date']                 =   !empty($request->payin2_end_date) ? HP::convertDate($request->payin2_end_date,true) : null;
            $requestData['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin2_file')) {
                $requestData['payin2_file']                 =    $this->storeFile($request->payin2_file);
                $requestData['payin2_file_client_name']     =     HP::ConvertCertifyFileName($request->payin2_file->getClientOriginalName());
            }
             $feewaiver  =  Feewaiver::where('certify',1)->first();
            if(!is_null($feewaiver)){
                $feewaiver->update($requestData);
            }else{
                $requestData['certify'] = 1; // ห้องปฏิบัติการ (LAB)
                Feewaiver::create($requestData);
            }

            $requestDataIB = [];
            $requestDataIB['payin1_status']                   =   isset($request->payin1_ib_status) ? 1 : null;
            $requestDataIB['payin1_start_date']               =   !empty($request->payin1_ib_start_date) ? HP::convertDate($request->payin1_ib_start_date,true) : null;
            $requestDataIB['payin1_end_date']                 =   !empty($request->payin1_ib_end_date) ? HP::convertDate($request->payin1_ib_end_date,true) : null;
            $requestDataIB['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin1_ib_file')) {
                $requestDataIB['payin1_file']                 =    $this->storeFile($request->payin1_ib_file);
                $requestDataIB['payin1_file_client_name']     =     HP::ConvertCertifyFileName($request->payin1_ib_file->getClientOriginalName());
            }
            $requestDataIB['payin2_status']                   =   isset($request->payin2_ib_status) ? 1 : null;
            $requestDataIB['payin2_start_date']               =   !empty($request->payin2_ib_start_date) ? HP::convertDate($request->payin2_ib_start_date,true) : null;
            $requestDataIB['payin2_end_date']                 =   !empty($request->payin2_ib_end_date) ? HP::convertDate($request->payin2_ib_end_date,true) : null;
            $requestDataIB['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin2_ib_file')) {
                $requestDataIB['payin2_file']                 =    $this->storeFile($request->payin2_ib_file);
                $requestDataIB['payin2_file_client_name']     =     HP::ConvertCertifyFileName($request->payin2_ib_file->getClientOriginalName());
            }
             $feewaiver_ib  =  Feewaiver::where('certify',2)->first();
            if(!is_null($feewaiver_ib)){
                $feewaiver_ib->update($requestDataIB);
            }else{
                $requestDataIB['certify'] = 2; // หน่วยตรวจ (IB)
                Feewaiver::create($requestDataIB);
            }


            $requestDataCB = [];
            $requestDataCB['payin1_status']                   =   isset($request->payin1_cb_status) ? 1 : null;
            $requestDataCB['payin1_start_date']               =   !empty($request->payin1_cb_start_date) ? HP::convertDate($request->payin1_cb_start_date,true) : null;
            $requestDataCB['payin1_end_date']                 =   !empty($request->payin1_cb_end_date) ? HP::convertDate($request->payin1_cb_end_date,true) : null;
            $requestDataCB['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin1_cb_file')) {
                $requestDataCB['payin1_file']                 =    $this->storeFile($request->payin1_cb_file);
                $requestDataCB['payin1_file_client_name']     =     HP::ConvertCertifyFileName($request->payin1_cb_file->getClientOriginalName());
            }
            $requestDataCB['payin2_status']                   =   isset($request->payin2_cb_status) ? 1 : null;
            $requestDataCB['payin2_start_date']               =   !empty($request->payin2_cb_start_date) ? HP::convertDate($request->payin2_cb_start_date,true) : null;
            $requestDataCB['payin2_end_date']                 =   !empty($request->payin2_cb_end_date) ? HP::convertDate($request->payin2_cb_end_date,true) : null;
            $requestDataCB['created_by']                      =   auth()->user()->runrecno;
            if ($request->hasFile('payin2_cb_file')) {
                $requestDataCB['payin2_file']                 =    $this->storeFile($request->payin2_cb_file);
                $requestDataCB['payin2_file_client_name']     =     HP::ConvertCertifyFileName($request->payin2_cb_file->getClientOriginalName());
            }
             $feewaiver  =  Feewaiver::where('certify',3)->first();
            if(!is_null($feewaiver)){
                $feewaiver->update($requestDataCB);
            }else{
                $requestDataCB['certify'] = 3; //  หน่วยรับรอง (CB)
                Feewaiver::create($requestDataCB);
            }

            return redirect('basic/feewaiver')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว');
        }
        abort(403);
    }
        public function storeFile($files)
        {
            if ($files) {
                $attach_path  =  $this->attach_path;
                $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $attach_path.''.$storageName;
            }else{
                return null;
            }
        }
        public function remove_file($payin_id,$certify)
        {
            $feewaiver  =  Feewaiver::where('certify',$certify)->first();
            if(!is_null($feewaiver)){

                if($payin_id == '1'){
                    $feewaiver->payin1_file             = null;
                    $feewaiver->payin1_file_client_name = null;
                }else  if($payin_id == '2'){
                    $feewaiver->payin2_file             = null;
                    $feewaiver->payin2_file_client_name = null;
                }else  if($payin_id == '1_ib'){
                    $feewaiver->payin2_file             = null;
                    $feewaiver->payin2_file_client_name = null;
                }else  if($payin_id == '2_ib'){
                    $feewaiver->payin2_file             = null;
                    $feewaiver->payin2_file_client_name = null;
                }else  if($payin_id == '1_cb'){
                    $feewaiver->payin2_file             = null;
                    $feewaiver->payin2_file_client_name = null;
                }else  if($payin_id == '2_cb'){
                    $feewaiver->payin2_file             = null;
                    $feewaiver->payin2_file_client_name = null;
                }
                $feewaiver->save();
                 return   'true';
            }else{
                return   'false';
            }
        }

}
