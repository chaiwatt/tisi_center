<?php

namespace App\Http\Controllers\Certify\CB;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\EstimatedCostCB;
use Illuminate\Http\Request;

use Exception;
use stdClass;
// use Storage;
use HP;
use DB;
use Illuminate\Support\Facades\Storage;

use App\User;
use App\Models\Certify\ApplicantCB\CertiCb;
use App\Models\Certify\ApplicantCB\CertiCbHistory;
use App\Models\Certify\ApplicantCB\CertiCBAttachAll;
use App\Models\Certify\ApplicantCB\CertiCBCheck;
use App\Models\Certify\ApplicantCB\CertiCBCost;
use App\Models\Certify\ApplicantCB\CertiCBCostItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\CB\CBCostMail;
class EstimatedCostCBController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_cb/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('estimatedcostcb','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiCBCost;
            $Query = $Query->select('app_certi_cb_costs.*');
            if ($filter['filter_status']!='') {
                $status =  $filter['filter_status'];
                if($status == 0){
                    $Query = $Query->where('draft', 0);
                }else{
                  if($status == 1){
                    $Query = $Query->where('draft', 1);
                  }else{
                    if($status == 2 ){
                        $Query = $Query->where('check_status',1)->where('status_scope',1);
                    }
                    if($status == 3){
                        $Query = $Query->orwhere('check_status','!=',1)->orwhere('status_scope','!=',1);
                    }
                  }
                }
            }
            if ($filter['filter_search'] != '') {
                $CertiCb  = CertiCb::where('app_no', 'like', '%'.$filter['filter_search'].'%')->pluck('id');
                $Query = $Query->whereIn('app_certi_cb_id', $CertiCb);
            }
             //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
             if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_cb_check','app_certi_cb_check.app_certi_cb_id','=','app_certi_cb_costs.app_certi_cb_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }
            $certi_cost =  $Query->orderby('id','desc')
                                // ->sortable()
                                ->paginate($filter['perPage']);


            return view('certify/cb.estimated_cost_cb.index', compact('certi_cost', 'filter'));
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
        $model = str_slug('estimatedcostcb','-');
        if(auth()->user()->can('add-'.$model)) {
            $app_no = [];
            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
           if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
            $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                if(count($check) > 0 ){
                    $app_no= CertiCb::whereNotIn('status',[0,4,5])
                                    ->whereIn('id',$check)
                                    ->whereIn('status',[6,7,8])
                                    ->orderby('id','desc')
                                    ->pluck('app_no', 'id');
                }
             }else{
                $app_no = CertiCb::whereNotIn('status',[0,4,5])
                                        ->whereIn('status',[6,7,8])
                                    ->orderby('id','desc')
                                    ->pluck('app_no', 'id');
            }
            $cost = new CertiCBCost;
            $cost_item = [new CertiCBCostItem];
            return view('certify/cb.estimated_cost_cb.create',[
                                                               'app_no' => $app_no,
                                                               'cost' => $cost,
                                                               'cost_item' => $cost_item
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
        $model = str_slug('estimatedcostcb','-');
        if(auth()->user()->can('add-'.$model)) {
    try {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['draft']   =  isset($request->draft) && ($request->draft == 1) ? 1 : null ;
            $requestData['vehicle'] =  isset($request->vehicle) && ($request->draft == 1) ? 1 : null ;
            $cost = CertiCBCost::create($requestData);

            $this->storeItems($requestData, $cost);

            // ไฟล์แนบ
            if ($request->attachs){
                $this->set_attachs($request->attachs, $cost);
            }

            $certi_cb = CertiCb::findOrFail($cost->app_certi_cb_id);
            if(!is_null($certi_cb->email) && $cost->draft == 1){
                if(isset($request->vehicle)){
                    $certi_cb->update(['status'=>8]); // ขอความเห็นประมาณการค่าใช้จ่าย
                     // Log
                    $this->set_history($cost,$certi_cb);
                    //E-mail
                    $this->set_mail($cost,$certi_cb);

                }else{
                     $certi_cb->update(['status'=>7]); //  ประมาณการค่าใช้จ่าย
                }

            }

            return redirect('certify/estimated_cost-cb')->with('flash_message', 'เพิ่ม EstimatedCostCB เรียบร้อยแล้ว');
          } catch (\Exception $e) {
            return redirect('certify/estimated_cost-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
          }


        }
        abort(403);
    }

    public function edit($id)
    {
        
        $model = str_slug('estimatedcostcb','-');
        if(auth()->user()->can('edit-'.$model)) {
            $cost =  CertiCBCost::findOrFail($id);
            $certi_cb =  CertiCb::findOrFail($cost->app_certi_cb_id);

            $checkFiles = CertiCBAttachAll::where('app_certi_cb_id',$certi_cb->id)
            ->where('file_section',1)
            ->where('table_name','app_certi_cb_costs')
            ->whereNotNull('file')
            ->get();

            if($checkFiles->count() == 0)
            {
                $latestRecord = CertiCBAttachAll::where('app_certi_cb_id', $certi_cb->id)
                            ->where('file_section',3)
                            ->where('table_name','app_certi_cb')
                            ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
                            ->first();
                $existingFilePath = 'files/applicants/check_files_cb/' . $latestRecord->file ;
                // dd($existingFilePath);


                if (HP::checkFileStorage($existingFilePath)) {
                    $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
                    
                    $no  = str_replace("RQ-","",$certi_cb->app_no);
                    $no  = str_replace("-","_",$no);
                    $dlName = 'scope_'.basename($existingFilePath);
                    $attach_path  =  'files/applicants/check_files_cb/'.$no;

                    $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                    $filePath = $no . '/' . $dlName;
                    if (Storage::disk('ftp')->exists($storagePath)) {
                        $tb = new CertiCBCost;
                        $certi_cb_attach_more = new CertiCBAttachAll();
                        $certi_cb_attach_more->app_certi_cb_id  = $cost->app_certi_cb_id ?? null;
                        $certi_cb_attach_more->ref_id           = $cost->id;
                        $certi_cb_attach_more->table_name       = $tb->getTable();
                        $certi_cb_attach_more->file_section     = '1';
                        $certi_cb_attach_more->file             = $filePath;
                        $certi_cb_attach_more->file_client_name = $dlName;
                        $certi_cb_attach_more->token            = str_random(16);
                        $certi_cb_attach_more->save();
                    } 
                    unlink($localFilePath);
                }
            }
            // dd($checkFiles);

            $cost_item = CertiCBCostItem::where('app_certi_cost_id',$id)->get();
            if(count($cost_item) <= 0){
                $cost_item = [new CertiCBCostItem];
            }
            $attach_path = $this->attach_path;//path ไฟล์แนบ
            return view('certify/cb.estimated_cost_cb.edit', compact('cost','cost_item','attach_path'));
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

        $model = str_slug('estimatedcostcb','-');
        if(auth()->user()->can('edit-'.$model)) {
  
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['check_status'] =   null ;
            $requestData['status_scope'] =   null ;
            $requestData['remark']       =   null ;
            $requestData['remark_scope'] =   null ;
            $requestData['draft']        =   isset($request->draft) && ($request->draft == 1) ? 1 : null ;
            $requestData['vehicle']      =  isset($request->vehicle) && ($request->draft == 1) ? 1 : null ;

            $cost =  CertiCBCost::findOrFail($id);
            $cost->update($requestData);

            $this->storeItems($requestData, $cost);

            // ไฟล์แนบ
            // if ($request->attachs){
            //      $this->set_attachs($request->attachs, $cost);
            // }

            $certi_cb = CertiCb::findOrFail($cost->app_certi_cb_id);


            if(!is_null($certi_cb->email) && $cost->draft == 1){
                if(isset($request->vehicle)){
                    $certi_cb->update(['status'=>8]); // ขอความเห็นประมาณการค่าใช้จ่าย
                    // Log
                    $this->set_history($cost,$certi_cb);
                    //E-mail
                    $this->set_mail($cost,$certi_cb);

                }else{
                     $certi_cb->update(['status'=>7]); //  ประมาณการค่าใช้จ่าย
                }

            }

            return redirect('certify/estimated_cost-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
        }
        abort(403);

    }
    public function storeItems($items, $cost) {
        try {
            $cost->items()->delete();
            $detail = (array)@$items['detail'];
            foreach($detail['detail'] as $key => $data ) {
                $item = new CertiCBCostItem;
                $item->app_certi_cost_id = $cost->id;
                $item->detail            = $data ?? null;
                $item->amount_date       = $detail['amount_date'][$key] ?? 0;
                $item->amount            =  !empty(str_replace(",","", $detail['amount'][$key]))?str_replace(",","",$detail['amount'][$key]):null;
                $item->save();
            }
        } catch (Exception $x) {
            throw $x;
        }
    }

    public function set_attachs($attachs, $cost) {
        $tb = new CertiCBCost;
       foreach ($attachs as $index => $item){
        // $filePath = $this->storeFile($item,($cost->CertiCBCostTo->app_no ?? 'files_cb'));
        // dd($filePath);
         $certi_cb_attach_more = new CertiCBAttachAll();
         $certi_cb_attach_more->app_certi_cb_id  = $cost->app_certi_cb_id ?? null;
         $certi_cb_attach_more->ref_id           = $cost->id;
         $certi_cb_attach_more->table_name       = $tb->getTable();
         $certi_cb_attach_more->file_section     = '1';
         $certi_cb_attach_more->file             = $this->storeFile($item,($cost->CertiCBCostTo->app_no ?? 'files_cb'));
         $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
         $certi_cb_attach_more->token            = str_random(16);
         $certi_cb_attach_more->save();
      }
 }
 public function set_mail($cost,$certi_cb) {
 
    if(!is_null($certi_cb->email)){

        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

        $data_app = [
                        'email'          =>  auth()->user()->email ?? 'admin@admin.com',
                        'certi_cb'       => $cost->CertiCBCostTo ?? '-',
                        'url'            => $url.'certify/applicant-cb' ?? '-',
                        'cost'           => $cost,
                        'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                        'email_cc'       =>  !empty($certi_cb->DataEmailDirectorCBCC) ? $certi_cb->DataEmailDirectorCBCC : 'cb@tisi.mail.go.th',
                        'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                  ];

        $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                $certi_cb->id,
                                                (new CertiCb)->getTable(),
                                                $cost->id,
                                                (new CertiCBCost)->getTable(),
                                                3,
                                                'การประมาณการค่าใช้จ่าย',
                                                view('mail.CB.cost', $data_app),
                                                $certi_cb->created_by,
                                                $certi_cb->agent_id,
                                                auth()->user()->getKey(),
                                                !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                $certi_cb->email,
                                                !empty($certi_cb->DataEmailDirectorCBCC) ? implode(',',(array)$certi_cb->DataEmailDirectorCBCC)   :   'cb@tisi.mail.go.th',
                                                !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                null
                                                );

        $html = new CBCostMail($data_app);
        $mail =  Mail::to($certi_cb->email)->send($html);

        if(is_null($mail) && !empty($log_email)){
            HP::getUpdateCertifyLogEmail($log_email->id);
        }

    }
 }
public function set_history($data,$certi_cb)
{
    $tb = new CertiCBCost;
    $Cost = CertiCBCost::select('app_certi_cb_id', 'draft', 'check_status', 'remark', 'status_scope', 'remark_scope')
                  ->where('id',$data->id)
                  ->first();

    $CostItem = CertiCBCostItem::select('app_certi_cost_id','detail','amount_date','amount')
                          ->where('app_certi_cost_id',$data->id)
                          ->get()
                          ->toArray();
   CertiCbHistory::create([
                                'app_certi_cb_id'=> $certi_cb->id ?? null,
                                'system'         => 4,
                                'table_name'     => $tb->getTable(),
                                'ref_id'         => $data->id,
                                'details_one'    =>  json_encode($Cost) ?? null,
                                'details_two'    =>  (count($CostItem) > 0) ? json_encode($CostItem) : null,
                                'attachs'        => (count($data->FileAttachCost1) > 0) ? json_encode($data->FileAttachCost1) : null,
                                'created_by'     =>  auth()->user()->runrecno
                         ]);
}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */



    public function GetDataTraderOperaterName($app_no = null) {
        $app = CertiCb::where('id', $app_no)->first();
        return response()->json([ 'app' => $app->name ?? ''  ], 200);
    }

        // สำหรับเพิ่มรูปไปที่ store
        public function storeFile($files, $app_no = 'files_cb', $name = null)
        {

            $no  = str_replace("RQ-","",$app_no);
            $no  = str_replace("-","_",$no);
            if ($files) {
                $attach_path  =  $this->attach_path.$no;
                $file_extension = $files->getClientOriginalExtension();
                $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
                $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
                $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
        }
}
