<?php

namespace App\Http\Controllers\Laws\Cases;

use HP;
use HP_Law;
use App\Http\Requests;

use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Law\Cases\LawCasesForm;
use App\Models\Law\Cases\LawCasesResult;
use App\Models\Law\Cases\LawCasesImpound;
use App\Mail\Mail\Law\Cases\MailCasesTemplate;
use App\Models\Law\Cases\LawCasesProductResult;
use App\Models\Law\Cases\LawCasesImpoundProduct;
use App\Models\Law\Cases\LawCaseProductOperations;

class LawCasesManageProductsController extends Controller
{
    private $attach_path;
    private $attach_path_operations;
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/cases_forms';
        $this->attach_path_operations = 'law_attach/cases_product_operations';
        $this->permission  = str_slug('law-cases-manage-products','-');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search  = $request->input('filter_condition_search');
        $filter_search            = $request->input('filter_search');

        $filter_status            = $request->input('filter_status');

        $filter_standard          = $request->input('filter_standard');
        $filter_license_number    = $request->input('filter_license_number');

        $model                    = str_slug('law-cases-manage-products','-');

        $query = LawCasesForm::query()
                                    ->whereHas('law_cases_result_to',function($query) {
                                        $query->where('product', '1');
                                    })
                                    ->where(function($query){
                                        $query->whereNotIn('status',['0','99']);
                                    })
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "2":
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            case "3":
                                                return $query->where(function($query) use ($search_full){
                                                                        $query->where(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                            default:
                                                return $query->where(function($query) use ($search_full){
                                                                    $query->where(DB::raw("REPLACE(case_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_taxid,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->orWhere(DB::raw("REPLACE(offend_license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_standard, function ($query, $filter_standard){
                                        return $query->where(function($query) use ($filter_standard){
                                                        $query->where('tis_id', $filter_standard);
                                                    });
                                    })
                                    ->when($filter_license_number, function ($query, $filter_license_number){
                                        return $query->where( function($query) use ($filter_license_number){
                                                        $query->where('offend_tb4_tisilicense_id', $filter_license_number);
                                                    });
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if($filter_status == 1){
                                            return $query->whereNull('status_impound');
                                        }else{
                                            return $query->whereNotNull('status_impound');
                                        }
                                    })
                                    ->when(!auth()->user()->can('view_all-'.$model), function($query) {//ถ้าไม่เลือกดูได้เฉพาะรายการที่ได้รับมอบหมาย
                                        $query->where(function($query){
                                            $query->where('lawyer_by', Auth::user()->getKey())
                                                ->Orwhere('assign_by', Auth::user()->getKey());
                                        });            
                                    });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('law_case_no', function ($item) {
                                $text  = !empty($item->case_number) ? $item->case_number : '';
                                $text  .= !empty($item->ref_no) ? '<br/><span class="text-muted">'.$item->ref_no.'</span>' : '';
                                return $text;
                            })
                            ->addColumn('law_case_name', function ($item) {
                                return ( !empty( $item->offend_name )? $item->offend_name:null  ).('<div><em>('.(!empty( $item->offend_taxid )? $item->offend_taxid:null ).')</em></div>');
                            })
                            ->addColumn('law_license_number', function ($item) {
                                return ( !empty( $item->StandardNo )? $item->StandardNo:null  ).('<div>'.(!empty( $item->offend_license_number )? $item->offend_license_number:null ).'</div>');
                            })
                            ->addColumn('detail', function ($item) {
                                return !empty($item->tis)?$item->tis->tb3_TisThainame:null;
                            }) 
                            ->addColumn('process_product', function ($item) {
                                return !empty($item->product_result)?$item->product_result->ProcessProductName:'<i class="text-muted">รอดำเนินการ</i>';
                            }) 
                            ->addColumn('status_impound', function ($item) {
                                return $item->StatusImpoundColorHtml;
                            }) 
                            ->addColumn('status_operations', function ($item) {
                                    return !empty($item->product_result->operations_to)?$item->product_result->operations_to->StatusText:'<i class="text-muted">รอดำเนินการ</i>';
                            })
                            ->addColumn('law_section', function ($item) {
                            //    return !empty($item->section_list)?($item->SectionListName):null;
                                return !empty($item->law_cases_result_to)?$item->law_cases_result_to->OffenseSectionNumber:'n/a';
                            })
                            ->addColumn('lawyer_name', function ($item) {
                                return !empty($item->user_lawyer_to)?$item->user_lawyer_to->FullName:'-';
                            })
                            ->addColumn('action', function ($item) {
                                $canview = auth()->user()->can('view-'.$this->permission);
                                $canedit = auth()->user()->can('edit-'.$this->permission);
                                $btn_color   = !empty($item->product_result)?'btn-light-success':'btn-light-warning';

                                if(!empty($item->status_close == 1)  && $item->status_close == 1){
                                    return self::buttonActionLaw( $item->id, 'law/cases/manage-products','Laws\Cases\\LawCasesManageProductsController@destroy', 'law-cases-manage-products',$canview, false, $btn_color);
                                }else{
                                    return self::buttonActionLaw( $item->id, 'law/cases/manage-products','Laws\Cases\\LawCasesManageProductsController@destroy', 'law-cases-manage-products',$canview, $canedit, $btn_color);
                                }

                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawCasesForm)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'action', 'law_case_no', 'law_case_name','law_license_number','detail', 'process_product', 'status_impound','status_operations'])
                            ->make(true);

    }


    public static function buttonActionLaw($id, $action_url, $controller_action, $str_slug_name, $show_view = true, $show_edit = true, $btn_color)
    {
        $form_action = '';

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-' . str_slug($str_slug_name)) && $show_view === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id) . '" title="ดูรายละเอียด ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle btn-light-info"><i class="fa fa-info-circle"  style="font-size: 1.5em;"></i></a>';
            
        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/edit') . '"title="แก้ไข ' . substr($str_slug_name, 0, -1) . '" class="btn btn-icon btn-circle '.$btn_color.'"><i class="fa fa-pencil-square-o"  style="font-size: 1.5em;"></i></a>';

        endif;

        if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-' . str_slug($str_slug_name)) && $show_edit === true && $btn_color == 'btn-light-success'):
            $form_action .= ' <a href="' . url('/' . $action_url . '/' . $id . '/report') . '"title="รายงานผลการติดตาม" class="btn btn-icon btn-circle btn-light-primary"><i class="fa fa-file-text-o"  style="font-size: 1.5em;"></i></a>';

        endif;

        return $form_action;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage-products",  "name" => 'ดำเนินการกับผลิตภัณฑ์' ],
            ];
            return view('laws.cases.manage-products.index',compact('breadcrumbs'));

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
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('add-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage-products",  "name" => 'ดำเนินการกับผลิตภัณฑ์' ],
            ];


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
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('add-'.$model)) {


        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('view-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage-products",  "name" => 'ดำเนินการกับผลิตภัณฑ์' ],
                [ "link" => "/law/cases/manage-products/".$id,  "name" => 'รายละเอียด' ],
            ];

            $lawcases = LawCasesForm::findOrFail($id);
            return view('laws.cases.manage-products.show',compact('breadcrumbs','lawcases'));
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
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('edit-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage-products",  "name" => 'ดำเนินการกับผลิตภัณฑ์' ],
            ];

            $lawcases = LawCasesForm::findOrFail($id);


            return view('laws.cases.manage-products.edit',compact('breadcrumbs','lawcases'));

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
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData  = $request->all();

            $lawcases = LawCasesForm::findOrFail($id);

            if( !empty( $lawcases ) ){
                $lawcases->update(['status_impound'=>1]);
            }


            $result = LawCasesProductResult::updateOrCreate(
                                                        [
                                                            'law_cases_id'                 => $lawcases->id,
                                                        ],
                                                        [
                                                            'law_cases_id'                 => $lawcases->id,
                                                            'result_process_product_id'    => $requestData['result_process_product_id'],
                                                            'result_description'           => !empty($requestData['result_description'])?$requestData['result_description']:null,
                                                            'result_start_date'            => !empty($requestData['result_start_date'])?HP::convertDate($requestData['result_start_date'],true):null,
                                                            'result_end_date'              => !empty($requestData['result_end_date'])?HP::convertDate($requestData['result_end_date'],true):null,
                                                            'result_amount'                => !empty($requestData['result_amount'])?$requestData['result_amount']:null,
                                                            'result_remark'                => !empty($requestData['result_remark'])?$requestData['result_remark']:null,
                                                            'result_by'                    => auth()->user()->getKey(),
                                                            'result_at'                    => date('Y-m-d H:i:s')
                                                        ]
                                                    );

                                                    
            LawCasesImpound::where('law_case_id', $lawcases->id)->update(['status' => 1]);

            if($request->hasFile('file_result_tisi')){

                $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

                $folder_app = (!empty($lawcases->ref_no)?$lawcases->ref_no:null ).'/ProductResult/';

                HP::singleFileUploadLaw(
                    $request->file('file_result_tisi') ,
                    $this->attach_path. $folder_app,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawCasesProductResult)->getTable() ),
                    $result->id,
                    'law_cases_product_results',
                    'หลักฐานคําสั่งคณะกรรมการอุตสาหกรรม (กมอ.)'
                );
            }

            $this->SendMail($lawcases,  $requestData);


            return redirect('law/cases/manage-products')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function report($id)
    {
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('edit-'.$model)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/manage-products",  "name" => 'ดำเนินการกับผลิตภัณฑ์' ],
            ];

            $lawcases = LawCasesForm::findOrFail($id);

            return view('laws.cases.manage-products.report',compact('breadcrumbs','lawcases'));

        }
        abort(403);
    }

    public function save_report(Request $request, $id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $product_results = LawCasesProductResult::where('law_cases_id',$id)->first();
     
            $requestData = $request->all();
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

            if( isset($requestData['repeater-operation']) ){

                $list = $requestData['repeater-operation'];
                $list_id_data = [];
                foreach($list as $lists){
                    if(isset($lists['product_operations_id'])){
                        $list_id_data[] = $lists['product_operations_id'];
                    }
                }
                $lists_id = array_diff($list_id_data, [null]);
    
                $product_operations_old =  LawCaseProductOperations::where('law_cases_product_results_id',$product_results->id)
                                            ->when($lists_id, function ($query, $lists_id){
                                                return $query->whereNotIn('id', $lists_id);
                                            });
        
                if(!empty($product_operations_old->get()) && count($product_operations_old->get()) > 0){
                    foreach(  $product_operations_old->get()  AS $item ){
                        //ลบไฟล์เดิม
                        $attachs_product_operations = $item->AttachFileOperations;
                        HP_Law::DeleteLawSingleFile($attachs_product_operations);
                    }
                    //ลบข้อมูลเดิม
                    $product_operations_old->delete();
                }
      
                foreach( $list as $item ){
                    $product_operations =  LawCaseProductOperations::where('id',$item['product_operations_id'])->first();
                    if(is_null($product_operations)){
                        $product_operations             = new LawCaseProductOperations;
                        $product_operations->created_by = auth()->user()->getKey();
                    }else{
                        $product_operations->updated_by = auth()->user()->getKey();
                    }
    
                    $product_operations->law_cases_product_results_id    = $product_results->id;
                    $product_operations->operation_date                  = !empty( $item['operation_date'] )?HP::convertDate($item['operation_date'],true) : null;
                    $product_operations->due_date                        = !empty( $item['due_date'] )?HP::convertDate($item['due_date'],true) : null;
                    $product_operations->status_job_track_id             = !empty( $item['status_job_track_id'])?$item['status_job_track_id']:null;
                    $product_operations->detail                          = !empty( $item['detail'])?$item['detail']:null;
                    $product_operations->save();

                    //อัพโหลดไฟล์
                    if( isset($item['file_law_product_operations']) && !empty($item['file_law_product_operations']) ){
                        HP::singleFileUploadLaw(
                            $item['file_law_product_operations'],
                            $this->attach_path_operations,
                            ( $tax_number),
                            (auth()->user()->FullName ?? null),
                            'Law',
                            (  (new LawCaseProductOperations)->getTable() ),
                            $product_operations->id,
                            'file_law_product_operations',
                            'ดำเนินการกับผลิตภัณฑ์'
                        );
                    }
                }
    
            }
    
            return redirect('law/cases/manage-products')->with('flash_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('law-cases-manage-products','-');
        if(auth()->user()->can('delete-'.$model)) {


        }
        abort(403);
    }

    public function SendMail( $lawcases, $requestData )
    {
        if( !empty($requestData['channel']) ){

            // ช่องทางแจ้งเตือน
            $channels  = $requestData['channel'];

            //แจ้งเตือนไปยัง
            $notify_types  = [];
            if( !empty($requestData['notify_types']) ){
                $notify_types = $requestData['notify_types'];
            }

            //อีเมลที่แจ้งเตือน
            $email_results = [];
            if( !empty($requestData['noti_email']) ){
                foreach( explode(",",$requestData['noti_email']) as $email ){
                    if(filter_var($email, FILTER_VALIDATE_EMAIL)  && !in_array($email,$email_results)){
                        $email_results[] =  $email;
                    }
                }
            }

            $law_cases      = $lawcases;
            $cases_impound  = $lawcases->law_cases_impound_to;
            $product_result = $lawcases->product_result;

            $topic   = 'แจ้งผลคำสั่งการดำเนินการกับผลิตภัณฑ์ที่ยัดและอายัดไว้ - เลขคดี '.(!empty($law_cases->case_number)?$law_cases->case_number:null);
            $subject = 'แจ้งผลมติคำสั่งการดำเนินการกับผลิตภัณฑ์ที่ยัดและอายัดไว้ - เลขคดี '.(!empty($law_cases->case_number)?$law_cases->case_number:null);
            $learn   = 'ผู้ประกอบการ และเจ้าหน้าที่ผู้เกี่ยวข้อง';

            
            $content = '';
            $content .= '<table width="100%">';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">&nbsp;&nbsp;&nbsp;ตามที่พบการกระทำความผิดของ'.( !empty($law_cases->offend_name)?$law_cases->offend_name:null ).' และได้มีการยึดผลิตภัณฑ์อุตสาหกรรมไว้เมื่อวันที่ '.( !empty($cases_impound->date_impound)?HP::formatDateThaiFull($cases_impound->date_impound):null ).' นั้น</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">คณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรมได้มีคําสั่งให้ดำเนินการกับผลิตภัณฑ์ ดังนี้</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2"ดำเนินการกับผลิตภัณฑ์'.(!empty($product_result->ProcessProductName)?$product_result->ProcessProductName:null).'</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">โดยวิธีการ '.(!empty($product_result->result_description)?$product_result->result_description:null).'</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">วันที่มีคำสั่ง '.(!empty($product_result->result_start_date)?HP::formatDateThaiFull($product_result->result_start_date):null).'</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">ดำเนินการภายใน/วัน '.(!empty($product_result->result_amount)?$product_result->result_amount:null).' วัน</td>';
            $content .= '</tr>';

            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">วันที่เสร็จสิ้น '.(!empty($product_result->result_end_date)?HP::formatDateThaiFull($product_result->result_end_date):null).'</td>';
            $content .= '</tr>';

            if( !empty($product_result->file_law_result) ){
                $file_law_result = $product_result->file_law_result;
                $content .= '<tr>';
                $content .= '<td valign="top" colspan="2">หลักฐานคำสั่งฯ <a href="'.HP::getFileStorage($file_law_result->url).'" target="_blank" class="m-l-5">'.(!empty($file_law_result->filename) ? $file_law_result->filename : '').'</a></td>';
                $content .= '</tr>';
            }
            
            $content .= '<tr>';
            $content .= '<td valign="top" colspan="2">หลังจากผู้กระทำความผิดดําเนินการเสร็จสิ้น ต้องแสดงหลักฐานภาพถ่ายการดําเนินการ หรือเอกสารอื่นใดให้ สมอ.ตรวจสอบ</td>';
            $content .= '</tr>';

            $content .= '</table>';
            
            HP_Law::getInsertLawNotifyEmail(
                1,
                ((new LawCasesForm)->getTable()),
                $lawcases->id,
                'งานคดี : ดำเนินการกับผลิตภัณฑ์',
                $topic,
                view('mail.Law.Cases.template-mail', [ 'topic' => $topic, 'subject' => $subject, 'learn' => $learn, 'content' => $content ] ),
                (count($channels) > 0 ?  json_encode($channels)  : null),  
                (count($notify_types) > 0 ?  json_encode($notify_types)  : null),   
                json_encode($email_results)   
            );
    
            if( count($email_results) >= 1 ){
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
}
