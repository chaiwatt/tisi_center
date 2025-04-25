<?php

namespace App\Http\Controllers\Certificate\Cb;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Certify\ApplicantCB\CertiCBExport; 
use App\Models\Certificate\Tracking;
use App\Models\Certificate\TrackingAuditors;
use App\Models\Certificate\TrackingAuditorsDate;
use App\Models\Certificate\TrackingAuditorsList; 
use App\Models\Certificate\TrackingAuditorsStatus; 
use App\Models\Certificate\TrackingHistory;
use DB;
use Illuminate\Support\Facades\Mail; 
use App\Mail\Tracking\AuditorsMail;

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
 
   
          $auditors_status = [new TrackingAuditorsStatus];
          return view('certificate.cb.auditor-cb.create', compact('tracking','auditors_status'));
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

          return view('certificate.cb.auditor-cb.edit', compact('auditor','tracking','auditors_status'));
        }
        abort(403);

    }

    public function auditor_cb_doc_review_edit($id)
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

          return view('certificate.cb.auditor-cb.edit', compact('auditor','tracking','auditors_status'));
        }
        abort(403);

    }



    

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

}
