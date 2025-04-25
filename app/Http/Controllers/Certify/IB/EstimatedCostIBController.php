<?php

namespace App\Http\Controllers\Certify\IB;

use HP;
use Storage;

use App\User;
use stdClass;
use Exception;
use App\Http\Requests;
use App\estimatedcostib;
use App\Mail\IB\IBCostMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certify\ApplicantIB\CertiIBCost;
use App\Models\Certify\ApplicantIB\CertiIBCheck;



use App\Models\Certify\ApplicantIB\CertiIbHistory;
use App\Models\Certify\ApplicantIB\CertiIBCostItem;
use App\Models\Certify\ApplicantIB\CertiIBAttachAll;


class estimatedcostibController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files_ib/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiIBCost;
            $Query = $Query->select('app_certi_ib_costs.*');

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
                $CertiIb  = CertiIb::where('app_no', 'like', '%'.$filter['filter_search'].'%')->pluck('id');
                $Query = $Query->whereIn('app_certi_ib_id', $CertiIb);
            }

          //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                     $Query = $Query->LeftJoin('app_certi_ib_check','app_certi_ib_check.app_certi_ib_id','=','app_certi_ib_costs.app_certi_ib_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }

            $certi_cost =  $Query->orderby('id','desc')
                                // ->sortable()
                                ->paginate($filter['perPage']);

               return view('certify/ib/estimated_cost_ib.index', compact('certi_cost','filter'));
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
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('add-'.$model)) {
            $app_no = [];
             //เจ้าหน้าที่ IB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("27",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = CertiIBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_ib_id'); // เช็คเจ้าหน้าที่ IB
                if(count($check) > 0 ){
                    $app_no= CertiIb::whereNotIn('status',[0,4,5])
                                     ->whereIn('id',$check)
                                     ->whereIn('status',[6,7,8])
                                     ->orderby('id','desc')
                                     ->pluck('app_no', 'id');
                 }
            }else{
                    $app_no = CertiIb::whereNotIn('status',[0,4,5])
                                        ->whereIn('status',[6,7,8])
                                        ->orderby('id','desc')
                                        ->pluck('app_no', 'id');
            }

            $cost = new CertiIBCost;
            $cost_item = [new CertiIBCostItem];
            return view('certify/ib/estimated_cost_ib.create',[
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
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['vehicle'] = isset($request->vehicle) && ($request->draft == 1) ? 1 : null ;
            $cost = CertiIBCost::create($requestData);

            $this->storeItems($requestData, $cost);

            // ไฟล์แนบ
            if ($request->attachs){
                $this->set_attachs($request->attachs, $cost);
            }

            $certi_ib = CertiIb::findOrFail($cost->app_certi_ib_id);

            if(!is_null($certi_ib->email) && $cost->draft == 1){
                if(isset($request->vehicle)){
                    $certi_ib->update(['status'=>8]); // ขอความเห็นประมาณการค่าใช้จ่าย
                    // Log
                    $this->set_history($cost,$certi_ib);
                   //E-mail
                    $this->set_mail($cost,$certi_ib);
                }else{
                     $certi_ib->update(['status'=>7]); //  ประมาณการค่าใช้จ่าย
                }

            }


            return redirect('certify/estimated_cost-ib')->with('flash_message', 'เพิ่ม estimatedcostib เรียบร้อยแล้ว');
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
        // dd('ok');
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('edit-'.$model)) {

            $cost =  CertiIBCost::findOrFail($id);
            $cost_item = CertiIBCostItem::where('app_certi_cost_id',$id)->get();
            if(count($cost_item) <= 0){
                $cost_item = [new CertiIBCostItem];
            }
            return view('certify/ib/estimated_cost_ib.edit',compact('cost','cost_item'));
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
        // dd($request->all());
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['check_status'] =   null ;
            $requestData['status_scope'] =   null ;
            $requestData['remark']       =   null ;
            $requestData['remark_scope'] =   null ;
            $requestData['vehicle']      = isset($request->vehicle) && ($request->draft == 1) ? 1 : null ;

            $cost =  CertiIBCost::findOrFail($id);
            $cost->update($requestData);

            $this->storeItems($requestData, $cost);

            // ไฟล์แนบ
            // if ($request->attachs){
            //     $this->set_attachs($request->attachs, $cost);
            // }

            $json = $this->copyScopeIbFromAttachement($cost->app_certi_ib_id);
            $copiedScopes = json_decode($json, true);
            
            $tb = new CertiIBCost;
      
            $certi_ib_attach_more                   = new CertiIBAttachAll();
            $certi_ib_attach_more->app_certi_ib_id  = $cost->CertiIBCostTo->id ?? null;
            $certi_ib_attach_more->ref_id           = $cost->id;
            $certi_ib_attach_more->table_name       = $tb->getTable();
            $certi_ib_attach_more->file_section     = '1';
            $certi_ib_attach_more->file             = $copiedScopes[0]['attachs'];
            $certi_ib_attach_more->file_client_name =  $copiedScopes[0]['file_client_name'];
            $certi_ib_attach_more->token            = str_random(16);
            $certi_ib_attach_more->save();
       


            $certi_ib = CertiIb::findOrFail($cost->app_certi_ib_id);

            if(!is_null($certi_ib->email) && $cost->draft == 1){
                if(isset($request->vehicle)){
                    $certi_ib->update(['status'=>8]); // ขอความเห็นประมาณการค่าใช้จ่าย
                    // Log
                    $this->set_history($cost,$certi_ib);
                   //E-mail
                    $this->set_mail($cost,$certi_ib);
                }else{
                     $certi_ib->update(['status'=>7]); //  ประมาณการค่าใช้จ่าย
                }

            }


            return redirect('certify/estimated_cost-ib')->with('flash_message', 'แก้ไข  เรียบร้อยแล้ว!');
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
        $model = str_slug('estimatedcostib','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new estimatedcostib;
            estimatedcostib::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            estimatedcostib::destroy($id);
          }

          return redirect('certify/estimated-cost-i-b')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('estimatedcostib','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new estimatedcostib;
          estimatedcostib::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/estimated-cost-i-b')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function GetDataTraderOperaterName($app_no = null) {
        $app = CertiIb::where('id', $app_no)->first();
        return response()->json(['app' => $app->EsurvTrader->name ?? '' ], 200);
    }




    public function storeItems($items, $cost) {
        try {
            $cost->items()->delete();
            $detail = (array)@$items['detail'];
            foreach($detail['detail'] as $key => $data ) {
                $item                    = new CertiIBCostItem;
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

    
    public function copyScopeIbFromAttachement($certiIbId)
    {
        $copiedScoped = null;
        $fileSection = null;
    
        $app = CertiIb::find($certiIbId);
    
        $latestRecord = CertiIBAttachAll::where('app_certi_ib_id', $certiIbId)
        ->where('file_section', 3)
        ->where('table_name', 'app_certi_ib')
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->first();
    
        $existingFilePath = 'files/applicants/check_files_ib/' . $latestRecord->file ;
    
        // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
        if (HP::checkFileStorage($existingFilePath)) {
            $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
            $no  = str_replace("RQ-","",$app->app_no);
            $no  = str_replace("-","_",$no);
            $dlName = 'scope_'.basename($existingFilePath);
            $attach_path  =  'files/applicants/check_files_ib/'.$no.'/';
    
            if (file_exists($localFilePath)) {
                $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );
                $filePath = $attach_path . $dlName;
                if (Storage::disk('ftp')->exists($filePath)) {
                    $list  = new  stdClass;
                    $list->attachs =  $no.'/'.$dlName;
                    $list->file_client_name =  $dlName;
                    $scope[] = $list;
                    $copiedScoped = json_encode($scope);
                } 
                unlink($localFilePath);
            }
        }
    
        return $copiedScoped;
    }

    public function set_attachs($attachs, $cost) {
           $tb = new CertiIBCost;
          foreach ($attachs as $index => $item){
            $certi_ib_attach_more                   = new CertiIBAttachAll();
            $certi_ib_attach_more->app_certi_ib_id  = $cost->CertiIBCostTo->id ?? null;
            $certi_ib_attach_more->ref_id           = $cost->id;
            $certi_ib_attach_more->table_name       = $tb->getTable();
            $certi_ib_attach_more->file_section     = '1';
            $certi_ib_attach_more->file             = $this->storeFile($item,$cost->CertiIBCostTo->app_no);
            $certi_ib_attach_more->file_client_name = HP::ConvertCertifyFileName($item->getClientOriginalName());
            $certi_ib_attach_more->token            = str_random(16);
            $certi_ib_attach_more->save();
         }
    }
    public function set_mail($cost,$certi_ib) {

        if(!is_null($certi_ib->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            $data_app = [ 
                        'certi_ib'   => $certi_ib ?? '-',
                        'url'        => $url.'certify/applicant-ib' ?? '-',
                        'cost'       => $cost,
                        'email'      => !empty($certi_ib->DataEmailCertifyCenter) ? $certi_ib->DataEmailCertifyCenter : 'ib@tisi.mail.go.th',
                        'email_cc'   => !empty($certi_ib->DataEmailDirectorIBCC) ? $certi_ib->DataEmailDirectorIBCC : 'ib@tisi.mail.go.th',
                        'email_reply'=> !empty($certi_ib->DataEmailDirectorIBReply) ? $certi_ib->DataEmailDirectorIBReply : 'ib@tisi.mail.go.th'       
                       ];
                
            $log_email =  HP::getInsertCertifyLogEmail($certi_ib->app_no,
                                                    $certi_ib->id,
                                                    (new CertiIb)->getTable(),
                                                    $cost->id,
                                                    (new CertiIBCost)->getTable(),
                                                    2,
                                                    'การประมาณการค่าใช้จ่าย',
                                                    view('mail.IB.cost', $data_app),
                                                    $certi_ib->created_by,
                                                    $certi_ib->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_ib->DataEmailCertifyCenter) ?  implode(',',(array)$certi_ib->DataEmailCertifyCenter)  :  'ib@tisi.mail.go.th',
                                                    $certi_ib->email,
                                                    !empty($certi_ib->DataEmailDirectorIBCC) ? implode(',',(array)$certi_ib->DataEmailDirectorIBCC)   :   'ib@tisi.mail.go.th',
                                                    !empty($certi_ib->DataEmailDirectorIBReply) ?implode(',',(array)$certi_ib->DataEmailDirectorIBReply)   :   'ib@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new IBCostMail($data_app);
            $mail =  Mail::to($certi_ib->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            }   
 
        }
     }
    public function set_history($data,$certi_ib)
    {
        $tb = new CertiIBCost;
        $Cost = CertiIBCost::select('app_certi_ib_id', 'draft', 'check_status', 'remark', 'status_scope', 'remark_scope')
                      ->where('id',$data->id)
                      ->first();

        $CostItem = CertiIBCostItem::select('app_certi_cost_id','detail','amount_date','amount')
                              ->where('app_certi_cost_id',$data->id)
                              ->get()
                              ->toArray();
       CertiIbHistory::create([
                                    'app_certi_ib_id'   => $certi_ib->id ?? null,
                                    'system'            => 4,
                                    'table_name'        => $tb->getTable(),
                                    'ref_id'            => $data->id,
                                    'details_one'       =>  json_encode($Cost) ?? null,
                                    'details_two'       =>  (count($CostItem) > 0) ? json_encode($CostItem) : null,
                                    'attachs'           => (count($data->FileAttachCost1) > 0) ? json_encode($data->FileAttachCost1) : null,
                                    'created_by'        =>  auth()->user()->runrecno
                             ]);
   }

    public function storeFile($files, $app_no = 'files_ib',$name =null)
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

        public function delete_file($id)
        {
                $Cost = CertiIBAttachAll::findOrFail($id);
                if(!is_null($Cost)){
                    $Cost->delete();
                    $file = 'true';
                }else{
                     $file = 'false';
                }

          return  $file;

        }
}
