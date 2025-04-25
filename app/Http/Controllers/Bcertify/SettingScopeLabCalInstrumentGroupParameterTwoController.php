<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\CalibrationBranchParam2;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class SettingScopeLabCalInstrumentGroupParameterTwoController extends Controller
{
    protected $model;

    public function __construct()
    {
        // ตั้งค่าค่าเริ่มต้นของ model ให้เป็น slug ของ 'bcertify_scope_lab_cal'
        $this->model = str_slug('bcertify_scope_lab_cal', '-');
    }

    public function index(Request $request, $id)
    {
        
        $filter = [];
        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['perPage'] = $request->get('perPage', 15);
       
        $calibrationBranchParam2s = CalibrationBranchParam2::where('calibration_branch_instrument_group_id',$id)
        ->where('name','LIKE','%'.$filter['filter_search'].'%')
            ->paginate($filter['perPage']);

        $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::find($id);

        if(auth()->user()->can('view-'.$this->model)) {
            return view('bcertify.setting_scope_lab_cal.instrument-group.parameter-two.index',[
                'calibrationBranchParam2s' => $calibrationBranchParam2s,
                'calibrationBranchInstrumentGroup' => $calibrationBranchInstrumentGroup,
                'filter' => $filter
            ]);
        }
        abort(403);
    }

    public function create($id)
    {
 
        if(auth()->user()->can('add-'.$this->model)) {
             $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::find($id);
            return view('bcertify.setting_scope_lab_cal.instrument-group.parameter-two.create',[
                'calibrationBranchInstrumentGroup' => $calibrationBranchInstrumentGroup,
            ]);
        }

        abort(403);
    }

    
    public function store(Request $request,$id)
    {
        
        if(auth()->user()->can('add-'.$this->model)) {
            $this->validate($request, [
        			'name' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['calibration_branch_instrument_group_id'] = $id;

            
            CalibrationBranchParam2::create($requestData);
            return redirect('bcertify/setting_scope_lab_cal/instrument-group/parameter-two/'.$id)->with('flash_message', 'เพิ่ม พารามิเตอร์1 เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $calibrationBranchParam2 = CalibrationBranchParam2::findOrFail($id);

            return view('bcertify.setting_scope_lab_cal.instrument-group.parameter-two.edit',[
                'calibrationBranchParam2' => $calibrationBranchParam2
            ]);
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {

        if(auth()->user()->can('edit-'.$this->model)) {
            $this->validate($request, [
        			'name' => 'required',
        		]);

            $calibrationBranchParam2 = CalibrationBranchParam2::findOrFail($id);

            $requestData = $request->all();
            
            $calibrationBranchParam2->update($requestData);
            return redirect('bcertify/setting_scope_lab_cal/instrument-group/parameter-two/'.$calibrationBranchParam2->calibrationBranchInstrumentGroup->id)->with('flash_message', 'แก้ไข เครื่องมือ2 เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->model)) {

            $calibrationBranchParam2 = CalibrationBranchParam2::findOrFail($id);
  

            return view('bcertify.setting_scope_lab_cal.instrument-group.parameter-two.show',[
                'calibrationBranchParam2' => $calibrationBranchParam2
            ]);
        }
        abort(403);
    }

    public function update_state(Request $request,$id){

        if(auth()->user()->can('edit-'.$this->model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new CalibrationBranchParam2;
            CalibrationBranchParam2::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }

          return redirect('bcertify/setting_scope_lab_cal/instrument-group/parameter-two/'.$id)->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
  
        abort(403);
  
      }

}
