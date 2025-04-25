<?php

namespace App\Http\Controllers\Certify\CB;

use DB;
use HP;
use Storage;

use App\User;
use stdClass;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

use App\Models\Besurv\Signer;

use App\Certify\CbAuditorTeam;
use App\Mail\CB\CBAuditorsMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;   
use  App\Models\Bcertify\StatusAuditor; 
use App\Mail\CB\CbDocReviewAuditorsMail;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Certify\ApplicantCB\CertiCb; 
use App\Models\Certify\Applicant\CostDetails;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Bcertify\HtmlCbMemorandumRequest;
use App\Models\Certify\ApplicantCB\CertiCBCost; 
use App\Models\Certify\MessageRecordTransaction;
use App\Models\Bcertify\BoardAuditorMsRecordInfo;

use App\Models\Bcertify\HtmlLabMemorandumRequest;

use App\Models\Certify\ApplicantCB\CertiCBCheck; 
use App\Models\Certify\ApplicantCB\CertiCBReview;
use App\Models\Bcertify\CbBoardAuditorMsRecordInfo;
use App\Models\Bcertify\CbMessageRecordTransaction;
use App\Models\Certify\ApplicantCB\CertiCbHistory; 
use App\Models\Certify\ApplicantCB\CertiCBAuditors; 
use App\Models\Certify\ApplicantCB\CertiCBPayInOne; 
use App\Models\Certify\ApplicantCB\CertiCBAttachAll; 
use App\Models\Certify\ApplicantCB\CertiCBAuditorsCost;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsDate;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsList; 
use App\Models\Certify\ApplicantCB\CertiCBAuditorsStatus;

