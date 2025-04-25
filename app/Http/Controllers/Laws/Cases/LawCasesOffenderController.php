<?php
namespace App\Http\Controllers\Laws\Cases;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderCases;
use App\Models\Law\Offense\LawOffenderProduct;
use App\Models\Law\Offense\LawOffenderLicense;
use App\Models\Law\Offense\LawOffenderStandard;
use App\Models\Law\Offense\LawOffenderLog;


use App\Models\Law\Cases\LawCasesForm;

use App\Models\Law\File\AttachFileLaw;

use App\Models\Basic\TisiLicense;
use App\Models\Basic\Tis;
use Illuminate\Support\Facades\Auth;
use stdClass;

class LawCasesOffenderController extends Controller
{
    private $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-cases-offender','-');
        set_time_limit(0);
    }

    public function data_list(Request $request)
    {
        $filter_condition_search  = $request->input('filter_condition_search');
        $filter_search            = $request->input('filter_search');
        $filter_status            = $request->input('filter_status');
        $filter_standard          = $request->input('filter_standard');
        $filter_license_number    = $request->input('filter_license_number');

        $query = LawOffender::query()
                                    ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        $search_full = str_replace(' ', '', $filter_search);

                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%");
                                                break;
                                            case "2":
                                                    return $query->Where(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                    break;
                                            case "3":
                                                return $query->whereHas('offender_cases', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                break;
                                            default:
                                                return $query->where( function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(taxid,' ','')"), 'LIKE', "%".$search_full."%");

                                                            })
                                                            ->OrwhereHas('offender_cases', function($query) use ($search_full){
                                                                $query->Where(DB::raw("REPLACE(license_number,' ','')"), 'LIKE', "%".$search_full."%");
                                                            });
                                            
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if( $filter_status == 1){
                                            return $query->where('state', $filter_status);
                                        }else{
                                            return $query->where('state', '<>', 1)->orWhereNull('state');
                                        }
                                    })
                                    ->when($filter_standard, function ($query, $filter_standard){
                                        return $query->whereHas('offender_cases', function($query) use ($filter_standard){
                                                            return $query->where('tis_id', $filter_standard);
                                                        });
                                    })
                                    ->when($filter_license_number, function ($query, $filter_license_number){
                                        return $query->whereHas('offender_cases', function($query) use ($filter_license_number){
                                                            return $query->where('tb4_tisilicense_id', $filter_license_number);
                                                        });
                                    })
                                    ->withCount(['offender_cases','tisi_license']);

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('offender_name', function ($item) {
                                return !empty($item->name)?$item->name:null;
                            })
                            ->addColumn('offender_taxid', function ($item) {
                                return !empty($item->taxid)?$item->taxid:null;
                            })
                            ->addColumn('offender_address', function ($item) {
                                return !empty($item->AddressFull)?$item->AddressFull:'-';
                            })
                            ->addColumn('offender_email', function ($item) {
                                return (!empty($item->email)?'<i class="icon-envelope-open"></i> '.$item->email:null).(!empty($item->tel)?'<div><i class="icon-phone"></i> '.$item->tel.'<div>':null);
                            })
                            ->addColumn('offender_certify', function ($item) {
                                return  number_format($item->tisi_license_count);
                            })
                            ->addColumn('offender_total', function ($item) {
                                return  number_format($item->offender_cases_count);
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/cases/offender','Laws\Cases\\LawCasesOffenderController@destroy', 'law-cases-offender',true, false,false);
                            })
                            ->order(function ($query) use($request){
                                $column_index  = $request->input('order.0.column');
                                $order  = $request->input('order.0.dir');
                                $column = $request->input("columns.$column_index.data");
                                if (in_array($column, (new LawOffender)->getFillable())){
                                    $query->orderBy($column, $order);
                                }else{
                                    $query->orderBy('id', $order);
                                }
                            })
                            ->rawColumns(['checkbox', 'action', 'offender_email'])
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
                [ "link" => "/law/cases/offender",  "name" => 'สืบค้นประวัติการกระทำความผิด' ],
            ];
            return view('laws.cases.offender.index',compact('breadcrumbs'));
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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/offender",  "name" => 'สืบค้นประวัติการกระทำความผิด' ],
                [ "link" => "/law/cases/offender/create",  "name" => 'เพิ่ม' ],

            ];

            return view('laws.cases.offender.create',compact('breadcrumbs','offender'));

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
        if(auth()->user()->can('view-'.$this->permission)) {

            $requestData  = $request->all();

            $offender = LawOffender::where('taxid', $requestData['taxid'] )->first();

            $requestData['power'] = (!empty($requestData['repeater-power']) && !empty(array_diff( array_column($requestData['repeater-power'], 'power'), [null] )))?array_diff( array_column( $requestData['repeater-power'] , 'power'), [null] ):null;
            if( is_null( $offender ) ){
                //สถานะ
                $requestData['state']          = 1;
                $offender = LawOffender::create($requestData);
    
            }else{
                $offender->update( $requestData );
            }


            if( isset( $requestData['repeater-cases'] ) ){

                $repeater_cases = $requestData['repeater-cases'];

                foreach( $repeater_cases AS $caes ){

                    $caesData['law_offender_id']    = $offender->id;

                    //เลขคดี
                    $caesData['case_number']        = !empty($caes['case_number'])?$caes['case_number']:null;
                    //ID ใบอนุญาต
                    $caesData['tb4_tisilicense_id'] = !empty($caes['tb4_tisilicense_id'])?$caes['tb4_tisilicense_id']:null;
                    //เลขใบอนุญาต
                    $caesData['license_number']     = !empty($caes['license_number'])?$caes['license_number']:null;
                    //ID มอก
                    $caesData['tis_id']             = !empty($caes['tis_id'])?$caes['tis_id']:null;
                    //เลขมอก
                    $caesData['tb3_tisno']          = !empty($caes['tb3_tisno'])?$caes['tb3_tisno']:null;
                    //วันที่กระทำความผิด
                    $caesData['date_offender_case'] = !empty($caes['date_offender']) ? HP::convertDate($caes['date_offender'],true):null;
                    //วันที่ปิดคดี
                    $caesData['date_close']         = !empty($caes['date_close']) ? HP::convertDate($caes['date_close'],true):null;
                    //ฝ่าฝืน
                    $caesData['section']            = !empty($caes['section'])?$caes['section']:null;
                    //บทลงโทษ
                    $caesData['punish']             = !empty($caes['punish'])?$caes['punish']:null;
                    //สถานะ
                    $caesData['status']             = !empty($caes['status'])?$caes['status']:null;
                    //ดำเนินการทางอาญา
                    $caesData['case_person']        = !empty($caes['case_person'])?$caes['case_person']:0;
                    //ดำเนินการปกครอง
                    $caesData['case_license']       = !empty($caes['case_license'])?$caes['case_license']:0;
                    //ดำเนินการของกลาง
                    $caesData['case_product']       = !empty($caes['case_product'])?$caes['case_product']:0;
                    //นิติกรเจ้าคดี
                    $caesData['lawyer_by']          = !empty($caes['lawyer_by'])?$caes['lawyer_by']:null;

                    LawOffenderCases::create($caesData);


                }

            }

            return redirect('law/cases/offender')->with('flash_message', 'offender added!');

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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/offender",  "name" => 'สืบค้นประวัติการกระทำความผิด' ],
            ];

            $offender = LawOffender::findOrFail($id);

            return view('laws.cases.offender.show',compact('breadcrumbs','offender'));
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
        if(auth()->user()->can('view-'.$this->permission)) {

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/cases/offender",  "name" => 'สืบค้นประวัติการกระทำความผิด' ],
            ];

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
        if(auth()->user()->can('view-'.$this->permission)) {


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
        if(auth()->user()->can('view-'.$this->permission)) {


        }
        abort(403);
    }

    public function data_offender_cases(Request $request)
    {
        $filter_search         = $request->input('filter_search');
        $filter_standard       = $request->input('filter_standard');
        $filter_license_number = $request->input('filter_license_number');
        $law_offender_id       = $request->input('law_offender_id');

        $query = LawOffenderCases::query()->where(function($query) use($law_offender_id){
                                                $query->where('law_offender_id', $law_offender_id);
                                            })
                                            ->when($filter_standard, function ($query, $filter_standard){
                                                return $query->where('tis_id', $filter_standard);
                                            })
                                            ->when($filter_license_number, function ($query, $filter_license_number){
                                                return $query->where('tb4_tisilicense_id', $filter_license_number);
                                            });
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->case_number)?$item->case_number:'<em class="text-muted">ไม่มีเลขคดี</em>';
                            })
                            ->addColumn('license_number', function ($item) {
                                return !empty($item->license_number)?$item->license_number:'<em class="text-muted">ไม่มีใบอนุญาต</em>';
                            })
                            ->addColumn('tis', function ($item) {
                                $tis_data = $item->tis_data;
                                return (!empty($tis_data->tb3_Tisno)?$tis_data->tb3_Tisno:null).(!empty($tis_data->tb3_TisThainame)?' : '.$tis_data->tb3_TisThainame:null);
                            })
                            ->addColumn('law_section', function ($item) {
                                return !empty($item->SectionListName)?($item->SectionListName):null;
                            })
                            ->addColumn('cases', function ($item) {
                                $html  = null;
                                $html .= !empty($item->case_person) && $item->case_person == 1 ?'<div>ดำเนินการทางอาญา</div>':null;
                                $html .= !empty($item->case_license) && $item->case_license == 1 ?'<div>ดำเนินการปกครอง</div>':null;
                                $html .= !empty($item->case_product) && $item->case_product == 1 ?'<div>ดำเนินการของกลาง</div>':null;

                                return !empty($html)?$html:'-';

                            })
                            ->addColumn('date_offender_case', function ($item) {
                                return !empty($item->date_offender_case)?HP::DateThai($item->date_offender_case):null;
                            })
                            ->addColumn('lawyer_by', function ($item) {
                                return !empty($item->LawyerName)?$item->LawyerName:null;                                
                            })
                            ->addColumn('status', function ($item) {
                                return (!empty($item->StatusName)?$item->StatusName:null).(!empty($item->date_close)?'<div>'.HP::DateThai($item->date_close).'</div>':null);
                            }) 
                            ->addColumn('action', function ($item) {
                                $html  = '<button  type="button" class="btn btn-icon btn-circle btn-light-warning btn-xs circle btn_cases_edit" value="'.($item->id).'"><i class="fa fa-pencil-square-o" style="font-size: 1.5em;"></i></button>';   
                                return $html;
                            })
                            ->rawColumns(['cases','license_number','status','case_number','action'])
                            ->make(true);
    }

    public function data_offender_certify(Request $request)
    {
        $filter_search         = $request->input('filter_search');
        $filter_standard       = $request->input('filter_standard');
        $filter_license_number = $request->input('filter_license_number');
        $law_offender_id       = $request->input('law_offender_id');

        $query = TisiLicense::query()->where(function($query) use($law_offender_id){
                                            $ids = LawOffenderCases::where('law_offender_id', $law_offender_id)->select('tb4_tisilicense_id');
                                            $query->whereIn('Autono', $ids);
                                        })
                                        ->when($filter_standard, function ($query, $filter_standard) use($law_offender_id){
                                            $ids = LawOffenderCases::where('filter_standard', $filter_standard)->where('law_offender_id', $law_offender_id)->select('tb4_tisilicense_id');
                                            $query->whereIn('Autono', $ids);
                                        })
                                        ->when($filter_license_number, function ($query, $filter_license_number){
                                            return $query->where('Autono', $filter_license_number);
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('license_number', function ($item) {
                                return !empty($item->tbl_licenseNo)?$item->tbl_licenseNo:'<em class="text-muted">ไม่มีใบอนุญาต</em>';
                            })
                            ->addColumn('license_type', function ($item) {
                                $type_name =  ['ท'=>'ทำ', 'ส'=>'แสดง','น'=>'นำเข้า', 'นค'=>'นำเข้าเฉพาะครั้ง'];
                                return  !empty($item->tbl_licenseType) && array_key_exists($item->tbl_licenseType,$type_name)?$type_name[$item->tbl_licenseType]:null;
                            })
                            ->addColumn('license_date', function ($item) {
                                return  !empty($item->tbl_licenseDate)?HP::DateThai($item->tbl_licenseDate):null;
                            })
                            ->addColumn('license_tisi', function ($item) {
                                $tbl_tisiName  = '';
                                if(!empty($item->tbl_tisiNo)){
                                    $tis = Tis::where('tb3_Tisno', $item->tbl_tisiNo )->first();
                                    $tbl_tisiName .= !empty($tis->tb3_Tisno)?$tis->tb3_Tisno:null;
                                    $tbl_tisiName .= !empty($tis->tb3_TisThainame)? ' : '. $tis->tb3_TisThainame:null;
                                }
                                return $tbl_tisiName;
                            })
                            ->addColumn('license_file', function ($item) {    
                                $file  = '';
                                if(!empty($item->license_pdf)){
                                    $file .= ' <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/'.$item->license_pdf.'" target="_blank">'.(HP::FileExtension($item->license_pdf)?? '').'</a>' ;
                                }else{
                                    $file .='<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
                                }
                                return $file;
                            })
                            ->addColumn('license_status', function ($item) {
                                $status = '';
                                if($item->tbl_licenseStatus == 1){
                                    $status .= '<div><span class="text-success">ใช้งาน</span></div>';
                                }else{
                                    $status .= '<div><span class="text-danger">ไม่ใช้งาน</span></div>';
                                }
                                if($item->Is_pause == 1){
                                    $status .= '<div><span class="text-danger">พักใช้</span></div>';
                                    $status .= '('. (!empty($item->date_pause_start)?HP::DateThai($item->date_pause_start):'-').(!empty($item->date_pause_end)?'ถึง'. HP::DateThai($item->date_pause_end):'-').')';
                                }
                                return $status;
                            })
                            ->order(function ($query) {
                                $query->orderBy('Autono', 'DESC');
                            })
                            ->rawColumns(['license_file', 'license_status'])
                            ->make(true);

    }

    public function data_offender_files(Request $request)
    {
        $filter_search         = $request->input('filter_search');
        $law_offender_id       = $request->input('law_offender_id');

        $query = AttachFileLaw::query()
                                    ->where(function($query) use($law_offender_id){
                                        $ids =  LawOffenderCases::where('law_offender_id' , $law_offender_id )->select('law_cases_id');
                                        return $query->whereIn('ref_id', $ids)->where('ref_table', (new LawCasesForm )->getTable() );
                                    })
                                    ->when($filter_search, function ($query, $filter_search){
                                        $search_full = str_replace(' ', '', $filter_search );
                                        $query->where( function($query) use($search_full) {
                                                    $query->where(DB::Raw("REPLACE(filename,' ','')"),  'LIKE', "%$search_full%");
                                                })
                                                ->Orwhere( function($query) use($search_full) {
                                                    
                                                    $ids =  LawOffenderCases::whereHas('law_cases', function($query) use ($search_full){
                                                                                    return $query->where('case_number', $search_full);
                                                                                })
                                                                                ->select('law_cases_id');
                                                    return $query->whereIn('ref_id', $ids)->where('ref_table', (new LawCasesForm )->getTable() );;
                                                });
                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('case_number', function ($item) {
                                return !empty($item->law_cases->case_number)?$item->law_cases->case_number:null;
                            })
                            ->addColumn('filename', function ($item) {
                                return !empty($item->filename)?$item->filename:null;
                            })
                            ->addColumn('caption', function ($item) {
                                return !empty($item->caption)?$item->caption:null;
                            })
                            ->addColumn('created_at', function ($item) {
                                return !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            })
                            ->addColumn('action', function ($item) {
                                $file_cover_url = HP::getFileStorage($item->url);
                                return '<a href="'.url( $file_cover_url ).'" class="btn btn-info btn-sm pull-right" target"_blank">ดาวน์โหลด</a>';;
                            })
                            ->order(function ($query) {
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }

    public function infomation_save(Request $request, $id)
    {
        if(auth()->user()->can('edit-'.$this->permission)) {

            $requestData                   = $request->all();

            $offender                      = LawOffender::findOrFail($id);
            $requestData['date_offender']  = !empty($requestData['date_offender']) ? HP::convertDate($requestData['date_offender'],true):null;

            //เก็บ Log
            $log = [];
            foreach(  $requestData AS $column => $Idata ){

                if( Schema::hasColumn( (new LawOffender)->getTable(), $column ) ){

                    if( $Idata != $offender->{$column}  ){

                        $dataLog['law_offender_id'] = $offender->id;
                        $dataLog['column']          = $column;
                        $dataLog['ref_table']       = (new LawOffender)->getTable();
                        $dataLog['ref_id']          = $offender->id;
                        $dataLog['data_old']        = $offender->{$column} ;
                        $dataLog['data_new']        = $Idata;
                        $dataLog['created_by']      = Auth::user()->getKey();
                        LawOffenderLog::create($dataLog);

                    }

                }

            }

            $requestData['updated_by'] = auth()->user()->getKey();
            $requestData['updated_at'] = Carbon::now()->timestamp;

            $offender->update($requestData);
            return redirect('law/cases/offender/'.$id)->with('infomation_success_message', 'บันทึกข้อมูลเรียบร้อยแล้ว');

        }
        abort(403);
    }

    public function data_offender_history(Request $request)
    {
        $filter_search         = $request->input('filter_search');
        $law_offender_id       = $request->input('law_offender_id');

        $query = LawOffenderLog::query()
                                        ->where(function($query) use($law_offender_id){
                                            return $query->where('law_offender_id', $law_offender_id)->where('ref_table', (new LawOffender )->getTable() );
                                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('column', function ($item) {
                                return !empty($item->column)?$item->ColumnName:null;
                            })
                            ->addColumn('data_new', function ($item) {
                                if( Carbon::hasFormat($item->data_new, 'Y-m-d') || Carbon::hasFormat($item->data_new, 'Y-m-d H:i:s') ){
                                    return !empty($item->data_new)?HP::DateThai($item->data_new):null;
                                }else{
                                    return !empty($item->data_new)?$item->data_new:null;
                                }
                            })
                            ->addColumn('data_old', function ($item) {
                                if( Carbon::hasFormat($item->data_old, 'Y-m-d') || Carbon::hasFormat($item->data_old, 'Y-m-d H:i:s') ){
                                    return !empty($item->data_old)?HP::DateThai($item->data_old):null;
                                }else{
                                    return !empty($item->data_old)?$item->data_old:null;
                                }
                            })
                            ->addColumn('created_at', function ($item) {
                                return !empty($item->created_at)?HP::DateThai($item->created_at):null;
                            })
                            ->order(function ($query) {
                                $query->orderBy('created_at', 'DESC');
                            })
                            ->rawColumns(['action'])
                            ->make(true);
    }


    public function GetHtmlCases(Request $request)
    {
        $id               = $request->input('id_case');
        $law_offender_id  = $request->input('law_offender_id');

        $offender = LawOffender::findOrFail($law_offender_id);

        $cases = LawOffenderCases::where('id', $id)->first();

        return view('laws.cases.offender.html.cases',compact('cases','offender'));

    }

    public function update_cases(Request $request){

        $requestData                        = $request->all();

        $id                                 = $requestData['id'];
        $law_offender_id                    = $requestData['law_offender_id'];

        $msg                                = "success";

        //ดำเนินการทางอาญา
        $requestData['case_person']         = !empty($requestData['case_person'])?$requestData['case_person']:0;
        //ดำเนินการปกครอง
        $requestData['case_license']        = !empty($requestData['case_license'])?$requestData['case_license']:0;
        //ดำเนินการของกลาง
        $requestData['case_product']        = !empty($requestData['case_product'])?$requestData['case_product']:0;
        //วันที่พบการกระทำผิด
        $requestData['date_offender_case']  = !empty($requestData['date_offender_case']) ? HP::convertDate($requestData['date_offender_case'],true):null;
        //วันที่ปิดคดี
        $requestData['date_close']          = !empty($requestData['date_close']) ? HP::convertDate($requestData['date_close'],true):null;
        //วันที่ได้รับมอบหมาย
        $requestData['assign_date']         = !empty($requestData['assign_date']) ? HP::convertDate($requestData['assign_date'],true):null;
        //วันที่อนุมัติ
        $requestData['approve_date']        = !empty($requestData['approve_date']) ? HP::convertDate($requestData['approve_date'],true):null;
        //วันที่เสนอ
        $requestData['power_present_date']  = !empty($requestData['power_present_date']) ? HP::convertDate($requestData['power_present_date'],true):null;
        //วันที่คำสั่ง กมอ.ทำให้สิ้นสภาพ
        $requestData['tisi_dictation_date'] = !empty($requestData['tisi_dictation_date']) ? HP::convertDate($requestData['tisi_dictation_date'],true):null;

        $cases = LawOffenderCases::where('id', $id)->first();

        $cases->update($requestData);

        if( isset($requestData['product-list']) ){
            $product_list = $requestData['product-list'];

            $list_ids = [];
            foreach( $product_list as $items ){
                if( !empty($items['id']) ){
                    $list_ids[] = $items['id'];
                }
            }

            LawOffenderProduct::where('law_offenders_cases_id', $cases->id )->whereNotIn('id', $list_ids)->delete();

            foreach(  $product_list AS $Ipro ){

                if( !empty($Ipro['detail']) ){
                    LawOffenderProduct::updateOrCreate(
                        [
                            'id'                       => $Ipro['id']
                        ],
                        [
                            'id'                       => $Ipro['id'],
                            'law_offender_id'          => $law_offender_id,
                            'law_offenders_cases_id'   => $cases->id,
                            'case_number'              => $cases->case_number,
                            'detail'                   => $Ipro['detail'],
                            'amount'                   => !empty($Ipro['amount'])?str_replace(",","",$Ipro['amount']):null,
                            'unit'                     => $Ipro['unit'],
                            'total_price'              => !empty($Ipro['total_price'])?str_replace(",","",$Ipro['total_price']):null,
                            'law_cases_id'             => $cases->law_cases_id,

                        ]
                    );
                }
     
            }
        }

        if( isset($requestData['standard-list']) ){
            $standard_list = $requestData['standard-list'];

            $list_ids = [];
            foreach( $standard_list as $items ){
                if( !empty($items['tis_id']) ){
                    $list_ids[] = $items['tis_id'];
                }
            }

            LawOffenderStandard::where('law_offenders_cases_id', $cases->id )->whereNotIn('tis_id', $list_ids)->delete();

            foreach(  $standard_list AS $Istd ){
                if( !empty($Istd['tis_id']) ){
                    LawOffenderStandard::updateOrCreate(
                        [
                            'law_offender_id'          => $law_offender_id,
                            'law_offenders_cases_id'   => $cases->id,
                            'tis_id'                   => $Istd['tis_id'],
                        ],
                        [
                            'law_offender_id'          => $law_offender_id,
                            'law_offenders_cases_id'   => $cases->id,
                            'case_number'              => $cases->case_number,
                            'tis_id'                   => $Istd['tis_id'],
                            'tb3_tisno'                => $Istd['tb3_tisno'],
                            'tis_name'                 => $Istd['tis_name'],
                            'law_cases_id'             => $cases->law_cases_id,
                        ]
                    );
                }
            }

        }

        if( isset($requestData['licenses-list']) ){
            $licenses_list = $requestData['licenses-list'];

            $list_ids = [];
            foreach( $licenses_list as $items ){
                if( !empty($items['tb4_tisilicense_id'][0]) ){
                    $list_ids[] = $items['tb4_tisilicense_id'][0];
                }
            }

            LawOffenderLicense::where('law_offenders_cases_id', $cases->id )->whereNotIn('tb4_tisilicense_id', $list_ids)->delete();

            foreach(  $licenses_list AS $Ilicen ){

                if( !empty($Ilicen['tb4_tisilicense_id'][0]) ){
                    LawOffenderLicense::updateOrCreate(
                        [
                            'law_offender_id'          => $law_offender_id,
                            'law_offenders_cases_id'   => $cases->id,
                            'tb4_tisilicense_id'       => $Ilicen['tb4_tisilicense_id'][0],
                        ],
                        [
                            'law_offender_id'          => $law_offender_id,
                            'law_offenders_cases_id'   => $cases->id,
                            'case_number'              => $cases->case_number,
                            'tb4_tisilicense_id'       => $Ilicen['tb4_tisilicense_id'][0],
                            'license_number'           => $Ilicen['license_number'],
                            'law_cases_id'             => $cases->law_cases_id,
                        ]
                    );
                }

            }

        }

        return response()->json(['msg' => $msg ]);

    }
}
