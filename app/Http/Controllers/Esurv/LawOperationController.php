<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Csurv\ControlCheck;
use App\Models\Csurv\ControlPerformance;
use App\Models\Esurv\LawOperation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use HP;
use SHP;
use Illuminate\Http\Request;

class LawOperationController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        
        $this->attach_path = 'esurv_attach/lawoperation/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);
            $operation = 'ส่งให้กองกฏหมายดำเนินการ';
            $conclude_result = 'ไม่เป็นไปตามข้อกำหนด ส่งเรื่องให้ กม. ดำเนินการ';

            $Query = new LawOperation;
            $ControlCheck = ControlCheck::where('operation','=',$operation)->select('auto_id_doc','tbl_tisiNo','tradeName','id');
            $ControlPerformance = ControlPerformance::where('conclude_result','=',$conclude_result)->select('auto_id_doc','tbl_tisiNo','tradeName','id');

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $lawoperation = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('esurv.law-operation.index', compact('lawoperation', 'filter','ControlCheck'));
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('add-'.$model)) {

            $operation = 'ส่งให้กองกฏหมายดำเนินการ';
            $conclude_result = 'ไม่เป็นไปตามข้อกำหนด ส่งเรื่องให้ กม. ดำเนินการ';
            $refers = [''];
            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            $control_check_list = ControlCheck::select(DB::raw("CONCAT(control_check.auto_id_doc,'(',control_check.tbl_tisiNo,' ',control_check.tradeName,')') AS title"), 'control_check.id')
            ->whereIn('control_check.operation', [$operation])
            ->get()->pluck('title', 'id');

            $control_performance_list = ControlPerformance::select(DB::raw("CONCAT(control_performance.auto_id_doc,'(',control_performance.tbl_tisiNo,' ',control_performance.tradeName,')') AS title"), 'control_performance.id')
            ->whereIn('control_performance.conclude_result', [$conclude_result])
            ->get()->pluck('title', 'id');

            return view('esurv.law-operation.create',compact('attachs','attach_path','refers','control_check_list','control_performance_list'));
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
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            dd($requestData);
            LawOperation::create($requestData);
            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

              foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $storageName = basename($storagePath); // Extract the filename

                $attachs[] = ['file_name'=>$storageName,
                              'file_client_name'=>$file->getClientOriginalName(),
                              'file_note'=>$requestData['attach_notes'][$key]
                             ];
              }

            }
            return redirect('esurv/law-operation')->with('flash_message', 'เพิ่ม LawOperation เรียบร้อยแล้ว');
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('view-'.$model)) {
            $lawoperation = LawOperation::findOrFail($id);
            return view('esurv.law-operation.show', compact('lawoperation'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('edit-'.$model)) {
            $lawoperation = LawOperation::findOrFail($id);
            return view('esurv.law-operation.edit', compact('lawoperation'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            
            $lawoperation = LawOperation::findOrFail($id);
            $lawoperation->update($requestData);

            return redirect('esurv/law-operation')->with('flash_message', 'แก้ไข LawOperation เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('lawoperation','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new LawOperation;
            LawOperation::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            LawOperation::destroy($id);
          }

          return redirect('esurv/law-operation')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('lawoperation','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new LawOperation;
          LawOperation::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('esurv/law-operation')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function getControlCheck($control_check_id){
        $control_check = ControlCheck::select('id','auto_id_doc','tradeName','tbl_tisiNo','checking_date','checking_time',
        'officer_name','latitude','Longitude','address_no','address_village_no','address_industrial_estate','address_road',
        'address_alley','address_province','address_amphoe','address_district','address_zip_code','tel','fax')
        ->where('id',$control_check_id)->first();
        $control_check->id_doc = $control_check->auto_id_doc; 
        $control_check->trade_name = HP::get_tb4_name_index($control_check->tradeName);
        $control_check->tbl_tisiNo = $control_check->tbl_tisiNo; 
        $control_check->checking_date = $control_check->checking_date;
        $control_check->checking_time = $control_check->checking_time;
        $control_check->officer_name = $control_check->officer_name;
        $control_check->latitude = $control_check->latitude;
        $control_check->Longitude = $control_check->Longitude;
        $control_check->address_no = $control_check->address_no;
        $control_check->address_village_no = $control_check->address_village_no;
        $control_check->address_industrial_estate = $control_check->address_industrial_estate;
        $control_check->address_road = $control_check->address_road;
        $control_check->address_alley = $control_check->address_alley;
        $control_check->address_amphoe = $control_check->AmphurName;
        $control_check->address_district = $control_check->DistrictName;
        $control_check->address_province = $control_check->ProvinceName;
        $control_check->address_zip_code = $control_check->address_zip_code;
        $control_check->tel = $control_check->tel;
        $control_check->fax = $control_check->fax;

        return  $control_check;
    }

    public function getControlPerformance($control_performance_id){
      $control_performance = ControlPerformance::select('id','auto_id_doc','tradeName','tbl_tisiNo','factory_name','address_no',
      'address_industrial_estate','address_road','address_village_no','address_alley','address_province','address_amphoe','address_district',
      'address_zip_code','tel','fax','latitude','Longitude','checking_date')
      ->where('id',$control_performance_id)->first();
      $control_performance->id_doc = $control_performance->auto_id_doc; 
      $control_performance->trade_name = HP::get_tb4_name_index($control_performance->tradeName);
      $control_performance->factory_name = $control_performance->factory_name; 
      $control_performance->address_no = $control_performance->address_no; 
      $control_performance->address_alley = $control_performance->address_alley; 
      $control_performance->address_village_no = $control_performance->address_village_no; 
      $control_performance->address_road = $control_performance->address_road; 
      $control_performance->address_industrial_estate = $control_performance->address_industrial_estate; 
      $control_performance->address_amphoe = $control_performance->AmphurName;
      $control_performance->address_district = $control_performance->DistrictName;
      $control_performance->address_province = $control_performance->ProvinceName;
      $control_performance->address_zip_code = $control_performance->address_zip_code;
      $control_performance->tel = $control_performance->tel;
      $control_performance->fax = $control_performance->fax;
      $control_performance->latitude = $control_performance->latitude;
      $control_performance->Longitude = $control_performance->Longitude;
      $control_performance->checking_date = $control_performance->checking_date;
      return  $control_performance;
  }

}
