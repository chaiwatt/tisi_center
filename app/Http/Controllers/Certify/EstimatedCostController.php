<?php

namespace App\Http\Controllers\Certify;

use HP;
use Storage;
use App\User;
use stdClass;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\Lab\EstimatedCost;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Models\Bcertify\LabCalRequest;
use App\Models\Certify\Applicant\Cost;
use App\Models\Bcertify\LabRequestType;
use App\Models\Bcertify\LabTestRequest;
use App\Models\Bcertify\BranchLabAdress;
use App\Models\Certify\Applicant\CertiLab;
use App\Models\Certify\Applicant\CostDate;
use App\Models\Certify\Applicant\CostItem;
use App\Models\Certify\CertificateHistory;
use App\Models\Certify\Applicant\Assessment;
use App\Models\Certify\Applicant\CostHistory;
use App\Models\Certify\Applicant\Information;
use App\Models\Certify\Applicant\StatusTrait;
use App\Models\Bcertify\LabCalScopeTransaction;
use App\Models\Bcertify\LabCalScopeUsageStatus;
use App\Models\Certify\Applicant\CostAssessment;
use App\Models\Certify\Applicant\CertiLabAttachAll;
use App\Models\Certify\Applicant\AssessmentExaminer;

class EstimatedCostController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/applicants/check_files/';
    }


    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $filter = [];
        $filter['at'] = $request->get('at', '');
        $filter['b'] = $request->get('b', '');
        $filter['s'] = $request->get('s', '');
        $filter['c'] = $request->get('c', '');
        $filter['app'] = $request->get('app', '');
        $filter['sort'] = $request->get('sort', '');
        $filter['direction'] = $request->get('direction', '');
        $filter['q'] = $request->get('q', '');
        $filter['perPage'] = $request->get('perPage', 10);


        $ao = new CertiLab;
        $arrStatus = $ao->arrStatus();
        $branches = collect();

        $app_id = $request->app;
        $app = $app_id ? CertiLab::find($app_id) : null;
        if ($app) {
            $Query = $app->costs();
        } else {
            $Query = new Cost;
        }
          $Query = $Query->select('app_certi_lab_costs.*');
        if ($filter['at']!='') { // ความสามารถห้องปฏิบัติการ
            $CertiLab  =  CertiLab::where('lab_type',$filter['at'])->get()->pluck('id');
            $Query = $Query->whereIn('app_certi_lab_id', $CertiLab);
        }

        if ($filter['b']!='' && $filter['at']!='') { // สาขา
            $Query = $Query->where('branch_name', $filter['b']);
        }

        if ($filter['s']!='') { // สถานะคำขอ
            $draft = ['0', '3'];
            $agree = ['1', '2'];
            if(in_array($filter['s'], $agree)){
                $Query = $Query->where('agree', $filter['s']);
            }else if(in_array($filter['s'], $draft)){
                $Query = $Query->where('draft', $filter['s']);
            }else{
                $Query = $Query->whereNotIn('draft', $draft)->whereNotIn('agree', $agree);
            }
        }

        if ($filter['c']!='') { // สถานะคำขอ
            $Query = $Query->whereHas('assessment', function ($query) use ($filter) {
                $query->where('checker_id', $filter['c']);
            });
        }

        if ($filter['q']!='') { // คำค้น
            $Query = $Query->where(function ($query) use ($filter) {
                $key = $filter['q'];

                $query->orWhereHas('applicant', function ($query) use ($key) {
                    $query->where('app_no', 'like', '%'.$key.'%')
                          ->orWhereHas('trader', function ($query) use ($key) {
                              $query->where('name', 'like', '%'.$key.'%');
                          });
                });

            });
        }

             //เจ้าหน้าที่ LAB และไม่มีสิทธิ์ admin , ผอ , ผก , ลท.
            if(in_array("28",auth()->user()->RoleListId) && auth()->user()->SetRolesAdminCertify() == "false" ){
                $check = AssessmentExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); // เช็คเจ้าหน้าที่ IB
                if(isset($check) && count($check) > 0  ) {
                    $Query = $Query->LeftJoin('app_certi_lab_assessments_examiner','app_certi_lab_assessments_examiner.app_certi_lab_id','=','app_certi_lab_costs.app_certi_lab_id')
                                     ->where('user_id',auth()->user()->runrecno);  // LAB เจ้าหน้าที่ที่ได้มอบหมาย
                }else{
                    $Query = $Query->whereIn('id',['']);  // ไม่ตรงกับเงื่อนไข
                }
            }
        // $examiner = AssessmentExaminer::where('user_id',auth()->user()->runrecno)->pluck('app_certi_lab_id'); //เจ้าหน้าที่ รับผิดชอบ  สก.
        // $User =   User::where('runrecno',auth()->user()->runrecno)->first();
        // $select_users = array();
        // if($User->IsGetIdRoles() == 'false'){  //ไม่ใช่ admin , ผอ , ลท
        //     if(!is_null($examiner) && count($examiner) > 0 ){
        //         $Query = $Query->LeftJoin('app_certi_lab_assessments_examiner','app_certi_lab_assessments_examiner.app_certi_lab_id','=','app_certi_lab_costs.app_certi_lab_id')
        //                      ->where('user_id',auth()->user()->runrecno);  // LAB เจ้าหน้าที่ที่ได้มอบหมาย
        //     }
        //  }

        $users = User::orderBy('reg_fname')->get();
        $select_users = array();
        foreach ($users as $user) {
            $select_users[$user->runrecno] = $user->reg_fname . ' ' . $user->reg_lname;
        }

        $costs = $Query ->orderby('id','desc')->sortable()->paginate($filter['perPage']);
        
        return view('certify.estimated_cost.index', compact(
            'costs', 'app','select_users','arrStatus','filter','branches'
        ));
    }


    public function create(CertiLab $app = null)
    {
        $cost_item = [new CostItem];
        $app_no = [];
         $certi_lab =  CertiLab::select('app_no','id')->where('status',9)->orderby('id','desc')->get();

         foreach ($certi_lab as $item) {
             if(count($item->costs_draft_not) == 0){
                $app_no[$item->app_no] = $item->app_no  ;
             }
        }

        return view('certify.estimated_cost.create', compact('app','cost_item','app_no'));
    }


    public function store(Request $request, CertiLab $app = null)
    {
        $request->validate([
            'app_no' => 'required',
            // 'cost_date' => 'required',
            // 'cost_assessment' => 'required',
            'draft' => 'required|in:1,0',
        ]);
 try {
        $redirectWithApp = true;
        if ($app == null) {
            $redirectWithApp = false;
            $app_no = $request->input('app_no');
            $app = CertiLab::where('app_no', $app_no)->first();

        }

        $items = $request->input('cost_assessment');
        $cost = new Cost;
        $cost->app_certi_assessment_id = $app->assessment->id ?? null;
        $cost->app_certi_lab_id = $app->id;
        $cost->checker_id = auth()->user()->runrecno;
        $cost->draft = $request->input('draft') ?? 0;
        $cost->vehicle = isset($request->vehicle) ? 1 : null ;
        if($request->attachs && $request->hasFile('attachs')){
            foreach ($request->attachs as $key => $itme) {
                $list  = new  stdClass;
                $list->attachs          =   $this->store_File($itme,$app->app_no);
                $list->file_client_name =   HP::ConvertCertifyFileName($itme->getClientOriginalName());
                $attachs[] = $list;
            }
            $cost->attachs = json_encode($attachs);
         }

        $cost->save();

        $requestData = $request->all();
        $this->storeItems($requestData, $cost);//บันทึกรายละเอียด

        if(!is_null($app) && $cost->draft == 1){
            if(isset($request->vehicle)){
                $config = HP::getConfig();
                $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                $app->update(['status'=>11]); // ขอความเห็นประมาณการค่าใช้จ่าย
                //Log
                 $this->CertificateHistory($cost);
               //E-mail
                $this->set_mail($cost,$app);

            }else{
                $app->update(['status'=>10]); //  ประมาณการค่าใช้จ่าย
            }

        }
        return redirect('certify/estimated_cost')->with('flash_message', 'สร้างเรียบร้อยแล้ว');
    } catch (\Exception $e) {
        return redirect('certify/estimated_cost')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
    }


    }

    // สำหรับเพิ่มรูปไปที่ store
    // public function store_File($files, $app_no = 'files_lab',$name =null)
    // {
    //     $no  = str_replace("RQ-","",$app_no);
    //     $no  = str_replace("-","_",$no);
        
    //     if ($files) {
    //         $attach_path  =  $this->attach_path.$no;
    //         $file_extension = $files->getClientOriginalExtension();
    //         $fileClientOriginal   =  HP::ConvertCertifyFileName($files->getClientOriginalName());
    //         $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
    //         $fullFileName = str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
            
    //         $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
    //         $storageName = basename($storagePath); // Extract the filename
    //         return  $no.'/'.$storageName;
    //     }else{
    //         return null;
    //     }

    // }

    public function store_File($files, $app_no = 'files_lab', $name = null)
    {
        $no = str_replace("RQ-", "", $app_no);
        $no = str_replace("-", "_", $no);

        if ($files) {
            $attach_path = $this->attach_path . $no;
            $file_extension = $files->getClientOriginalExtension();
            $fileClientOriginal = HP::ConvertCertifyFileName($files->getClientOriginalName());
            $filename = pathinfo($fileClientOriginal, PATHINFO_FILENAME);
            $fullFileName = str_random(10) . '-date_time' . date('Ymd_hms') . '.' . $files->getClientOriginalExtension();

            try {
                $storagePath = Storage::putFileAs($attach_path, $files, str_replace(" ", "", $fullFileName));
                $storageName = basename($storagePath); // Extract the filename
                return $no . '/' . $storageName;
            } catch (\Exception $e) {
                // แสดงผลข้อความข้อผิดพลาด
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return null;
        }
    }



    /**
     * @param $dates
     * @param $cost
     * @throws Exception
     */
    public function storeDates($dates, $cost) {
        try {
            $cost->dates()->delete();

            foreach ($dates as $data) {
                $date = new CostDate;
                $date->app_certi_cost_id = $cost->id;
                $date->desc = $data['desc'] ?? null;
                $date->amount_date = $data['date'] ?? 0;
                $date->save();
            }
        } catch (Exception $x) {
            throw $x;
        }
    }

    /**
     * @param $items
     * @param $cost
     * @throws Exception
     */
    public function storeItems($items, $cost) {
        try {
            $cost->items()->delete();
            $detail = (array)@$items['detail'];
            foreach($detail['desc'] as $key => $data ) {
                $item = new CostItem;
                $item->app_certi_cost_id = $cost->id;
                $item->desc = $data ?? null;
                $item->amount_date = $detail['nod'][$key] ?? 0;
                $item->amount =  !empty(str_replace(",","", $detail['cost'][$key]))?str_replace(",","",$detail['cost'][$key]):null;
                $item->save();
            }
        } catch (Exception $x) {
            throw $x;
        }
    }



    public function edit(Cost $ec, CertiLab $app = null)
    {
        $previousUrl = app('url')->previous();
        $cost_item = CostItem::where('app_certi_cost_id',$ec->id)->get();
        if(count($cost_item) <= 0){
            $cost_item = [new CostItem];
        }

        $certi_lab = CertiLab::find($ec->app_certi_lab_id);
        $labCalScopeUsageStatus = LabCalScopeUsageStatus::where('app_certi_lab_id', $certi_lab->id)
        ->where('status', 2)
        ->first();
    
        $labCalScopeTransactions = $labCalScopeUsageStatus ? 
        $labCalScopeUsageStatus->transactions()->with([
        'calibrationBranch',
        'calibrationBranchInstrumentGroup',
        'calibrationBranchInstrument',
        'calibrationBranchParam1',
        'calibrationBranchParam2'
        ])->get() : [];

        if (is_null($labCalScopeTransactions)) {
        $labCalScopeTransactions = [];
        }


        $branchLabAdresses = BranchLabAdress::where('app_certi_lab_id', $certi_lab->id)->with([
                            'certiLab', 
                            'province', 
                            'amphur', 
                            'district'
                        ])->get();

        $labCalScopeTransactionGroups = LabCalScopeUsageStatus::where('app_certi_lab_id', $certi_lab->id)
                        ->where('status', 1)
                        ->select('group', 'created_at') // เลือกฟิลด์ที่ต้องการ
                        ->get()
                        ->unique('group') // ทำให้ค่า group ไม่ซ้ำกัน
                        ->values(); // รีเซ็ต index ของ Collection

        $labTestRequest = LabTestRequest::with([
                            'certiLab', 
                            'labTestTransactions.labTestMeasurements'
                        ])
                        ->where('app_certi_lab_id', $certi_lab->id)
                        ->get();

        $labCalRequest = LabCalRequest::with([
                            'certiLab', 
                            'labCalTransactions.labCalMeasurements.labCalMeasurementRanges'
                        ])
                        ->where('app_certi_lab_id', $certi_lab->id)
                        ->get();

        $certi_lab_attach_all61 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                        ->where('file_section', '61')
                        ->get();
        $certi_lab_attach_all62 = CertiLabAttachAll::where('app_certi_lab_id', $certi_lab->id)
                       ->where('file_section', '62')
                       ->get();                
        return view('certify.estimated_cost.edit', compact('ec',
                                                            'app',
                                                            'previousUrl',
                                                            'cost_item',
                                                            'labCalScopeTransactions',
                                                            'branchLabAdresses',
                                                            'certi_lab',
                                                            'labCalScopeTransactionGroups',
                                                            'labTestRequest',
                                                            'labCalRequest',
                                                            'certi_lab_attach_all61',
                                                            'certi_lab_attach_all62',
                                                        ));
    }

        /**
     * ฟังก์ชันสำหรับตรวจสอบการเปลี่ยนแปลง
     */
    private function getChangedTransactions(array $labTypes, $certilabId)
    {
        // เตรียม Collection สำหรับเก็บข้อมูลที่เปลี่ยนแปลง
        $changedTransactions = collect();

        // นับจำนวนรายการใน $labTypes ที่เข้ามา
        $inputTransactionsCount = 0;
        foreach ($labTypes as $labTypeValues) {
            if (is_array($labTypeValues)) {
                $inputTransactionsCount += count($labTypeValues);
            }
        }

        $newUsageStatus = LabCalScopeUsageStatus::where('app_certi_lab_id',$certilabId)->where('status',2)->first();
   

        // นับจำนวนรายการในฐานข้อมูล
        $existingTransactionsCount = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
            ->where('branch_type', 1)
            ->where('group', $newUsageStatus->group)
            ->count();

        // ถ้าจำนวนไม่เท่ากัน แสดงว่ามีการเปลี่ยนแปลง
        if ($inputTransactionsCount !== $existingTransactionsCount) {
            $changedTransactions->push([
                'message' => 'Number of lab types transactions has changed.',
                'input_count' => $inputTransactionsCount,
                'database_count' => $existingTransactionsCount,
            ]);
        
        }else{


            // วนลูปผ่านแต่ละ site_type เช่น "pl_2_1_main", "pl_2_2_main"
            foreach ($labTypes as $key => $labTypeValues) {
                // ตรวจสอบว่าค่าของ key เป็น array ก่อนที่จะ loop
                if (is_array($labTypeValues)) {
                    // วนลูปผ่านแต่ละรายการใน labTypeValues
                    foreach ($labTypeValues as $labType) {
                        // ค้นหารายการในฐานข้อมูลที่ตรงกับเงื่อนไข
                        $existingTransaction = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
                            ->where('branch_type', 1)
                            ->where('site_type', $key)
                            // ตรวจสอบ cal_main_branch
                            ->when($labType['cal_main_branch'] === "", function ($query) {
                                return $query->whereNull('bcertify_calibration_branche_id');
                            }, function ($query) use ($labType) {
                                return $query->where('bcertify_calibration_branche_id', $labType['cal_main_branch']);
                            })
                            // ตรวจสอบ cal_instrumentgroup
                            ->when($labType['cal_instrumentgroup'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_instrument_group_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_instrument_group_id', $labType['cal_instrumentgroup']);
                            })
                            // ตรวจสอบ cal_instrument
                            ->when($labType['cal_instrument'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_instrument_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_instrument_id', $labType['cal_instrument']);
                            })
                            // ตรวจสอบ cal_parameter_one
                            ->when($labType['cal_parameter_one'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_parameter_one_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_parameter_one_id', $labType['cal_parameter_one']);
                            })
                            // ตรวจสอบ cal_parameter_two
                            ->when($labType['cal_parameter_two'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_parameter_two_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_parameter_two_id', $labType['cal_parameter_two']);
                            })
                            ->first();

                        // ถ้าหากไม่มีในฐานข้อมูล แสดงว่ามีการเปลี่ยนแปลง
                        if (!$existingTransaction) {
                            $changedTransactions->push([
                                'site_type' => $key,
                                'new' => [
                                    'cal_main_branch' => [
                                        'existing' => null,
                                        'change' => $labType['cal_main_branch'] ?? null,
                                    ],
                                    'cal_instrumentgroup' => [
                                        'existing' => null,
                                        'change' => $labType['cal_instrumentgroup'] ?? null,
                                    ],
                                    'cal_instrument' => [
                                        'existing' => null,
                                        'change' => $labType['cal_instrument'] ?? null,
                                    ],
                                    'cal_parameter_one' => [
                                        'existing' => null,
                                        'change' => $labType['cal_parameter_one'] ?? null,
                                    ],
                                    'cal_parameter_two' => [
                                        'existing' => null,
                                        'change' => $labType['cal_parameter_two'] ?? null,
                                    ]
                                ]
                            ]);
                        }
                    }
                }
            }
        }


        // return collection ของข้อมูลที่เปลี่ยนแปลง
        return $changedTransactions;
    }


    /**
     * ฟังก์ชันสำหรับตรวจสอบการเปลี่ยนแปลงของสาขา
     */
    private function getChangedBranchTransactions(array $labAddresses, $certilabId)
    {
        // เตรียม Collection สำหรับเก็บข้อมูลที่เปลี่ยนแปลง
        $changedBranchTransactions = collect();
    
        // นับจำนวนรายการใน $labAddresses ที่เข้ามา
        $inputBranchTransactionsCount = 0;
        foreach ($labAddresses as $branch) {
            if (isset($branch['lab_types']) && is_array($branch['lab_types'])) {
                foreach ($branch['lab_types'] as $labTypeValues) {
                    if (is_array($labTypeValues)) {
                        $inputBranchTransactionsCount += count($labTypeValues);
                    }
                }
            }
        }
    
        $newUsageStatus = LabCalScopeUsageStatus::where('app_certi_lab_id',$certilabId)->where('status',2)->first();
        // นับจำนวนรายการในฐานข้อมูลของสาขา
        $existingBranchTransactionsCount = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
            ->where('branch_type', 2)
            ->where('group', $newUsageStatus->group)
            ->count();
    
        // ถ้าจำนวนไม่เท่ากัน แสดงว่ามีการเปลี่ยนแปลง
        if ($inputBranchTransactionsCount !== $existingBranchTransactionsCount) {
            $changedBranchTransactions->push([
                'message' => 'Number of branch lab types transactions has changed.',
                'input_count' => $inputBranchTransactionsCount,
                'database_count' => $existingBranchTransactionsCount,
            ]);
        } else {
            // ตรวจสอบข้อมูล lab_types ของสาขา
            foreach ($labAddresses as $branchIndex => $branch) {
                if (isset($branch['lab_types']) && is_array($branch['lab_types'])) {
                    foreach ($branch['lab_types'] as $key => $labTypeValues) {
                        if (is_array($labTypeValues)) {
                            foreach ($labTypeValues as $labType) {
                                // ค้นหารายการในฐานข้อมูลที่ตรงกับเงื่อนไข
                                $existingTransaction = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
                                    ->where('branch_type', 2)
                                    ->where('site_type', $key)
                                    // ตรวจสอบ cal_main_branch
                                    ->when($labType['cal_main_branch'] === "", function ($query) {
                                        return $query->whereNull('bcertify_calibration_branche_id');
                                    }, function ($query) use ($labType) {
                                        return $query->where('bcertify_calibration_branche_id', $labType['cal_main_branch']);
                                    })
                                    // ตรวจสอบ cal_instrumentgroup
                                    ->when($labType['cal_instrumentgroup'] === "", function ($query) {
                                        return $query->whereNull('calibration_branch_instrument_group_id');
                                    }, function ($query) use ($labType) {
                                        return $query->where('calibration_branch_instrument_group_id', $labType['cal_instrumentgroup']);
                                    })
                                    // ตรวจสอบ cal_instrument
                                    ->when($labType['cal_instrument'] === "", function ($query) {
                                        return $query->whereNull('calibration_branch_instrument_id');
                                    }, function ($query) use ($labType) {
                                        return $query->where('calibration_branch_instrument_id', $labType['cal_instrument']);
                                    })
                                    // ตรวจสอบ cal_parameter_one
                                    ->when($labType['cal_parameter_one'] === "", function ($query) {
                                        return $query->whereNull('calibration_branch_parameter_one_id');
                                    }, function ($query) use ($labType) {
                                        return $query->where('calibration_branch_parameter_one_id', $labType['cal_parameter_one']);
                                    })
                                    // ตรวจสอบ cal_parameter_two
                                    ->when($labType['cal_parameter_two'] === "", function ($query) {
                                        return $query->whereNull('calibration_branch_parameter_two_id');
                                    }, function ($query) use ($labType) {
                                        return $query->where('calibration_branch_parameter_two_id', $labType['cal_parameter_two']);
                                    })
                                    ->first();
    
                                // ถ้าหากไม่มีในฐานข้อมูล แสดงว่ามีการเปลี่ยนแปลง
                                if (!$existingTransaction) {
                                    $changedBranchTransactions->push([
                                        'branch_lab_adress_id' => $branchIndex,
                                        'site_type' => $key,
                                        'new' => [
                                            'cal_main_branch' => [
                                                'existing' => null,
                                                'change' => $labType['cal_main_branch'] ?? null,
                                            ],
                                            'cal_instrumentgroup' => [
                                                'existing' => null,
                                                'change' => $labType['cal_instrumentgroup'] ?? null,
                                            ],
                                            'cal_instrument' => [
                                                'existing' => null,
                                                'change' => $labType['cal_instrument'] ?? null,
                                            ],
                                            'cal_parameter_one' => [
                                                'existing' => null,
                                                'change' => $labType['cal_parameter_one'] ?? null,
                                            ],
                                            'cal_parameter_two' => [
                                                'existing' => null,
                                                'change' => $labType['cal_parameter_two'] ?? null,
                                            ]
                                        ]
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
    
        // return collection ของข้อมูลที่เปลี่ยนแปลง
        return $changedBranchTransactions;
    }
    

    private function updateBranchTransactions(array $labAddresses, $certilabId)
{
    foreach ($labAddresses as $branchIndex => $branch) {
        if (isset($branch['lab_types']) && is_array($branch['lab_types'])) {
            foreach ($branch['lab_types'] as $key => $labTypeValues) {
                if (is_array($labTypeValues)) {
                    foreach ($labTypeValues as $labType) {
                        // ค้นหารายการในฐานข้อมูลที่ตรงกับเงื่อนไข
                        $existingTransaction = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
                            ->where('branch_type', 2)
                            ->where('site_type', $key)
                            // ตรวจสอบ cal_main_branch
                            ->when($labType['cal_main_branch'] === "", function ($query) {
                                return $query->whereNull('bcertify_calibration_branche_id');
                            }, function ($query) use ($labType) {
                                return $query->where('bcertify_calibration_branche_id', $labType['cal_main_branch']);
                            })
                            // ตรวจสอบ cal_instrumentgroup
                            ->when($labType['cal_instrumentgroup'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_instrument_group_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_instrument_group_id', $labType['cal_instrumentgroup']);
                            })
                            // ตรวจสอบ cal_instrument
                            ->when($labType['cal_instrument'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_instrument_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_instrument_id', $labType['cal_instrument']);
                            })
                            // ตรวจสอบ cal_parameter_one
                            ->when($labType['cal_parameter_one'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_parameter_one_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_parameter_one_id', $labType['cal_parameter_one']);
                            })
                            // ตรวจสอบ cal_parameter_two
                            ->when($labType['cal_parameter_two'] === "", function ($query) {
                                return $query->whereNull('calibration_branch_parameter_two_id');
                            }, function ($query) use ($labType) {
                                return $query->where('calibration_branch_parameter_two_id', $labType['cal_parameter_two']);
                            })
                            ->first();

                        // ถ้าพบรายการที่ตรงกับเงื่อนไข ให้ทำการอัปเดต parameter_one_value, parameter_two_value, cal_method
                        if ($existingTransaction) {
                            $existingTransaction->parameter_one_value = $labType['cal_parameter_one_value'] ?? null;
                            $existingTransaction->parameter_two_value = $labType['cal_parameter_two_value'] ?? null;
                            $existingTransaction->cal_method = $labType['cal_method'] ?? null;
                            $existingTransaction->save();
                        }
                    }
                }
            }
        }
    }
}



    public function update(Request $request, Cost $cost, CertiLab $app = null)
    {
    //    dd($request->all());
        // $labAddresses = json_decode($request->input('lab_addresses'), true);
        // $labMainAddress = json_decode($request->input('lab_main_address'), true);

        // dd($labAddresses);
        $request->validate([
            'app_no' => 'required',
            // 'cost_date' => 'required',
            // 'cost_assessment' => 'required',
            'draft' => 'required|in:1,0',
        ]);
        try {
    
            $redirectWithApp = true;
            if ($app == null) {
                $redirectWithApp = false;
                $app_no = $request->input('app_no');
                $app = CertiLab::where('app_no', $app_no)->orderby('id','desc')->first();
            }

            // เรียกฟังก์ชันตรวจสอบการเปลี่ยนแปลง
            // $changedTransactions = $this->getChangedTransactions($labMainAddress['lab_types'], $app->id);

            // $changedBranchTransactions = $this->getChangedBranchTransactions($labAddresses, $app->id);
        
            // if($changedTransactions->count() === 0 && $changedBranchTransactions->count() === 0){
            //     // dd('same in main and branch, need update existing');
            //     $this->updateMainTransactions($labMainAddress['lab_types'], $app->id);
            //     $this->updateBranchTransactions($labAddresses, $app->id);

            // }else{
            //     $this->createNewTransactions($labMainAddress, $labAddresses, $app->id);
            //     // dd('diff in main or branch, need ceate new transaction');
            // }


            // dd($changedTransactions,$changedBranchTransactions);


            $dates = $request->input('cost_date');
            
            $cost->app_certi_assessment_id = $app->assessment->id ?? null;
            $cost->app_certi_lab_id = $app->id;
            $cost->checker_id = auth()->user()->runrecno;
            $cost->draft = $request->input('draft') ?? 0;
            $cost->remark = null;
            $cost->check_status = null;
            $cost->remark_scope = null;
            $cost->agree = null;
            $cost->vehicle = isset($request->vehicle) ? 1 : null ;

            // ดาวน์โหลดจาก LabAttachAll แล้วนำมาอัพโหลดใหม่
            if($cost->attachs == null){       
                $latestRecord = CertiLabAttachAll::where('app_certi_lab_id', $app->id)
                        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
                        ->first();
                $existingFilePath = 'files/applicants/check_files/' . $latestRecord->file ;

                // ตรวจสอบว่าไฟล์มีอยู่ใน FTP และดาวน์โหลดลงมา
                if (HP::checkFileStorage($existingFilePath)) {
                    $localFilePath = HP::getFileStoragePath($existingFilePath); // ดึงไฟล์ลงมาที่เซิร์ฟเวอร์
                    $no  = str_replace("RQ-","",$app->app_no);
                    $no  = str_replace("-","_",$no);
                    $dlName = 'attached_'.basename($existingFilePath);
                    $attach_path  =  'files/applicants/check_files/'.$no.'/';

                    if (file_exists($localFilePath)) {
                        $storagePath = Storage::putFileAs($attach_path, new \Illuminate\Http\File($localFilePath),  $dlName );

                        $filePath = $attach_path . $dlName;
                        if (Storage::disk('ftp')->exists($filePath)) {
                            
                            if($cost->attachs != ''){
                                $attachs = array_values((array)json_decode($cost->attachs));
                            }
                            $list  = new  stdClass;
                            $list->attachs =  $no.'/'.$dlName;
                            $list->file_client_name =  $dlName;
                            $attachs[] = $list;
                            $cost->attachs = json_encode($attachs);
                            // dd('File Path on Server: ' . $filePath);
                        } 
                        unlink($localFilePath);
                    }
                }
            }

            // if ($request->hasFile('attachs')) {
                
            //     if($cost->attachs != ''){
            //         $attachs = array_values((array)json_decode($cost->attachs));
            //     }
                
            //     foreach ($request->attachs as $key => $itme) {
            //         $list  = new  stdClass;
                    
            //         $list->attachs =   $this->store_File($itme,$app->app_no);

            //         $list->file_client_name =  HP::ConvertCertifyFileName($itme->getClientOriginalName());
            //         $attachs[] = $list;
            //     }
            //     $cost->attachs = json_encode($attachs);
            //  }

            $cost->save();

            $requestData = $request->all();
            $this->storeItems($requestData, $cost);//บันทึกรายละเอียด

                if(!is_null($app) && $cost->draft == 1){
                    if(isset($request->vehicle)){
                        $config = HP::getConfig();
                        $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
                        $app->update(['status'=>11]); // ขอความเห็นประมาณการค่าใช้จ่าย
                        //log
                        $this->CertificateHistory($cost);
                        //E-mail
                        $this->set_mail($cost,$app);

                    }else{
                        $app->update(['status'=>10]); //  ประมาณการค่าใช้จ่าย
                    }
                }

            return redirect('certify/estimated_cost')->with('flash_message', 'อัพเดทเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect('certify/estimated_cost')->with('message_error', 'เกิดข้อผิดพลาดกรุณาบันทึกใหม่');
        }

        }

    private function createNewTransactions($labMainAddress, $labAddresses, $certilabId)
    {
        
        // ตรวจสอบและอัปเดต LabCalScopeUsageStatus ทั้งหมด
        $usageStatuses = LabCalScopeUsageStatus::where('app_certi_lab_id', $certilabId)->get();

        if ($usageStatuses->isNotEmpty()) {
            // อัปเดต status เป็น 1 ในทุกสถานะ
            foreach ($usageStatuses as $usageStatus) {
                $usageStatus->status = 1;
                $usageStatus->save();
            }

            // ดึงค่า group จากรายการล่าสุด และเพิ่ม 1
            $lastUsageStatus = $usageStatuses->last();
            $newGroup = $lastUsageStatus->group + 1;
        } else {
            // ถ้าไม่มีค่า ให้ตั้งค่า group เป็น 1
            $newGroup = 1;
        }
        // dd('update');
        // สร้าง LabCalScopeTransaction สำหรับสำนักงานใหญ่
        $labTypes = $labMainAddress['lab_types'];
        foreach ($labTypes as $key => $labTypeValues) {
            if (is_array($labTypeValues)) {
                foreach ($labTypeValues as $labType) {
                    $transaction = new LabCalScopeTransaction();               
                    $transaction->branch_type = 1;
                    $transaction->site_type = $key;
                    $transaction->app_certi_lab_id = $certilabId;
                    $transaction->bcertify_calibration_branche_id = !empty($labType['cal_main_branch']) ? $labType['cal_main_branch'] : null;
                    $transaction->calibration_branch_instrument_group_id = !empty($labType['cal_instrumentgroup']) ? $labType['cal_instrumentgroup'] : null;
                    $transaction->calibration_branch_instrument_id = !empty($labType['cal_instrument']) ? $labType['cal_instrument'] : null;
                    $transaction->calibration_branch_parameter_one_id = !empty($labType['cal_parameter_one']) ? $labType['cal_parameter_one'] : null;
                    $transaction->parameter_one_value = !empty($labType['cal_parameter_one_value']) ? $labType['cal_parameter_one_value'] : null;
                    $transaction->calibration_branch_parameter_two_id = !empty($labType['cal_parameter_two']) ? $labType['cal_parameter_two'] : null;
                    $transaction->parameter_two_value = !empty($labType['cal_parameter_two_value']) ? $labType['cal_parameter_two_value'] : null;
                    $transaction->cal_method = !empty($labType['cal_method']) ? $labType['cal_method'] : null;
                    $transaction->group = $newGroup;
                    $transaction->save();
                }
            }
        }

        // สร้าง LabCalScopeTransaction สำหรับสาขา
        // BranchLabAdress::where('app_certi_lab_id',$certilabId)->delete();
        foreach ($labAddresses as $labAddress) {
            $branchAdress = BranchLabAdress::find($labAddress['branch_lab_adress_id']);    
            $labTypes = $labAddress['lab_types'];
            foreach ($labTypes as $key => $labTypeValues) {
                if (is_array($labTypeValues)) {
                    foreach ($labTypeValues as $labType) {
                        $transaction = new LabCalScopeTransaction();
                        $transaction->branch_type = 2;
                        $transaction->site_type = $key;
                        $transaction->app_certi_lab_id = $certilabId;
                        $transaction->branch_lab_adress_id = $branchAdress->id;
                        $transaction->bcertify_calibration_branche_id = !empty($labType['cal_main_branch']) ? $labType['cal_main_branch'] : null;
                        $transaction->calibration_branch_instrument_group_id = !empty($labType['cal_instrumentgroup']) ? $labType['cal_instrumentgroup'] : null;
                        $transaction->calibration_branch_instrument_id = !empty($labType['cal_instrument']) ? $labType['cal_instrument'] : null;
                        $transaction->calibration_branch_parameter_one_id = !empty($labType['cal_parameter_one']) ? $labType['cal_parameter_one'] : null;
                        $transaction->parameter_one_value = !empty($labType['cal_parameter_one_value']) ? $labType['cal_parameter_one_value'] : null;
                        $transaction->calibration_branch_parameter_two_id = !empty($labType['cal_parameter_two']) ? $labType['cal_parameter_two'] : null;
                        $transaction->parameter_two_value = !empty($labType['cal_parameter_two_value']) ? $labType['cal_parameter_two_value'] : null;
                        $transaction->cal_method = !empty($labType['cal_method']) ? $labType['cal_method'] : null;
                        $transaction->group = $newGroup;
                        $transaction->save();
                    }
                }
            }
        }

        // สร้าง LabCalScopeUsageStatus ใหม่
        $newUsageStatus = new LabCalScopeUsageStatus();
        $newUsageStatus->app_certi_lab_id = $certilabId;
        $newUsageStatus->group = $newGroup;
        $newUsageStatus->status = 2;
        $newUsageStatus->save();

        // สร้าง LabRequestType ใหม่
 
    }


    private function updateMainTransactions(array $labTypes, $certilabId)
    {
        foreach ($labTypes as $key => $labTypeValues) {
            if (is_array($labTypeValues)) {
                foreach ($labTypeValues as $labType) {
                    // ค้นหารายการในฐานข้อมูลที่ตรงกับเงื่อนไข
                    $existingTransaction = LabCalScopeTransaction::where('app_certi_lab_id', $certilabId)
                        ->where('branch_type', 1)
                        ->where('site_type', $key)
                        // ตรวจสอบ cal_main_branch
                        ->when($labType['cal_main_branch'] === "", function ($query) {
                            return $query->whereNull('bcertify_calibration_branche_id');
                        }, function ($query) use ($labType) {
                            return $query->where('bcertify_calibration_branche_id', $labType['cal_main_branch']);
                        })
                        // ตรวจสอบ cal_instrumentgroup
                        ->when($labType['cal_instrumentgroup'] === "", function ($query) {
                            return $query->whereNull('calibration_branch_instrument_group_id');
                        }, function ($query) use ($labType) {
                            return $query->where('calibration_branch_instrument_group_id', $labType['cal_instrumentgroup']);
                        })
                        // ตรวจสอบ cal_instrument
                        ->when($labType['cal_instrument'] === "", function ($query) {
                            return $query->whereNull('calibration_branch_instrument_id');
                        }, function ($query) use ($labType) {
                            return $query->where('calibration_branch_instrument_id', $labType['cal_instrument']);
                        })
                        // ตรวจสอบ cal_parameter_one
                        ->when($labType['cal_parameter_one'] === "", function ($query) {
                            return $query->whereNull('calibration_branch_parameter_one_id');
                        }, function ($query) use ($labType) {
                            return $query->where('calibration_branch_parameter_one_id', $labType['cal_parameter_one']);
                        })
                        // ตรวจสอบ cal_parameter_two
                        ->when($labType['cal_parameter_two'] === "", function ($query) {
                            return $query->whereNull('calibration_branch_parameter_two_id');
                        }, function ($query) use ($labType) {
                            return $query->where('calibration_branch_parameter_two_id', $labType['cal_parameter_two']);
                        })
                        ->first();
    
                    // ถ้าพบรายการที่ตรงกับเงื่อนไข ให้ทำการอัปเดต parameter_one_value, parameter_two_value, cal_method
                    if ($existingTransaction) {
                        $existingTransaction->parameter_one_value = $labType['cal_parameter_one_value'] ?? null;
                        $existingTransaction->parameter_two_value = $labType['cal_parameter_two_value'] ?? null;
                        $existingTransaction->cal_method = $labType['cal_method'] ?? null;
                        $existingTransaction->save();
                    }
                }
            }
        }
    }
    
    public function destroy(Cost $ec, CertiLab $app = null)
    {

        try {
            $cost = Cost::findOrFail($ec->id);
            if(!is_null($cost)){
                $cost->draft = 3;
                $cost->save();
                $app = CertiLab::where('id', $cost->app_certi_lab_id)->orderby('id','desc')->first();
                if(!is_null($app)){
                    $app->status = 9;
                    $app->save();
                }
            }
            // foreach ($ec->files as $file) {
            //     File::delete(storage_path($file->file));
            //     $file->delete();
            // }

            // $ec->dates()->delete();
            // $ec->items()->delete();
            // $ec->delete();
            return redirect('certify/estimated_cost')->with('flash_message', 'อัพเดทเรียบร้อยแล้ว');
        } catch (Exception $x) {
            return back();
        }
    }

    public function delete_file($id,$no = null)
    {
        $file = 'delete_not_file';
        try {
            $Cost = Cost::findOrFail($id);
            if(!is_null($Cost)){
                $attachs = array_values((array)json_decode($Cost->attachs));
                unset($attachs[$no-1]);
                $Cost->attachs = (count($attachs) >0) ? json_encode($attachs) : '';
                $Cost->save();
                $file = 'delete_file';
            }
        } catch (Exception $x) {
         $file = 'delete_not_file';
        }
      return  $file;

    }

    public function destroyMultiple(Request $request, CertiLab $app = null)
    {
        foreach ($request->cb as $id) {
            $ec = Cost::findOrFail($id);
            foreach ($ec->files as $file) {
                File::delete(storage_path($file->file));
                $file->delete();
            }

            $ec->dates()->delete();
            $ec->items()->delete();
            $ec->delete();
        }

        return redirect()->route('estimated_cost.index', ['app' => $app ? $app->id : ''])->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function apiGetApp($app_no = null) {
        $app = CertiLab::where('app_no', $app_no)->with('trader')->first();

        return response()->json([
            'app_no' => $app_no,
            'app' => $app
        ], 200);
    }


    public function set_mail($cost,$certi_lab) {


        if(!is_null($certi_lab->email)){

            $config = HP::getConfig();
            $url  =   !empty($config->url_acc) ? $config->url_acc : url('');
            $dataMail = ['1804'=> 'lab1@tisi.mail.go.th','1805'=> 'lab2@tisi.mail.go.th','1806'=> 'lab3@tisi.mail.go.th'];
            $EMail =  array_key_exists($certi_lab->subgroup,$dataMail)  ? $dataMail[$certi_lab->subgroup] :'admin@admin.com';


            $data_app = [
                        'certi_lab'     => $certi_lab,
                        'cost'          => $cost,
                        'url'           => $url.'/certify/applicant' ,
                        'email'         =>  !empty($certi_lab->DataEmailCertifyCenter) ? $certi_lab->DataEmailCertifyCenter : $EMail,
                        'email_cc'      =>  !empty($certi_lab->DataEmailDirectorLABCC) ? $certi_lab->DataEmailDirectorLABCC :  $EMail,
                        'email_reply'   => !empty($certi_lab->DataEmailDirectorLABReply) ? $certi_lab->DataEmailDirectorLABReply :  $EMail
                         ];
        
            $log_email =  HP::getInsertCertifyLogEmail( $certi_lab->app_no,
                                                        $certi_lab->id,
                                                        (new CertiLab)->getTable(),
                                                        $cost->id,
                                                        (new Cost)->getTable(),
                                                        1,
                                                        'การประมาณการค่าใช้จ่าย',
                                                        view('mail.Lab.cost', $data_app),
                                                        $certi_lab->created_by,
                                                        $certi_lab->agent_id,
                                                        auth()->user()->getKey(),
                                                        !empty($certi_lab->DataEmailCertifyCenter) ?  implode(',',(array)$certi_lab->DataEmailCertifyCenter)  : $EMail,
                                                        $certi_lab->email,
                                                        !empty($certi_lab->DataEmailDirectorLABCC) ? implode(',',(array)$certi_lab->DataEmailDirectorLABCC)   :  $EMail,
                                                        !empty($certi_lab->DataEmailDirectorLABReply) ?implode(',',(array)$certi_lab->DataEmailDirectorLABReply)   :  $EMail,
                                                        null
                                                        );

              $html = new  EstimatedCost($data_app);
              $mail = Mail::to($certi_lab->email)->send($html);

              if(is_null($mail) && !empty($log_email)){
                HP::getUpdateCertifyLogEmail($log_email->id);
              }

        }
     }

      public function CertificateHistory($data)
      {
          $ao = new Cost;
          $Cost = Cost::select('app_certi_assessment_id', 'app_certi_lab_id', 'checker_id', 'draft', 'remark', 'agree', 'status_scope','check_status','remark_scope','amount','date','created_at')
                        ->where('id',$data->id)
                        ->first();

          $CostItem = CostItem::select('app_certi_cost_id','desc','amount_date','amount')
                                ->where('app_certi_cost_id',$data->id)
                                ->get()
                                ->toArray();
          CertificateHistory::create([
                                      'app_no'=> $data->applicant->app_no ?? null,
                                      'system'=>1,
                                      'table_name'=> $ao->getTable(),
                                      'ref_id'=> $data->id,
                                      'details'=>  json_encode($Cost) ?? null,
                                      'details_table'=>  (count($CostItem) > 0) ? json_encode($CostItem) : null,
                                      'attachs'=> $data->attachs ?? null,
                                      'created_by' =>  auth()->user()->runrecno
                                    ]);
      }

    public function GetDataAppNo($app_no = null) {
        $app = CertiLab::where('app_no', $app_no)->first();
        return response()->json([ 'app' => $app->BelongsInformation->name ?? ''  ], 200);
    }
}
