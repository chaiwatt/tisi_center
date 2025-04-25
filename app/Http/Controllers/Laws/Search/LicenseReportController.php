<?php

namespace App\Http\Controllers\Laws\Search;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Basic\TisiLicense;
use App\Models\Basic\Tis;

class LicenseReportController extends Controller
{
    private $permission;
    public function __construct()
    {
        $this->middleware('auth');
        $this->permission = str_slug('license-report','-');
    }


    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_status           = $request->input('filter_status');
        $filter_is_pause         = $request->input('filter_is_pause');
        $filter_license_type     = $request->input('filter_license_type');
        $filter_tisi_no          = $request->input('filter_tisi_no');
        $filter_license_date     = !empty($request->input('filter_license_date'))? HP::convertDate($request->input('filter_license_date'),true):null;


        $query = TisiLicense::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                    switch ( $filter_condition_search ):
                                        case "1":
                                            return $query->Where('tbl_licenseNo', 'LIKE', '%' . $filter_search . '%');
                                            break;
                                        case "2":
                                            $search_full = str_replace(' ', '', $filter_search);
                                            $tb3_Tisno  = Tis::Where(DB::Raw("REPLACE(tb3_TisThainame,' ','')"),  'LIKE', "%$search_full%")->select('tb3_Tisno');
                                            return $query->whereIn('tbl_tisiNo', $tb3_Tisno);
                                            break;
                                        case "3":
                                            return $query->where('tbl_tradeName',  'LIKE', "%$filter_search%");
                                            break;
                                        case "4":
                                            return $query->where('tbl_taxpayer',  'LIKE', "%$filter_search%");
                                            break;
                                        default:
                                            $search_full = str_replace(' ', '', $filter_search);
                                            $tb3_Tisno  = Tis::Where(DB::Raw("REPLACE(tb3_TisThainame,' ','')"),  'LIKE', "%$search_full%")->select('tb3_Tisno');
                                            return  $query->where(function ($query2) use($tb3_Tisno, $filter_search) {
                                                        $query2->whereIn('tbl_tisiNo', $tb3_Tisno)
                                                               ->orWhere('tbl_tisiNo', 'LIKE', '%' . $filter_search . '%')
                                                               ->orWhere('tbl_licenseNo', 'LIKE', '%' . $filter_search . '%')
                                                               ->orWhere('tbl_tradeName', 'LIKE', '%' . $filter_search . '%')
                                                               ->orWhere('tbl_taxpayer', 'LIKE', '%' . $filter_search . '%');
                                                    });
                                            break;
                                    endswitch;
                                })
                                ->when($filter_status, function ($query, $filter_status){
                                    if( $filter_status == 1){
                                        return $query->where('tbl_licenseStatus', $filter_status);
                                    }else{
                                        return $query->where(function ($query){
                                                    $query->where('tbl_licenseStatus', '<>', 1)
                                                            ->orWhereNull('tbl_licenseStatus');
                                                });
                                    }
                                })
                                ->when($filter_is_pause, function ($query, $filter_is_pause){
                                    if( $filter_is_pause == 1){
                                        return $query->where(function($query){
                                                        $query->where('Is_pause', '1')
                                                                ->OrwhereHas('license_pause',function($query){
                                                                    $query->whereNull('date_pause_cancel');
                                                                });
                                                    });                  
                                    }else{
                                        return $query->where(function ($query){
                                                        $query->where('Is_pause', '<>', 1)
                                                                ->whereDoesntHave('license_pause',function($query){
                                                                    $query->whereNull('date_pause_cancel');
                                                                });
                                                    });
                                    }
                                })
                                ->when($filter_license_type, function ($query, $filter_license_type){
                                    return $query->where('tbl_licenseType', $filter_license_type);
                                })
                                ->when($filter_tisi_no, function ($query, $filter_tisi_no){
                                    return $query->where('tbl_tisiNo', $filter_tisi_no);
                                })
                                ->when($filter_license_date, function ($query, $filter_license_date){
                                    return $query->whereDate('tbl_licenseDate', $filter_license_date);
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('tbl_licenseNo', function ($item) {
                                return !empty($item->tbl_licenseNo)?$item->tbl_licenseNo:null;
                            })
                            ->addColumn('tbl_licenseType', function ($item) {
                                $type_name =  ['ท'=>'ทำ', 'ส'=>'แสดง','น'=>'นำเข้า', 'นค'=>'นำเข้าเฉพาะครั้ง'];
                                return @array_key_exists($item->tbl_licenseType,$type_name)?$type_name[$item->tbl_licenseType]:null;
                            })
                            ->addColumn('tbl_licenseDate', function ($item) {
                                return  !empty($item->tbl_licenseDate)?HP::DateThai($item->tbl_licenseDate):null;
                            })
                            ->addColumn('tbl_tisiNo', function ($item) {
                                $tbl_tisiName  = '';
                                if(!empty($item->tbl_tisiNo)){
                                    $book_manage_access = Tis::where('tb3_Tisno', $item->tbl_tisiNo )->first();
                                    $tbl_tisiName .= !empty($book_manage_access->tb3_Tisno)?$book_manage_access->tb3_Tisno:null;
                                    $tbl_tisiName .= !empty($book_manage_access->tb3_TisThainame)? ' : '. $book_manage_access->tb3_TisThainame:null;
                                }
                                return $tbl_tisiName;
                            })
                            ->addColumn('tbl_tradeName', function ($item) {  
                                return (!empty($item->tbl_tradeName)?$item->tbl_tradeName:null).(!empty($item->tbl_taxpayer)?'<br>'.$item->tbl_taxpayer:null);
                            })
                            ->addColumn('tbl_pdf_path', function ($item) {    
                                $file  = '';
                                if(!empty($item->license_pdf)){
                                    $file .= ' <a href="http://appdb.tisi.go.th/tis_dev/p4_license_report/file/'.$item->license_pdf.'" target="_blank">'.(HP::FileExtension($item->license_pdf)?? '').'</a>' ;
                                }else{
                                    $file .='<i class="fa  fa-file-text" style="font-size:20px; color:#92b9b9" aria-hidden="true"></i>';
                                }

                                if(  (!is_null($item->license_pause) && count($item->license_pause_list)) != 0 || !is_null($item->license_cancel) || $item->Is_pause == 1 ){
                                    $file .= '<div><button type="button" data-id="'.($item->getkey()).'" class="btn btn-link btn_modal_history"><small>ประวัติข้อมูลใบอนุญาต</small></button></div>';
                                }

                                return $file;
                            })
                            ->addColumn('tbl_licenseStatus', function ($item) {
                                $status = '';

                                $Is_pause       = ($item->Is_pause);
                                $license_pause  = $item->license_pause;
                                $license_cancel = $item->license_cancel;
                                
                                if($item->tbl_licenseStatus == 1){
                                    $status .= '<div><span class="text-success">ใช้งาน</span></div>';
                                }else{

                                    if( empty($Is_pause) && empty( $license_pause) && empty( $license_cancel) ){
                                        $status .= '<div><span class="text-danger">ไม่ใช้งาน</span></div>';
                                    }
                        
                                }
                                if($item->Is_pause == 1){
                                    $status .= '<div><span class="text-danger">พักใช้</span></div>';
                                    $status .= '('. (!empty($item->date_pause_start)?HP::DateThai($item->date_pause_start):'-').(!empty($item->date_pause_end)?'ถึง'. HP::DateThai($item->date_pause_end):'-').')';
                                    $status .= '<div><span class="text-muted">NSW</span></div>';
                                }

                                if( !is_null($license_pause) && empty($license_pause->date_pause_cancel) ){
                                    $status .= '<div><span class="text-danger">พักใช้</span></div>';
                                    $status .= '('. (!empty($license_pause->date_pause_start)?HP::DateThai($license_pause->date_pause_start):'-').(!empty($license_pause->date_pause_end)?'ถึง'. HP::DateThai($license_pause->date_pause_end):'-').')';
                                    $status .= '<div><span class="text-muted">Law</span></div>';
                                }

                                if( !is_null($license_cancel) ){
                                    $status .= '<div><span class="text-danger">เพิกถอน</span></div>';
                                    $status .= '('. (!empty($license_cancel->tbl_cancelDate)?HP::DateThai($license_cancel->tbl_cancelDate):'-').')';
                                }

                                return $status;
                            })
                            ->order(function ($query) {
                                $query->orderBy('Autono', 'DESC');
                            })
                            ->rawColumns(['tbl_tradeName', 'status', 'created_by', 'tbl_pdf_path','tbl_licenseStatus'])
                            ->make(true);
    }

    public function history(Request $request){

        $id    = $request->get('id');
        $db    = new TisiLicense;

        $query = TisiLicense::where( $db->getKeyName(), $id )->first();

        return view('laws.search.license-report.modals.html',compact('query'));

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(auth()->user()->can('view-'.$this->permission)) {
            return view('laws.search.license-report.index');

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
}