class AuditorCBController extends Controller
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
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
        
            $filter = [];
 
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);


            $Query = new CertiCBAuditors;
            $Query = $Query->select('app_certi_cb_auditors.*');

            if ($filter['filter_status']!='') {
                if($filter['filter_status'] == 0){
                    $Query = $Query->whereNull('status');
                }else{
                    $Query = $Query->where('status', $filter['filter_status']);
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
                     $Query = $Query->LeftJoin('app_certi_cb_check','app_certi_cb_check.app_certi_cb_id','=','app_certi_cb_auditors.app_certi_cb_id')
                                    ->where('user_id',auth()->user()->runrecno);  //เจ้าหน้าที่  IB ที่ได้มอบหมาย 
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                } 
            }
            $auditors =  $Query->orderby('id','desc')
                                // ->sortable()
                                ->paginate($filter['perPage']);


            return view('certify.cb.auditor_cb.index', compact('auditors', 'filter'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
     
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('add-'.$model)) {
          $previousUrl = app('url')->previous();
           
            $auditorcb = new CertiCBAuditors;
            $auditors_status = [new CertiCBAuditorsStatus]; 
          if(!empty($request->certicb_id)){
            $auditorcb->app_certi_cb_id = $request->certicb_id;
            $auditorcb->certi_cb_change =  true;
          } 
         
            $app_no = [];
            //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
           if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
               $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
               if(count($check) > 0 ){
                   $app_no= CertiCb::whereNotIn('status',[0,4,5])
                                    ->whereIn('id',$check)
                                    ->whereIn('status',[9,10,11])
                                    ->orderby('id','desc')
                                    ->pluck('app_no', 'id');
                } 
           }else{
                   $app_no = CertiCb::whereNotIn('status',[0,4,5])
                                        ->whereIn('status',[9,10,11])
                                       ->orderby('id','desc')
                                       ->pluck('app_no', 'id');
           }
           $signers = Signer::all();
           $cbAuditorTeams = CbAuditorTeam::where('state',1)->get();

           $selectUserIds  = User::whereIn('reg_subdepart',[1803])
           ->pluck('runrecno')
           ->toArray();

           $select_users = Signer::whereIn('user_register_id',$selectUserIds)->get();
          //  dd($select_users);
          //  public function user()
          //  {
          //      return $this->belongsTo(User::class, 'user_register_id', 'runrecno');
          //  }

            return view('certify.cb.auditor_cb.create',[
              'cbAuditorTeams'=>$cbAuditorTeams,
              'signers'=>$signers,
              'app_no' => $app_no ,
              'auditorcb' => $auditorcb,
              'auditors_status'=> $auditors_status,
              'previousUrl'=>$previousUrl,
              'select_users' => $select_users
            ]);

        }
        abort(403);

    }


    public function store(Request $request)
    {
        $cbAuditorTeam = CbAuditorTeam::find($request->cbAuditorTeam);
        $auditorTeamData = json_decode($cbAuditorTeam->auditor_team_json, true);
        // dd($request->all());
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('add-'.$model)) {
  
          try {
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();
                $requestData['cb_auditor_team_id'] =   $request->cbAuditorTeam ;
                $requestData['status'] =   null ;
                $requestData['step_id'] =  2  ;//ขอความเห็นแต่งคณะผู้ตรวจประเมิน
                $requestData['vehicle'] = isset($request->vehicle) ? 1 : null ;
                $auditors =  CertiCBAuditors::create($requestData);
              // ไฟล์แนบ
                  // if ($request->other_attach){
                  //   $this->set_attachs($request->other_attach, $auditors,"1");
                  // }
                  if ($request->attach){
                    $this->set_attachs($request->attach, $auditors,"2");
                  }

                  //วันที่ตรวจประเมิน
                $this->DataCertiCBAuditorsDate($auditors->id,$request);

                // $this->storeStatus($auditors->id,(array)$requestData['list']);
                $this->storeStatusFromCbAuditorTeam($auditors->id,$auditorTeamData);

                //ค่าใช้จ่าย
                $this->storeItems($auditors->id,$request);

                $certi_cb = CertiCb::findOrFail($auditors->app_certi_cb_id);
                $this->saveSignature($request,$auditors->id,$certi_cb);
                if(!is_null($certi_cb->email)){
                    if(isset($request->vehicle)){
                        $certi_cb->update(['status'=>10]); // อยู่ระหว่างดำเนินการ 	
                        // Log
                        $this->set_history($auditors,$certi_cb);
                        //E-mail 
                        $this->set_mail($auditors,$certi_cb);
              
                    }else{
                        $certi_cb->update(['status'=>10]); //  อยู่ระหว่างดำเนินการ
                    }
                }
                
            
                if($request->previousUrl){
                  return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
                }else{
                    return redirect('certify/auditor-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
                }
          } catch (\Exception $e) {
                 return redirect('certify/auditor-cb')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
          }

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
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('view-'.$model)) {
            $auditorcb = AuditorCB::findOrFail($id);
            return view('certify/cb.auditor_cb.show', compact('auditorcb'));
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
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('edit-'.$model)) {
          $previousUrl = app('url')->previous();
          $auditorcb = CertiCBAuditors::findOrFail($id);
  
          $auditors_status = CertiCBAuditorsStatus::where('auditors_id',$id)->get();
          if(count($auditors_status) <= 0){
              $auditors_status = [new CertiCBAuditorsStatus];
          }
            $attach_path = $this->attach_path;//path ไฟล์แนบ
            $signers = Signer::all();
            $cbAuditorTeams = CbAuditorTeam::where('state',1)->get();

            // $messageRecordTransaction = CbMessageRecordTransaction::where('board_auditor_id',$id)->first();
            // $messageRecordTransactions = CbMessageRecordTransaction::where('board_auditor_id',$id)->get();

           $messageRecordTransaction = MessageRecordTransaction::where('board_auditor_id',$id)->where('certificate_type',0)->first();
          $messageRecordTransactions = MessageRecordTransaction::where('board_auditor_id',$id)->where('certificate_type',0)->get();


            return view('certify.cb.auditor_cb.edit', compact('messageRecordTransaction','messageRecordTransactions','cbAuditorTeams','signers','auditorcb','auditors_status','previousUrl','attach_path'));
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

      $cbAuditorTeam = CbAuditorTeam::find($request->cbAuditorTeam);
      $auditorTeamData = json_decode($cbAuditorTeam->auditor_team_json, true);
        // dd($request->cbAuditorTeam );
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('edit-'.$model)) {
     
          try {
          $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
          $requestData = $request->all();
          $requestData['cb_auditor_team_id'] =   $request->cbAuditorTeam ;
          $requestData['status'] =   null ;
          $requestData['step_id'] =  2  ; //ขอความเห็นแต่งคณะผู้ตรวจประเมิน
          $requestData['vehicle'] = isset($request->vehicle) ? 1 : null ;
          $auditors = CertiCBAuditors::findOrFail($id);
          $auditors->update($requestData);
        
         // ไฟล์แนบ
            if ($request->other_attach){
              $this->set_attachs($request->other_attach, $auditors,"1");
            }
            if ($request->attach){
              $this->set_attachs($request->attach, $auditors,"2");
            }

          //วันที่ตรวจประเมิน
          $this->DataCertiCBAuditorsDate($auditors->id,$request);

          // $this->storeStatus($auditors->id,(array)$requestData['list']);
          $this->storeStatusFromCbAuditorTeam($auditors->id,$auditorTeamData);

           //ค่าใช้จ่าย
          $this->storeItems($auditors->id,$request);

          $certi_cb = CertiCb::findOrFail($auditors->app_certi_cb_id);
          if(!is_null($certi_cb->email)){
              if(isset($request->vehicle)){
                  $certi_cb->update(['status'=>10]); // อยู่ระหว่างดำเนินการ 	
                  //Log
                  $this->set_history($auditors,$certi_cb);
                  //E-mail 
                   $this->set_mail($auditors,$certi_cb);
 
              }else{
                   $certi_cb->update(['status'=>10]); //  อยู่ระหว่างดำเนินการ
              }
          }

            if($request->previousUrl){
              return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
            }else{
                return redirect('certify/auditor-cb')->with('flash_message', 'เรียบร้อยแล้ว!');
            }

          } catch (\Exception $e) {
           return redirect('certify/auditor-cb/'.$id.'/edit')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
          }

          
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
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new AuditorCB;
            AuditorCB::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            AuditorCB::destroy($id);
          }

          return redirect('certify/auditor_cb')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('auditorcb','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new AuditorCB;
          AuditorCB::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/auditor_cb')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function set_attachs($attachs, $auditors,$number) {
        $tb = new CertiCBAuditors;
            $certi_cb_attach_more = new CertiCBAttachAll();
            $certi_cb_attach_more->app_certi_cb_id = $auditors->CertiCbCostTo->id ?? null;
            $certi_cb_attach_more->ref_id = $auditors->id;
            $certi_cb_attach_more->table_name = $tb->getTable();
            $certi_cb_attach_more->file_section =  $number;
            $certi_cb_attach_more->file = $this->storeFile($attachs,($auditors->CertiCbCostTo->app_no ?? 'files_cb'));
            $certi_cb_attach_more->file_client_name = HP::ConvertCertifyFileName($attachs->getClientOriginalName());
            $certi_cb_attach_more->token = str_random(16);
            $certi_cb_attach_more->save();
      }

    public function DataCertiNo($id) {

        $app_no =  CertiCb::findOrFail($id);
        if(!is_null($app_no)){
            $cost = CertiCBCost::where('app_certi_cb_id',$app_no->id)->orderby('id','desc')->first();
            if(!is_null($cost)){
                $cost_item = $cost->items;
            }
        }
        $cost_details =  StatusAuditor::orderbyRaw('CONVERT(title USING tis620)')->pluck('title', 'id');
        return response()->json([
           'name'=> !empty($app_no->name) ? $app_no->name : ' ' ,
           'id'=> !empty($app_no->id) ? $app_no->id : ' ' ,
           'cost_item' => isset($cost_item) ? $cost_item : '-',
           'cost_details' => $cost_details
        ]);
    }

    public function update_delete(Request $request, $id)
    {
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('delete-'.$model)) {
            
            try {
                $details  = [];
                $requestData = $request->all();
                $requestData['reason_cancel']   =  $request->reason_cancel ;
                $requestData['status_cancel']   =   1 ;
                $requestData['created_cancel']  =  auth()->user()->runrecno;
                $requestData['date_cancel']     =    date('Y-m-d H:i:s') ;
                $requestData['step_id']         =   12 ; // ยกเลิกแต่งตั้งคณะผู้ตรวจประเมิน
                $auditors = CertiCBAuditors::findOrFail($id);
                $auditors->update($requestData);
    
                $response = [];
                $response['reason_cancel']  =  $auditors->reason_cancel ?? null;
                $response['status_cancel']  =  $auditors->status_cancel ?? null;
                $response['created_cancel'] =  $auditors->created_cancel ?? null;    
                $response['date_cancel']    =  $auditors->date_cancel ?? null;
                $response['step_id']        =  $auditors->step_id ?? null;
 
                if(count($response) > 0){ // update log แต่งตั้งคณะกรรมการ
                  CertiCbHistory::where('ref_id',$id)->where('table_name',(new CertiCBAuditors)->getTable())->update(['details_auditors_cancel' => json_encode($response) ]);
                }

                $CertiCb = CertiCb::findOrFail($auditors->app_certi_cb_id);
                if(!is_null($CertiCb)){

                  $payin_one =  CertiCBPayInOne::where('app_certi_cb_id',$CertiCb->id)->where('app_certi_cb_id',$CertiCb->id)->orderby('id','desc')->first();
                  if(!is_null($payin_one)){ // update log payin
                    // / update   payin
                    CertiCBPayInOne::where('auditors_id',$auditors->id)->update(['status'=>3]);
                    CertiCbHistory::where('ref_id',$payin_one->id)->where('table_name',(new CertiCBPayInOne)->getTable())->update(['details_auditors_cancel' => json_encode($response) ]);
                  }
                 // สถานะ แต่งตั้งคณะกรรมการ
                 $auditor = CertiCBAuditors::where('app_certi_cb_id',$CertiCb->id)
                                            ->whereIn('step_id',[9,10])
                                            ->whereNull('status_cancel')
                                            ->get(); 
                  if(count($auditor) == count($CertiCb->CertiCBAuditorsManyBy)){
                      $report = new   CertiCBReview;  //ทบทวนฯ
                      $report->app_certi_cb_id  = $CertiCb->id;
                      $report->save();
                      $CertiCb->update(['review'=>1,'status'=>11]);  // ทบทวน
                  }
                }
   
                return redirect('certify/auditor-cb')->with('flash_message', 'update ยกเลิกแต่งตั้งคณะผู้ตรวจประเมินเรียบร้อยแล้ว');
            } catch (\Exception $e) {
                return redirect('certify/auditor-cb')->with('flash_message', 'เกิดข้อผิดพลาดในการบันทึก');
            }
        }
        abort(403);
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
                $fullFileName =   str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
  
                $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
                $storageName = basename($storagePath); // Extract the filename
                return  $no.'/'.$storageName;
            }else{
                return null;
            }
        }

        public function auditor_cb_doc_review($id)
        {
   
            $model = str_slug('auditorcb','-');
            if(auth()->user()->can('add-'.$model)) {
          
              $previousUrl = app('url')->previous();
               
                $auditorcb = new CertiCBAuditors;
                $auditors_status = [new CertiCBAuditorsStatus]; 
              if(!empty($request->certicb_id)){
                $auditorcb->app_certi_cb_id = $request->certicb_id;
                $auditorcb->certi_cb_change =  true;
              } 
            
                $app_no = [];
                //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
               if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
                   $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
                   if(count($check) > 0 ){
                       $app_no= CertiCb::whereNotIn('status',[0,4,5])
                                        ->whereIn('id',$check)
                                        ->whereIn('status',[9,10,11])
                                        ->orderby('id','desc')
                                        ->pluck('app_no', 'id');
                    } 
               }else{
                       $app_no = CertiCb::whereNotIn('status',[0,4,5])
                                            ->whereIn('status',[9,10,11])
                                           ->orderby('id','desc')
                                           ->pluck('app_no', 'id');
               }
               $certiCb = CertiCb::find($id);
               
                return view('certify.cb.auditor_cb_doc_review.create',[
                    'certiCb' => $certiCb ,
                    'app_no' => $app_no ,
                    'auditorcb' => $auditorcb,
                    'auditors_status'=> $auditors_status,
                ]);
            }
            abort(403);
    
        }
          
    public function DataCertiCBAuditorsDate($baId, $request) {
      if(isset($request->start_date)){ 
          CertiCBAuditorsDate::where('auditors_id',$baId)->delete();
        /* วันที่ตรวจประเมิน */
        foreach($request->start_date as $key => $itme) {
            $input = [];
            $input['auditors_id'] = $baId;
            $input['start_date'] = HP::convertDate( $itme ,true) ?? null;
            $input['end_date']   = HP::convertDate( $request->end_date[$key]  ,true)?? null;
            CertiCBAuditorsDate::create($input);
          }
       }
     }
     public function storeStatus($baId, $list) {
      if(isset($list['status'])){ 
        CertiCBAuditorsStatus::where('auditors_id',$baId)->delete();
        CertiCBAuditorsList::where('auditors_id',$baId)->delete();
          foreach($list['status'] as $key => $itme) {
            if($itme != null){
                $input = [];
                $input['auditors_id'] = $baId;
                $input['status'] =  $itme;
                $auditors_status =  CertiCBAuditorsStatus::create($input);
                $this->storeList($auditors_status,
                                $list['temp_users'][$auditors_status->status],
                                $list['user_id'][$auditors_status->status],
                                $list['temp_departments'][$auditors_status->status]
                              );
            }
          }
       }
     } 
     public function storeStatusFromCbAuditorTeam($baId, $auditorTeamData) 
     {
        CertiCBAuditorsStatus::where('auditors_id',$baId)->delete();
        CertiCBAuditorsList::where('auditors_id',$baId)->delete();
          foreach($auditorTeamData['status'] as $key => $itme) {
            if($itme != null){
                $input = [];
                $input['auditors_id'] = $baId;
                $input['status'] =  $itme;
                $auditors_status =  CertiCBAuditorsStatus::create($input);
                $this->storeList($auditors_status,
                                $auditorTeamData['temp_users'][$auditors_status->status],
                                $auditorTeamData['user_id'][$auditors_status->status],
                                $auditorTeamData['temp_departments'][$auditors_status->status]
                              );
            }
          }
     } 


     public function saveSignature($request,$baId,$app)
     {
         CbBoardAuditorMsRecordInfo::where('board_auditor_id',$baId)->delete();
         CertiCBAuditors::find($baId)->update([
             'message_record_status' => 1
         ]);
 
         $check = MessageRecordTransaction::where('board_auditor_id',$baId)
         ->where('certificate_type',0)
         ->get();
         if($check->count() == 0){
             $signatures = json_decode($request->input('signaturesJson'), true);
             $viewUrl = url('/certify/auditor/view-cb-message-record/'.$baId);
             if ($signatures) {
                 foreach ($signatures as $signatureId => $signature) {
                     try {
                         // ลองสร้างข้อมูลในฐานข้อมูล
                         MessageRecordTransaction::create([
                             'board_auditor_id' => $baId,
                             'signer_id' => $signature['signer_id'],
                             'certificate_type' => 0,
                             'app_id' => $app->app_no,
                             'view_url' => $viewUrl,
                             'signature_id' => $signature['id'],
                             'is_enable' => false,
                             'show_name' => false,
                             'show_position' => false,
                             'signer_name' => $signature['signer_name'],
                             'signer_position' => $signature['signer_position'],
                             'signer_order' => preg_replace('/[^0-9]/', '', $signatureId),
                             'file_path' => null,
                             'page_no' => 0,
                             'pos_x' => 0,
                             'pos_y' => 0,
                             'linesapce' => 20,
                             'approval' => 0,
                         ]);
                     } catch (\Exception $e) {
                         // จัดการข้อผิดพลาดหากล้มเหลว
                         echo "เกิดข้อผิดพลาด: " . $e->getMessage();
                     }
                 } 
             }
         }else{
            MessageRecordTransaction::where('board_auditor_id',$baId)->where('certificate_type',0)->update([
                 'approval' => 0
             ]);
         }
      
        //  $board  =  BoardAuditor::findOrFail($baId);
        //  $this->sendMailToExaminer($board,$board->CertiLabs); 
     }



     public function storeList($status,$temp_users,$user_id,$temp_departments) {
        foreach($temp_users as $key => $itme) {
          if($itme != null){
              $input = [];
              $input['auditors_status_id'] = $status->id;
              $input['auditors_id'] = $status->auditors_id;
              $input['status'] = $status->status;
              $input['temp_users'] =  $itme;
              $input['user_id'] =   $user_id[$key] ?? null;
              $input['temp_departments'] =  $temp_departments[$key] ?? null;
              CertiCBAuditorsList::create($input);
          }
        }
     }
  
    public function storeItems($baId, $items) {
        if(isset($items['detail'])){
            CertiCBAuditorsCost::where('auditors_id',$baId)->delete();    
            $detail = (array)@$items['detail'];
          foreach($detail['detail'] as $key => $data ) {
              $item = new CertiCBAuditorsCost;
              $item->auditors_id = $baId;
              $item->detail = $data ?? null;
              $item->amount_date = $detail['amount_date'][$key] ?? 0;
              $item->amount =  !empty(str_replace(",","", $detail['amount'][$key]))?str_replace(",","",$detail['amount'][$key]):null; 
              $item->save();
          }
        }
  }
  
      public function set_history($data,$certi_cb = null) {
              $tb = new CertiCBAuditors;
          $auditors = CertiCBAuditors::select('app_certi_cb_id', 'no','auditor')
                        ->where('id',$data->id)
                        ->first();
        
          $auditors_date = CertiCBAuditorsDate::select('start_date','end_date')
                                        ->where('auditors_id',$data->id)
                                        ->get()
                                        ->toArray();
          $auditors_list = CertiCBAuditorsList::select('status','temp_users','user_id','temp_departments')
                                        ->where('auditors_id',$data->id)
                                        ->get()
                                        ->toArray();
          $auditors_cost = CertiCBAuditorsCost::select('detail','amount_date','amount')
                                        ->where('auditors_id',$data->id)
                                        ->get()
                                        ->toArray();
  
         CertiCbHistory::create([ 
                                      'app_certi_cb_id'   => $certi_cb->id ?? null,
                                      'auditors_id'       =>  $data->id ?? null,
                                      'system'            => 5,
                                      'table_name'        => $tb->getTable(),
                                      'ref_id'            => $data->id,
                                      'details_one'       =>  json_encode($auditors) ?? null,
                                      'details_two'       =>  (count($auditors_date) > 0) ? json_encode($auditors_date) : null,
                                      'details_three'     =>  (count($auditors_list) > 0) ? json_encode($auditors_list) : null,
                                      'details_four'      =>  (count($auditors_cost) > 0) ? json_encode($auditors_cost) : null,
                                      'file'              => !empty($data->FileAuditors1->file) ?  $data->FileAuditors1->file  : null,
                                      'file_client_name'  => !empty($data->FileAuditors1->file_client_name) ?  $data->FileAuditors1->file_client_name  : null,
                                      'attachs'           => !empty($data->FileAuditors2->file) ? $data->FileAuditors2->file : null,
                                      'attach_client_name'=> !empty($data->FileAuditors2->file_client_name) ?  $data->FileAuditors2->file_client_name  : null,
                                      'created_by'        =>  auth()->user()->runrecno
                               ]);

      }
      
      public function set_mail($auditors,$certi_cb) {
        if(!is_null($certi_cb->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

            if(!empty($certi_cb->DataEmailDirectorCBCC)){
                $mail_cc = $certi_cb->DataEmailDirectorCBCC;
                array_push($mail_cc, auth()->user()->reg_email) ;
            }

            $data_app = [
                          'title'          =>  'แต่งตั้งคณะผู้ตรวจประเมิน (CB)',
                          'auditors'       => $auditors,
                          'certi_cb'       => $certi_cb ,
                          'url'            => $url.'certify/applicant-cb' ?? '-',
                          'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                          'email_cc'       =>  !empty($mail_cc) ? $mail_cc : 'cb@tisi.mail.go.th',
                          'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
                    ];

            $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                                    $certi_cb->id,
                                                    (new CertiCb)->getTable(),
                                                    $auditors->id,
                                                    (new CertiCBAuditors)->getTable(),
                                                    3,
                                                    'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                    view('mail.CB.auditors', $data_app),
                                                    $certi_cb->created_by,
                                                    $certi_cb->agent_id,
                                                    auth()->user()->getKey(),
                                                    !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                                    $certi_cb->email,
                                                    !empty($mail_cc) ?  implode(',',(array)$mail_cc)  : 'cb@tisi.mail.go.th',
                                                    !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                                    null
                                                    );

            $html = new CBAuditorsMail($data_app);
            $mail =  Mail::to($certi_cb->email)->send($html);

            if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
            } 
        }
   }

   public function bypass_doc_auditor_assignment(Request $request)
   {
      CertiCb::find($request->certiCbId)->update([
        'doc_auditor_assignment' => 2
      ]);
   }

   public function auditor_cb_doc_review_store(Request $request)
  {
      // ตรวจสอบความถูกต้องของข้อมูลที่ได้รับ (Validation)
      $request->validate([
          'cb_id' => 'required|string',
          'cb_name' => 'required|string',
          'auditor' => 'required|string',
          'start_date' => 'required|array',
          'end_date' => 'required|array',
          'assessment_type' => 'required|string',
          'list' => 'required|array',
      ]);

      // จัดการค่าของ auditors (แปลง list เป็น JSON)
      $auditors = [];
      if (isset($request->list['status'])) {
          foreach ($request->list['status'] as $index => $status) {
              $auditors[] = [
                  'status' => $status,
                  'user_id' => $request->list['user_id'][$index] ?? [],
                  'temp_users' => $request->list['temp_users'][$index] ?? [],
                  'temp_departments' => $request->list['temp_departments'][$index] ?? [],
              ];
          }
      }

      // dd($request->list,$auditors);

      // อัปโหลดไฟล์ถ้ามี
      $filePath = null;
      $fileName = null;

      if ($request->hasFile('attach')) {
          $file = $request->file('attach');
          $filePath = $this->storeFile($file,'doc_review_file_cb');
          $fileName = basename($filePath);
      }

      $from_date = isset($request->start_date[0]) ? $this->convertThaiYearToAD($request->start_date[0]) : null;
      $to_date = isset($request->end_date[0]) ? $this->convertThaiYearToAD($request->end_date[0]) : null;
  
      // บันทึกข้อมูลลงในฐานข้อมูล
      $cbDocReviewAuditor = CbDocReviewAuditor::create([
          'app_certi_cb_id' => $request->cb_id,
          'team_name' => $request->auditor,
          'from_date' => $from_date,
          'to_date' => $to_date,
          'type' => $request->assessment_type,
          'file' => $filePath,
          'filename' => $fileName,
          'auditors' => json_encode($auditors, JSON_UNESCAPED_UNICODE),
          'status' => '0', 
      ]);

      // CertiCb::find($request->certiCbId)->update([
      //   'doc_auditor_assignment' => 2,
      //   'doc_review_reject' => null,
      //   'doc_review_reject_message' => null,
      // ]);

      $certiCb = CertiCb::find($request->cb_id);
      if($request->assessment_type == '1')
      {
        $this->sendMailAuditorDocReview($certiCb,$cbDocReviewAuditor);
      }

    return redirect()->to('/certify/check_certificate-cb/' . $certiCb->token . '/show/' . $certiCb->id);

  }

  // ฟังก์ชันแปลงวันที่จาก พ.ศ. → ค.ศ.
