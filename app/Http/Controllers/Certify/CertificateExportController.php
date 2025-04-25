<?php

namespace App\Http\Controllers\Certify;

use App\Certificate;
use App\CertificateExport;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CertificationBranch;
use App\Models\Bcertify\InspectBranch;
use App\Models\Bcertify\TestBranch;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\Applicant\CertifyTestScope;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\Check;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use App\User;
use HP;
use QrCode;
use File;
use Response;
use Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\Lab\CertifyCertificateExport;
class CertificateExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('certificate','-');
        if(auth()->user()->can('view-'.$model)) {
            $filter = [];
            $filter['filter_start_date'] = $request->get('filter_start_date', '');
            $filter['filter_end_date'] = $request->get('filter_end_date', '');
            $filter['filter_start_date_exp'] = $request->get('filter_start_date_exp', '');
            $filter['filter_end_date_exp'] = $request->get('filter_end_date_exp', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', '');
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_assessment'] = $request->get('filter_assessment', '');
            $filter['filter_standard'] = $request->get('filter_standard', '');
            $filter['filter_cerType'] = $request->get('filter_cerType', '');

            $Query = new CertificateExport;

            if ($filter['filter_search']!='') {
                $Query = $Query->where('request_number','LIKE','%'.$filter['filter_search'].'%')
                    ->orWhere('certificate_file_number','LIKE','%'.$filter['filter_search'].'%');
            }

            if ($filter['filter_cerType']!='') {
                $Query = $Query->where('certificate_option',$filter['filter_cerType']);
            }

            if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date']);
                $Query = $Query->whereBetween('certified_date', [$start->toDateString(),$end->toDateString()]);

            }elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date']);
                $Query = $Query->whereDate('certified_date',$start->toDateString());
            }

            /////////////////////////////////

            if ($filter['filter_start_date_exp'] != null && $filter['filter_end_date_exp'] != null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date_exp']);
                $end = Carbon::createFromFormat('d/m/Y',$filter['filter_end_date_exp']);
                $Query = $Query->whereBetween('certified_exp', [$start->toDateString(),$end->toDateString()]);

            }elseif ($filter['filter_start_date_exp'] != null && $filter['filter_end_date_exp'] == null){
                $start = Carbon::createFromFormat('d/m/Y',$filter['filter_start_date_exp']);
                $Query = $Query->whereDate('certified_exp',$start->toDateString());
            }

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state',$filter['filter_state']);
            }


            if ($filter['filter_standard']!='') {
                $Query = $Query->where('formula_id',$filter['filter_standard']);
            }

            $certificates = $Query->orderby('id','desc')->paginate($filter['perPage']);

            return view('certify.certificate-export.index', compact('certificates', 'filter'));
        }
        abort(403);
    }

    public function create($lang)
    {

//        $data = [
//            'foo' => 'bar'
//        ];
//        $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-thai', $data);
//        return $pdf->stream('document.pdf');

        if ($lang == "th" || $lang == "en"){
            $model = str_slug('certificate','-');
            if(auth()->user()->can('add-'.$model)) {

                $requests = CertiLab::distinct('trader_id')->whereIn('status',['25','26','27'])->select('id','lab_type','trader_id','app_no')->orderby('id','desc')->get();
                foreach ($requests as $request){
                    $request->trader_name = $request->trader->name . " ( $request->app_no )";
                }

                return view('certify.certificate-export.create',[
                    'lang'          => $lang,
                    'requests'      => $requests,
                ]);
            }
            abort(403);
        }

        abort(403);
    }

    public function createStore(Request $request)
    {

        if ($request->lang == "th" || $request->lang == "en"){

            $lang                   = $request->lang;
            $certificate_no         = $request->certificate_no;
            $lab_name               = $request->lab_name;
            $lab_type               = $request->lab_type;
            // $address                = $request->address_no . ($request->lang == "th" ? " หมู่ที่ " : " Moo.") . $request->address_moo." ".$request->address_soi . " ".$request->address_road . " " . $request->address_district. " " .$request->address_subdistrict . " " . $request->address_province . " ". $request->address_postcode;

            $address                = $this->FormatAddress($request);

            $formula                = $request->formula;
            $accereditatio_no       = $request->accereditatio_no;
            $certificate_for        = CertiLab::find($request->certificate_for)->trader->name;

            $request_number         = $request->request_number;


            $issue_no               = $request->issue_no ?? 1;
            $scope_permanent        = $request->scope_permanent ? 1 : 0;
            $scope_site             = $request->scope_site ? 1 : 0;
            $scope_temporary        = $request->scope_temporary ? 1 : 0;
            $scope_mobile           = $request->scope_mobile ? 1 : 0;


            setlocale(LC_TIME, $lang);
            Carbon::setLocale($lang);
            // return $request;
            // if($lang == "th"){
            //     $certificate_date_start = $request->certificate_date_start ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_start)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
            //     $certificate_date_end = $request->certificate_date_end ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_end)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
            //     $certificate_date_first = $request->certificate_date_first ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_first)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
            // }else{
            //     $certificate_date_start = $request->certificate_date_start ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_start)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            //     $certificate_date_end = $request->certificate_date_end ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_end)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            //     $certificate_date_first = $request->certificate_date_first ?
            //         Carbon::createFromFormat("d/m/Y",$request->certificate_date_first)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            // }

            if($lang == "th"){
                $certificate_date_start = $request->certificate_date_start ? Carbon::createFromFormat("d/m/Y",$request->certificate_date_start)->addYear(-543)->formatLocalized('%d %B B.E. %Y'):"";

                $certificate_date_end   = $request->certificate_date_end ? Carbon::createFromFormat("d/m/Y",$request->certificate_date_end)->addYear(-543)->formatLocalized('%d %B B.E. %Y'):"";
                $certificate_date_first = $request->certificate_date_first ? Carbon::createFromFormat("d/m/Y",$request->certificate_date_first)->addYear(-543)->formatLocalized('%d %B B.E. %Y'):"";
            }else{
                $certificate_date_start = $request->certificate_date_start ?
                    Carbon::createFromFormat("d/m/Y",$request->certificate_date_start)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
                $certificate_date_end = $request->certificate_date_end ?
                    Carbon::createFromFormat("d/m/Y",$request->certificate_date_end)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
                $certificate_date_first = $request->certificate_date_first ?
                    Carbon::createFromFormat("d/m/Y",$request->certificate_date_first)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            }

            if ($request->submit == "submit"){

//                dd($request->all());
                $certi_lab = CertiLab::where('app_no',$request->request_number)->first();
                $export                             = new CertificateExport();
                $export->lang                       = $lang;
                $export->certificate_no             = $certificate_no;
                $export->certificate_order          = 1;
                $export->request_number             = $request->request_number;
                $export->status                     = $request->status;
                $export->certificate_for            = $request->certificate_for;
                $export->lab_name                   = $request->lab_name;
                $export->lab_type                   = $request->lab_type;
                $export->address_no                 = $request->address_no;
                $export->address_moo                = $request->address_moo;
                $export->address_soi                = $request->address_soi;
                $export->address_road               = $request->address_road;
                $export->address_province           = $request->address_province;
                $export->address_district           = $request->address_district;
                $export->address_subdistrict        = $request->address_subdistrict;
                $export->address_postcode           = $request->address_postcode;
                $export->formula                    = $request->formula;
                $export->accereditatio_no           = $request->accereditatio_no;

                if($request->attachs){
                    $export->attachs      =     $this->store_File($request->attachs,$applicant->app_no) ?? null;
                }

                if($lang == "th"){
                    $export->certificate_date_start     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_start)->addYear(-543);
                    $export->certificate_date_end       = Carbon::createFromFormat('d/m/Y',$request->certificate_date_end)->addYear(-543);
                    $export->certificate_date_first     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_first)->addYear(-543);
                } else {
                    $export->certificate_date_start     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_start);
                    $export->certificate_date_end       = Carbon::createFromFormat('d/m/Y',$request->certificate_date_end);
                    $export->certificate_date_first     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_first);
                }

                $export->issue_no                   = $issue_no;
                $export->scope_permanent            = $scope_permanent;
                $export->scope_site                 = $scope_site;
                $export->scope_temporary            = $scope_temporary;
                $export->scope_mobile               = $scope_mobile;

                try{
                    $export->save();


                    if(!is_null($certi_lab) && $request->status == 3){
                      $certi_lab->status =  27;
                      $certi_lab->save();
                    }elseif(!is_null($certi_lab)){
                        $certi_lab->status =  26;
                        $certi_lab->save();
                    }
                    // $ao = new CertificateExport;
                    // CertificateHistory::create([
                    //                          'app_no'=> $certi_lab->app_no ?? null,
                    //                          'system'=>12,
                    //                          'table_name'=> $ao->getTable(),
                    //                          'ref_id'=> $export->id,
                    //                          'details'=>  @$export  ?? null,
                    //                          'created_by' =>  auth()->user()->runrecno
                    // ]);

                    return redirect(url('/certify/certificate-export'))->with('flash_message', 'ออกใบรับรองเรียบร้อย');
                }catch (\Exception $x){
                    return back()->with('flash_message', $x->getMessage());

                }


            }elseif ($request->submit == "print"){

                $certi_lab = CertiLab::where('app_no',$request->request_number)->first();
                //ข้อมูลภาพ QR Code
                if(!is_null($certi_lab)  && !is_null($certi_lab->attach_pdf) && $certi_lab->attach_pdf != '' ){
                    $url       =    url("certify/check/files/".$certi_lab->attach_pdf );
                }else{
                    $url      = url("certify/certificate-export/{$request->request_number}/{$request->lang}/pdf/scope");
                }


                $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                                  ->size(500)->errorCorrection('H')
                                  ->generate($url);

                $data = [
                    'certificate_no'                => $certificate_no,
                    'certificate_for'               => $certificate_for,
                    'lab_name'                      => $lab_name,
                    'app_information_name'          => !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name : null,
                    'lab_name_font_size'            => $this->CalFontSize($lab_name),
                    'lab_type'                      => $lab_type,
                    'address'                       => $address,
                    'formula'                       => $formula,
                    'accereditatio_no'              => $accereditatio_no,
                    'certificate_date_start'        => !empty($request->certificate_date_start) ? HP::convertDate($request->certificate_date_start,true): date('Y-m-d'),
                    'certificate_date_end'          => $certificate_date_end,
                    'certificate_date_first'        => $certificate_date_first,
                    'image_qr'                      => $image_qr,
                    'attach_pdf'                    => $certi_lab->attach_pdf ?? null,
                    'request_number'                => $request_number,
                    'laboratory'                    =>  $certi_lab->LabTypeTitle ?? null
                ];

                if ($lang == "th"){
                    $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-thai', $data);
                    $files =   $certi_lab->trader->trader_id.'_LAB_'.$certi_lab->app_no;  // ชื่อไฟล์

                    $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
                    $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
                         //delete old pic if exists
                       if (File::exists($public . $path)) {
                           File::delete($public . $path);
                        }
                    Storage::put($path, $pdf->output());
                    return redirect('certify/certificate-export/FilePdf/'.$files);
                    // return $pdf->stream($certificate_no.'-thai.pdf');

                }else if ($lang == "en"){
                    $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-eng', $data);
                    return $pdf->stream($certificate_no.'-eng.pdf');

                }else{
                    abort(403);

                }
            }elseif ($request->submit == "printscope"){

                $certi_lab = CertiLab::where('app_no',$request_number)->first();
                if (!$certi_lab || is_null($certi_lab->attach_pdf)){
                    return redirect('certify/certificate-export')->with('flash_message', 'ไม่พบหลักฐานแนบท้าย');
                }
                $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
                $file = File::get($public.$certi_lab->attach_pdf);
                $response = Response::make($file, 200);
                $response->header('Content-Type', 'application/pdf');
                return $response;

                $scopes = $this->getScope($request_number,$lang);
                if ($scopes === false){
                    return "พบข้อผิดพลาด";
                }

                $data = [
                    'certificate_no'                => $certificate_no,
                    'certificate_for'               => $certificate_for,
                    'lab_name'                      => $lab_name,
                    'lab_type'                      => $lab_type,
                    'address'                       => $address,
                    'accereditatio_no'              => $accereditatio_no,
                    'certificate_date_start'        => $certificate_date_start,
                    'certificate_date_first'        => $certificate_date_first,
                    'issue_no'                      => $issue_no,
                    'scope_permanent'               => $scope_permanent,
                    'scope_site'                    => $scope_site,
                    'scope_temporary'               => $scope_temporary,
                    'scope_mobile'                  => $scope_mobile,
                    'scopes'                        => $scopes,
                ];

                if ($lang == "th"){
                    $pdf = PDF::loadView('certify.certificate-export.pdf.scope-thai', $data);
                    // return $pdf->stream($certificate_no.'-scope-thai.pdf');
                    $files =   $certi_lab->trader->trader_id.'_LAB_'.$certi_lab->app_no;  // ชื่อไฟล์

                    $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
                    $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
                         //delete old pic if exists
                       if (File::exists($public . $path)) {
                           File::delete($public . $path);
                        }
                    Storage::put($path, $pdf->output());
                    return redirect('certify/certificate-export/FilePdf/'.$files);
                }else if ($lang == "en"){
                    $pdf = PDF::loadView('certify.certificate-export.pdf.scope-eng', $data);
                    return $pdf->stream($certificate_no.'-scope-english.pdf');

                }else{
                    abort(403);

                }


            }


        }else{
            abort(403);
        }

    }

    public function apiGetAddress(Request $request)
    {
        if (!$request->id){
            return response()->json([
                'status'        => false,
                'message'       => "ไม่พบหน่วยงาน",
            ]);
        }
        // 'amphur' => $c->basic_amphur->AMPHUR_NAME ?? '-',
        // 'district' => $c->basic_district->DISTRICT_NAME ?? '-',
        $c = CertiLab::find($request->id);
        $lab_name       = $c->lab_name;
        $address        =  [
                            'address_no' => $c->address_no ?? '-',
                            'allay' => $c->allay ?? '-',
                            'village_no' => $c->village_no ?? '-',
                            'road' => $c->road ?? '-',
                            'province' => $c->basic_province->PROVINCE_NAME ?? '-',
                            'amphur' => $c->amphur ?? '-',
                            'district' => $c->district ?? '-',
                            'postcode' => $c->postcode ?? '-'
                           ];

        $standardNumber = $c->lab_type;

        if ($standardNumber == '2'){
            $branch = InspectBranch::whereState(1)->first();
            $text   = "I";
        }elseif ($standardNumber == '1'){
            $branch = CertificationBranch::whereState(1)->first();
            $text   = "Q";
        }elseif ($standardNumber == '3'){
            $branch = TestBranch::whereState(1)->first();
            $text   = "T";
        }elseif ($standardNumber == '4'){
            $branch = CalibrationBranch::whereState(1)->first();
            $text   = "C";
        }


        $last   = CertificateExport::where('lab_type',$standardNumber)->whereYear('certificate_date_start',Carbon::now())->count() + 1;
        $all   = CertificateExport::count() + 1;

        $certificate    = Carbon::now()->format("y").$text.sprintf("%03d", $last)."/".sprintf("%04d", $all);

        if ($standardNumber == 3){
            if ($c->certi_test_scope->count() > 0){

                if ($c->certi_test_scope->first()->get_detail()->count() > 0){
                    $certi = $c->certi_test_scope->first()->get_detail()->first();
                }else{
                    $certi = null;

                }
            }else{
                $certi = null;
            }
        }else{
            $certi = null;
        }


        $scope = $this->getScope($c->app_no,'th');


        $lab_type = ['1'=>'Testing','2'=>'Cal','3'=>'IB','4'=>'CB'];
        $accereditatio_no = '';
        if(array_key_exists($standardNumber,$lab_type)){
            $accereditatio_no .=  $lab_type[$standardNumber].'-';
        }
        if(!is_null($c->app_no)){
            $app_no = explode('-', $c->app_no);
            $accereditatio_no .= $app_no[2].'-';
        }
        if(!is_null($last)){
            $accereditatio_no .=  str_pad($last, 3, '0', STR_PAD_LEFT).'-'.(date('Y') +543);
        }

        $data = [
            'id'                => $request->id,
            'lab_name'          => $lab_name,
            'address'           => $address,
            'formula'           => $branch->formula ? $branch->formula->title . " (".$branch->formula->title_en.")" : null,
            'accereditatio_no'     => $accereditatio_no ? $accereditatio_no : null,
            // 'certificate_no'    => $certificate,
            'certificate_no'    =>  $this->running() ?? null,
            'lab_type'          => $standardNumber,
            'request_number'    => $c->app_no,
            'certi_test_scope'  => $certi,
            'scope'             => $scope,
        ];

        return response()->json([
            'status'    => true,
            'data'      => $data,
        ]);

    }

    public function getYear(Request $request)
    {
        if (!$request->year){
            return response()->json([
                'status'        => false,
                'message'       => "Not found , Try again",
            ]);
        }

        $get        = Carbon::createFromFormat('d/m/Y',$request->year);
        $next       = $get->addYear(3)->subDay(1);

        return response()->json([
            'status'    => true,
            'data'      => $next->format("d/m/Y"),
        ]);
    }

    public function edit(CertificateExport $cer)
    {
        $requests = CertiLab::distinct('trader_id')->whereIn('status',['25','26','27'])->select('id','lab_type','trader_id','app_no')->orderby('id','desc')->get();
        foreach ($requests as $request){
            $requests->trader_name           = $request->trader->name . " ( $request->app_no )";
        }

        return view('certify.certificate-export.edit',[
            'certificate'   => $cer,
            'lang'          => $cer->lang,
            'requests'      => $requests,
        ]);
    }

    public function editStore(Request $request, CertificateExport $cer)
    {

        $request->request->add(['lang' => $cer->lang]); //add request lang

        $lang                   = $cer->lang;
        $certificate_no         = $request->certificate_no;
        $lab_name               = $request->lab_name;
        $lab_type               = $request->lab_type;
        // $address                = $request->address_no . ($cer->lang == "th" ? " หมู่ที่ " : " Moo.") . $request->address_moo." ".$request->address_soi . " ".$request->address_road . " " . $request->address_district. " " .$request->address_subdistrict . " " . $request->address_province . " ". $request->address_postcode;
        $address                = $this->FormatAddress($request);

        $formula                = $request->formula;
        $accereditatio_no       = $request->accereditatio_no;
        $certificate_for        = CertiLab::find($request->certificate_for)->trader->name;
        $request_number         = $request->request_number;
        $issue_no               = $request->issue_no ?? 1;
        $scope_permanent        = $request->scope_permanent ? 1 : 0;
        $scope_site             = $request->scope_site ? 1 : 0;
        $scope_temporary        = $request->scope_temporary ? 1 : 0;
        $scope_mobile           = $request->scope_mobile ? 1 : 0;


        setlocale(LC_TIME, $lang);
        Carbon::setLocale($lang);

        if($lang == "th"){
            $certificate_date_start = $request->certificate_date_start ?  HP::DateThaiFormal(HP::convertDate($request->certificate_date_start,true)) : '';
            $certificate_date_end   = $request->certificate_date_start ? HP::DateThaiFormal(HP::convertDate($request->certificate_date_end,true)) : '';
            $certificate_date_first = $request->certificate_date_start ? HP::DateThaiFormal(HP::convertDate($request->certificate_date_first,true)) : '';
        }else{
            $certificate_date_start = $request->certificate_date_start ?
                Carbon::createFromFormat("d/m/Y",$request->certificate_date_start)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            $certificate_date_end = $request->certificate_date_end ?
                Carbon::createFromFormat("d/m/Y",$request->certificate_date_end)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            $certificate_date_first = $request->certificate_date_first ?
                Carbon::createFromFormat("d/m/Y",$request->certificate_date_first)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
        }

        if ($request->submit == "submit"){

//                dd($request->all());
            $applicant = CertiLab::where('app_no',$cer->request_number)->first();
            $export                             = $cer;
            $export->certificate_no             = $certificate_no;
            $export->certificate_order          = 1;
            $export->request_number             = $request->request_number;
            $export->status                     = $request->status;
            $export->certificate_for            = $request->certificate_for;
            $export->lab_name                   = $request->lab_name;
            $export->lab_type                   = $request->lab_type;
            $export->address_no                 = $request->address_no;
            $export->address_moo                = $request->address_moo;
            $export->address_soi                = $request->address_soi;
            $export->address_road               = $request->address_road;
            $export->address_province           = $request->address_province;
            $export->address_district           = $request->address_district;
            $export->address_subdistrict        = $request->address_subdistrict;
            $export->address_postcode           = $request->address_postcode;
            $export->formula                    = $request->formula;
            $export->accereditatio_no           = $request->accereditatio_no;
            if($lang == "th"){
                $export->certificate_date_start     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_start)->addYear(-543);
                $export->certificate_date_end       = Carbon::createFromFormat('d/m/Y',$request->certificate_date_end)->addYear(-543);
                $export->certificate_date_first     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_first)->addYear(-543);
            } else {
                $export->certificate_date_start     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_start);
                $export->certificate_date_end       = Carbon::createFromFormat('d/m/Y',$request->certificate_date_end);
                $export->certificate_date_first     = Carbon::createFromFormat('d/m/Y',$request->certificate_date_first);
            }

            if($request->attachs){
                $export->attachs      =     $this->store_File($request->attachs,$applicant->app_no) ?? @$export->attachs;
            }
            $export->issue_no                   = $issue_no;
            $export->scope_permanent            = $scope_permanent;
            $export->scope_site                 = $scope_site;
            $export->scope_temporary            = $scope_temporary;
            $export->scope_mobile               = $scope_mobile;


            if($request->status == 3){
               $applicant->status = 27;
            }else{
                $applicant->status =  26;
            }


            try{
                $export->save();
                $applicant->save();
                $user =   User::where('runrecno',auth()->user()->runrecno)->first();
                $config = HP::getConfig();
                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                if(!is_null($export->attachs) && !is_null($applicant->email)){

                    $mail = new CertifyCertificateExport(['app_no'=>  $applicant->app_no ??  '-',
                                                          'attachs' =>  $export->attachs ?? '-',
                                                          'attachs_client_name' =>  $export->attachs_client_name ?? '-',
                                                          'url' => $url ?? '-',
                                                          'email'=> !empty($user->reg_email) ? $user->reg_email : 'admin@admin.com'
                                                        ]);
                     Mail::to($applicant->email)->send($mail);
                }


                return redirect(url('/certify/certificate-export'))->with('flash_message', 'ออกใบรับรองเรียบร้อย');
            }catch (\Exception $x){
                return back()->with('flash_message', $x->getMessage());

            }


        }elseif ($request->submit == "print"){

            $certi_lab = CertiLab::where('app_no',$request_number)->first();

              //ข้อมูลภาพ QR Code
            if(!is_null($certi_lab)  && !is_null($certi_lab->attach_pdf) && $certi_lab->attach_pdf != '' ){
                $url = url("certify/check/files/".$certi_lab->attach_pdf );
            }else{
                $url  = url("certify/certificate-export/{$request_number}/{$lang}/pdf/scope");
            }

            $image_qr = QrCode::format('png')->merge('plugins/images/tisi.png', 0.2, true)
                              ->size(500)->errorCorrection('H')
                              ->generate($url);
            //return response($image_qr)->header('Content-type','image/png');

            $data = [
                'certificate_no'                => $certificate_no,
                'certificate_for'               => $certificate_for,
                'app_information_name'          =>!empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name : null,
                'lab_name'                      => $lab_name,
                'lab_name_font_size'            => $this->CalFontSize($lab_name),
                'lab_type'                      => $lab_type,
                'address'                       => $address,
                'formula'                       => $formula,
                'accereditatio_no'              => $accereditatio_no,
                'certificate_date_start'        => !empty($request->certificate_date_start) ? HP::convertDate($request->certificate_date_start,true): date('Y-m-d'),
                'certificate_date_end'          => $certificate_date_end,
                'certificate_date_first'        => $certificate_date_first,
                'image_qr'                      => $image_qr,
                'attach_pdf'                    => $certi_lab->attach_pdf ?? null,
                'request_number'                => $request_number,
                'laboratory'                    =>  $certi_lab->LabTypeTitle ?? null
            ];

            if ($lang == "th"){
                $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-thai', $data);
                // return $pdf->stream("scope-thai.pdf");

                 $files =   $certi_lab->trader->trader_id.'_LAB_'.$certi_lab->app_no;  // ชื่อไฟล์

                    $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
                    $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
                         //delete old pic if exists
                       if (File::exists($public . $path)) {
                           File::delete($public . $path);
                        }
                    Storage::put($path, $pdf->output());
                    return redirect('certify/certificate-export/FilePdf/'.$files);

            }else if ($lang == "en"){
                $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-eng', $data);
                return $pdf->stream($certificate_no.'-eng.pdf');

            }else{
                abort(403);

            }
        }elseif ($request->submit == "printscope"){


            $certi_lab = CertiLab::where('app_no',$request_number)->first();
            if (!$certi_lab || is_null($certi_lab->attach_pdf)){
                return redirect('certify/certificate-export')->with('flash_message', 'ไม่พบหลักฐานแนบท้าย');
            }

            $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
            $file = File::get($public.$certi_lab->attach_pdf);
            $response = Response::make($file, 200);
            $response->header('Content-Type', 'application/pdf');
            return $response;

            $scope = $this->getScope($request_number, $lang);
            if ($scope === false){
                return "พบข้อผิดพลาด";
            }

            $data = [
                'certificate_no'                => $certificate_no,
                'certificate_for'               => $certificate_for,
                'lab_name'                      => $lab_name,
                'lab_type'                      => $lab_type,
                'address'                       => $address,
                'accereditatio_no'              => $accereditatio_no,
                'certificate_date_start'        => $certificate_date_start,
                'certificate_date_first'        => $certificate_date_first,
                'issue_no'                      => $issue_no,
                'scope_permanent'               => $scope_permanent,
                'scope_site'                    => $scope_site,
                'scope_temporary'               => $scope_temporary,
                'scope_mobile'                  => $scope_mobile,
                'scopes'                        => $scope,
                'lang'                          => $lang
            ];

            if ($lang == "th"){
                $pdf = PDF::loadView('certify.certificate-export.pdf.scope-thai', $data);
                // return $pdf->stream($certificate_no.'-scope-thai.pdf');
                $files =   $certi_lab->trader->trader_id.'_LAB_'.$certi_lab->app_no;  // ชื่อไฟล์

                $path = 'files/applicants/CertifyFilePdf/'. $files.'.pdf';
                $public = Storage::disk()->getDriver()->getAdapter()->getPathPrefix();
                     //delete old pic if exists
                   if (File::exists($public . $path)) {
                       File::delete($public . $path);
                    }
                Storage::put($path, $pdf->output());
                return redirect('certify/certificate-export/FilePdf/'.$files);

            }else if ($lang == "en"){
                $pdf = PDF::loadView('certify.certificate-export.pdf.scope-eng', $data);
                return $pdf->stream($certificate_no.'-scope-english.pdf');

            }else{
                abort(403);

            }

        }
    }

    public function printPDF($request,$lang)
    {
        $certificate = CertificateExport::where('request_number',$request)->where('lang',$lang)->first();
        if (!$certificate){
            abort(403);
        }

        // $address    = $certificate->address_no . ($certificate->lang == "th" ? " หมู่ที่ " : " Moo.") . $certificate->address_moo." ".$certificate->address_soi . " ".$certificate->address_road . " " . $certificate->address_district. " " .$certificate->address_subdistrict . " " . $certificate->address_province . " ". $certificate->address_postcode;
        $address    = $this->FormatAddress($certificate);

    if($lang == "th"){
        $certificate_date_start = $certificate->certificate_date_start ?
            Carbon::parse($certificate->certificate_date_start)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
        $certificate_date_end = $certificate->certificate_date_end ?
            Carbon::parse($certificate->certificate_date_end)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
        $certificate_date_first = $certificate->certificate_date_first ?
            Carbon::parse($certificate->certificate_date_first)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
    }else{
        $certificate_date_start = $certificate->certificate_date_start ?
            Carbon::parse($certificate->certificate_date_start)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
        $certificate_date_end = $certificate->certificate_date_end ?
            Carbon::parse($certificate->certificate_date_end)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
        $certificate_date_first = $certificate->certificate_date_first ?
            Carbon::parse($certificate->certificate_date_first)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
    }

        $certificate_for = CertiLab::find($certificate->certificate_for)->trader->name;
        $certi_lab = CertiLab::find($certificate->certificate_for);
        $data = [
            'certificate_no'                => $certificate->certificate_no,
            'certificate_for'               => $certificate_for,
            'lab_name'                      => $certificate->lab_name,
            'app_information_name'          => !empty($certi_lab->BelongsInformation->name) ? $certi_lab->BelongsInformation->name : null,
            'lab_name_font_size'            => $this->CalFontSize($certificate->lab_name),
            'lab_type'                      => $certificate->lab_type,
            'address'                       => $address,
            'formula'                       => $certificate->formula,
            'accereditatio_no'              => $certificate->accereditatio_no,
            'certificate_date_start'        => $certificate_date_start,
            'certificate_date_end'          => $certificate_date_end,
            'certificate_date_first'        => $certificate_date_first,
            'laboratory'                    =>  $certi_lab->LabTypeTitle ?? null
        ];

        if ($certificate->lang == "th"){
            $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-thai', $data);
            return $pdf->stream($certificate->certificate_no.'-thai.pdf');

        }else if ($certificate->lang == "en"){
            $pdf = PDF::loadView('certify.certificate-export.pdf.certificate-eng', $data);
            return $pdf->stream($certificate->certificate_no.'-eng.pdf');

        }else{
            abort(403);

        }

    }

    public function printPDFScope($request,$lang)
    {
        $certificate = CertificateExport::where('request_number', $request)->where('lang',$lang)->first();
        if (!$certificate){
            abort(404);
        }

        // $address = $certificate->address_no . ($certificate->lang == "th" ? " หมู่ที่ " : " Moo.") . $certificate->address_moo." ".$certificate->address_soi . " ".$certificate->address_road . " " . $certificate->address_district. " " .$certificate->address_subdistrict . " " . $certificate->address_province . " ". $certificate->address_postcode;
        $address = $this->FormatAddress($certificate);

        if($lang == "th"){
            $certificate_date_start = $certificate->certificate_date_start ?
                Carbon::parse($certificate->certificate_date_start)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
            $certificate_date_first = $certificate->certificate_date_first ?
                Carbon::parse($certificate->certificate_date_first)->addYear(543)->formatLocalized('%d %B พ.ศ. %Y') : "";
        }else{
            $certificate_date_start = $certificate->certificate_date_start ?
                Carbon::parse($certificate->certificate_date_start)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
            $certificate_date_first = $certificate->certificate_date_first ?
                Carbon::parse($certificate->certificate_date_first)->addYear(543)->formatLocalized('%d %B B.E. %Y') : "";
        }

        $certificate_no                 = $certificate->certificate_no;
        $certificate_for                = CertiLab::find($certificate->certificate_for)->trader->name;
        $lab_name                       = $certificate->lab_name;
        $lab_type                       = $certificate->lab_type;
        $address                        = $address;
        $accereditatio_no               = $certificate->accereditatio_no;
        $certificate_date_start         = $certificate_date_start;
        $certificate_date_first         = $certificate_date_first;
        $issue_no                       = $certificate->issue_no;
        $scope_permanent                = $certificate->scope_permanent;
        $scope_site                     = $certificate->scope_site;
        $scope_temporary                = $certificate->scope_temporary;
        $scope_mobile                   = $certificate->scope_mobile;
        $scope                          = $scope = $this->getScope($request,$lang);

        $data = [
            'certificate_no'                => $certificate_no,
            'certificate_for'               => $certificate_for,
            'lab_name'                      => $lab_name,
            'lab_type'                      => $lab_type,
            'address'                       => $address,
            'accereditatio_no'              => $accereditatio_no,
            'certificate_date_start'        => $certificate_date_start,
            'certificate_date_first'        => $certificate_date_first,
            'issue_no'                      => $issue_no,
            'scope_permanent'               => $scope_permanent,
            'scope_site'                    => $scope_site,
            'scope_temporary'               => $scope_temporary,
            'scope_mobile'                  => $scope_mobile,
            'scopes'                         => $scope,
        ];

        if ($lang == "th"){
            $pdf = PDF::loadView('certify.certificate-export.pdf.scope-thai', $data);
            return $pdf->stream($certificate_no.'-scope-thai.pdf');

        }else if ($lang == "en"){
            $pdf = PDF::loadView('certify.certificate-export.pdf.scope-eng', $data);
            return $pdf->stream($certificate_no.'-scope-english.pdf');

        }else{
            abort(403);

        }


    }


    public function getScope($request_number,$lang = 'th')
    {
        $certificate = CertiLab::where('app_no',$request_number)->first();

        if (!$certificate){
            return false;
        }

        $type = $certificate->lab_type;

        if (!$type){
            return false;
        }

        $certi_scope = [];

        if ($type === 3){ // Test

            if ($certificate->certi_test_scope){

                $certi_scope = $certificate->certi_test_scope;

                // if ($lang == 'th'){
                //     $scope_branch = $certificate->certi_test_scope->first()->getBranch()->title ?? "N/A";
                //
                // }else{
                //     $scope_branch = $certificate->certi_test_scope->first()->getBranch()->title_en ?? "N/A";
                // }
                //
                // $scope_detail = $certificate->certi_test_scope->first()->get_detail()->map(function ($item) {
                //     return (string)collect($item)
                //         ->only(['detail_test'])['detail_test'];
                // })->toArray();
                //
                // $scope_howto = $certificate->certi_test_scope->first()->get_how()->map(function ($item) {
                //     return (string)collect($item)
                //         ->only(['how_test'])['how_test'];
                // })->toArray();
                //
                // $scope_capability = null;


            }else{
                return false;
            }

        }else if ($type === 4){ // Calibrate

            $certi_scope = $certificate->certi_lab_calibrate;

            // if ($lang == 'th'){
            //     $scope_branch = $certificate->certi_lab_calibrate->first()->getBranch()->title ?? "N/A";
            //
            // }else{
            //     $scope_branch = $certificate->certi_lab_calibrate->first()->getBranch()->title_en ?? "N/A";
            // }
            //
            // $scope_detail = $certificate->certi_lab_calibrate->first()->get_detail()->map(function ($item) {
            //     return (string)collect($item)
            //         ->only(['detail'])['detail'];
            // })->toArray();
            //
            // $scope_capability = $certificate->certi_lab_calibrate->first()->get_detail()->map(function ($item) {
            //     return (string)collect($item)
            //         ->only(['limit'])['limit'];
            // })->toArray();
            //
            // $scope_howto = [];
        }


        // $data = [
        //     'scope_branch'          => $scope_branch,
        //     'scope_detail'          => $scope_detail,
        //     'scope_capability'      => $scope_capability,
        //     'scope_how'             => $scope_howto,
        // ];



        return $certi_scope;
    }

    //คำนวนขนาดฟอนต์ของชื่อหน่วยงานผู้ได้รับรอง
    private function CalFontSize($certificate_for){

        $alphas = array_combine(range('A', 'Z'), range('a', 'z'));
        $thais = array('ก','ข', 'ฃ', 'ค', 'ฅ', 'ฆ','ง','จ','ฉ','ช','ซ','ฌ','ญ', 'ฎ', 'ฏ', 'ฐ','ฑ','ฒ'
,'ณ','ด','ต','ถ','ท','ธ','น','บ','ป','ผ','ฝ','พ','ฟ','ภ','ม','ย','ร','ล'
,'ว','ศ','ษ','ส','ห','ฬ','อ','ฮ', 'ำ', 'า', 'แ');

        if(function_exists('mb_str_split')){
          $chars = mb_str_split($certificate_for);
        }else if(function_exists('preg_split')){
          $chars = preg_split('/(?<!^)(?!$)/u', $certificate_for);
        }

        $i = 0;
        foreach ($chars as $char) {
            if(in_array($char, $alphas) || in_array($char, $thais)){
                $i++;
            }
        }

        if($i>40){
            $font = 15;
        }else{
            $font = 18;
        }

        return $font;

    }

    private function FormatAddress($request){

        $address   = [];
        $address[] = $request->address_no;

        if($request->address_moo!=''){
          $address[] = ($request->lang == "th" ? "หมู่ที่ " : "Moo.") . $request->address_moo;
        }

        if($request->address_soi!='' && $request->address_soi !='-'  && $request->address_soi !='--'){
          $address[] = ($request->lang == "th" ? "ซอย" : "Soi.") . $request->address_soi;
        }

        if($request->address_road!='' && $request->address_road !='-'  && $request->address_road !='--'){
          $address[] = ($request->lang == "th" ? "ถนน" : "Road.") . $request->address_road;
        }

        if($request->address_province=='กรุงเทพมหานคร'){//กรุงเทพฯ
          $address[] = ($request->lang == "th" ? "แขวง" : "") . $request->address_subdistrict;
        }else{
          $address[] = ($request->lang == "th" ? "ตำบล" : "") . $request->address_subdistrict;
        }

        if($request->address_province=='กรุงเทพมหานคร'){//กรุงเทพฯ
          $address[] = ($request->lang == "th" ? "เขต" : "") . $request->address_district;
        }else{
          $address[] = ($request->lang == "th" ? "อำเภอ" : "") . $request->address_district;
        }

        $address[] = $request->address_province;
        $address[] = $request->address_postcode;

        return implode(' ', $address);

    }


    // สำหรับเพิ่มรูปไปที่ store
    public function store_File($files, $app_no = 'files_lab',$name =null)
    {
        $no  = str_replace("RQ-","",$app_no);
        $no  = str_replace("-","_",$no);
        $path = 'files/applicants/check_files/'.$no.'/' ;
        $destinationPath = Storage::disk()->getAdapter()->getPathPrefix().$path;
        if ($files) {

            $fileClientOriginal = $files->getClientOriginalName();
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName = ($name ?? $filename).'-'.time() . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $fullFileName);
            $file_certificate_toDB = $path . $fullFileName;
            return  $no.'/'.$fullFileName;
        }
        return $model->file;
    }


    public function running()
    {
        if(date('m') >= 10){
            $date = date('y')+44;
        }else{
            $date = date('y')+43;
        }
        $running =  CertificateExport::get()->count();
        $running_no =  str_pad(($running + 1), 4, '0', STR_PAD_LEFT);
        return (date('y') + 43).'L:LAB'.$running_no;
    }



}
