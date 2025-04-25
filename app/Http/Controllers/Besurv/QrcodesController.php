<?php

namespace App\Http\Controllers\Besurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Qrcode;
use Illuminate\Http\Request;
use HP;
use Storage;
class QrcodesController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
     public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/qrcode/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('qrcodes','-');
        if(auth()->user()->can('view-'.$model)) {
            // $attach_path  =  $this->attach_path;
            $qrcode20  =  Qrcode::where('type_of_ter',20)->first();
            $qrcode21  =  Qrcode::where('type_of_ter',21)->first();
       
            return view('besurv.qrcodes.index',['qrcode20'   => $qrcode20,
                                                'qrcode21' => $qrcode21
                                                ]);
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
        $model = str_slug('qrcodes','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData20ter = [];
            
            $requestData20ter['qrcode_state']                    =   isset($request->qrcode20_state) ? 1 : null;
            $requestData20ter['qrcode_link']                     =   !empty($request->qrcode20_link)     ? $request->qrcode20_link : null;
            $requestData20ter['qrcode_announce']                 =   !empty($request->qrcode20_announce)     ? $request->qrcode20_announce : null;
            $requestData20ter['index_state']                     =   isset($request->index20_state) ? 1 : null;
            $requestData20ter['index_link']                      =   !empty($request->index20_link)     ? $request->index20_link : null;
            $requestData20ter['index_announce']                  =   !empty($request->index20_announce)     ? $request->index20_announce : null;
            $requestData20ter['created_by']                      =   auth()->user()->runrecno;
            $requestData20ter['attach_state']                     =   isset($request->attach20_state) ? 1 : null;
            if ($request->hasFile('attach20')) {
                $requestData20ter['attach']                      =    $this->storeFile($request->attach20);
                $requestData20ter['file_client_name']            =    HP::ConvertCertifyFileName($request->attach20->getClientOriginalName());
            }

             $qrcode  =  Qrcode::where('type_of_ter','20')->first();
            if(!is_null($qrcode)){
                $qrcode->update($requestData20ter);
            }else{
                $requestData20ter['type_of_ter'] = '20'; // ห้องปฏิบัติการ (LAB)
                Qrcode::create($requestData20ter);
            }

            $requestData21ter = [];
            $requestData21ter['qrcode_state']                    =   isset($request->qrcode21_state) ? 1 : null;
            $requestData21ter['qrcode_link']                     =   !empty($request->qrcode21_link)     ? $request->qrcode21_link : null;
            $requestData21ter['qrcode_announce']                 =   !empty($request->qrcode21_announce)     ? $request->qrcode21_announce : null;
            $requestData21ter['index_state']                     =   isset($request->index21_state) ? 1 : null;
            $requestData21ter['index_link']                      =   !empty($request->index21_link)     ? $request->index21_link : null;
            $requestData21ter['index_announce']                  =   !empty($request->index21_announce)     ? $request->index21_announce : null;
            $requestData21ter['created_by']                      =   auth()->user()->runrecno;
            $requestData21ter['attach_state']                     =   isset($request->attach21_state) ? 1 : null;
            if ($request->hasFile('attach21')) {
                $requestData21ter['attach']                      =    $this->storeFile($request->attach21);
                $requestData21ter['file_client_name']            =    HP::ConvertCertifyFileName($request->attach21->getClientOriginalName());
            }

             $qrcode  =  Qrcode::where('type_of_ter','21')->first();
            if(!is_null($qrcode)){
                $qrcode->update($requestData21ter);
            }else{
                $requestData21ter['type_of_ter'] = '21'; // ห้องปฏิบัติการ (LAB)
                Qrcode::create($requestData21ter);
            }

            return redirect('besurv/qrcodes')->with('flash_message', 'แก้ไขการประกาศเรียบร้อยแล้ว');
        }
        abort(403);
    }
    public function storeFile($files)
    {
        if ($files) {
            $attach_path  =  $this->attach_path;
            $fullFileName = date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
            $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $attach_path.''.$storageName;
        }else{
            return null;
        }
    }


    public function remove_file($type)
    {
        $qrcode  =  Qrcode::where('type_of_ter',$type)->first();
        if(!is_null($qrcode)){
            $qrcode->attach             = null;
            $qrcode->file_client_name   = null;
            $qrcode->save();
             return   'true';
        }else{
            return   'false';
        }
    }

}