private function convertThaiYearToAD($thaiDate)
{
    // แปลงวันที่จาก "08/02/2568" → "08/02/2025"
    $dateParts = explode('/', $thaiDate);
    if (count($dateParts) == 3) {
        $year = (int)$dateParts[2] - 543; // แปลง พ.ศ. → ค.ศ.
        return $year . '-' . $dateParts[1] . '-' . $dateParts[0]; // YYYY-MM-DD
    }
    return null;
}

public function sendMailAuditorDocReview($certi_cb,$cbDocReviewAuditor)
{
  if(!is_null($certi_cb->email))
  {

      $config = HP::getConfig();
      $url  =   !empty($config->url_acc) ? $config->url_acc : url('');

      if(!empty($certi_cb->DataEmailDirectorCBCC)){
          $mail_cc = $certi_cb->DataEmailDirectorCBCC;
          array_push($mail_cc, auth()->user()->reg_email) ;
      }
      $auditors = json_decode($cbDocReviewAuditor->auditors, true);

      $data_app = [
                    'title'          =>  'แต่งตั้งคณะผู้ตรวจประเมินเอกสาร',
                    'cbDocReviewAuditor'       => $cbDocReviewAuditor,
                    'auditors'       => $auditors,
                    'certi_cb'       => $certi_cb ,
                    'url'            => $url.'certify/applicant-cb' ?? '-',
                    'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                    'email_cc'       =>  !empty($mail_cc) ? $mail_cc : 'cb@tisi.mail.go.th',
                    'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
              ];

      $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                              $certi_cb->id,
                                              (new CertiCb)->getTable(),
                                              $certi_cb->id,
                                              (new CbDocReviewAuditor)->getTable(),
                                              $cbDocReviewAuditor->id,
                                              'แต่งตั้งคณะผู้ตรวจประเมินเอกสาร',
                                              view('mail.CB.auditor_doc_review', $data_app),
                                              $certi_cb->created_by,
                                              $certi_cb->agent_id,
                                              auth()->user()->getKey(),
                                              !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                              $certi_cb->email,
                                              !empty($mail_cc) ?  implode(',',(array)$mail_cc)  : 'cb@tisi.mail.go.th',
                                              !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                              null
                                              );

      $html = new CbDocReviewAuditorsMail($data_app);
      $mail =  Mail::to($certi_cb->email)->send($html);

      if(is_null($mail) && !empty($log_email)){
          HP::getUpdateCertifyLogEmail($log_email->id);
      } 
  }
}

  public function auditor_cb_doc_review_edit ($id)
  {
    $cbDocReviewAuditor = CbDocReviewAuditor::where('app_certi_cb_id',$id)->first();
    // dd($cbDocReviewAuditor);

    
    $model = str_slug('auditorcb','-');
    if(auth()->user()->can('add-'.$model)) {
  
      $previousUrl = app('url')->previous();
       
        // $auditorcb = new CertiCBAuditors;
        // $auditors_status = [new CertiCBAuditorsStatus]; 
      // if(!empty($request->certicb_id)){
      //   $auditorcb->app_certi_cb_id = $request->certicb_id;
      //   $auditorcb->certi_cb_change =  true;
      // } 
    
        $app_no = [];
        //เจ้าหน้าที่ CB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
       if(in_array("29",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){ 
           $check = CertiCBCheck::where('user_id',auth()->user()->runrecno)->pluck('app_certi_cb_id'); // เช็คเจ้าหน้าที่ IB
           if(count($check) > 0 ){
               $app_no= CertiCb::whereNotIn('status',[0,4,5])
                                ->whereIn('id',$check)
                                ->whereIn('status',[9,10,11])
                                ->orderby('id','desc')
                                ->pluck('app_no', 'id');
            } 
       }else{
               $app_no = CertiCb::whereNotIn('status',[0,4,5])
                                    ->whereIn('status',[9,10,11])
                                   ->orderby('id','desc')
                                   ->pluck('app_no', 'id');
       }
       $certiCb = CertiCb::find($id);
       
        return view('certify.cb.auditor_cb_doc_review.edit',[
            'certiCb' => $certiCb ,
            'app_no' => $app_no ,
            'cbDocReviewAuditor' => $cbDocReviewAuditor ,
            'doc_review_auditors' => json_decode($cbDocReviewAuditor->auditors, true),
            // 'auditorcb' => $auditorcb,
            // 'auditors_status'=> $auditors_status,
        ]);
    }
    abort(403);
  }

  public function cancel_doc_review_team(Request $request)
  {
    CbDocReviewAuditor::where('app_certi_cb_id',$request->certiCbId)->delete();
  }

  public function reject_doc_review(Request $request)
  {
    CertiCb::find($request->certiCbId)->update([
      'doc_review_reject' => 1,
      'doc_review_reject_message' => $request->rejectText,
    ]);
  }

  public function accept_doc_review(Request $request)
  {
    CertiCb::find($request->certiCbId)->update([
      'doc_auditor_assignment' => 2,
      'doc_review_reject' => null,
      'doc_review_reject_message' => null,
    ]);
  }

  public function CreateCbMessageRecord($id)
  {
    
      // สำหรับ admin และเจ้าหน้าที่ lab
      if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
          abort(403);
      }

      $boardAuditor = CertiCBAuditors::find($id);

      $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

      $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล


      $uniqueAuditorIds = array_unique($auditorIds);

      $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();

      $certi_cb = CertiCb::find($boardAuditor->app_certi_cb_id);

   

      $boardAuditorDate = CertiCBAuditorsDate::where('auditors_id',$id)->first();
      $dateRange = "";

      

      if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
          if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
              // ถ้าเป็นวันเดียวกัน
              $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
          } else {
              // ถ้าเป็นคนละวัน
              $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                          " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
          }
      }
      


      $data = new stdClass();

      $data->header_text1 = '';
      $data->header_text2 = '';
      $data->header_text3 = '';
      $data->header_text4 = $certi_cb->app_no;
      $data->lab_type = $certi_cb->lab_type == 3 ? 'ทดสอบ' : ($certi_cb->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
      $data->name_standard = $certi_cb->name_standard;
      $data->app_no =  $certi_cb->app_no;
      $data->certificate_no = '13-LB0037';
      $data->register_date = HP::formatDateThaiFullNumThai($certi_cb->created_at);
      $data->get_date = HP::formatDateThaiFullNumThai($certi_cb->get_date);

      $data->date_range = $dateRange;
      $data->statusAuditorMap = $statusAuditorMap;
      $data->fix_text1 = <<<HTML
                  <div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
                  <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรองตามวรรคหนึ่ง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
                  <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข วันที่ ๔ มีนาคม ๒๕๕๑ การรับรองหน่วยรับรองระบบงาน (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div>
                  <div style="text-indent:150px">ข้อ ๖.๑.๒.๑ (๑) ระบุว่า "แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าคณะผู้ตรวจ ประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม"</div>
                  <div style="text-indent:150px">และข้อ ๖.๑.๒.๑ (๑) "คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารต่างๆ ของหน่วยตรวจ ตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานของหน่วยตรวจ โดยพิจารณาหลักฐานและเอกสารที่เกี่ยวข้อง การสัมภาษณ์รวมทั้งการสังเกตการปฎิบัติตามมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง"</div>
                  <div style="text-indent:125px">๒.๓ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม (สั่ง ณ วันที่ ๑๓ พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุว่า "ให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑" </div>
              HTML;

      $data->fix_text2 = <<<HTML
                  <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div>
                  <div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองหน่วยตรวจ พ.ศ.๒๕๖๔ สำนักงานจะตรวจติดตามผลรับรองหน่วยตรวจอย่างน้อย ๑ ครั้ง ภายใน ๒ ปี โดยแต่ละครั้งอาจจะตรวจประเมินเพียงบางส่วนหรือทุกข้อกำหนดก็ได้ตามความเหมาะสม และก่อนครบการรับรอง ๕ ปี ต้องตรวจประเมินให้ครบทุกข้อกำหนด</div>
              HTML;
      

      return view('certify.cb.auditor_cb.initial-message-record', [
          'data' => $data,
          'id' => $id,
          'certi_cb' => $certi_cb,
          'boardAuditor' => $boardAuditor,
      ]);
  }

  public function SaveCbMessageRecord(Request $request)
  {
     // สร้างและบันทึกข้อมูลโดยตรง
     $record = new CbBoardAuditorMsRecordInfo([
        'board_auditor_id' => $request->id,
        'header_text1' => $request->header_text1,
        'header_text2' => $request->header_text2,
        'header_text3' => $request->header_text3,
        'header_text4' => $request->header_text4,
        'body_text1'   => $request->body_text1,
        'body_text2'   => $request->body_text2,
    ]);


    // บันทึกลงฐานข้อมูล
    $record->save();

    CertiCBAuditors::find($request->id)->update([
        'message_record_status' => 2
    ]);
    $auditor = CertiCBAuditors::find($request->id);

    return response()->json([
      'auditor'=> $auditor
    ]);
  }

  public function viewCbMessageRecord($id)
  {

      $boardAuditor = CertiCBAuditors::find($id);
      $boardAuditorMsRecordInfo = $boardAuditor->cbBoardAuditorMsRecordInfos->first();

      $auditorIds = []; // สร้าง array ว่างเพื่อเก็บ auditor_id

      $statusAuditorMap = []; // สร้าง array ว่างสำหรับเก็บข้อมูล


      $uniqueAuditorIds = array_unique($auditorIds);

      $auditorInformations = AuditorInformation::whereIn('id',$uniqueAuditorIds)->get();

      $certi_cb = CertiCb::find($boardAuditor->app_certi_cb_id);


      
      $boardAuditorDate = CertiCBAuditorsDate::where('auditors_id',$id)->first();
      $dateRange = "";

      

      if (!empty($boardAuditorDate->start_date) && !empty($boardAuditorDate->end_date)) {
          if ($boardAuditorDate->start_date == $boardAuditorDate->end_date) {
              // ถ้าเป็นวันเดียวกัน
              $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date);
          } else {
              // ถ้าเป็นคนละวัน
              $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->start_date) . 
                          " ถึงวันที่ " . HP::formatDateThaiFullNumThai($boardAuditorDate->end_date);
          }
      }
      

    $data = new stdClass();


    $data->header_text1 = '';
    $data->header_text2 = '';
    $data->header_text3 = '';
    $data->header_text4 = $certi_cb->app_no;
    $data->name_standard = $certi_cb->name_standard;
    $data->app_no = $certi_cb->app_no;
    $data->certificate_no = '13-LB0037';
    $data->register_date = HP::formatDateThaiFullNumThai($certi_cb->created_at);
    $data->get_date = HP::formatDateThaiFullNumThai($certi_cb->get_date);

    $data->date_range = $dateRange;
    $data->statusAuditorMap = $statusAuditorMap;



      $htmlLabMemorandumRequest = HtmlCbMemorandumRequest::where('type',"ia")->first();

      $data->fix_text1 = <<<HTML
             $htmlLabMemorandumRequest->text1
          HTML;

      $data->fix_text2 = <<<HTML
             $htmlLabMemorandumRequest->text2
          HTML;



      return view('certify.cb.auditor_cb.view-message-record', [
          'data' => $data,
          'id' => $id,
          'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo,
          'boardAuditor' =>  $boardAuditor
      ]);
  }

  
}
