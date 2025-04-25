<?php

namespace App\Http\Controllers\Laws\Cases;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use HP_Law;
use App\Models\Law\Cases\LawCasesDelivery;
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\File\AttachFileLaw;

use App\Mail\Mail\Law\Cases\MailCasesTemplate;
use Illuminate\Support\Facades\Auth;

class LawCasesDeliveryController extends Controller
{
    private $attach_path;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/cases/delivery/';
        $this->permission  = str_slug('law-cases-delivery','-');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search  = $request->input('filter_condition_search');
        $filter_search            = $request->input('filter_search');
        $filter_send_type         = $request->input('filter_send_type');
        $filter_status            = $request->input('filter_status');

        $query = LawCasesDelivery::query()
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    return $query->whereHas('law_cases', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                case "2":
                                                    return $query->whereHas('law_cases', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(owner_name,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                case "3":
                                                    return $query->whereHas('law_cases', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(owner_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        });
                                                    break;
                                                case "4":
                                                    return $query->where( function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                default:
                                                    return $query->where( function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                })
                                                                ->OrwhereHas('law_cases', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(ref_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(owner_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(owner_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_send_type, function ($query, $filter_send_type){
                                            return $query->where('send_type', $filter_send_type);
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            return $query->where('status', $filter_status);
                                        })
                                        ->when(!auth()->user()->can('view_all-'.$this->permission), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                            $query->where('created_by', Auth::user()->getKey())
                                                    ->OrwhereHas('law_cases',function($query){
                                                        $query->where('lawyer_by', Auth::user()->getKey())
                                                            ->Orwhere('assign_by', Auth::user()->getKey());
                                                    });     
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('law_case_no', function ($item) {
                                return  !is_null($item->law_cases) && !empty($item->law_cases->ref_no)?$item->law_cases->ref_no:null;
                            })
                            ->addColumn('law_case_name', function ($item) {
                                $law_cases = $item->law_cases;
                                return ( !empty( $law_cases->owner_name )? $law_cases->owner_name:null  ).('<div><em>('.(!empty( $law_cases->owner_taxid )? $law_cases->owner_taxid:null ).')</em></div>');
                            })
                            ->addColumn('law_send_type', function ($item) {
                                return  !is_null($item->basic_delivery) && !empty($item->basic_delivery->title)?$item->basic_delivery->title:null;
                            })
                            ->addColumn('law_title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            }) 
                            ->addColumn('law_date_due', function ($item) {
                                $count = '';
                                if( !empty($item->date_due) ){
                                    $startDate = \Carbon\Carbon::parse( date('Y-m-d') )->format('Y-m-d');
                                    $endDate   = \Carbon\Carbon::parse( !empty($item->date_due)?$item->date_due:date('Y-m-d') )->format('Y-m-d');
                                    $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);

                                    $count = '<div><span class="text-'.( empty($item->response_date)?'warning':'success' ).'">จำนวน '.(count($lits)).' วัน</span></div>';
                                }
                                return !empty($item->date_due)?HP::DateThai($item->date_due):'<span class="text-danger">ไม่มี</span>'.$count;
                            })
                            ->addColumn('law_state', function ($item) {
                                $html = $item->status == 1 ?'<span class="text-suscess">ส่งแล้ว</span>':'<span class="text-info">ตอบกลับแล้ว</span>';
                                return $html.('<div>('.($item->ConditionName).')</div>');
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.'('.HP::DateThai($item->created_at).')':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/cases/delivery','Laws\Cases\\LawCasesDeliveryController@destroy', 'law-cases-delivery',true, true, false);
                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesDelivery)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'action', 'law_case_name', 'law_state','created_by'])
                            ->make(true);

    }

    public function data_file_list(Request $request)
    {
        $filter_search   = $request->input('filter_search');
        $law_case_id     = $request->input('law_case_id');
        $input_other_row = $request->input('input_other_row');

        $query = AttachFileLaw::query()
                                    ->where( function($query) use($law_case_id) {
                                        $query->where('ref_id', $law_case_id )->where('ref_table', (new LawCasesForm)->getTable() );
                                    })
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->where( function($query) use($search_full) {
                                            $query->where(DB::Raw("REPLACE(filename,' ','')"),  'LIKE', "%$search_full%")
                                                    ->Orwhere(DB::Raw("REPLACE(caption,' ','')"),  'LIKE', "%$search_full%");
                                        });
                                    });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('file_name', function ($item) {
                                return '<a class="" href="'.url(HP::getFileStorage($item->url)).'" target="_blank">'.$item->filename.'</a>';
                            })
                            ->addColumn('file_cation', function ($item) {
                                return !empty($item->caption)?$item->caption:null;
                            })
                            ->addColumn('file_create_at', function ($item) {
                                return !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            })
                            ->addColumn('action', function ($item) use($input_other_row) {

                                $data_input  =  'data-caption="'.$item->caption.'"';
                                $data_input  .= 'data-id="'.$item->id.'"';
                                $data_input  .= 'data-row="'.($input_other_row).'"';
                                $data_input  .= 'data-url="'.(HP::getFileStorage($item->url)).'"';
                                $data_input  .= 'data-filename="'.$item->filename.'"';

                                return  '<button class="btn btn-icon rounded-circle btn-light-info mr-1 mb-1 btn_file_document" type="button" '.( $data_input ).'>เลือก</button>';
                            })
                            ->order(function ($query) {
                                $query->orderBy('created_at', 'desc');
                            })
                            ->rawColumns(['file_name','action'])
                            ->make(true);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/delivery",  "name" => 'บันทึกจัดส่งหนังสือ' ],
            ];

            return view('laws.cases.delivery.index',compact('breadcrumbs'));

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
        if(auth()->user()->can('add-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/delivery",  "name" => 'บันทึกจัดส่งหนังสือ' ],
                [ "link" => "/law/cases/delivery/create",  "name" => 'เพิ่ม' ],

            ];

            return view('laws.cases.delivery.create',compact('breadcrumbs'));

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
        if(auth()->user()->can('add-'.$this->permission)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData                     = $request->all();
            $requestData['date_due']         = !empty(  $requestData['date_due'] ) ?  HP::convertDate( $requestData['date_due'],true) : null;
            $requestData['status']           = 1;
            $requestData['send_mail_status'] = !empty( $requestData['send_mail_status'])?$requestData['send_mail_status']:null;
            $requestData['noti_email']       = !empty( $requestData['noti_email'])?explode(',',$requestData['noti_email']):null;

            $lawcasesdelivery                = LawCasesDelivery::create($requestData);

            //บันทึกไฟล์
            $this->SaveFile( $lawcasesdelivery , $requestData  );

            //ส่งเมล
            $this->SendMail($lawcasesdelivery);

            $law_cases                       = $lawcasesdelivery->law_cases;

            HP_Law::InsertLawLogWorking(         
                1,
                ((new LawCasesDelivery)->getTable()),
                $lawcasesdelivery->id,
                $law_cases->ref_no ?? null,
                'บันทึกจัดส่งหนังสือ',
                'บันทึกจัดส่งหนังสือ',
                ('ส่งแล้ว'),
                (!empty($requestData['remark'])?$requestData['remark']:null)
            );

            return redirect('law/cases/delivery')->with('flash_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');

        }
        abort(403);
    }

    
    public function SaveFile($lawcasesdelivery , $requestData )
    {
    
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        if( isset($requestData['repeater-attach']) ){

            $attachs = $requestData['repeater-attach'];

            $law_cases = $lawcasesdelivery->law_cases;

            $folder_app = ($law_cases->ref_no).'/';

            foreach( $attachs as $file ){
                if( isset($file['attach_file']) && !empty($file['attach_file']) ){
                    HP::singleFileUploadLaw(
                        $file['attach_file'],
                        $this->attach_path. $folder_app,
                        ( $tax_number),
                        (auth()->user()->FullName ?? null),
                        'Law',
                        (  (new LawCasesDelivery)->getTable() ),
                        $lawcasesdelivery->id,
                        'law_cases_delivery_file',
                        !empty($file['attach_description'])?$file['attach_description']:null
                    );
                }

                if( isset($file['attachfilein_id']) && !empty($file['attachfilein_id']) ){

                    $fileold = AttachFileLaw::find($file['attachfilein_id']);
                    if( !is_null($fileold ) ){
                        HP::CopyFile(
                            $fileold->url ,
                            $this->attach_path. $folder_app,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            (  (new LawCasesDelivery)->getTable() ),
                            $lawcasesdelivery->id,
                            'law_cases_delivery_file',
                            !empty($file['attach_description'])?$file['attach_description']:null
                        );
                    }

                }
            }

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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/delivery",  "name" => 'บันทึกจัดส่งหนังสือ' ],
                [ "link" => "/law/cases/delivery/".$id,  "name" => 'รายละเอียด' ],

            ];

            $lawcasesdelivery = LawCasesDelivery::findOrFail($id);

            return view('laws.cases.delivery.show',compact('breadcrumbs','lawcasesdelivery'));

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
        if(auth()->user()->can('edit-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/delivery",  "name" => 'บันทึกจัดส่งหนังสือ' ],
                [ "link" => "/law/cases/delivery/".$id."/edit",  "name" => 'แก้ไข' ],
            ];

            $lawcasesdelivery = LawCasesDelivery::findOrFail($id);

            return view('laws.cases.delivery.edit',compact('breadcrumbs','lawcasesdelivery'));

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
        if(auth()->user()->can('edit-'.$this->permission)) {

            $lawcasesdelivery                = LawCasesDelivery::findOrFail($id);
            $requestData                     = $request->all();
            $requestData['date_due']         = !empty(  $requestData['date_due'] ) ?  HP::convertDate( $requestData['date_due'],true) : null;
            $requestData['updated_by']       = auth()->user()->getKey();
            $requestData['send_mail_status'] = !empty( $requestData['send_mail_status'])?$requestData['send_mail_status']:null;
            $requestData['noti_email']       = !empty( $requestData['noti_email'])?explode(',',$requestData['noti_email']):null;

            $lawcasesdelivery->update($requestData);

            //บันทึกไฟล์
            $this->SaveFile( $lawcasesdelivery , $requestData  );

            //ส่งเมล
            $this->SendMail($lawcasesdelivery);

            return redirect('law/cases/delivery')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');

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
        if(auth()->user()->can('delete-'.$this->permission)) {
            #code
        }
        abort(403);
    }

    public function SendMail( $lawcasesdelivery )
    {
        //แจ้งเตือนไปยัง
        $notify_types  = [];
        if( !empty($lawcasesdelivery->send_mail_status) ){

            // เจ้าของงานคดี (ผู้แจ้ง)
            if( in_array( "owner" , $lawcasesdelivery->send_mail_status ) ){
                $notify_types[] = 2;
            }

            // ผู้ประสานงานคดี
            if( in_array( "coordinator" , $lawcasesdelivery->send_mail_status ) ){
                $notify_types[] = 3;
            }

            // ผู้มอบหมาย
            if( in_array( "assign" , $lawcasesdelivery->send_mail_status ) ){
                $notify_types[] = 4;
            }

            // ผู้กระทำผิด
            if( in_array( "offender" , $lawcasesdelivery->send_mail_status ) ){
                $notify_types[] = 6;
            }
  
        }

        //ช่องทางแจ้งเตือน
        $channels  = [];
        if( !empty($lawcasesdelivery->noti_sytem_status) ){
            $channels[] =  1;
        }
        if( !empty($lawcasesdelivery->noti_email_status) ){
            $channels[] =  2;
        }

        //อีเมลที่แจ้งเตือน
        $email_results = [];
        if( !empty($lawcasesdelivery->noti_email) ){
            foreach( $lawcasesdelivery->noti_email as $email ){
                if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                    $email_results[] =  $email;
                }
            }
        }

        $law_cases = $lawcasesdelivery->law_cases;
        
        $topic   = ($lawcasesdelivery->title).' - เลขคดี '.(!empty($law_cases->ref_no)?$law_cases->ref_no:null);
        $subject = ($lawcasesdelivery->title).' - เลขคดี '.(!empty($law_cases->ref_no)?$law_cases->ref_no:null);
        $learn   = !empty($lawcasesdelivery->send_to)?$lawcasesdelivery->send_to:null;


        $content = '';
        $content .= '<table width="100%">';
        $content .= '<tr>';
        $content .= '<td valign="top" width="20%"><b>เลขคดี</b></td>';
        $content .= '<td valign="top" width="80%">'.(!empty($law_cases->ref_no)?$law_cases->ref_no:null).'</td>';
        $content .= '</tr>';

        $content .= '<tr>';
        $content .= '<td valign="top" width="20%"><b>ผู้ประกอบการ</b></td>';
        $content .= '<td valign="top" width="80%">'.(!empty($law_cases->owner_name)?$law_cases->owner_name:null).'</td>';
        $content .= '</tr>';

        $content .= '<tr>';
        $content .= '<td valign="top" width="20%"><b>ประเภทหนังสือ</b></td>';
        $content .= '<td valign="top" width="80%">';
        if( isset($lawcasesdelivery->file_law_cases_delivery) && ($lawcasesdelivery->file_law_cases_delivery->count() >= 1) ){
            foreach ($lawcasesdelivery->file_law_cases_delivery as $key => $Ifile ){
                $content .= '<div>'.($key+1).' '.(!empty($Ifile->caption)?$Ifile->caption:null).' <a href="'. HP::getFileStorage($Ifile->url) .'" target="_blank" class="m-l-5">'.(!empty($Ifile->filename) ? $Ifile->filename : '').'</a></div>';
            }
        }
        $content .= '</td>';
        $content .= '</tr>';

        if( !empty($lawcasesdelivery->remark) ){
            $content .= '<tr>';
            $content .= '<td valign="top" width="20%"><b>หมายเหตุ</b></td>';
            $content .= '<td valign="top" width="80%">'.(!empty($lawcasesdelivery->remark)?$lawcasesdelivery->remark:null).'</td>';
            $content .= '</tr>';
        }

        if( !empty($lawcasesdelivery->date_due)  ){

            $config = HP::getConfig(false);

            $content .= '<tr>';
            $content .= '<td valign="top" width="20%"><b>เงื่อนไข</b></td>';
            $content .= '<td valign="top" width="80%">ตอบกลับ ภายในวันที่ '.(!empty($lawcasesdelivery->date_due)?HP::formatDateThaiFull($lawcasesdelivery->date_due):null).'</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" width="20%"><b>ลิงค์สำหรับตอบกลับ</b></td>';
            $content .= '<td valign="top" width="80%"><a href="'.url($config->url_law.'law/cases/delivery/'.( base64_encode(($lawcasesdelivery->id) ))).'" target="_blank" class="m-l-5">คลิก</a></td>';
            $content .= '</tr>';
        }

        $content .= '</table>';

        HP_Law::getInsertLawNotifyEmail(
            1,
            ((new LawCasesDelivery)->getTable()),
            $lawcasesdelivery->id,
            'งานคดี : บันทึกจัดส่งหนังสือ',
            $topic,
            view('mail.Law.Cases.template-mail', [ 'topic' => $topic, 'subject' => $subject, 'learn' => $learn, 'content' => $content ] ),
            (count($channels) > 0 ?  json_encode($channels)  : null),  
            (count($notify_types) > 0 ?  json_encode($notify_types)  : null),   
            json_encode($email_results)   
        );

        if( count($email_results) >= 1 && !empty($lawcasesdelivery->noti_email_status) ){
            $html = new MailCasesTemplate([
                'topic'   => $topic,
                'subject' => $subject,
                'learn'   => $learn,
                'content' => $content
            ]);
            Mail::to($email_results)->send($html);
        }

    }
}
