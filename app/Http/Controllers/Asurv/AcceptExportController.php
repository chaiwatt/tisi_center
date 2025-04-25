<?php

namespace App\Http\Controllers\Asurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\accept_export;
use App\Models\Asurv\EsurvTers20;
use App\Models\Asurv\EsurvTers20detail;
use App\Models\Csurv\ControlCheck;
use App\Models\Basic\Tis;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;

use App\Models\Besurv\Signer;
use App\Models\Besurv\TisSubDepartment;
use App\Models\Besurv\Department;
use App\Models\Basic\SubDepartment;
use App\Models\Sso\User AS SSO_User;

use App\Qrcode as qr_code;

use stdClass;
use HP;
use DB;
use QrCode;
use File;
use Storage;
use HP_API_PID;
use Carbon\Carbon;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Mpdf\Mpdf;

class AcceptExportController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/applicant_20ter/';
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $filter = [];
        $filter['perPage']                  = $request->get('perPage', 10);
        $filter['filter_start_month']       = $request->get('filter_start_month', '');
        $filter['filter_start_year']        = $request->get('filter_start_year', '');
        $filter['filter_end_month']         = $request->get('filter_end_month', '');
        $filter['filter_end_year']          = $request->get('filter_end_year', '');
        $filter['filter_notify']            = $request->get('filter_notify', '');
        $filter['filter_request']           = $request->get('filter_request', '');
        $filter['filter_title']             = $request->get('filter_title', '');
        $filter['filter_department']        = $request->get('filter_department', '');
        $filter['filter_sub_department']    = $request->get('filter_sub_department', '');
        $filter['filter_tis']               = $request->get('filter_tis', '');
        $Query = new EsurvTers20;

        if ($filter['filter_request']!='') {
            $Query = $Query->where('state', $filter['filter_request']);
        }
        if ($filter['filter_notify']!='') {
            $Query = $Query->where('state_check', $filter['filter_notify']);
        }
        if ($filter['filter_start_month']!='') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'].'-'.$filter['filter_start_month'].'-01'.' 00:00:00');
        }

        if ($filter['filter_end_month']!='') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'].'-'.$filter['filter_end_month'].'-31'.' 00:00:00');
        }

        if($filter['filter_title']!=''){
            $trader_ids = SSO_User::where('name', 'LIKE', '%'.$filter['filter_title'].'%')->pluck('id');
            $Query = $Query->where('ref_no', 'LIKE', '%'.$filter['filter_title'].'%')->orWhere('title', 'like', '%'.$filter['filter_title'].'%')->orWhereIn('created_by', $trader_ids);
        }

        if($filter['filter_tis']!=''){
            $Query = $Query->orwhereJsonContains('different_no',$filter['filter_tis']);
        }


        if($filter['filter_department']!='' || $filter['filter_sub_department']!=''){
            $department                  = $filter['filter_department'];
            $filter_sub_department     = $filter['filter_sub_department'];

            if($filter_sub_department != ''){
                $sub_department = SubDepartment::where('did', $department)->where('sub_id', $filter_sub_department)->select('sub_id');

            }else{
                $sub_department = SubDepartment::where('did', $department)->select('sub_id');
            }

            $tis_sub        = TisSubDepartment::whereIn('sub_id', $sub_department)->select('tb3_Tisno');

            $tb3_tis_autono = Tis::whereIn('tb3_Tisno',$tis_sub)->pluck('tb3_TisAutono')->toArray();
            if(count($tb3_tis_autono) > 0){
                $select = [];
                foreach($tb3_tis_autono as $item){
                        $applicant_20ter_ids = EsurvTers20::orwhereJsonContains('different_no',explode(" ",$item))->select('id')->get();
                        if(count($applicant_20ter_ids) > 0){
                            foreach($applicant_20ter_ids as $item1){
                                $select[$item1->id] = $item1->id;
                           }
                        }
                }
                $Query = $Query->whereIn('id', $select);
            }else{
                $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
            }


        }


        $accept_export = $Query->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        $temp_num = $accept_export->firstItem();

        return view('asurv.accept_export.index', compact('accept_export', 'filter', 'temp_num'));
    }

    public function create()
    {
        return view('asurv.accept_export.create');
    }

    public function show($id)
    {
        return view('asurv.accept_export.show');
    }

    public function edit($id)
    {
        $data = EsurvTers20::findOrFail($id);

        $data->start_date = $data->start_date?HP::revertDate($data->start_date, true):null;
        $data->end_date = $data->end_date?HP::revertDate($data->end_date, true):null;
 
        $data_detail = EsurvTers20detail::query()->where('applicant_20ter_id',$id)->get();

        $applicant_20ter = EsurvTers20::findOrFail($id);

        $attach_product_plan = json_decode($applicant_20ter['attach_product_plan']);
        $attach_product_plan = !empty($attach_product_plan)?$attach_product_plan:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_purchase_order = json_decode($applicant_20ter['attach_purchase_order']);
        $attach_purchase_order = !empty($attach_purchase_order)?$attach_purchase_order:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_factory_license = json_decode($applicant_20ter['attach_factory_license']);
        $attach_factory_license = !empty($attach_factory_license)?$attach_factory_license:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_standard_to_made = json_decode($applicant_20ter['attach_standard_to_made']);
        $attach_standard_to_made = !empty($attach_standard_to_made)?$attach_standard_to_made:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_difference_standard = json_decode($applicant_20ter['attach_difference_standard']);
        $attach_difference_standard = !empty($attach_difference_standard)?$attach_difference_standard:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_permission_letter = json_decode($applicant_20ter['attach_permission_letter']);
        $attach_permission_letter = !empty($attach_permission_letter)?$attach_permission_letter:(object)['file_name'=>'', 'file_client_name'=>''];

        //ไฟล์แนบ

        $data_file_check = EsurvTers20::query()->where('id',$id)->first();
        if ($data_file_check->attach_other!='[]' and $data_file_check->attach_other!=null){
            $attachs = json_decode($applicant_20ter['attach_other']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
        }else{
            $attachs = null;
        }
        $attach_path = $this->attach_path; //path ไฟล์แนบ


        $tb3_tisno      = [];
        $signer_options = [];
        $user = auth()->user();
         if($user->isAdmin() === true){
            $signer_options         =  Signer::where('state',1)->pluck('name', 'id');
         }else{
            $sub_depart             = SubDepartment::where('sub_id', $user->reg_subdepart)->pluck('did');
            $department             = Department::whereIn('did', $sub_depart)->pluck('did');
            $signer_options         =  Signer::whereJsonContains('main_group', $department)->where('state',1)->pluck('name', 'id');
         }
        $tis_no = json_decode($data->different_no);
        if(!empty($tis_no)){
            $tb3_tisno              = Tis::select('tb3_Tisno','tb3_TisThainame')->whereIn('tb3_TisAutono',$tis_no)->get();
            // $tis_sub_department     = TisSubDepartment::whereIn('tb3_Tisno', $tb3_tisno->pluck('tb3_Tisno'))->pluck('sub_id');
            // $sub_depart             = SubDepartment::whereIn('sub_id', $tis_sub_department)->pluck('did');
            // $department             = Department::whereIn('did', $sub_depart)->pluck('did');
            // $signer_options         = Signer::whereJsonContains('main_group', $department)->where('state',1)->pluck('name', 'id');
        }

        return view('asurv.accept_export.edit',["data"=>$data,'data_detail'=>$data_detail,'signer_options'=> $signer_options], compact(
                                                'applicant_20ter',
                                                'attach_product_plan',
                                                'attach_purchase_order',
                                                'attach_factory_license',
                                                'attach_standard_to_made',
                                                'attach_difference_standard',
                                                'attach_permission_letter',
                                                'attachs',
                                                'attach_path',
                                                'tb3_tisno'
                                               ));
    }

    public function update(Request $request)
    {
        return redirect('accept_export/accept_export');
    }

    public function save_data(Request $request){
        $data = EsurvTers20::findOrFail($request->id);
        $data->remake_officer_export    = $request->remake_officer_export;
        $data->state                    = $request->state;
        $data->officer_export           = $request->officer_export;
        $data->signer_id                = $request->signer_id;
        $data->signer_name              = $request->signer_name;
        $data->signer_position          = $request->signer_position;
        if ($data->state=='4'){
            $data->state_check = 1;
            $data->state_approved_date = date('Y-m-d');
        }else{
            $data->state_check = 0;
        }

        if ($data->save()){
            return response()->json([
                'status'=>'success'
            ]);
        }
    }
    public function download_file($NAME){
        // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
        // return response()->download($public . $this->attach_path.$NAME);
        $public = public_path();
        $attach_path = $this->attach_path;
        if(HP::checkFileStorage($attach_path. $NAME)){
            HP::getFileStoragePath($attach_path. $NAME);
            $filePath =  response()->download($public.'/uploads/'.$attach_path.$NAME);
                return $filePath;
        }else{
            return 'ไม่พบไฟล์';
        }
    }
    public function detail($ID){
        $data = EsurvTers20::findOrFail($ID);
        
        $data->start_date = $data->start_date?HP::revertDate($data->start_date, true):null;
        $data->end_date = $data->end_date?HP::revertDate($data->end_date, true):null;

        $data_detail = EsurvTers20detail::query()->where('applicant_20ter_id',$ID)->get();

        $applicant_20ter = EsurvTers20::findOrFail($ID);

        $attach_product_plan = json_decode($applicant_20ter['attach_product_plan']);
        $attach_product_plan = !empty($attach_product_plan)?$attach_product_plan:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_purchase_order = json_decode($applicant_20ter['attach_purchase_order']);
        $attach_purchase_order = !empty($attach_purchase_order)?$attach_purchase_order:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_factory_license = json_decode($applicant_20ter['attach_factory_license']);
        $attach_factory_license = !empty($attach_factory_license)?$attach_factory_license:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_standard_to_made = json_decode($applicant_20ter['attach_standard_to_made']);
        $attach_standard_to_made = !empty($attach_standard_to_made)?$attach_standard_to_made:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_difference_standard = json_decode($applicant_20ter['attach_difference_standard']);
        $attach_difference_standard = !empty($attach_difference_standard)?$attach_difference_standard:(object)['file_name'=>'', 'file_client_name'=>''];

        $attach_permission_letter = json_decode($applicant_20ter['attach_permission_letter']);
        $attach_permission_letter = !empty($attach_permission_letter)?$attach_permission_letter:(object)['file_name'=>'', 'file_client_name'=>''];

        //ไฟล์แนบ

        $data_file_check = EsurvTers20::query()->where('id',$ID)->first();
        if ($data_file_check->attach_other!='[]' and $data_file_check->attach_other!=null){
            $attachs = json_decode($applicant_20ter['attach_other']);
            $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
        }else{
            $attachs = null;
        }
        $attach_path = $this->attach_path; //path ไฟล์แนบ

        $tb3_tisno      = [];
        $tis_no = json_decode($data->different_no);
        if(!empty($tis_no)){
            $tb3_tisno              = Tis::select('tb3_Tisno','tb3_TisThainame')->whereIn('tb3_TisAutono',$tis_no)->get();

        }

        return view('asurv.accept_export.detail',["data"=>$data,'data_detail'=>$data_detail], compact(
            'applicant_20ter',
            'attach_product_plan',
            'attach_purchase_order',
            'attach_factory_license',
            'attach_standard_to_made',
            'attach_difference_standard',
            'attach_permission_letter',
            'attachs',
            'attach_path',
            'tb3_tisno'
        ));
    }
    public function update_status($ID,$STATE){
        $data = EsurvTers20::findOrFail($ID);
        if ($STATE=='0'){
            $data->state_check = 1;
        }else{
            $data->state_check = 0;
        }

        if ($data->save()){
            $filter = [];
            $filter['perPage'] =10;
            $filter['filter_department']        = '';
            $filter['filter_sub_department']    = '';
            $filter['filter_tis']               = '';
            $Query = new EsurvTers20;

            $accept_export = $Query->sortable()->paginate($filter['perPage']);
            $temp_num = $accept_export->firstItem();

            return view('asurv.accept_export.index', compact('accept_export', 'filter', 'temp_num'));
        }
    }

    public function ExportPDF($id=null)
    {
		if(!is_null($id)){
            $applicant20ter = EsurvTers20::Where('id', $id)->first();
			$ref_arr = explode("-", $applicant20ter->ref_no);
            $ref_no_number = $ref_arr[0];

            $numberforshow = self::genReceivingNumber($applicant20ter->different_no, $applicant20ter->ref_no,$applicant20ter->state_approved_date);
            $data_export = [
                        'different_no'         =>  $applicant20ter->different_no,
                        'numberforshow'        =>  $numberforshow,
                        'created_name'         =>  $applicant20ter->CreatedName, // ชื่อบริษัท
                        'applicant_name'       =>  $applicant20ter->applicant_name, // ชื่อผู้ยื่น
                        'applicant_position'   =>  $applicant20ter->applicant_position, //ตำแหน่ง
                        'ref_no_number'        =>  $ref_no_number,
                        'approved_date'        =>  $applicant20ter->state_approved_date,
                        'signer_name'          =>  $applicant20ter->signer_name ?? '',
                        'signer_position'      =>  $applicant20ter->signer_position ?? ''
                       ];

            $pdf = PDF::loadView('asurv/accept_export/pdf/document', $data_export);
            $files =   "applicant_20ter_".$id;  // ชื่อไฟล์

            $path = 'files/accept_export/20TerPdf/'. $files.'.pdf';
            $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
            if (File::exists($public . $path)) {
                 File::delete($public . $path);
             }
            Storage::put($path, $pdf->output());
            return  $pdf->download($files.'.pdf');
        }
        abort(403);
    }

    public function pdf_download($id){
        if(!is_null($id)){

            $applicant20ter = EsurvTers20::Where('id', $id)->first();
            $ref_arr = explode("-", $applicant20ter->ref_no);

            if(!is_null($applicant20ter->app_no)){
                $numberforshow          = HP::toThaiNumber($applicant20ter->app_no);
            }else{
                $number                 = self::genReceivingNumber($applicant20ter->different_no, $applicant20ter->ref_no,$applicant20ter->state_approved_date);
                $numberforshow          = HP::toThaiNumber($number);
                $applicant20ter->app_no =  !empty($number) ? $number : null;
                $applicant20ter->save();
            }
            $qrcode20  =  qr_code::select('qrcode_announce','qrcode_link')->where('type_of_ter',20)->where('qrcode_state',1)->first();
            if(!is_null($qrcode20)){
                $url    = $qrcode20->qrcode_link ?? "";
                // $image_qr = QrCode::size(50)->generate($url);
                $image_qr = QrCode::format('png')->size(100)->generate($url)  ;
            }else{
                 $image_qr =  null;
                 $url       = null;
            }


            $data_export = [
                'different_no'         =>  $applicant20ter->different_no,
                'numberforshow'        =>  $numberforshow,
                'created_name'         =>  $applicant20ter->CreatedName, // ชื่อบริษัท
                'applicant_name'       =>  $applicant20ter->applicant_name, // ชื่อผู้ยื่น
                'applicant_position'   =>  $applicant20ter->applicant_position, //ตำแหน่ง
                'ref_no_number'        =>  $ref_arr[0],
                'approved_date'        =>  $applicant20ter->state_approved_date,
                'signer_name'          =>  $applicant20ter->signer_name ?? '',
                'signer_position'      =>  $applicant20ter->signer_position ?? '',
                'image_qr'             => $image_qr,
                'url'                  => $url,
                'qrcode_announce'      => $qrcode20->qrcode_announce ?? ""
               ];

             $mpdf = new Mpdf([
                                'format'            => 'A4',
                                'mode'              => 'utf-8',
                                'default_font_size' => '15',
                             ]);

            $title = "รับคำขอการทำผลิตภัณฑ์เพื่อส่งออก20 ตรี_".date('Ymd_hms').".pdf";
            $html  = view('asurv/accept_export/pdf/document', $data_export);
            $mpdf->WriteHTML($html);

            $qrcode20  =  qr_code::select('attach')->where('type_of_ter',20)->where('attach_state',1)->first();
            if(!is_null($qrcode20) && !is_null($qrcode20->attach)){

                // $public  =  Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

                $fullFileName =  basename($qrcode20->attach);

                $attach_path  =  'files/applicants/qrcode/';
                if(HP::checkFileStorage($attach_path.$fullFileName) ) {//ถ้ายังไม่มีไลฟ์ที่พร้อมแสดงอยู่แล้ว
                    HP::getFileStoragePath($attach_path.$fullFileName);
                    // header("Content-Encoding: None", true);

                    $mpdf->SetImportUse();
                    $dashboard_pdf_file         =  public_path('uploads/'.$attach_path.$fullFileName);

                    if(is_file($dashboard_pdf_file)){
                        $pagecount                  = $mpdf->SetSourceFile($dashboard_pdf_file);
                        for ($i=1; $i<=$pagecount; $i++) {
                            $mpdf->AddPage();
                            $import_page = $mpdf->ImportPage($i);
                            $mpdf->UseTemplate($import_page);
                        }
                     }
               }
            }

            $mpdf->SetTitle($title);
            $mpdf->Output($title, 'D');


            // $pdf = PDF::loadView('asurv/accept_export/pdf/document', $data_export);
            // $files =   "applicant_20ter_".$id;  // ชื่อไฟล์
            // return $pdf->stream('document.pdf');
            // $path = 'files/accept_export/20TerPdf/'. $files.'.pdf';
            // $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
            // if (File::exists($public . $path)) {
            //      File::delete($public . $path);
            //  }
            // Storage::put($path, $pdf->output());
            // return  $pdf->download($files.'.pdf');
        }
        abort(403);
	}

    public function genReceivingNumber($different_no, $ref_no, $state_approved_date = null){


		$different_decode = json_decode($different_no);
		$moao_number = $different_decode[0];

        $nameShort =  self::depart_nameShort($moao_number);

		$tb3_Tisno = Tis::Where('tb3_TisAutono', $moao_number)->select('tb3_Tisno')->first();
		$tb3_Tisno_arr = explode("-", $tb3_Tisno->tb3_Tisno);
		$tis_no = $tb3_Tisno_arr[0];

		$ref_arr = explode("-", $ref_no);
		$ref_no_number = $ref_arr[0];


        if($state_approved_date != null){
            $today  = $state_approved_date;
            $dates  = explode('-', $today);
            $year   = ($dates[1]>9)? $dates[0]+1+543 : $dates[0]+543; // ปีงบประมาณ
        }else{
            $year = (date('n')>9)?date('Y')+1+543:date('Y')+543; // ปีงบประมาณ
        }

        return $nameShort."-20-".$tis_no."-".$ref_no_number."/".$year;
	}

	public static function depart_nameShort($tis_no = null){
         $request = 'กค';
         if(!empty($tis_no)){
             $tb3_tisno              = Tis::where('tb3_TisAutono',$tis_no)->pluck('tb3_Tisno');

             $tis_sub_department     = TisSubDepartment::whereIn('tb3_Tisno', $tb3_tisno)->pluck('sub_id');
             $sub_depart             = SubDepartment::whereIn('sub_id', $tis_sub_department)->pluck('did');
             $department             = Department::whereIn('did', $sub_depart)->first();
             if(!is_null($department)){
                $request = $department->depart_nameShort ?? 'กค';
             }
         }
		return $request;
	}


    public function GetTisLike(Request $request){

        $searchTerm = !empty($request->searchTerm)?$request->searchTerm:null;


        $tb3_tisno              = Tis::where('tb3_TisThainame',  'LIKE', "%$searchTerm%")
                                       ->orWhere('tb3_Tisno',  'LIKE', "%$searchTerm%")
                                        ->get();

        $data_list = [];
        if(count($tb3_tisno) > 0){
            foreach($tb3_tisno as $item){
                $data = new stdClass;
                $data->id = $item->tb3_TisAutono;
                $data->text = $item->tb3_Tisno.(!empty($item->tb3_TisThainame)?' : '.$item->tb3_TisThainame:null);

                $data_list[] = $data;
            }
        }
        echo json_encode($data_list,JSON_UNESCAPED_UNICODE);
    }

    public function check_api_pid(Request $request)
    {

        $data  =  EsurvTers20::findOrFail($request->id);

         return response()->json([
                                    'message' =>  HP_API_PID::CheckDataApiPid($data,(new EsurvTers20)->getTable())
                                 ]);
    }
}
