<?php

namespace App\Http\Controllers\Certificate\Labs;

use DB;
use HP;
use stdClass;
use App\CertificateExport;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use Yajra\Datatables\Datatables;
use App\Mail\Tracking\AuditorsMail;
use App\Http\Controllers\Controller;
use App\Models\Certificate\Tracking;
use Illuminate\Support\Facades\Mail; 
use App\Models\Bcertify\StatusAuditor;
use App\Models\Certificate\TrackingHistory;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingPayInOne;
use App\Mail\Tracking\MailBoardAuditorSigner;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingAuditorsList; 
use App\Mail\Lab\MailBoardAuditorTrackingExaminer;
use App\Models\Bcertify\BoardAuditoExpertTracking;
use App\Models\Certificate\TrackingAuditorsStatus; 
use App\Models\Certify\MessageRecordTrackingTransaction;
use App\Models\Bcertify\BoardAuditorTrackingMsRecordInfo;

class AuditorLabsController extends Controller
{
   private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/trackinglabs';
    }

    public function index(Request $request)
    { 
        $model = str_slug('auditorlabs','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certificate.labs.auditor-labs.index' );
        }
        abort(403);
    } 

    public function data_list(Request $request)
    { 
      $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
 
      $model = str_slug('auditorlabs', '-');
      $filter_search = $request->input('filter_search');
      $filter_certificate_no = $request->input('filter_certificate_no');
      $filter_status_id = $request->input('filter_status_id');
      $filter_start_date = !empty($request->get('filter_start_date'))?HP::convertDate($request->get('filter_start_date'),true):null;
      $filter_end_date = !empty($request->get('filter_end_date'))?HP::convertDate($request->get('filter_end_date'),true):null;
      $query = TrackingAuditors::query()
                                      ->where('certificate_type',3) ->where('ref_table',(new CertificateExport)->getTable())
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
                                $text = '';
                                $text .= HP::buttonAction( $item->id, 'certificate/auditor-labs','Certificate\labs\\AuditorLabsController@destroy', 'auditorlabs',false,true,false);
                               if(auth()->user()->can('delete-'.$model)  && $item->step_id != '12') {  
                                $text .= ' <button class="btn btn-danger btn-xs modal_delete" data-id="'.($item->id).'"> <i class="fa fa-trash-o" aria-hidden="true"></i> </button>';
                              }
                           
                                  return  $text; 
                          })
                          ->order(function ($query) {
                              $query->orderBy('id', 'DESC');
                          })
                          ->rawColumns([ 'checkbox', 'date_title',  'action', 'reference_refno']) 
                          ->make(true);
    } 


    

    public function create(Request $request)
    {
       
        $model = str_slug('auditorlabs','-');
        if(auth()->user()->can('add-'.$model)) {
          $previousUrl = app('url')->previous();
          if(!empty($request->refno)){
              $tracking                =   Tracking::where('reference_refno', $request->refno)->first(); 
              $tracking->name          =  !empty($tracking->certificate_export_to->CertiLabTo->name)? $tracking->certificate_export_to->CertiLabTo->name:'';
              $tracking->lab_name     =  !empty($tracking->certificate_export_to->CertiLabTo->lab_name)? $tracking->certificate_export_to->CertiLabTo->lab_name:'';
              $tracking->certificate_for     =  !empty($tracking->certificate_export_to->certificate_for)? $tracking->certificate_export_to->certificate_for:'';
              $tracking->tracking_id   =  !empty($tracking->id)? $tracking->id:'';
          }else{
              $tracking = '';
          }
 
   
          $auditors_status = [new TrackingAuditorsStatus];
          $signers = Signer::all();
        //   dd('okff');
          return view('certificate.labs.auditor-labs.create', compact('tracking','auditors_status','signers'));
        }
        abort(403);

    }

    public function store(Request $request)
    {
        $model = str_slug('auditorlabs','-');
        if(auth()->user()->can('add-'.$model)) {
           
          // try {
                $tracking =  Tracking::findOrFail($request->tracking_id);

                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
                $requestData = $request->all();
                $requestData['certificate_type'] =   3 ;
                $requestData['reference_refno'] =   $tracking->reference_refno ?? null ;
                $requestData['ref_table']       =   (new CertificateExport)->getTable() ;
                $requestData['ref_id']          =   $tracking->ref_id ?? null ;
                $requestData['tracking_id']     =   $tracking->id ?? null ;
                $requestData['status']          =   null ;
                $requestData['step_id']         =  2  ;//ขอความเห็นแต่งคณะผู้ตรวจประเมิน
                $requestData['vehicle']         = isset($request->vehicle) ? 1 : null ;
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
                  $this->DataAuditorsDate($auditors->id,$request);

        
                  $this->storeStatus($auditors->id,(array)$requestData['list']);

                  
                if(!is_null($tracking)){
                    if(isset($request->vehicle)){

                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                        // Log
                        $this->set_history($auditors,4);
                        //E-mail 
                        //  $this->set_mail($auditors);
              
                    }else{

                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();

                         $this->set_history($auditors,3);
                    }
                }
                

                $this->saveSignature($request,$auditors->id,$tracking->ref_id);

                $categories = [];
                foreach($request->list['status'] as $status)
                {
                
                  $statusAuditor = StatusAuditor::find($status);
                  if ($statusAuditor) {
                    $categories[] = $statusAuditor->title;
                  }

                }
                if(count($categories) != 0)
                {
                   
                    $boardAuditorExpertTracking = new BoardAuditoExpertTracking();
                                        
                    // กำหนดค่า
                    $boardAuditorExpertTracking->tracking_auditor_id = $auditors->id; // ตัวอย่าง ID
                    $boardAuditorExpertTracking->expert = json_encode($categories); // แปลง $categories เป็น JSON

                    // บันทึกข้อมูล
                    $boardAuditorExpertTracking->save(); // บันทึกข้อมูลลงฐานข้อมูล
                    // dd('ok',$tracking->id,$categories);
                }
                
            
                if($request->previousUrl){
                  return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
                }else{
                    return redirect('certificate/auditor-labs')->with('flash_message', 'เรียบร้อยแล้ว!');
                }
          // } catch (\Exception $e) {
          //        return redirect('certificate/auditor-labs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
          // }

        }
        abort(403);
    }
    public function saveSignature($request,$boardTrackingAutitorId,$certilabExportId)
    {
      // dd($boardTrackingAutitorId);
        TrackingAuditors::find($boardTrackingAutitorId)->update([
          'message_record_status' => 1
        ]);
        $check = MessageRecordTrackingTransaction::where('ba_tracking_id',$boardTrackingAutitorId)->get();
        if($check->count() == 0){
            $signatures = json_decode($request->input('signaturesJson'), true);
            $viewUrl = url('/certificate/auditor-labs/view-lab-tracking-message-record/'.$boardTrackingAutitorId);
            if ($signatures) {
                foreach ($signatures as $signatureId => $signature) {
                    // dd($signature);
                    try {
                        // ลองสร้างข้อมูลในฐานข้อมูล
                        MessageRecordTrackingTransaction::create([
                            'ba_tracking_id' => $boardTrackingAutitorId,
                            'signer_id' => $signature['signer_id'],
                            'certificate_type' => 2,
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
          MessageRecordTrackingTransaction::where('ba_tracking_id',$boardTrackingAutitorId)->update([
                'approval' => 0
            ]);
        }
     
        $board  =  TrackingAuditors::findOrFail($boardTrackingAutitorId);
        $this->sendMailToExaminer($board,$board->CertiLabs); 
    }


    public function sendMailToExaminer($auditors) {
      $config = HP::getConfig();
      $url  =   !empty($config->url_center) ? $config->url_center : url('');
      
      if( !empty($auditors->certificate_export_to->CertiLabTo)){
           $certi = $auditors->certificate_export_to->CertiLabTo;

           if(!empty($certi->DataEmailDirectorLABCC)){
              $mail_cc = $certi->DataEmailDirectorLABCC;
              array_push($mail_cc, auth()->user()->reg_email) ;
           }
        if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                  $data_app = [
                                'title'          =>  'จัดทำหนังสือบันทึกข้อความการลงการแต่งตั้งคณะผู้ตรวจประเมิน',
                                'auditors'       => $auditors,
                                'data'           => $certi,
                                'export'         => $auditors->certificate_export_to  ,
                                'url'            => $url.'certificate/auditor-labs/create-tracking-lab-message-record/'.$auditors->id,
                                'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'lab1@tisi.mail.go.th',
                                'email_cc'       =>  !empty($mail_cc) ? $mail_cc : [],
                                'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($auditors->tracking_to->reference_refno)? $auditors->tracking_to->reference_refno:null,   
                                                            $auditors->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $auditors->id ?? null,
                                                            (new TrackingAuditors)->getTable(),
                                                            4,
                                                            'จัดทำหนังสือบันทึกข้อความการลงการแต่งตั้งคณะผู้ตรวจประเมิน',
                                                            view('mail.Tracking.mail_board_auditor_examiner', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter : null,
                                                            $certi->email,
                                                            !empty($mail_cc) ? implode(",",$mail_cc) : null,
                                                            !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                          );

              $html = new MailBoardAuditorTrackingExaminer($data_app);
              $mail =  Mail::to($certi->email)->send($html);  

              if(is_null($mail) && !empty($log_email)){
                  HP::getUpdateCertifyLogEmail($log_email->id);
              }    
         }  
      }
    }



    public function edit(Request $request,$id)
    {
        $model = str_slug('auditorlabs','-');
        if(auth()->user()->can('edit-'.$model)) {
          $previousUrl = app('url')->previous();
          $auditor =  TrackingAuditors::findOrFail($id);
          if(!empty($auditor->tracking_id)){
            $tracking                   =   Tracking::where('id', $auditor->tracking_id)->first();
            $tracking->name             =  !empty($tracking->certificate_export_to->CertiLabTo->name)? $tracking->certificate_export_to->CertiLabTo->name:'';
            $tracking->lab_name         = !empty($tracking->certificate_export_to->CertiLabTo->lab_name)? $tracking->certificate_export_to->CertiLabTo->lab_name:'';
            $tracking->certificate_for  =  !empty($tracking->certificate_export_to->certificate_for)? $tracking->certificate_export_to->certificate_for:'';
          }else{
            $tracking = '';
          }
             $auditors_status =   TrackingAuditorsStatus::where('auditors_id', $id)->get();  
          if(count($auditors_status) == 0){
            $auditors_status = [new TrackingAuditorsStatus];
          }
          $signers = Signer::all();
          return view('certificate.labs.auditor-labs.edit', compact('auditor','tracking','auditors_status','signers'));
        }
        abort(403);

    }


    public function update(Request $request,$id)
    {
 
        $model = str_slug('auditorlabs','-');
        if(auth()->user()->can('add-'.$model)) {
  
          // try {
 
 
                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
                $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
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
                  $this->DataAuditorsDate($auditors->id,$request);

                  $this->storeStatus($auditors->id,(array)$requestData['list']);

                  $tracking = Tracking::find($auditors->tracking_id);
                if(!is_null($tracking)){
                    if(isset($request->vehicle)){
                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                        // Log
                        $this->set_history($auditors,4);
                        //E-mail 
                         $this->set_mail($auditors);
              
                    }else{
                        $tracking->status_id = 3; // ขอความเห็นแต่งคณะผู้ตรวจประเมิน 	
                        $tracking->save();
                         $this->set_history($auditors,3);
                    }
                }
                
            
                if($request->previousUrl){
                  return redirect("$request->previousUrl")->with('flash_message', 'เรียบร้อยแล้ว!');
                }else{
                    return redirect('certificate/auditor-labs')->with('flash_message', 'เรียบร้อยแล้ว!');
                }
          // } catch (\Exception $e) {
          //        return redirect('certificate/auditor-labs')->with('message_error', 'เกิดข้อผิดพลาดกรุณาทำรายการใหม่!');
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
                  $this->storeList($auditors_status,
                                  $list['temp_users'][$key],
                                  $list['user_id'][$key],
                                  $list['temp_departments'][$key]
                );
                  // $this->storeList($auditors_status,
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
          $auditors_list = TrackingAuditorsList::select('auditors_status_id','temp_users','user_id','temp_departments','status_id')
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
                                      'tracking_id'       => $data->tracking_id ?? null,
                                      'certificate_type'  => 3,
                                      'reference_refno'   => $data->reference_refno ?? null,
                                      'ref_table'         =>  (new CertificateExport)->getTable() ,
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
        
        if( !empty($auditors->certificate_export_to->CertiLabTo)){
             $certi = $auditors->certificate_export_to->CertiLabTo;

             if(!empty($certi->DataEmailDirectorLABCC)){
                $mail_cc = $certi->DataEmailDirectorLABCC;
                array_push($mail_cc, auth()->user()->reg_email) ;
             }
          if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                    $data_app = [
                                  'title'          =>  'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                  'auditors'       => $auditors,
                                  'data'           => $certi,
                                  'export'         => $auditors->certificate_export_to  ,
                                  'url'            => $url.'certify/tracking-labs',
                                  'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'lab1@tisi.mail.go.th',
                                  'email_cc'       =>  !empty($mail_cc) ? $mail_cc : [],
                                  'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                              ];
          
                  $log_email =  HP::getInsertCertifyLogEmail(!empty($auditors->tracking_to->reference_refno)? $auditors->tracking_to->reference_refno:null,   
                                                              $auditors->tracking_id,
                                                              (new Tracking)->getTable(),
                                                              $auditors->id ?? null,
                                                              (new TrackingAuditors)->getTable(),
                                                              4,
                                                              'การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                              view('mail.Tracking.auditors', $data_app),
                                                              !empty($certi->created_by)? $certi->created_by:null,   
                                                              !empty($certi->agent_id)? $certi->agent_id:null, 
                                                              auth()->user()->getKey(),
                                                              !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter : null,
                                                              $certi->email,
                                                              !empty($mail_cc) ? implode(",",$mail_cc) : null,
                                                              !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                            );

                $html = new AuditorsMail($data_app);
                $mail =  Mail::to($certi->email)->send($html);  

                if(is_null($mail) && !empty($log_email)){
                    HP::getUpdateCertifyLogEmail($log_email->id);
                }    
           }  
        }
      }

      public function update_delete(Request $request, $id)
      {
           $model = str_slug('auditorlabs', '-');
          if(auth()->user()->can('delete-'.$model)) {
              
              try {
                  $requestData = $request->all();
                  $requestData['step_id']          =   12 ;
                  $requestData['reason_cancel']    =  $request->reason_cancel ;
                  $requestData['status_cancel']    =   1 ;
                  $requestData['status']           =   3 ;
                  $requestData['created_cancel']   =  auth()->user()->runrecno;
                  $requestData['date_cancel']       =    date('Y-m-d H:i:s') ;
                  $auditors = TrackingAuditors::findOrFail($id);
                  $auditors->update($requestData);
  
                  $response = [];
                  $response['reason_cancel']  =  $auditors->reason_cancel ?? null;
                  $response['status_cancel']  =  $auditors->status_cancel ?? null;
                  $response['created_cancel'] =  $auditors->created_cancel ?? null;    
                  $response['date_cancel']    =  $auditors->date_cancel ?? null;
                  $response['step_id']        =  $auditors->step_id ?? null;
                  $response['status']         =  $auditors->status ?? null;
  
                  TrackingHistory::where('auditors_id',$auditors->id)->where('table_name',(new TrackingAuditors)->getTable())->update(['details_auditors_cancel' => json_encode($response) ]);
  
                  $tracking = Tracking::where('id',$auditors->tracking_id)->first();
      
                  if(!is_null($tracking)){

                      $tracking->status_id = 3; // อยู่ระหว่างดำเนินการ
                      $tracking->save();
  
                      $cost =  TrackingPayInOne::where('auditors_id',$auditors->id)->orderby('id','desc')->first();
                      if(!is_null($cost)){ // update log payin
                        // / update   payin
                        TrackingPayInOne::where('auditors_id',$auditors->id)->update(['status' =>'3','amount_bill'=>'0.00']);
                        TrackingHistory::where('auditors_id',$auditors->id)->where('table_name',(new TrackingPayInOne)->getTable())->update(['details_auditors_cancel' => json_encode($response) ]);
                      }
                      return response()->json([
                                              'message' => 'update ยกเลิกแต่งตั้งคณะผู้ตรวจประเมินเรียบร้อยแล้ว'
                                          ]);
                  }else{
                    return response()->json([
                                           'message' => 'เกิดข้อผิดพลาดในการบันทึก'
                                        ]);
 
                  } 
              } catch (\Exception $e) {
                      return response()->json([
                        'message' => 'เกิดข้อผิดพลาดในการบันทึก'
                    ]);  
              }
          }
          abort(403);
      }
  
 
      public function CreateTrackingLabMessageRecord($id)
      {
          // สำหรับ admin และเจ้าหน้าที่ lab
          if (!in_array(auth()->user()->role, [6, 7, 11, 28])) {
              abort(403);
          }
  
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
              // dd($auditors_status);
              // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
              foreach ($auditors as $auditor) {
                  
                  $statusAuditorMap[$statusAuditorId][] = $auditor->id;
              }
          }
  
          // dd($statusAuditorMap);
  
          
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
  
          // dd($dateRange );
  
          $boardAuditorExpertTracking = BoardAuditoExpertTracking::where('tracking_auditor_id',$id)->first();
  
          $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
          // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
          // dd($boardAuditorExpert->expert);
          if ($boardAuditorExpertTracking && $boardAuditorExpertTracking->expert) {
              // แปลงข้อมูล JSON ใน expert กลับเป็น array
              $categories = json_decode($boardAuditorExpertTracking->expert, true);
          
              // ถ้ามีหลายรายการ
              if (count($categories) > 1) {
                  // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
                  $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
                  $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
              } elseif (count($categories) == 1) {
                  // ถ้ามีแค่รายการเดียว
                  $experts = $categories[0];
              } else {
                  $experts = ''; // ถ้าไม่มีข้อมูล
              }
          
          }
          
          $certi_lab = $tracking->certificate_export_to->applications;
          // dd($certi_lab);
          $scope_branch = "";
          if ($certi_lab->lab_type == 3){
              $scope_branch =$certi_lab->BranchTitle;
          }else if($certi_lab->lab_type == 4)
          {
              $scope_branch = $certi_lab->ClibrateBranchTitle;
          }
  
         
          $data = new stdClass();
       
          $data->header_text1 = '';
          $data->header_text2 = '';
          $data->header_text3 = '';
          $data->header_text4 = $certi_lab->app_no;
          $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
          $data->lab_name = $certi_lab->lab_name;
          $data->scope_branch = $scope_branch;
          $data->tracking = $tracking;
          $data->app_np = 'ทดสอบ ๑๖๗๑';
          $data->certificate_no = '13-LB0037';
          $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
          $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
          $data->experts = $experts;
          // $data->certiLabFileAll = $certiLabFileAll;
          $data->date_range = $dateRange;
          $data->statusAuditorMap = $statusAuditorMap;
          $data->fix_text1 = <<<HTML
                      <div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
                      <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
                      <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข วันที่ ๔ มีนาคม ๒๕๕๑) การรับรองห้องปฏิบัติการ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div>
                      <div style="text-indent:150px">ข้อ ๖.๑.๒ (๑) แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าคณะผู้ตรวจ ประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม</div>
                      <div style="text-indent:150px">ข้อ ๖.๑.๒ (๒.๑) คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารของห้องปฏิบัติการ และข้อ ๖.๑.๒ (๒.๒) คณะผู้ตรวจประเมินจะตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานตามระบบการบริหารงานและมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div>
                      <div style="text-indent:125px">๒.๓ ประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เรื่อง แนวทางการแต่งตั้งพนักงานเจ้าหน้าที่ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศ ณ วันที่ ๙ กุมภาพันธ์ พ.ศ. ๒๕๖๐) ซึ่งระบุพนักงานเจ้าหน้าที่ต้องมีคุณสมบัติตามข้อ ๑. ถึง ๓. </div>
                      <div style="text-indent:125px">๒.๔ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมในการเป็นผู้มีอำนาจพิจารณาดำเนินการตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (สั่ง ณ วันที่ ๑๓พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ และข้อ ๕.๒ ในกรณีที่ข้าราชการผู้รับมอบอำนาจตามข้อ ๓.ไม่อาจปฏิบัติราชการได้ หรือไม่มีผู้ดำรงตำแหน่งดังกล่าว ให้รองเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมที่กำกับ เป็นผู้พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</div>
                  HTML;
  
          $data->fix_text2 = <<<HTML
                      <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div>
                      <div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองห้องปฏิบัติการ สมอ. มีอำนาจหน้าที่ในการรับรองความสามารถห้องปฏิบัติการ กำหนดให้มีการประเมินเพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการ{{$data->lab_type}} ตามมาตรฐานเลขที่ มอก. 17025-2561</div>
                  HTML;
  
          return view('certificate.labs.auditor-labs.initial-message-record', [
              'data' => $data,
              'id' => $id
          ]);
      }
      
    public function SaveTrackingLabMessageRecord(Request $request)
    {
      $trackingAuditor  =  TrackingAuditors::findOrFail($request->id);
      


      // dd($request->all());
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

        
        $this->sendMailToSigner($trackingAuditor,$trackingAuditor->certificate_export_to); 

        // return redirect()->route('certify.auditor.index');

    }

          
    public function ViewTrackingLabMessageRecord($id)
    {

       if (!in_array(auth()->user()->role, [5,6, 7, 11, 28])) {
        abort(403);
      }

      $trackingAuditor = TrackingAuditors::find($id);
      
      $boardAuditorMsRecordInfo = $trackingAuditor->boardAuditorTrackingMsRecordInfos->first();

      // dd($boardAuditorMsRecordInfo);

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
          // เพิ่ม auditor_id เข้าไปใน array ตาม status_auditor_id
          foreach ($auditors as $auditor) {
              
              $statusAuditorMap[$statusAuditorId][] = $auditor->id;
          }
      }

    //   dd($statusAuditorMap,$trackingAuditor);


      
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

      // dd($dateRange );

      $boardAuditorExpertTracking = BoardAuditoExpertTracking::where('tracking_auditor_id',$id)->first();

      $experts = "หัวหน้าคณะผู้ตรวจประเมิน ผู้ตรวจประเมิน และผู้สังเกตการณ์";
      // ตรวจสอบว่ามีข้อมูลในฟิลด์ expert หรือไม่
      // dd($boardAuditorExpert->expert);
      if ($boardAuditorExpertTracking && $boardAuditorExpertTracking->expert) {
          // แปลงข้อมูล JSON ใน expert กลับเป็น array
          $categories = json_decode($boardAuditorExpertTracking->expert, true);
      
          // ถ้ามีหลายรายการ
          if (count($categories) > 1) {
              // ใช้ implode กับ " และ" สำหรับรายการสุดท้าย
              $lastItem = array_pop($categories); // ดึงรายการสุดท้ายออก
              $experts = implode(' ', $categories) . ' และ' . $lastItem; // เชื่อมรายการที่เหลือแล้วใช้ "และ" กับรายการสุดท้าย
          } elseif (count($categories) == 1) {
              // ถ้ามีแค่รายการเดียว
              $experts = $categories[0];
          } else {
              $experts = ''; // ถ้าไม่มีข้อมูล
          }
      
      }
      
      $certi_lab = $tracking->certificate_export_to->applications;
      // dd($certi_lab);
      $scope_branch = "";
      if ($certi_lab->lab_type == 3){
          $scope_branch =$certi_lab->BranchTitle;
      }else if($certi_lab->lab_type == 4)
      {
          $scope_branch = $certi_lab->ClibrateBranchTitle;
      }

    
      $data = new stdClass();
  
      $data->header_text1 = '';
      $data->header_text2 = '';
      $data->header_text3 = '';
      $data->header_text4 = $certi_lab->app_no;
      $data->lab_type = $certi_lab->lab_type == 3 ? 'ทดสอบ' : ($certi_lab->lab_type == 4 ? 'สอบเทียบ' : 'ไม่ทราบประเภท');
      $data->lab_name = $certi_lab->lab_name;
      $data->scope_branch = $scope_branch;
      $data->tracking = $tracking;
      $data->app_np = 'ทดสอบ ๑๖๗๑';
      $data->certificate_no = '13-LB0037';
      $data->register_date = HP::formatDateThaiFullNumThai($certi_lab->created_at);
      $data->get_date = HP::formatDateThaiFullNumThai($certi_lab->get_date);
      $data->experts = $experts;
      // $data->certiLabFileAll = $certiLabFileAll;
      $data->date_range = $dateRange;
      $data->statusAuditorMap = $statusAuditorMap;
      
      $data->fix_text1 = <<<HTML
                  <div class="section-title">๒. ข้อกฎหมาย/กฎระเบียบที่เกี่ยวข้อง</div>
                  <div style="text-indent:125px">๒.๑ พระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศในราชกิจจานุเบกษา วันที่ ๔ มีนาคม ๒๕๕๑) มาตรา ๒๘ วรรค ๒ ระบุ "การขอใบรับรอง การตรวจสอบและการออกใบรับรอง ให้เป็นไปตามหลักเกณฑ์ วิธีการ และเงื่อนไขที่คณะกรรมการประกาศกำหนด"</div>
                  <div style="text-indent:125px">๒.๒ ประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไข วันที่ ๔ มีนาคม ๒๕๕๑) การรับรองห้องปฏิบัติการ (ประกาศในราชกิจจานุเบกษา วันที่ ๑๗ พฤษภาคม ๒๕๖๔)"</div>
                  <div style="text-indent:150px">ข้อ ๖.๑.๒ (๑) แต่งตั้งคณะผู้ตรวจประเมิน ประกอบด้วย หัวหน้าคณะผู้ตรวจ ประเมิน ผู้ตรวจประเมินด้านวิชาการ และผู้ตรวจประเมิน ซึ่งอาจมีผู้เชี่ยวชาญร่วมด้วยตามความเหมาะสม</div>
                  <div style="text-indent:150px">ข้อ ๖.๑.๒ (๒.๑) คณะผู้ตรวจประเมินจะทบทวนและประเมินและประเมินเอกสารของห้องปฏิบัติการ และข้อ ๖.๑.๒ (๒.๒) คณะผู้ตรวจประเมินจะตรวจประเมินความสามารถและ ประสิทธิผลของการดำเนินงานตามระบบการบริหารงานและมาตรฐานการตรวจสอบและรับรองที่เกี่ยวข้อง ณ สถานประกอบการของผู้ยื่นคำขอ และสถานที่ทำการอื่นในสาขาที่ขอรับการรับรอง</div>
                  <div style="text-indent:125px">๒.๓ ประกาศสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม เรื่อง แนวทางการแต่งตั้งพนักงานเจ้าหน้าที่ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (ประกาศ ณ วันที่ ๙ กุมภาพันธ์ พ.ศ. ๒๕๖๐) ซึ่งระบุพนักงานเจ้าหน้าที่ต้องมีคุณสมบัติตามข้อ ๑. ถึง ๓. </div>
                  <div style="text-indent:125px">๒.๔ คำสั่งสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรม ที่ ๓๔๒/๒๕๖๖ เรื่อง มอบอำนาจให้ข้าราชการสั่งและปฏิบัติราชการแทนเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมในการเป็นผู้มีอำนาจพิจารณาดำเนินการตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ (สั่ง ณ วันที่ ๑๓พฤศจิกายน ๒๕๖๖) ข้อ ๓ ระบุให้ผู้อำนวยการสำนักงานคณะกรรมการการมาตรฐานแห่งชาติ เป็นผู้มีอำนาจพิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑ และข้อ ๕.๒ ในกรณีที่ข้าราชการผู้รับมอบอำนาจตามข้อ ๓.ไม่อาจปฏิบัติราชการได้ หรือไม่มีผู้ดำรงตำแหน่งดังกล่าว ให้รองเลขาธิการสำนักงานมาตรฐานผลิตภัณฑ์อุตสาหกรรมที่กำกับ เป็นผู้พิจารณาแต่งตั้งคณะผู้ตรวจประเมิน ตามพระราชบัญญัติการมาตรฐานแห่งชาติ พ.ศ. ๒๕๕๑</div>
              HTML;

      $data->fix_text2 = <<<HTML
                  <div class="section-title">๓. สาระสำคัญและข้อเท็จจริง</div>
                  <div style="text-indent:125px">ตามประกาศคณะกรรมการการมาตรฐานแห่งชาติ เรื่อง หลักเกณฑ์ วิธีการ และเงื่อนไขการรับรองห้องปฏิบัติการ สมอ. มีอำนาจหน้าที่ในการรับรองความสามารถห้องปฏิบัติการ กำหนดให้มีการประเมินเพื่อพิจารณาให้การรับรองความสามารถห้องปฏิบัติการ{{$data->lab_type}} ตามมาตรฐานเลขที่ มอก. 17025-2561</div>
              HTML;
      
      return view('certificate.labs.auditor-labs.view-message-record', [
          'data' => $data,
          'id' => $id,
          'boardAuditorMsRecordInfo' => $boardAuditorMsRecordInfo
      ]);

    }

    public function sendMailToSigner($auditors) {
      $config = HP::getConfig();
      $url  =   !empty($config->url_center) ? $config->url_center : url('');
      
      if( !empty($auditors->certificate_export_to->CertiLabTo)){
           $certi = $auditors->certificate_export_to->CertiLabTo;

           if(!empty($certi->DataEmailDirectorLABCC)){
              $mail_cc = $certi->DataEmailDirectorLABCC;
              array_push($mail_cc, auth()->user()->reg_email) ;
           }
        if(!empty($certi->email) &&  filter_var($certi->email, FILTER_VALIDATE_EMAIL)){
                  $data_app = [
                                'title'          =>  'ลงนามบันทึกข้อความ การแต่งตั้งคณะผู้ตรวจประเมิน',
                                'auditors'       => $auditors,
                                'data'           => $certi,
                                'export'         => $auditors->certificate_export_to  ,
                                'url' => $url.'/certificate/auditor-tracking-assignment/',
                                'email'          =>  !empty($certi->DataEmailCertifyCenter) ? $certi->DataEmailCertifyCenter : 'lab1@tisi.mail.go.th',
                                'email_cc'       =>  !empty($mail_cc) ? $mail_cc : [],
                                'email_reply'    => !empty($certi->DataEmailDirectorLABReply) ? $certi->DataEmailDirectorLABReply :  []
                            ];
        
                $log_email =  HP::getInsertCertifyLogEmail(!empty($auditors->tracking_to->reference_refno)? $auditors->tracking_to->reference_refno:null,   
                                                            $auditors->tracking_id,
                                                            (new Tracking)->getTable(),
                                                            $auditors->id ?? null,
                                                            (new TrackingAuditors)->getTable(),
                                                            4,
                                                            'ลงนามบันทึกข้อความ การแต่งตั้งคณะผู้ตรวจประเมิน',
                                                            view('mail.Tracking.mail_board_auditor_signer', $data_app),
                                                            !empty($certi->created_by)? $certi->created_by:null,   
                                                            !empty($certi->agent_id)? $certi->agent_id:null, 
                                                            auth()->user()->getKey(),
                                                            !empty($certi->DataEmailCertifyCenter) ?  @$certi->DataEmailCertifyCenter : null,
                                                            $certi->email,
                                                            !empty($mail_cc) ? implode(",",$mail_cc) : null,
                                                            !empty($certi->DataEmailDirectorLABReply) ? implode(",",$certi->DataEmailDirectorLABReply):  null
                                                          );

              $signerEmails = $auditors->messageRecordTrackingTransactions()
              ->with('signer.user')
              ->get()
              ->pluck('signer.user.reg_email')
              ->filter() // กรองค่า null ออก
              ->unique()
              ->toArray();
                                              
              $html = new MailBoardAuditorSigner($data_app);
              $mail =  Mail::to($signerEmails)->send($html);  

              if(is_null($mail) && !empty($log_email)){
                  HP::getUpdateCertifyLogEmail($log_email->id);
              }    
         }  
      }
    }


    
}
