<?php

namespace App\Http\Controllers\Certificate\Cb;

use DB;
use HP;
use stdClass;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;
use App\Mail\Tracking\AuditorsMail;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Facades\Storage;
use App\Mail\CB\CbDocReviewAuditorsMail;
use App\Models\Certificate\TrackingHistory;
use App\Models\Certify\ApplicantIB\CertiIb;
use App\Models\Certificate\TrackingAuditors;
use App\Mail\CB\TrackingDocReviewAuditorsMail;
use App\Models\Certificate\CbDocReviewAuditor;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingAuditorsList; 
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certificate\TrackingAuditorsStatus; 
use App\Models\Certify\ApplicantCB\CertiCBAuditors;
use App\Models\Certificate\TrackingDocReviewAuditor;
use App\Models\Certify\MessageRecordTrackingTransaction;
use App\Models\Bcertify\BoardAuditorTrackingMsRecordInfo;
use App\Models\Certify\ApplicantCB\CertiCBAuditorsStatus;

class AuditorCbController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/trackingcb';
    }

    public function index(Request $request)
    { 
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.cb.auditor-cb.index' );
        }
        abort(403);
    } 


    public function data_list(Request $request)
    { 
     
      $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
 
      $model = str_slug('auditorcb', '-');
      $filter_search = $request->input('filter_search');
      $filter_certificate_no = $request->input('filter_certificate_no');
      $filter_status_id = $request->input('filter_status_id');
      $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
      $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
      $query = TrackingAuditors::query()
                                         ->where('certificate_type',1) ->where('ref_table',(new CertiCBExport)->getTable())
                                          ->when($filter_search, function ($query, $filter_search){
                                              $search_full = str_replace(' ', '', $filter_search ); 
                                              return  $query->Where(DB::raw("REPLACE(reference_refno,' ','')"), 'LIKE', "%".$search_full."%")
                                                      ->OrWhere(DB::raw("REPLACE(auditor,' ','')"), 'LIKE', "%".$search_full."%")
                                                      ->OrWhere(DB::raw("REPLACE(no,' ','')"), 'LIKE', "%".$search_full."%")  ;
                                           }) 
                                           ->when($filter_certificate_no, function ($query, $filter_certificate_no){
                                              return  $query->where('id', $filter_certificate_no);
                                            })
                                            ->when($filter_status_id, function ($query, $filter_status_id){
                                              if($filter_status_id == '-1'){
                                                 return  $query->whereNull('status');
                                              }else{
                                                return  $query->where('status', $filter_status_id);
                                              }
                                             
                                            })
                                            ->when($filter_start_date, function ($query, $filter_start_date) use($filter_end_date){
                                              if(!is_null($filter_start_date) && !is_null($filter_end_date) ){
                                                  return  $query->whereBetween('created_at',[$filter_start_date,$filter_end_date]);
                                              }else if(!is_null($filter_start_date) && is_null($filter_end_date)){
                                                   return  $query->whereDate('created_at',$filter_start_date);
                                              }
                                          }) ; 
                                  
                                                  
      return Datatables::of($query)
                          ->addIndexColumn()
                          ->addColumn('checkbox', function ($item) {
                              return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                          })
                          ->addColumn('reference_refno', function ($item) {
                              $status_name = '';
                              if(!empty($item->status_cancel) && $item->status_cancel  == '1'){
                                $status_name = '<br><span class="text-danger"">ยกเลิกคณะผู้ตรวจ</span>';
                              }
                              return   !empty($item->reference_refno)? $item->reference_refno. $status_name:'';
                          }) 
                          ->addColumn('auditor', function ($item) {
                              return   !empty($item->auditor)? $item->auditor:'';
                          })
                          ->addColumn('status', function ($item) {
                              return   !empty($item->StatusTitle)? $item->StatusTitle:'';
                          }) 
                          ->addColumn('date_title', function ($item) {
                              return   !empty($item->CertiAuditorsDateTitle)? $item->CertiAuditorsDateTitle:'';
                          }) 
                          ->addColumn('created_at', function ($item) {
                              return   !empty($item->created_at) ?HP::DateThai($item->created_at):'-';
                          })
                          ->addColumn('full_name', function ($item) {
                            return   !empty($item->user_created->FullName) ? $item->user_created->FullName :'-';
                          })
                          ->addColumn('action', function ($item) use($model) {
                                  return HP::buttonAction( $item->id, 'certificate/auditor-cbs','Certificate\Cb\\AuditorCbController@destroy', 'auditorcb',false,true,false);
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns([ 'checkbox', 'date_title',  'action', 'reference_refno']) 
                          ->make(true);
    } 


    

    public function create(Request $request)
    {
      // dd("ok");
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('add-'.$model)) {
          $previousUrl = app('url')->previous();
          if(!empty($request->refno)){
             $tracking =   Tracking::where('reference_refno', $request->refno)->first();
             $tracking->name          =  !empty($tracking->certificate_export_to->CertiCbTo->name)? $tracking->certificate_export_to->CertiCbTo->name:'';
             $tracking->name_standard =  !empty($tracking->certificate_export_to->CertiCbTo->name_standard)? $tracking->certificate_export_to->CertiCbTo->name_standard:'';
             $tracking->tracking_id   =  !empty($tracking->id)? $tracking->id:'';
          }else{ 
             $tracking = '';
          }
    $signers = Signer::all();
   
          $auditors_status = [new TrackingAuditorsStatus];
          return view('certificate.cb.auditor-cb.create', compact('tracking','auditors_status','signers'));
        }
        abort(403);

    }

    public function store(Request $request)
    {
 
      // dd('ok');
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('add-'.$model)) {
  
          // try {
                $tracking =  Tracking::findOrFail($request->tracking_id);
 
                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();
                $requestData['certificate_type']  =   1 ;
                $requestData['reference_refno']   =   $tracking->reference_refno ?? null ;
                $requestData['ref_table']         =   (new CertiCBExport)->getTable() ;
                $requestData['ref_id']            =   $tracking->ref_id ?? null ;
                $requestData['tracking_id']       =   $tracking->id ?? null ;
                $requestData['status']            =   null ;
                $requestData['step_id'] =  2  ;//ขอความเห็นแต่งคณะผู้ตรวจประเมิน
                $requestData['vehicle'] = isset($request->vehicle) ? 1 : null ;
                 $auditors =  TrackingAuditors::create($requestData);
              // ไฟล์แนบ
                  if ($request->other_attach){
                      if ($request->hasFile('other_attach')) {
                        HP::singleFileUploadRefno(
                            $request->file('other_attach') ,
                            $this->attach_path.'/'.$auditors->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAuditors)->getTable() ),
                            $auditors->id,
                            'other_attach',
                            null
                        );
                    }
                  }
                  if ($request->attach){
                    if ($request->hasFile('attach')) {
                      HP::singleFileUploadRefno(
                          $request->file('attach') ,
                          $this->attach_path.'/'.$auditors->reference_refno,
                          ( $tax_number),
                          (auth()->user()->FullName ?? null),
                          'Center',
                          (  (new TrackingAuditors)->getTable() ),
                          $auditors->id,
                          'attach',
                          null
                      );
                  }
                    
                  }

                  //วันที่ตรวจประเมิน
                  self::DataAuditorsDate($auditors->id,$request);

        
                  self::storeStatus($auditors->id,(array)$requestData['list']);
                  $this->saveSignature($request,$auditors->id,$tracking->ref_id);
 
                if(!is_null($tracking)){
                    if(isset($request->vehicle)){

                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                        // Log
                        self::set_history($auditors,4);
                        //E-mail 
                         self::set_mail($auditors);
              
                    }else{

                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();

                         self::set_history($auditors,3);
                    }
                }
                
            
                if($request->previousUrl){
                  return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
                }else{
                    return redirect('certificate/auditor-cbs')->with('flash_message', 'เรียบร้อยแล้ว!');
                }
          // } catch (\Exception $e) {
          //        return redirect('certificate/auditor-cbs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
          // }

        }
        abort(403);
    }


        public function saveSignature($request,$boardTrackingAutitorId,$certilabExportId)
    {
     
      // dd($request->all());
        TrackingAuditors::find($boardTrackingAutitorId)->update([
          'message_record_status' => 1
        ]);
        $check = MessageRecordTrackingTransaction::where('ba_tracking_id',$boardTrackingAutitorId)
        ->where('certificate_type',1)
        ->get();
        
        if($check->count() == 0){
            $signatures = json_decode($request->input('signaturesJson'), true);
            //  dd($signatures);
            $viewUrl = url('/certificate/auditor_cb_doc_review/view-cb-tracking-message-record/'.$boardTrackingAutitorId);
            if ($signatures) {
                foreach ($signatures as $signatureId => $signature) {
                    // dd($signature);
                    try {
                        // ลองสร้างข้อมูลในฐานข้อมูล
                        MessageRecordTrackingTransaction::create([
                            'ba_tracking_id' => $boardTrackingAutitorId,
                            'signer_id' => $signature['signer_id'],
                            'certificate_type' => 1,
                            'certificate_export_id' => $certilabExportId,
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
                    
                        // แสดงข้อความหากสำเร็จ
                        echo "บันทึกข้อมูลสำเร็จ";
                    } catch (\Exception $e) {
                        // จัดการข้อผิดพลาดหากล้มเหลว
                        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
                        // dd( $e->getMessage());
                    }
                    
                } 
            }
        }else{
          // dd('sss');
          MessageRecordTrackingTransaction::where('ba_tracking_id',$boardTrackingAutitorId)
          ->where('certificate_type',1)
          ->update([
                'approval' => 0
            ]);
        }
     
        $board  =  TrackingAuditors::findOrFail($boardTrackingAutitorId);
        // $this->sendMailToExaminer($board,$board->CertiLabs); 
    }

    public function edit(Request $request,$id)
    {
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('edit-'.$model)) {
          $previousUrl = app('url')->previous();
          $auditor =  TrackingAuditors::findOrFail($id);
          if(!empty($auditor->tracking_id)){
            $tracking                =   Tracking::where('id', $auditor->tracking_id)->first();
            $tracking->name          =  !empty($tracking->certificate_export_to->CertiCbTo->name)? $tracking->certificate_export_to->CertiCbTo->name:'';
            $tracking->name_standard =  !empty($tracking->certificate_export_to->CertiCbTo->name_standard)? $tracking->certificate_export_to->CertiCbTo->name_standard:'';
          }else{
            $tracking = '';
          }
 
             $auditors_status =   TrackingAuditorsStatus::where('auditors_id', $id)->get();  
          if(count($auditors_status) == 0){
            $auditors_status = [new TrackingAuditorsStatus];
          }
$signers = Signer::all();
          return view('certificate.cb.auditor-cb.edit', compact('auditor','tracking','auditors_status','signers'));
        }
        abort(403);

    }

    // public function auditor_cb_doc_review_edit($id)
    // {
    //   // dd('fuck');
    //     $model = str_slug('auditorcb','-');
    //     if(auth()->user()->can('edit-'.$model)) {
    //       $previousUrl = app('url')->previous();
    //       $auditor =  TrackingAuditors::findOrFail($id);
    //       if(!empty($auditor->tracking_id)){
    //         $tracking                =   Tracking::where('id', $auditor->tracking_id)->first();
    //         $tracking->name          =  !empty($tracking->certificate_export_to->CertiCbTo->name)? $tracking->certificate_export_to->CertiCbTo->name:'';
    //         $tracking->name_standard =  !empty($tracking->certificate_export_to->CertiCbTo->name_standard)? $tracking->certificate_export_to->CertiCbTo->name_standard:'';
    //       }else{
    //         $tracking = '';
    //       }
 
    //          $auditors_status =   TrackingAuditorsStatus::where('auditors_id', $id)->get();  
    //       if(count($auditors_status) == 0){
    //         $auditors_status = [new TrackingAuditorsStatus];
    //       }

    //       return view('certificate.cb.auditor-cb.edit', compact('auditor','tracking','auditors_status'));
    //     }
    //     abort(403);

    // }



    

    public function update(Request $request,$id)
    {
      //  dd('update');
        $model = str_slug('auditorcb','-');
        if(auth()->user()->can('add-'.$model)) {
  
          // try {

                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                $request->request->add(['updated_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();
                $requestData['status'] =   null ;
                $requestData['step_id'] =  2  ;//ขอความเห็นแต่งคณะผู้ตรวจประเมิน
                $requestData['vehicle'] = isset($request->vehicle) ? 1 : null ;
                $auditors =  TrackingAuditors::findOrFail($id);
                $auditors->update($requestData);
              // ไฟล์แนบ
                  if ($request->other_attach){
                      if ($request->hasFile('other_attach')) {
                        HP::singleFileUploadRefno(
                            $request->file('other_attach') ,
                            $this->attach_path.'/'.$auditors->reference_refno,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Center',
                            (  (new TrackingAuditors)->getTable() ),
                            $auditors->id,
                            'other_attach',
                            null
                        );
                    }
                  }
                  if ($request->attach){
                    if ($request->hasFile('attach')) {
                      HP::singleFileUploadRefno(
                          $request->file('attach') ,
                          $this->attach_path.'/'.$auditors->reference_refno,
                          ( $tax_number),
                          (auth()->user()->FullName ?? null),
                          'Center',
                          (  (new TrackingAuditors)->getTable() ),
                          $auditors->id,
                          'attach',
                          null
                      );
                  }
                    
                  }

                  //วันที่ตรวจประเมิน
                  self::DataAuditorsDate($auditors->id,$request);

                  self::storeStatus($auditors->id,(array)$requestData['list']);
                  

                    $tracking = Tracking::find($auditors->tracking_id);
                if(!is_null($tracking)){
                    if(isset($request->vehicle)){
                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                        // Log
                        self::set_history($auditors,4);
                        //E-mail 
                         self::set_mail($auditors);
              
                    }else{
                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                         self::set_history($auditors,3);
                    }
                }
                
            
                if($request->previousUrl){
                  return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
                }else{
                    return redirect('certificate/auditor-cbs')->with('flash_message', 'เรียบร้อยแล้ว!');
                }
          // } catch (\Exception $e) {
          //        return redirect('certificate/auditor-cbs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
          // }

        }
        abort(403);
    }




    public function DataAuditorsDate($baId, $request) {
        if(isset($request->start_date)){ 
          TrackingAuditorsDate::where('auditors_id',$baId)->delete();
          /* วันที่ตรวจประเมิน */
          foreach($request->start_date as $key => $itme) {
              $input = [];
              $input['auditors_id'] = $baId;
              $input['start_date'] =  HP::convertDate($itme,true);
              $input['end_date'] =  HP::convertDate($request->end_date[$key],true);   
              TrackingAuditorsDate::create($input);
            }
         }
       }
       public function storeStatus($baId, $list) {
        if(isset($list['status'])){ 
          TrackingAuditorsStatus::where('auditors_id',$baId)->delete();
          TrackingAuditorsList::where('auditors_id',$baId)->delete();
            foreach($list['status'] as $key => $itme) {
              if($itme != null){
                  $input = [];
                  $input['auditors_id'] = $baId;
                  $input['status_id']   =  $itme;
                  $input['amount_date'] = $list['amount_date'][$key] ?? 0;
                  $input['amount']      =  !empty(str_replace(",","", $list['amount'][$key]))?str_replace(",","",$list['amount'][$key]):null; 
                  $auditors_status      =  TrackingAuditorsStatus::create($input);
                   self::storeList($auditors_status,
                                  $list['temp_users'][$key],
                                  $list['user_id'][$key],
                                  $list['temp_departments'][$key]
                );
                  // self::storeList($auditors_status,
                  //                 $list['temp_users'][$auditors_status->status_id],
                  //                 $list['user_id'][$auditors_status->status_id],
                  //                 $list['temp_departments'][$auditors_status->status_id]
                  //               );
              }
            }
         }
       } 
       public function storeList($status,$temp_users,$user_id,$temp_departments) {
          foreach($temp_users as $key => $itme) {
            if($itme != null){
                $input = [];
                $input['auditors_status_id'] = $status->id;
                $input['auditors_id'] = $status->auditors_id;
                $input['status_id']   = $status->status_id;
                $input['temp_users']  =  $itme;
                $input['user_id']    =   $user_id[$key] ?? null;
                $input['temp_departments'] =  $temp_departments[$key] ?? null;
                TrackingAuditorsList::create($input);
            }
          }
       }
          public function set_history($data ,$system) {
    
          $auditors = TrackingAuditors::select( 'no','auditor')
                        ->where('id',$data->id)
                        ->first();
        
          $auditors_date = TrackingAuditorsDate::select('start_date','end_date')
                                        ->where('auditors_id',$data->id)
                                        ->get()
                                        ->toArray();
          $auditors_list = TrackingAuditorsList::select('auditors_status_id','temp_users','user_id','temp_departments' ,'status_id')
                                        ->where('auditors_id',$data->id)
                                        ->get()
                                        ->toArray();
          $auditors_status = TrackingAuditorsStatus::select('status_id','amount_date','amount')
                                        ->where('auditors_id',$data->id)
                                        ->get() ->toArray();
                                        
         $file = [];
         if( !empty($data->FileAuditors1->url)){
          $file['url'] =  $data->FileAuditors1->url;
         }
         if( !empty($data->FileAuditors1->new_filename)){
             $file['new_filename'] =  $data->FileAuditors1->new_filename;
         }
         if( !empty($data->FileAuditors1->filename)){
             $file['filename'] =  $data->FileAuditors1->filename;
         }

         $attachs = [];
         if( !empty($data->FileAuditors2->url)){
            $attachs['url'] =  $data->FileAuditors2->url;
         }
         if( !empty($data->FileAuditors2->new_filename)){
             $attachs['new_filename'] =  $data->FileAuditors2->new_filename;
         }
         if( !empty($data->FileAuditors2->filename)){
             $attachs['filename'] =  $data->FileAuditors2->filename;
         }
         TrackingHistory::create([ 
            
                                      'certificate_type'  => 1,
                                      'tracking_id'       =>  $data->tracking_id ?? null,
                                      'reference_refno'   => $data->reference_refno ?? null,
                                      'ref_table'         =>  (new CertiCBExport)->getTable() ,
                                      'ref_id'            =>  $data->ref_id ?? null,
                                      'auditors_id'       =>  $data->id ?? null,
                                      'system'            => $system,
                                      'table_name'        => (new TrackingAuditors)->getTable() ,
                                      'refid'             => $data->id,
                                      'details_one'       =>  json_encode($auditors) ?? null,
                                      'details_two'       =>  (count($auditors_date) > 0) ? json_encode($auditors_date) : null,
                                      'details_three'     =>  (count($auditors_list) > 0) ? json_encode($auditors_list) : null,
                                      'details_four'      =>  (count($auditors_status) > 0) ? json_encode($auditors_status) : null,
                                      'file'              =>  (count($file) > 0) ? json_encode($file) : null,
                                      'attachs'           =>  (count($attachs) > 0) ? json_encode($attachs) : null,
                                      'created_by'        =>  auth()->user()->runrecno
                               ]);

      }
      
      public function set_mail($auditors) {
        $config = HP::getConfig();
        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
        
        if( !empty($auditors->certificate_export_to->CertiCbTo)){

          $certi = $auditors->certificate_export_to->CertiCbTo;
         
           if(!empty($certi->DataEmailDirectorCBCC)){
              $mail_cc = $certi->DataEmailDirectorCBCC;
              array_push($mail_cc, auth()->user()->reg_email) ;
           }

           if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
 
                  $data_app = [
                                  'title'          =>  'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                  'auditors'       => $auditors,
                                  'data'           => $certi,
                                  'export'         => $auditors->certificate_export_to  ,
                                  'url'            => $url.'certify/tracking-cb',
                                  'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                                  'email_cc'       =>  !empty($mail_cc) ? $mail_cc : [],
                                  'email_reply'    => !empty($certi->DataEmailDirectorCBReply) ? $certi->DataEmailDirectorCBReply : []
                              ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($auditors->tracking_to->reference_refno)? $auditors->tracking_to->reference_refno:null,   
                                                            $auditors->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $auditors->id ?? null,
                                                            (new TrackingAuditors)->getTable(),
                                                            6,
                                                            'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                            view('mail.Tracking.auditors', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  implode(",",$certi->DataEmailCertifyCenter) : 'cb@tisi.mail.go.th',
                                                            $certi->email,
                                                            !empty($mail_cc) ? implode(",",$mail_cc) : null,
                                                            !empty($certi->DataEmailDirectorCBReply) ? implode(",",$certi->DataEmailDirectorCBReply):  null
                                                          );

                $html = new AuditorsMail($data_app);
                $mail =  Mail::to($certi->email)->send($html);  
            
                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
           }  
 
        }
      }

      public function auditor_cb_doc_review($id)
      {
        // dd($id);
        $model = str_slug('auditorcb','-');
            if(auth()->user()->can('add-'.$model)) {
          
              $previousUrl = app('url')->previous();
               
                $auditorcb = new CertiCBAuditors;
                $auditors_status = [new CertiCBAuditorsStatus]; 
      
               $tracking = Tracking::find($id);

              //  dd($tracking->certificate_export_to->CertiCbTo);
               
                return view('certificate.cb.auditor_cb_doc_review.create',[
                    'tracking' => $tracking ,
                    'auditorcb' => $auditorcb,
                    'auditors_status'=> $auditors_status,
                ]);
            }
            abort(403);
      }

  public function auditor_cb_doc_review_store(Request $request)
  {
      // ตรวจสอบความถูกต้องของข้อมูลที่ได้รับ (Validation)
      // dd($request->all());
      $request->validate([
          'tracking_id' => 'required|string',
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
// dd($request->hasFile('attach'));
      if ($request->hasFile('attach')) {
          $file = $request->file('attach');
          $filePath = $this->storeFile($file,'doc_review_file_cb');
          $fileName = basename($filePath);
      }

      $from_date = isset($request->start_date[0]) ? $this->convertThaiYearToAD($request->start_date[0]) : null;
      $to_date = isset($request->end_date[0]) ? $this->convertThaiYearToAD($request->end_date[0]) : null;
  
      // บันทึกข้อมูลลงในฐานข้อมูล
      $cbDocReviewAuditor = TrackingDocReviewAuditor::create([
          'tracking_id' => $request->tracking_id,
          'doc_type' => '1',
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

      $tracking = Tracking::find($request->tracking_id);
      if($request->assessment_type == '1')
      {
        $this->sendMailAuditorDocReview($tracking,$cbDocReviewAuditor);
      }

      // /certificate/tracking-cb/385/edit
    // return redirect()->to('/certificate/auditor_cb_doc_review/auditor_cb_doc_review/' . $tracking->id);
    return redirect()->to('/certificate/tracking-cb/' . $tracking->id . '/edit');

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

public function sendMailAuditorDocReview($tracking,$cbDocReviewAuditor)
{
  // dd($tracking);
   $certi_cb = $tracking->certificate_export_to->CertiCbTo;
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
                     'tracking'       => $tracking,
                    'certi_cb'       => $certi_cb ,
                    'url'            => $url.'certificate/auditor_cb_doc_review/auditor_cb_doc_review/' . $tracking->id ?? '-',
                    'email'          =>  !empty($certi_cb->DataEmailCertifyCenter) ? $certi_cb->DataEmailCertifyCenter : 'cb@tisi.mail.go.th',
                    'email_cc'       =>  !empty($mail_cc) ? $mail_cc : 'cb@tisi.mail.go.th',
                    'email_reply'    => !empty($certi_cb->DataEmailDirectorCBReply) ? $certi_cb->DataEmailDirectorCBReply : 'cb@tisi.mail.go.th'
              ];

      $log_email =  HP::getInsertCertifyLogEmail($certi_cb->app_no,
                                              $certi_cb->id,
                                              (new Tracking)->getTable(),
                                              $certi_cb->id,
                                              (new CbDocReviewAuditor)->getTable(),
                                              $cbDocReviewAuditor->id,
                                              'แต่งตั้งคณะผู้ตรวจประเมินเอกสาร',
                                              view('mail.Tracking.auditor_doc_review', $data_app),
                                              $certi_cb->created_by,
                                              $certi_cb->agent_id,
                                              auth()->user()->getKey(),
                                              !empty($certi_cb->DataEmailCertifyCenter) ?  implode(',',(array)$certi_cb->DataEmailCertifyCenter)  :  'cb@tisi.mail.go.th',
                                              $certi_cb->email,
                                              !empty($mail_cc) ?  implode(',',(array)$mail_cc)  : 'cb@tisi.mail.go.th',
                                              !empty($certi_cb->DataEmailDirectorCBReply) ?implode(',',(array)$certi_cb->DataEmailDirectorCBReply)   :   'cb@tisi.mail.go.th',
                                              null
                                              );

      $html = new TrackingDocReviewAuditorsMail($data_app);
      $mail =  Mail::to($certi_cb->email)->send($html);

      if(is_null($mail) && !empty($log_email)){
          HP::getUpdateCertifyLogEmail($log_email->id);
      } 
  }
}


public function auditor_cb_doc_review_edit ($id)
{
  //  dd('fuck');
    $model = str_slug('auditorcb','-');
    if(auth()->user()->can('add-'.$model)) {
        $previousUrl = app('url')->previous();

        $auditorcb = new CertiCBAuditors;
        $auditors_status = [new CertiCBAuditorsStatus];
        $tracking = Tracking::find($id);
        $trackingDocReviewAuditor = trackingDocReviewAuditor::where('tracking_id',$id)->first();

        // dd($trackingDocReviewAuditor);
        return view('certificate.cb.auditor_cb_doc_review.edit',['auditorcb' => $auditorcb,
                                                    'auditors_status' => $auditors_status,
                                                    'previousUrl' => $previousUrl,
                                                    'tracking' => $tracking,
                                                    'certiCb' => $tracking->certificate_export_to->applications,
                                                    'trackingDocReviewAuditor' => $trackingDocReviewAuditor ,
                                                    'doc_review_auditors' => json_decode($trackingDocReviewAuditor->auditors, true),
                                                    ]);
    }
    abort(403);
}

public function accept_doc_review(Request $request)
{
    // dd($request->all());
    Tracking::find($request->trackingId)->update([
    'doc_auditor_assignment' => 2,
    'doc_review_reject' => null,
    'doc_review_reject_message' => null,
  ]);
}

public function reject_doc_review(Request $request)
{
    // dd($request->all());
  Tracking::find($request->trackingId)->update([
    'doc_review_reject' => 1,
    'doc_review_reject_message' => $request->rejectText,
  ]);
}

 public function bypass_doc_auditor_assignment(Request $request)
 {
  // dd($request->all());
    Tracking::find($request->trackingId)->update([
      'doc_auditor_assignment' => 2
    ]);
 }


public function cancel_doc_review_team(Request $request)
{
    // dd($request->all());
  trackingDocReviewAuditor::where('tracking_id',$request->trackingId)->delete();
}



      public function CreateTrackingCbMessageRecord($id)
      {
          // สำหรับ admin และเจ้าหน้าที่ lab
          // if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
          //     abort(403);
          // }
  
          $trackingAuditor = TrackingAuditors::find($id);
          $auditors_statuses= $trackingAuditor->auditors_status_many;
          $statusAuditorMap = [];
          foreach ($auditors_statuses as $auditors_status)
          {
              // dd($auditors_status->auditors_list_many);
              $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
              $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection
  
              // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
              if (!isset($statusAuditorMap[$statusAuditorId])) {
                  $statusAuditorMap[$statusAuditorId] = [];
              }
              foreach ($auditors as $auditor) {
                  
                  $statusAuditorMap[$statusAuditorId][] = $auditor->id;
              }
          }

          
          $tracking = Tracking::find($trackingAuditor->tracking_id);
  
          $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$id)->first();
          $dateRange = "";
  
          if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
              if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
                  // ถ้าเป็นวันเดียวกัน
                  $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
              } else {
                  // ถ้าเป็นคนละวัน
                  $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
                              " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
              }
          }
  

          
          $certi_cb = $tracking->certificate_export_to->applications;

          // dd($certi_ib);
         
          // $data = new stdClass();
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
        //   $data->statusAuditorMap = $statusAuditorMap;
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
  
          $auditors =        $tracking->AuditorsManyBy;
          // dd($auditors);


          return view('certificate.cb.auditor-cb.initial-message-record', [
              'data' => $data,
              'certi_cb' => $certi_cb,
              'id' => $id,
              'auditors' => $auditors,
              'trackingAuditor' => $trackingAuditor,
              'tracking' => $tracking
          ]);
      }



    public function SaveTrackingCbMessageRecord(Request $request)
    {
         // สร้างและบันทึกข้อมูลโดยตรง
         $record = new BoardAuditorTrackingMsRecordInfo([
            'tracking_auditor_id' => $request->id,
            'header_text1' => $request->header_text1,
            'header_text2' => $request->header_text2,
            'header_text3' => $request->header_text3,
            'header_text4' => $request->header_text4,
            'body_text1'   => $request->body_text1,
            'body_text2'   => $request->body_text2,
        ]);

        $record->save();

        TrackingAuditors::find($request->id)->update([
            'message_record_status' => 2
        ]);


    }

          public function ViewTrackingCbMessageRecord($id)
      {
          // สำหรับ admin และเจ้าหน้าที่ lab
          // if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
          //     abort(403);
          // }
  
          $trackingAuditor = TrackingAuditors::find($id);
          $boardAuditorMsRecordInfo = $trackingAuditor->boardAuditorTrackingMsRecordInfos->first();
          $auditors_statuses= $trackingAuditor->auditors_status_many;
          $statusAuditorMap = [];
          foreach ($auditors_statuses as $auditors_status)
          {
              // dd($auditors_status->auditors_list_many);
              $statusAuditorId = $auditors_status->status_id; // ดึง status_auditor_id มาเก็บในตัวแปร
              $auditors = $auditors_status->auditors_list_many; // $auditors เป็น Collection
  
              // ตรวจสอบว่ามีค่าใน $statusAuditorMap อยู่หรือไม่ หากไม่มีให้กำหนดเป็น array ว่าง
              if (!isset($statusAuditorMap[$statusAuditorId])) {
                  $statusAuditorMap[$statusAuditorId] = [];
              }
              foreach ($auditors as $auditor) {
                  
                  $statusAuditorMap[$statusAuditorId][] = $auditor->id;
              }
          }

          
          $tracking = Tracking::find($trackingAuditor->tracking_id);
  
          $trackingAuditorsDate = TrackingAuditorsDate::where('auditors_id',$id)->first();
          $dateRange = "";
  
          if (!empty($trackingAuditorsDate->start_date) && !empty($trackingAuditorsDate->end_date)) {
              if ($trackingAuditorsDate->start_date == $trackingAuditorsDate->end_date) {
                  // ถ้าเป็นวันเดียวกัน
                  $dateRange = "ในวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date);
              } else {
                  // ถ้าเป็นคนละวัน
                  $dateRange = "ตั้งแต่วันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->start_date) . 
                              " ถึงวันที่ " . HP::formatDateThaiFullNumThai($trackingAuditorsDate->end_date);
              }
          }
  

          
          $certi_cb = $tracking->certificate_export_to->applications;

          // dd($certi_ib);
         
          // $data = new stdClass();
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
        //   $data->statusAuditorMap = $statusAuditorMap;
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
  
          $auditors =        $tracking->AuditorsManyBy;
          // dd($boardAuditorMsRecordInfo);


          return view('certificate.cb.auditor-cb.view-message-record', [
              'data' => $data,
              'certi_cb' => $certi_cb,
              'id' => $id,
              'auditors' => $auditors,
              'trackingAuditor' => $trackingAuditor,
              'tracking' => $tracking,
              'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo
          ]);
      }

}
