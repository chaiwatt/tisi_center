<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\CalibrationBranch;
use App\Models\Bcertify\CalibrationBranchInstrumentGroup;

class SettingScopeLabCalInstrumentGroupController extends Controller
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
        

        $calibrationBranchInstrumentGroups = CalibrationBranchInstrumentGroup::where('bcertify_calibration_branche_id',$id)
            ->where('name','LIKE','%'.$filter['filter_search'].'%')
            ->paginate($filter['perPage']);
        $calibrationBranch = CalibrationBranch::find($id);
        
        if(auth()->user()->can('view-'.$this->model)) {
            // dd($calibrationBranch);
            return view('bcertify.setting_scope_lab_cal.instrument-group.index',[
                'calibrationBranchInstrumentGroups' => $calibrationBranchInstrumentGroups,
                'calibrationBranch' => $calibrationBranch,
                'filter' => $filter,
            ]);
        }
        abort(403);
    }

    public function create($id)
    {
       
        if(auth()->user()->can('add-'.$this->model)) {
             $calibrationBranch = CalibrationBranch::find($id);
            return view('bcertify.setting_scope_lab_cal.instrument-group.create',[
                'calibrationBranch' => $calibrationBranch,
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
            $requestData['bcertify_calibration_branche_id'] = $id;

            
            CalibrationBranchInstrumentGroup::create($requestData);
            return redirect('bcertify/setting_scope_lab_cal/instrument-group/'.$id)->with('flash_message', 'เพิ่ม เครื่องมือ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->model)) {

            $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::findOrFail($id);
            

            return view('bcertify.setting_scope_lab_cal.instrument-group.show',[
                'calibrationBranchInstrumentGroup' => $calibrationBranchInstrumentGroup
            ]);
        }
        abort(403);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::findOrFail($id);
            return view('bcertify.setting_scope_lab_cal.instrument-group.edit',[
                'calibrationBranchInstrumentGroup' => $calibrationBranchInstrumentGroup
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

            $calibrationBranchInstrumentGroup = CalibrationBranchInstrumentGroup::findOrFail($id);

            $requestData = $request->all();


            $calibrationBranchInstrumentGroup->update($requestData);

            return redirect('bcertify/setting_scope_lab_cal/instrument-group/'.$calibrationBranchInstrumentGroup->calibrationBranch->id)->with('flash_message', 'เพิ่ม เครื่องมือ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function update_state(Request $request,$id){

        if(auth()->user()->can('edit-'.$this->model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new CalibrationBranchInstrumentGroup;
            CalibrationBranchInstrumentGroup::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }

          return redirect('bcertify/setting_scope_lab_cal/instrument-group/'.$id)->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
  
        abort(403);
  
      }

}
