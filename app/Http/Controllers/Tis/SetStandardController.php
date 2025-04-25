<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Method;
use App\Models\Basic\StatusOperation;
use App\Models\Tis\SetStandard as set_standard;
use App\Models\Tis\Standard;
use App\Models\Tis\Appoint;
use App\Models\Tis\PublicDraft as public_draft;
use Exception;
use HP;
use Illuminate\Http\Request;
use App\Models\Tis\SetStandardPlan as set_standard_plan;
use App\Models\Tis\SetStandardResult as set_standard_result;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Storage;
// use SHP;
use Carbon\Carbon;
use stdClass;
use Yajra\Datatables\Datatables;
class SetStandardController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->standard_path = 'tis_attach/standard/';
        $this->attach_path = 'tis_attach/set_standard/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('view-' . $model)) {

 


            return view('tis.set_standard.index');
        }
        abort(403);

    }


    public function data_list(Request $request)
    {
        $model = str_slug('set_standard', '-');
        $filter_status = $request->input('filter_status');
        $filter_search = $request->input('filter_search');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_standard_format = $request->input('filter_standard_format');
        $filter_method_id = $request->input('filter_method_id');
        $filter_method_detail = $request->input('filter_method_detail');
        $filter_tis_no = $request->input('filter_tis_no');
        $filter_set_format = $request->input('filter_set_format');
        $filter_product_group = $request->input('filter_product_group');
        $filter_staff_group = $request->input('filter_staff_group');
        $filter_secretary = $request->input('filter_secretary');
        $filter_activity = $request->input('filter_activity');
        $filter_announce = $request->input('filter_announce');
        $filter_plan_year = $request->input('filter_plan_year');
        $query = set_standard::query()->when($filter_search, function ($query, $filter_search){
                                                        $search_full = str_replace(' ', '', $filter_search );
                                                            $query->where(function ($query2) use($search_full) {
                                                                        $query2->Where(DB::raw("REPLACE(tis_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(tis_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        if($filter_status == 1){
                                                            $query->where('state', $filter_status);
                                                        }else{
                                                            $query->where('state', '!=','1');
                                                        }
                                                     
                                                    })
                                            
                                                    ->when($filter_standard_type, function ($query, $filter_standard_type){
                                                        $query->where('standard_type_id', $filter_standard_type);
                                                    })
                                                    ->when($filter_standard_format, function ($query, $filter_standard_format){
                                                        $query->where('set_format_id', $filter_standard_format);
                                                    })
                                                    ->when($filter_method_id, function ($query, $filter_method_id){
                                                        $query->where('method_id', $filter_method_id);
                                                    })
                                                    ->when($filter_method_detail, function ($query, $filter_method_id ) use ($filter_method_detail) {
                                                        $query->where('method_id', $filter_method_id)->where('method_id_detail', $filter_method_detail);
                                                    })
                                                    ->when($filter_set_format, function ($query, $filter_set_format){
                                                        $query->where('set_format_id', $filter_set_format);
                                                    })
                                                    ->when($filter_product_group, function ($query, $filter_product_group){
                                                        $query->where('product_group_id', $filter_product_group);
                                                    })
                                                    ->when($filter_staff_group, function ($query, $filter_staff_group){
                                                        $query->where('staff_group', $filter_staff_group);
                                                    })
                                                    ->when($filter_secretary, function ($query, $filter_secretary){
                                                        $secretary = str_replace(' ', '', $filter_secretary );
                                                        $query->Where(DB::raw("REPLACE(secretary,' ','')"), 'LIKE', "%".$secretary."%");
                                                    })
                                                    ->when($filter_activity, function ($query, $filter_activity){
                                                        $set_standard_result = set_standard_result::select('id_tis_set_standards')->where('statusOperation_id', $filter_activity);
                                                        $query->whereIn('id', $set_standard_result);
                                                    })
                                                    ->when($filter_announce, function ($query, $filter_announce){
                                                        $query->where('announce', $filter_announce);
                                                    })
                                                    ->when($filter_plan_year, function ($query, $filter_plan_year){
                                                        $query->where('plan_year', $filter_plan_year);
                                                    })  ; 
 
                                                    
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                            })
                            ->addColumn('tis_book', function ($item) {
                                $tis_book = ($item->tis_book)?'เล่ม '.$item->tis_book.'-':'';
                                return  @$item->tis_no.'-'.$tis_book.@$item->start_year ;
                            })
                            ->addColumn('title_en', function ($item) {
                                return      !empty($item->title_en)? $item->title.' ('.$item->title_en.')':'';
                            })
                            ->addColumn('standard_type_short_name', function ($item) {
                                return $item->StandardTypeShortName ?? 'n/a';
                            })
                            ->addColumn('secretary', function ($item) {
                                return @$item->secretary;
                            })
                            ->addColumn('staff_group_name', function ($item) {
                                return @$item->StaffGroupName;
                            })
                            ->addColumn('appoint_name', function ($item) {
                                return @$item->AppointName;
                            })
                            ->addColumn('standard_format_name', function ($item) {
                                return @$item->StandardFormatName;
                            })
                            ->addColumn('method_name', function ($item) {
                                return @$item->MethodName;
                            })
                            ->addColumn('product_group_name', function ($item) {
                                return @$item->ProductGroupName;
                            })
                            ->addColumn('operation_result_name', function ($item) {
                                return @$item->OperationResultName;
                            })
                            ->addColumn('operation_result_name', function ($item) {
                                return @$item->OperationResultName;
                            })
                            ->addColumn('state', function ($item) {
                                return  $item->stateIcon;
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn .=  ' <a href="'. url('tis/set_standard/'.$item->id) .'" class="btn btn-info btn-xs">   <i class="fa fa-eye" aria-hidden="true"></i> </a>';
                                }

                                if(auth()->user()->getKey()==$item->created_by ||  auth()->user()->can('edit-'.$model) ){
                                    $btn .=  ' <a href="'. url('tis/set_standard/'.$item->id. '/edit') .'" class="btn btn-primary btn-xs">     <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> </a>';      
                                }
 
 
                                if(auth()->user()->getKey()==$item->created_by ||  auth()->user()->can('delete-'.$model) ){
                                    $btn .=  ' <a href="'. url('tis/set_standard/destroy/'.$item->id) .'"   title="ลบ" class="btn  btn-danger  btn-xs" onclick="return confirm_delete()">  <i class="fa fa-trash-o" aria-hidden="true"></i> </a>'; 
                                }

           
                                return $btn;
 
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['operation_result_name','state', 'checkbox', 'action'])
                            ->make(true);
    }


    public function apiGetSetStandard($id) {
        try {
            $set_standard = set_standard::findOrFail($id);
            $attachs = json_decode($set_standard['attach']);
            if (!is_null($attachs)&&count($attachs)>0) {
                foreach ($attachs as $attach) {
                    $attach->check = HP::checkFileStorage($this->attach_path.$attach->file_name);
                    $attach->href = HP::getFileStorage($this->attach_path.$attach->file_name);
                }
            } else {
                $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            }

            $set_standard->attaches = $attachs;
            $refers = json_decode($set_standard['refer']);
            $set_standard->refers = !is_null($refers)&&count($refers)>0 ? $refers : [''];

            $standards = standard::where('id',$set_standard->standard_id )->get();

            foreach ($standards as $standard) {
                $attachs = json_decode($standard['attach']);
                if (!is_null($attachs)&&count($attachs)>0) {
                    foreach ($attachs as $attach) {
                        $attach->check = HP::checkFileStorage($this->attach_path.$attach->file_name);
                        $attach->href = HP::getFileStorage($this->attach_path.$attach->file_name);
                    }
                } else {
                    $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
                }
                $standard->attaches = $attachs;
                $standard->refers = json_decode($standard['refer']);
            }

            return response()->json(compact('set_standard','standards'));
        } catch (Exception $x) {
            return response()->json([
                'status' => false,
                'message' => $x->getMessage()
            ]);
        }
    }

    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = str_slug('set_standard', '-');
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $state = $request->input('state');
            $result = set_standard::where('id', $id)->update(['state' => $state]);

            if($result) {
                return 'success';
            } else {
                return "not success";
            }

        }else{
            abort(403);
        }

    }

        //เลือกเผยแพร่หรือไม่เผยแพร่สถานะทั้งหมดได้
        public function update_publish(Request $request)
        {
            $arr_publish = $request->input('id_publish');
            $state = $request->input('state');
    
            $result = set_standard::whereIn('id', $arr_publish)->update(['state' => $state]);
            if($result)
            {
                echo 'success';
            } else {
                echo "not success";
            }
    
        }
    



    public function apiGetSetStandardPlan($id) {
        $plan = set_standard_plan::with('status_operation')->findOrFail($id);
        if ($plan) {
            $strStartDateTemp = $plan->startdate->format('d/m/Y');
            $plan->strStartDate = $strStartDateTemp?Carbon::createFromFormat("d/m/Y",$strStartDateTemp)->addYear(543)->formatLocalized('%d/%m/%Y'):'';
            $strEndDateTemp = $plan->enddate->format('d/m/Y');
            $plan->strEndDate  = $strEndDateTemp?Carbon::createFromFormat("d/m/Y",$strEndDateTemp)->addYear(543)->formatLocalized('%d/%m/%Y'):'';
            return response()->json(compact('plan'));
        }
        $message = 'Set standard plan not found.';
        return response()->json(compact('message'));
    }

    public function apiGetSetStandardResult($id) {
        $plan = set_standard_result::with('status_operation')->findOrFail($id);
        // dd($plan);
        if ($plan) {
            $strStartDateTemp = $plan->startdate ? $plan->startdate->format('d/m/Y') : '';
            $plan->strStartDate = $strStartDateTemp?Carbon::createFromFormat("d/m/Y",$strStartDateTemp)->addYear(543)->formatLocalized('%d/%m/%Y'):'';
            $strEndDateTemp = $plan->enddate ? $plan->enddate->format('d/m/Y') : '';
            $plan->strEndDate  = $strEndDateTemp?Carbon::createFromFormat("d/m/Y",$strEndDateTemp)->addYear(543)->formatLocalized('%d/%m/%Y'):'';
            return response()->json(compact('plan'));
        }
        $message = 'Set standard result not found.';
        return response()->json(compact('message'));
    }

    public function apiGetPlans($id) {
        $set_standard = set_standard::findOrFail($id);
        // dd($set_standard);
        $set_standard->totalAllowances = $set_standard->totalAllowances2();
        $set_standard->totalFoods = $set_standard->totalFoods2();
        $set_standard->total = $set_standard->total2();
        $set_standard->totalAllowancesResult = $set_standard->totalAllowancesResult2();
        $set_standard->totalFoodsResult = $set_standard->totalFoodsResult2();
        $set_standard->totalResult = $set_standard->totalResult2();

        $plans = $set_standard->set_standard_plan()
            ->orderBy('startdate')
            ->with('result.status_operation')
            ->with('status_operation')
            ->get()->each(function ($plan) {
                $plan->strDate = HP::DateThai($plan->startdate) . ' - ' . HP::DateThai($plan->enddate);
                $plan->strQuarter = $plan->strQuarter();
                $plan->totalAllowances = $plan->totalAllowances2();
                // $plan->totalAllowances = 1200999;
                $plan->totalFoods = $plan->totalFoods2();

                // $plan->result->strDate = HP::DateThai($plan->result->startdate) . ' - ' . HP::DateThai($plan->result->enddate);
                // $plan->result->strQuarter = $plan->result->strQuarter();
                // $plan->result->totalAllowances = $plan->result->totalAllowances2();
                // $plan->result->totalFoods = $plan->result->totalFoods2();
            });

        if ($plans) {
            return response()->json(compact('plans', 'set_standard'));
        }
        $message = 'Set standard plan not found.';
        return response()->json(compact('message'));
    }

    public function apiGetResults($id) {
        $set_standard = set_standard::findOrFail($id);
        // dd($set_standard);
        $set_standard->totalAllowances = $set_standard->totalAllowances2();
        $set_standard->totalFoods = $set_standard->totalFoods2();
        $set_standard->total = $set_standard->total2();
        $set_standard->totalAllowancesResult = $set_standard->totalAllowancesResult2();
        $set_standard->totalFoodsResult =  $set_standard->totalFoodsResult2();
        $set_standard->totalResult =  $set_standard->totalResult2();

        $results = $set_standard->set_standard_result()
            ->orderBy('startdate')
            // ->with('result.status_operation')
            ->with('status_operation')
            ->get()->each(function ($result) {
                $result->strDate = @HP::DateThai($result->startdate) . ' - ' . @HP::DateThai($result->enddate);
                $result->strQuarter = $result->strQuarter();
                $result->totalAllowances = $result->totalAllowances2();
                $result->totalFoods = $result->totalFoods2();
            });

        if ($results) {
            return response()->json(compact('results', 'set_standard'));
        }
        $message = 'Set standard plan not found.';
        return response()->json(compact('message'));
    }

    public function apiGetStoreResult($id) {
        $set_standard = set_standard::findOrFail($id);
        $set_standard_plan = [];
        if(count($set_standard->set_standard_result) > 0){
            foreach($set_standard->set_standard_result as $itme){
                $data  = new  stdClass;
                $data->status_operation = (string)$itme->status_operation->title ;
                $data->year = (string)$itme->year ;
                $set_standard_plan[] = $data;
             }
        }

        if ($set_standard) {
            return response()->json(compact('set_standard','set_standard_plan'));
        }
    }




    public function apiGetYears() {
        $years = array_values(HP::Years());
        return response()->json(compact('years'));
    }

    public function apiGetStatusOperations() {
        $status_operations = StatusOperation::get();
        return response()->json(compact('status_operations'));
    }

    public function apiGetAppointNames() {

        $appoint_names = Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->where('state',1)->get();
        return response()->json(compact('appoint_names'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('add-' . $model)) {

            $refers = [''];

            $attachs = [(object)['file_note' => '', 'file_name' => '']];
            $attach_path = $this->attach_path;


            //โชว์ข้อมูลหน้าแผน//
            $Query = new set_standard_plan;
//            $set_standard = $Query->with('status_operation')->paginate(20);
            $set_standard = null;

            return view('tis.set_standard.create', compact('attachs', 'refers', 'attach_path', 'set_standard'));

        }
        abort(403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('add-' . $model)) {

            $this->validate($request, [
                'review_status' => 'required',
                'title' => 'required',
                'title_en' => 'required',
                // 'start_year' => 'required',
                'tis_no' => 'required',
                'made_by' => 'required',
                'product_group_id' => 'required',
                'appoint_id' => 'required',
                'standard_type_id' => 'required',
                'standard_format_id' => 'required',
                'set_format_id' => 'required',
                'method_id' => 'required',
                'industry_target_id' => 'required',
                // 'cluster_id' => 'required'
            ]);


            $requestData = $request->all();
            if ($requestData['review_status'] == 2) { // เลือก ทบทวน
                $standard = Standard::findOrFail($requestData['tis_no']);
                $requestData['standard_id'] = $requestData['tis_no'];
                // $requestData['tis_no'] = $standard->tis_no . '-' . $standard->tis_year;
                $requestData['tis_no'] = $standard->tis_no;
            }

            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['refer'] = json_encode(isset($requestData['refer']) ? $requestData['refer'] : []);//ข้อมูลอ้างอิง

            //ไฟล์แนบ
            $attachs = [];
            // $files = $request->file('attachs');
            // foreach (isset($requestData['attach_filenames']) ? $requestData['attach_filenames'] : [] as $key => $attach_filename) {
            //     if ($attach_filename != null) {
            //         $ext = pathinfo($attach_filename, PATHINFO_EXTENSION);
            //         $filename = str_random(40) . '.' . $ext;
            //         Storage::copy($this->standard_path . $attach_filename, $this->attach_path . $filename);
            //         $attachs[] = [
            //             'file_name' => $filename,
            //             'file_client_name' => $attach_filename,
            //             'file_note' =>  array_key_exists( $key, $requestData['attach_notes'] )?$requestData['attach_notes'][$key]:null
            //         ];
            //     }
            // }

            //ไฟล์แนบ เพิ่มเติม (ไฟล์ใหม่)
            $files = $request->file('attachs');
            if ($files) {
                foreach ($files as $key => $file) {
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename
                    $attachs[] = [
                        'file_name' => $storageName,
                        'file_client_name' => $file->getClientOriginalName(),
                        'file_note' => array_key_exists( $key, $requestData['attach_notes'] )?$requestData['attach_notes'][$key]:null
                    ];
                }
            }

            $requestData['attach'] = json_encode($attachs, JSON_UNESCAPED_UNICODE);

            $getId = set_standard::create($requestData)->id;
            return redirect('tis/set_standard')->with('flash_message', 'เพิ่ม มอก. เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function apiStorePlans(Request $request, $id)
    {
            $set_standard = set_standard::findOrFail($id);
            $arr = ['ไตรมาสที่ 1','ไตรมาสที่ 2','ไตรมาสที่ 3','ไตรมาสที่ 4'];
            $requestData = $request->all();
 
            $requestData['startdate']   =  !empty($requestData['startdate']) ? HP::convertDate($requestData['startdate'],true) : null;   
            $requestData['enddate']     =   !empty($requestData['enddate']) ? HP::convertDate($requestData['enddate'],true) : null;   
            $requestData['id_tis_set_standards'] = $set_standard->id;
            $quarter_date = $requestData['startdate'];
            $quarter = set_standard::selectRaw('(QUARTER("'.$quarter_date.'") % 4) + 1 As ThisQ')->first();
            $requestData['quarter']               = $quarter->ThisQ;

            $requestData['numpeople_g']                 =  !empty($requestData['numpeople_g'])   ? str_replace(",","",$requestData['numpeople_g']) : null;  
            $requestData['allowances_referee_g']        =  !empty($requestData['allowances_referee_g'])   ? str_replace(",","",$requestData['allowances_referee_g']) : null;  
            $requestData['allowances_persident_g']      =  !empty($requestData['allowances_persident_g'])   ? str_replace(",","",$requestData['allowances_persident_g']) : null;  
            $requestData['numpeople_attendees']         =  !empty($requestData['numpeople_attendees'])   ? str_replace(",","",$requestData['numpeople_attendees']) : null;  
            $requestData['food_morning_attendees']      =  !empty($requestData['food_morning_attendees'])   ? str_replace(",","",$requestData['food_morning_attendees']) : null;  
            $requestData['food_noon_attendees']         =  !empty($requestData['food_noon_attendees'])   ? str_replace(",","",$requestData['food_noon_attendees']) : null;  
            $requestData['food_afternoon_attendees']    =  !empty($requestData['food_afternoon_attendees'])   ? str_replace(",","",$requestData['food_afternoon_attendees']) : null;  


            $requestData['sum_g']                 =  !empty($requestData['sum_g'])   ? str_replace(",","",$requestData['sum_g']) : null;  
            $requestData['sum_attendees']         =  !empty($requestData['sum_attendees'])   ? str_replace(",","",$requestData['sum_attendees']) : null;  
            $requestData['sum']                   =  !empty($requestData['sum_g'])  &&  !empty($requestData['sum_attendees']) ?  ($requestData['sum_g'] + $requestData['sum_attendees'] ): null;   
       
            if(!is_null($requestData['plan_id'])) {
                 set_standard_plan::findOrFail($requestData['plan_id'])->update($requestData);
            } else {
                 set_standard_plan::create($requestData);
            }

           
            $set_standard_plans = set_standard_plan::where('id_tis_set_standards',$id )->get();

            foreach ($set_standard_plans as $plan) {
                $plan->quarter          =  !empty($plan->strQuarter()) ?  $plan->strQuarter() : ''  ;
                $plan->operation        =  !empty($plan->status_operation->title) ?  $plan->status_operation->title : ''   ;
                $plan->startdates       =  !empty($plan->startdate) ?   HP::DateThai($plan->startdate): ''   ;
                $plan->enddates         =  !empty($plan->enddate) ?  HP::DateThai($plan->enddate) : ''   ;
                $plan->sum_gs           =  !empty($plan->sum_g) ?   number_format($plan->sum_g,2) : '0.00';   
                $plan->sum_attendeess   =  !empty($plan->sum_attendees) ?   number_format($plan->sum_attendees,2) : '0.00';   
            }
            return response()->json(compact('set_standard_plans'));
    } 

    public function apiDataStorePlans(Request $request)
    {
        $set_standard_plans = set_standard_plan::findOrFail($request->id);
        if(!is_null($set_standard_plans)){
            $set_standard_plans->startdates =  !empty($set_standard_plans->startdate) ? HP::revertDate(date('Y-m-d', strtotime($set_standard_plans->startdate)) ,true) : '';   
            $set_standard_plans->enddates =  !empty($set_standard_plans->enddate) ? HP::revertDate(date('Y-m-d', strtotime($set_standard_plans->enddate)),true) : '';   

            $set_standard_plans->numpeople_g                  =  !empty($set_standard_plans->numpeople_g) ?   number_format($set_standard_plans->numpeople_g,2) : '0.00';   
            $set_standard_plans->allowances_referee_g         =  !empty($set_standard_plans->allowances_referee_g) ?   number_format($set_standard_plans->allowances_referee_g,2) : '0.00';   
            $set_standard_plans->allowances_persident_g       =  !empty($set_standard_plans->allowances_persident_g) ?   number_format($set_standard_plans->allowances_persident_g,2) : '0.00';   

            $set_standard_plans->numpeople_attendees           =  !empty($set_standard_plans->numpeople_attendees) ?   number_format($set_standard_plans->numpeople_attendees,2) : '0.00';   
            $set_standard_plans->food_morning_attendees        =  !empty($set_standard_plans->food_morning_attendees) ?   number_format($set_standard_plans->food_morning_attendees,2) : '0.00';   
            $set_standard_plans->food_noon_attendees           =  !empty($set_standard_plans->food_noon_attendees) ?   number_format($set_standard_plans->food_noon_attendees,2) : '0.00';   
            $set_standard_plans->food_afternoon_attendees      =  !empty($set_standard_plans->food_afternoon_attendees) ?   number_format($set_standard_plans->food_afternoon_attendees,2) : '0.00';   

            $set_standard_plans->sum_g                          =  !empty($set_standard_plans->sum_g) ?   number_format($set_standard_plans->sum_g,2) : '0.00';   
            $set_standard_plans->sum_attendees                  =  !empty($set_standard_plans->sum_attendees) ?   number_format($set_standard_plans->sum_attendees,2) : '0.00';  
            $set_standard_plans->sum                            =  !empty($set_standard_plans->sum) ?   number_format($set_standard_plans->sum,2) : '0.00';  
        }
        return response()->json(compact('set_standard_plans'));
    } 
    

    public function apiDeleteStorePlans(Request $request)
    {
          set_standard_plan::where('id', $request->id)->delete();
          $id =    $request->standard_id;
        $set_standard_plans = set_standard_plan::where('id_tis_set_standards',$id )->get();
        foreach ($set_standard_plans as $plan) {
            $plan->quarter          =  !empty($plan->strQuarter()) ?  $plan->strQuarter() : ''  ;
            $plan->operation        =  !empty($plan->status_operation->title) ?  $plan->status_operation->title : ''   ;
            $plan->startdates       =  !empty($plan->startdate) ?   HP::DateThai($plan->startdate): ''   ;
            $plan->enddates         =  !empty($plan->enddate) ?  HP::DateThai($plan->enddate) : ''   ;
            $plan->sum_gs           =  !empty($plan->sum_g) ?   number_format($plan->sum_g,2) : '0.00';   
            $plan->sum_attendeess   =  !empty($plan->sum_attendees) ?   number_format($plan->sum_attendees,2) : '0.00';   
        }
        return response()->json(compact('set_standard_plans'));
    } 
    
    public function apiStoreResults(Request $request, $id)
    {
 
            $requestData = $request->all();
            $set_standard = set_standard::findOrFail($id);

            $arr = ['ไตรมาสที่ 1','ไตรมาสที่ 2','ไตรมาสที่ 3','ไตรมาสที่ 4'];
            $requestData = $request->all();
 
            $requestData['startdate']   =  !empty($requestData['startdate']) ? HP::convertDate($requestData['startdate'],true) : null;   
            $requestData['enddate']     =   !empty($requestData['enddate']) ? HP::convertDate($requestData['enddate'],true) : null;   
            $requestData['id_tis_set_standards'] = $set_standard->id;
            $quarter_date = $requestData['startdate'];
            $quarter = set_standard::selectRaw('(QUARTER("'.$quarter_date.'") % 4) + 1 As ThisQ')->first();
            $requestData['quarter']               = $quarter->ThisQ;

            $requestData['numpeople_g']                 =  !empty($requestData['numpeople_g'])   ? str_replace(",","",$requestData['numpeople_g']) : null;  
            $requestData['allowances_referee_g']        =  !empty($requestData['allowances_referee_g'])   ? str_replace(",","",$requestData['allowances_referee_g']) : null;  
            $requestData['allowances_persident_g']      =  !empty($requestData['allowances_persident_g'])   ? str_replace(",","",$requestData['allowances_persident_g']) : null;  
            $requestData['numpeople_attendees']         =  !empty($requestData['numpeople_attendees'])   ? str_replace(",","",$requestData['numpeople_attendees']) : null;  
            $requestData['food_morning_attendees']      =  !empty($requestData['food_morning_attendees'])   ? str_replace(",","",$requestData['food_morning_attendees']) : null;  
            $requestData['food_noon_attendees']         =  !empty($requestData['food_noon_attendees'])   ? str_replace(",","",$requestData['food_noon_attendees']) : null;  
            $requestData['food_afternoon_attendees']    =  !empty($requestData['food_afternoon_attendees'])   ? str_replace(",","",$requestData['food_afternoon_attendees']) : null;  

            $requestData['sum_g']                 =  !empty($requestData['sum_g'])   ? str_replace(",","",$requestData['sum_g']) : null;  
            $requestData['sum_attendees']         =  !empty($requestData['sum_attendees'])   ? str_replace(",","",$requestData['sum_attendees']) : null;  
            $requestData['sum']                   =  !empty($requestData['sum_g'])  &&  !empty($requestData['sum_attendees']) ?  ($requestData['sum_g'] + $requestData['sum_attendees'] ): null;   
           

            if ($requestData['result_id'] != null) {
               set_standard_result::findOrFail($requestData['result_id'])->update($requestData);
            } else {
               set_standard_result::create($requestData);
            }

            $set_standard_results = set_standard_result::where('id_tis_set_standards',$id )->get();

            foreach ($set_standard_results as $plan) {
                $plan->quarter          =  !empty($plan->strQuarter()) ?  $plan->strQuarter() : ''  ;
                $plan->operation        =  !empty($plan->status_operation->title) ?  $plan->status_operation->title : ''   ;
                $plan->startdates       =  !empty($plan->startdate) ?   HP::DateThai($plan->startdate): ''   ;
                $plan->enddates         =  !empty($plan->enddate) ?  HP::DateThai($plan->enddate) : ''   ;
                $plan->sum_gs           =  !empty($plan->sum_g) ?   number_format($plan->sum_g,2) : '0.00';   
                $plan->sum_attendeess   =  !empty($plan->sum_attendees) ?   number_format($plan->sum_attendees,2) : '0.00';   
            }

            return response()->json(compact('set_standard_results'));
 
    }

    public function apiDataStoreResults(Request $request)
    {
        $set_standard_result = set_standard_result::findOrFail($request->id);
        if(!is_null($set_standard_result)){
            $set_standard_result->startdates =  !empty($set_standard_result->startdate) ? HP::revertDate(date('Y-m-d', strtotime($set_standard_result->startdate)) ,true) : '';   
            $set_standard_result->enddates =  !empty($set_standard_result->enddate) ? HP::revertDate(date('Y-m-d', strtotime($set_standard_result->enddate)),true) : '';   

            $set_standard_result->numpeople_g                  =  !empty($set_standard_result->numpeople_g) ?   number_format($set_standard_result->numpeople_g,2) : '0.00';   
            $set_standard_result->allowances_referee_g         =  !empty($set_standard_result->allowances_referee_g) ?   number_format($set_standard_result->allowances_referee_g,2) : '0.00';   
            $set_standard_result->allowances_persident_g       =  !empty($set_standard_result->allowances_persident_g) ?   number_format($set_standard_result->allowances_persident_g,2) : '0.00';   

            $set_standard_result->numpeople_attendees           =  !empty($set_standard_result->numpeople_attendees) ?   number_format($set_standard_result->numpeople_attendees,2) : '0.00';   
            $set_standard_result->food_morning_attendees        =  !empty($set_standard_result->food_morning_attendees) ?   number_format($set_standard_result->food_morning_attendees,2) : '0.00';   
            $set_standard_result->food_noon_attendees           =  !empty($set_standard_result->food_noon_attendees) ?   number_format($set_standard_result->food_noon_attendees,2) : '0.00';   
            $set_standard_result->food_afternoon_attendees      =  !empty($set_standard_result->food_afternoon_attendees) ?   number_format($set_standard_result->food_afternoon_attendees,2) : '0.00';   

            $set_standard_result->sum_g                          =  !empty($set_standard_result->sum_g) ?   number_format($set_standard_result->sum_g,2) : '0.00';   
            $set_standard_result->sum_attendees                  =  !empty($set_standard_result->sum_attendees) ?   number_format($set_standard_result->sum_attendees,2) : '0.00';  
            $set_standard_result->sum                            =  !empty($set_standard_result->sum) ?   number_format($set_standard_result->sum,2) : '0.00';  
        }
        return response()->json(compact('set_standard_result'));
    } 
    

    public function apiDeleteStoreResults(Request $request)
    {
        set_standard_result::where('id', $request->id)->delete();
          $id =    $request->standard_id;
        $delete_set_standard_result = set_standard_result::where('id_tis_set_standards',$id )->get();
        foreach ($delete_set_standard_result as $result) {
            $result->quarter          =  !empty($result->strQuarter()) ?  $result->strQuarter() : ''  ;
            $result->operation        =  !empty($result->status_operation->title) ?  $result->status_operation->title : ''   ;
            $result->startdates       =  !empty($result->startdate) ?   HP::DateThai($result->startdate): ''   ;
            $result->enddates         =  !empty($result->enddate) ?  HP::DateThai($result->enddate) : ''   ;
            $result->sum_gs           =  !empty($result->sum_g) ?   number_format($result->sum_g,2) : '0.00';   
            $result->sum_attendeess   =  !empty($result->sum_attendees) ?   number_format($result->sum_attendees,2) : '0.00';   
        }
        return response()->json(compact('delete_set_standard_result'));
    } 
    

    public function apiStorePlan(Request $request, $id)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $set_standard = set_standard::findOrFail($id);

            $requestData = $request->all();
            $requestData['startdate'] = $request->startdate?Carbon::createFromFormat("d/m/Y",$request->startdate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['enddate'] = $request->enddate?Carbon::createFromFormat("d/m/Y",$request->enddate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            $quarter_date = $requestData['startdate'];
            $quarter = set_standard::selectRaw('(QUARTER("'.$quarter_date.'") % 4) + 1 As ThisQ')->first();
            $requestData['quarter'] = $quarter->ThisQ;
            $requestData['id_tis_set_standards'] = $set_standard->id;

            if($requestData['plan_id'] != null) {
                $plan = set_standard_plan::findOrFail($requestData['plan_id'])->update($requestData);
            } else {
                $plan = set_standard_plan::create($requestData);
                // $requestDataResult['set_standard_plan_id'] = $plan->id;
                // $requestDataResult['statusOperation_id'] = $plan->statusOperation_id;
                // $requestDataResult['id_tis_set_standards'] = $set_standard->id;
                // $plan->result()->create($requestDataResult);
            }

            return response()->json(compact('requestData', 'plan','set_standard'));
        }
        return response()->json([
            'status' => false,
            'message' => 'Can not access'
        ]);
    }

    public function apiStoreResult(Request $request, $id)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $requestData = $request->all();
            $set_standard = set_standard::findOrFail($id);


            $requestData['startdate'] = $request->startdate?Carbon::createFromFormat("d/m/Y",$request->startdate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['enddate'] = $request->enddate?Carbon::createFromFormat("d/m/Y",$request->enddate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            $quarter_date = $requestData['startdate'];
            $quarter = set_standard::selectRaw('(QUARTER("'.$quarter_date.'") % 4) + 1 As ThisQ')->first();

            $requestData['quarter'] = $quarter->ThisQ;
            $requestData['id_tis_set_standards'] = $set_standard->id;

            if ($requestData['plan_id'] != null) {
                $plan = set_standard_result::findOrFail($requestData['plan_id'])->update($requestData);
            } else {
                $plan = set_standard_result::create($requestData);
            }

            return response()->json(compact('requestData', 'plan', 'set_standard'));
        }
        return response()->json([
            'status' => false,
            'message' => 'Can not access'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('view-' . $model)) {
            $set_standard = set_standard::findOrFail($id);
            return view('tis.set_standard.show', compact('set_standard'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $set_standard = set_standard::with('set_standard_plan')->findOrFail($id);
            //ไฟล์แนบ
            $attachs = json_decode($set_standard['attach']);
            $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object)['file_note' => '', 'file_name' => '']];
            $attach_path = $this->attach_path;

            // $refers = json_decode($set_standard['refer']);
            // $set_standard->totalAllowancesResult = $set_standard->totalAllowancesResult2() ?? null;
            // $set_standard->totalFoodsResult = $set_standard->totalFoodsResult2() ?? null;
            // $set_standard->totalResult = $set_standard->totalResult2() ?? null;
 
      
            return view('tis.set_standard.edit', compact('set_standard', 'attachs', 'attach_path'));

        }
        abort(403);
    }

    public function editPlan($id)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $set_standard_plan = set_standard_plan::findOrFail($id);

            return response()->json($set_standard_plan);
        }
        abort(403);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
//        dd($request);
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {
            $this->validate($request, [
                'review_status' => 'required',
                'title' => 'required',
                'title_en' => 'required',
                // 'start_year' => 'required',
                'tis_no' => 'required',
                'made_by' => 'required',
                'product_group_id' => 'required',
                'appoint_id' => 'required',
                'standard_type_id' => 'required',
                'standard_format_id' => 'required',
                'set_format_id' => 'required',
                'method_id' => 'required',
                'industry_target_id' => 'required',
                // 'cluster_id' => 'required'
            ]);

            $set_standard = set_standard::findOrFail($id);

            $requestData = $request->all();
            if ($requestData['review_status'] == 2) { // เลือก ทบทวน
                $standard = Standard::findOrFail($requestData['tis_no']);
                $requestData['standard_id'] = $requestData['tis_no'];
                // $requestData['tis_no'] = $standard->tis_no . '-' . $standard->tis_year;
                $requestData['tis_no'] = $standard->tis_no;
            }
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['refer'] = json_encode(isset($requestData['refer']) ? $requestData['refer'] : []);

            //ข้อมูลไฟล์แนบ
            $attachs = array_values((array)json_decode($set_standard->attach));
            $existAttachs = array_values((array)json_decode($set_standard->attach));

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {

                if (in_array($attach->file_name, isset($requestData['attach_filenames']) ? $requestData['attach_filenames'] : []) === false) {//ถ้าไม่มีไฟล์เดิมกลับมา

                    unset($attachs[$key]);
                    Storage::disk('uploads')->delete($this->attach_path . $attach->file_name);
                }
            }


            //ไฟล์แนบ ข้อความที่แก้ไข
            foreach ($attachs as $key => $attach) {
                $search_key = array_search($attach->file_name, $requestData['attach_filenames']);
                if ($search_key !== false) {
                    $attach->file_note = $requestData['attach_notes'][$search_key];
                    unset($requestData['attach_notes'][$search_key]);
                }
            }
            $requestData['attach_notes'] = array_values(isset($requestData['attach_notes']) ? $requestData['attach_notes'] : []);

            //ไฟล์แนบ เพิ่มเติม (สำหรับไฟล์จาก standard)
            foreach (isset($requestData['attach_filenames']) ? $requestData['attach_filenames'] : [] as $key => $attach_filename) {

                // copy ไฟล์ที่มีอยู่แล้วของ standard && ไม่อยู่ในไฟล์ที่มีอยู่แล้วของ set_standard
                if ($attach_filename != null && !in_array($attach_filename, collect($existAttachs)->pluck('file_name')->toArray())) {
                    $ext = pathinfo($attach_filename, PATHINFO_EXTENSION);
                    $filename = str_random(40) . '.' . $ext;
                    Storage::copy($this->standard_path . $attach_filename, $this->attach_path . $filename);
                    $attachs[] = [
                        'file_name' => $filename,
                        'file_client_name' => $attach_filename,
                        'file_note' => array_key_exists( $key, $requestData['attach_notes'] )?$requestData['attach_notes'][$key]:null
                    ];
                }
            }

            //ไฟล์แนบ เพิ่มเติม (ไฟล์ใหม่)
            $files = $request->file('attachs');
            if ($files) {
                foreach ($files as $key => $file) {
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename
                    $attachs[] = [
                        'file_name' => $storageName,
                        'file_client_name' => $file->getClientOriginalName(),
                        'file_note' => array_key_exists( $key, $requestData['attach_notes'] )?$requestData['attach_notes'][$key]:null
                    ];
                }
            }

            $requestData['attach'] = json_encode(array_values($attachs), JSON_UNESCAPED_UNICODE);

            $set_standard->update($requestData);


            return redirect(url("/tis/set_standard/{$set_standard->id}/edit"))->with('flash_message', 'แก้ไขมอก. เรียบร้อยแล้ว!');
        }
        abort(403);

    }


    public function updatePlan(Request $request) {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {


            $requestData = $request->all();

            $plan = set_standard_plan::findOrFail($requestData['plan_id']);
            $requestData['startdate'] = $request->startdate?Carbon::createFromFormat("d/m/Y",$request->startdate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['enddate'] = $request->enddate?Carbon::createFromFormat("d/m/Y",$request->enddate)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            $quarter_date = $requestData['startdate'];
            $quarter = set_standard::selectRaw('(QUARTER("'.$quarter_date.'") % 4) + 1 As ThisQ')->first();
            $requestData['quarter'] = $quarter->ThisQ;

            $plan->update($requestData);

            return redirect('tis/set_standard/' . $plan->set_standard->id . '/edit')->with('flash_message', 'แก้ไขแพลนเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('delete-' . $model)) {

            $set_standard = set_standard::findOrFail($id);
            $attachs = array_values((array)json_decode($set_standard->attach));

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {
                Storage::disk('uploads')->delete($this->attach_path . $attach->file_name);
            }

            $set_standard->set_standard_plan()->delete();
            $set_standard->delete();

            return redirect('tis/set_standard')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    public function destroyMultiple(Request $request)
    {
        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('delete-' . $model)) {

            $requestData = $request->all();

            $ids = $requestData['cb'];
            foreach ($ids as $id) {
                $set_standard = set_standard::findOrFail($id);
                $attachs = array_values((array)json_decode($set_standard->attach));

                //ไฟล์แนบ ที่ถูกกดลบ
                foreach ($attachs as $key => $attach) {
                    Storage::disk('uploads')->delete($this->attach_path . $attach->file_name);
                }

                $set_standard->set_standard_plan()->delete();
                $set_standard->delete();
            }

            return redirect('tis/set_standard')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request)
    {

        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $requestData = $request->all();

            if (array_key_exists('cb', $requestData)) {
                $ids = $requestData['cb'];
                $db = new set_standard;
                set_standard::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
            }

            return redirect('tis/set_standard')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }

        abort(403);

    }



    public function destroyPlan(Request $request, $id) {

        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $plan = set_standard_plan::findOrFail($id);
            // dd($plan);
            $set_standard = $plan->set_standard;
            $plan->delete();


            return redirect('tis/set_standard/' . $set_standard->id . '/edit')->with('flash_message', 'ลบแพลนเรียบร้อยแล้ว!');
        }

        abort(403);
    }

    // public function destroyResult(Request $request, $id) {

    //     $model = str_slug('set_standard', '-');
    //     if (auth()->user()->can('edit-' . $model)) {

    //         $result = set_standard_result::findOrFail($id);
    //         dd($result);
    //         $set_standard = $result->set_standard;
    //         $result->delete();


    //         return redirect('tis/set_standard/' . $set_standard->id . '/edit')->with('flash_message', 'ลบผลเรียบร้อยแล้ว!');
    //     }

    //     abort(403);
    // }

    public function apiDestroyPlan(Request $request, $id) {

        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $plan = set_standard_plan::findOrFail($id);
            $plan->result()->delete();
            $plan->delete();


            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Can not access'
        ]);
    }

    public function apiDestroyResult(Request $request, $id) {

        $model = str_slug('set_standard', '-');
        if (auth()->user()->can('edit-' . $model)) {

            $result = set_standard_result::findOrFail($id);
            // $result->result()->delete();
            $result->delete();


            return response()->json([
                'status' => true
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Can not access'
        ]);
    }

    /*
      **** Save หน้า แผน ***
    */
    public function savePlan($requestData)
    {
        try {
            $basic_oparation = $requestData['statusOperation'];
            $year = $requestData['year'];
            $quarter = $requestData['quarter'];
            $start_day = $requestData['start_day'];
            $end_day = $requestData['end_day'];
            $numpeople_g = $requestData['numpeople_g'];
            $allowances_referee_g = $requestData['allowances_referee_g'];
            $allowances_persident_g = $requestData['allowances_persident_g'];
            $sum_g = $requestData['sum_g'];
            $numpeople_subg = $requestData['numpeople_subg'];
            $allowances_referee_subg = $requestData['allowances_referee_subg'];
            $allowances_persident_subg = $requestData['allowances_persident_subg'];
            $sum_subg = $requestData['sum_subg'];
            $numpeople_attendees = $requestData['numpeople_attendees'];
            $food_morning_attendees = $requestData['food_morning_attendees'];
            $food_noon_attendees = $requestData['food_noon_attendees'];
            $food_afternoon_attendees = $requestData['food_afternoon_attendees'];
            $sum_attendees = $requestData['sum_attendees'];
            $total = $requestData['total'];
            $sum = $requestData['sum'];

            //InsertDatabase//
            // $tis_setStandardPlan = new SetStandardPlan;
            // $tis_setStandardPlan->statusOperation_id = $basic_oparation;
            // $tis_setStandardPlan->year = $year;
            // $tis_setStandardPlan->quarter = $quarter;
            // $tis_setStandardPlan->start_day = $start_day;
            // $tis_setStandardPlan->end_day = $end_day;
            // $tis_setStandardPlan->numpeople_g = $numpeople_g;
            // $tis_setStandardPlan->allowances_referee_g = $allowances_referee_g;
            // $tis_setStandardPlan->allowances_persident_g = $allowances_persident_g;
            // $tis_setStandardPlan->sum_g = $sum_g;
            // $tis_setStandardPlan->numpeople_subg = $numpeople_subg;
            // $tis_setStandardPlan->allowances_referee_subg = $allowances_referee_subg;
            // $tis_setStandardPlan->allowances_persident_subg = $allowances_persident_subg;
            // $tis_setStandardPlan->sum_subg = $sum_subg;
            // $tis_setStandardPlan->numpeople_attendees = $numpeople_attendees;
            // $tis_setStandardPlan->food_morning_attendees = $food_morning_attendees;
            // $tis_setStandardPlan->food_noon_attendees = $food_noon_attendees;
            // $tis_setStandardPlan->food_afternoon_attendees = $food_afternoon_attendees;
            // $tis_setStandardPlan->sum_attendees = $sum_attendees;
            // $tis_setStandardPlan->total = $total;
            // $tis_setStandardPlan->sum = $sum;
            // $tis_setStandardPlan->save();


        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function get_secretary(Request $request)
    {
        $data_details = Appoint::select('secretary')->where('id', $request->get('appoint_id'))->first();
        //   $data = explode(",",$data_details->secretary);
          $data = $data_details->secretary;
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

     public function get_method_detail($method_id)
    {

        $data_details = Method::select('details')->where('id', $method_id)->first();
        $data = explode(",",$data_details->details);
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }

    public function standard_announcement(Request $request)
    {
        $standard_format_arr = ['1'=>'ทั่วไป', '2'=>'บังคับ'];
        $set_standard_id = $request->get('set_standard_id');
        $set_standard_data = set_standard::where('id',$set_standard_id)->first();

        if(!empty($set_standard_data->standard_id)){
            Standard::where('id',$set_standard_data->standard_id)->update(array('state' => '0'));
        }

        $requestData['title'] = $set_standard_data->title;
        $requestData['title_en'] = $set_standard_data->title_en;
        $requestData['tis_force'] = $standard_format_arr[$set_standard_data->standard_format_id]??null;
        $requestData['tis_no'] = $set_standard_data->tis_no;
        $requestData['tis_year'] = $set_standard_data->start_year;
        $requestData['tis_book'] = $set_standard_data->tis_book;
        $requestData['remark'] = $set_standard_data->remark;
        $requestData['board_type_id'] = $set_standard_data->appoint_id;
        $requestData['standard_type_id'] = $set_standard_data->standard_type_id;
        $requestData['standard_format_id'] = $set_standard_data->standard_format_id;
        $requestData['set_format_id'] = $set_standard_data->set_format_id;
        $requestData['method_id'] = $set_standard_data->method_id;
        $requestData['method_id_detail'] = $set_standard_data->method_id_detail;
        $requestData['product_group_id'] = $set_standard_data->product_group_id;
        $requestData['industry_target_id'] = $set_standard_data->industry_target_id;
        $requestData['staff_group_id'] = $set_standard_data->staff_group;
        $requestData['staff_responsible'] = $set_standard_data->secretary;
        $requestData['refer'] = $set_standard_data->refer;
        // $requestData['attach'] = $set_standard_data->attach;
        $requestData['state'] = $set_standard_data->state;
        $requestData['review_status'] = $set_standard_data->review_status;
        $requestData['created_by'] = $set_standard_data->created_by;
        $requestData['updated_by'] = $set_standard_data->updated_by;
        $requestData['government_gazette'] = 'w';
        $requestData['set_std_id'] =  $set_standard_data->id;

        if($set_standard_data->review_status == 2){
            $requestData['tisid_ref'] = $set_standard_data->tis_no;
            $standards = Standard::where('id',$set_standard_data->tis_no)->first();
            if(!is_null($standards)){
                $requestData['tisno_ref'] = $standards->tis_no ?? null;
            }
          
        }else{
            $requestData['tisno_ref'] = $set_standard_data->tis_no ?? null;
        }
   

        $standard_data = Standard::create($requestData);

        if($standard_data){
            set_standard::where('id',$set_standard_id)->update(array('announce' => 'y', 'state' => 0));
            return response()->json([
            "status" => "success",
        ]);
        } else {
            return response()->json([
            "status" => "error",
        ]);
        }


    }

    public function cancel_announcement(Request $request)
    {
        $set_standard_id = $request->get('set_standard_id');
        $set_standard_data = set_standard::where('id',$set_standard_id)->update(array('announce' => 'n', 'state' => 1));

        if($set_standard_data){
            return response()->json([
            "status" => "success",
        ]);
        } else {
            return response()->json([
            "status" => "error",
        ]);
        }
    }


    public function apiGetStandards() {
        $set_standard_id = public_draft::where('result_draft',2)->pluck('set_standard_id');
        $standards = Standard::select('title','tis_no', 'id')->whereIn('id',$set_standard_id)->get();
        $tis_book = Standard::select('tis_book')
                            ->whereIn('id',$set_standard_id)
                            ->whereNotNull('tis_book')
                            ->groupBy('tis_book')
                            ->get();
        return response()->json(compact('standards','tis_book'));
    }

    public function apiGet_Standards() {
        $standards = Standard::select('title','tis_no', 'id')->get();
        $tis_book = Standard::select('tis_book')
                            ->whereNotNull('tis_book')
                            ->groupBy('tis_book')
                            ->get();
        return response()->json(compact('standards','tis_book'));
    }

    public function apiFirst_Standards($id) {
        $standards = Standard::where('id',$id)->first();
        return response()->json(compact('standards'));
    }

    public function filter_method_detail($id) {
      
            $method_details = [];
            $data_details = Method::select('details')->where('id', $id)->first();
            if(!is_null($data_details)){
                $data_method_details = explode(",",$data_details->details);
                foreach ($data_method_details as $key => $value) {
                    $method_details[$key] = $value;
                }
            }
        
     
        return response()->json(compact('method_details'));
    }


}
