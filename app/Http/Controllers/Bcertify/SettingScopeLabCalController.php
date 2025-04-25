<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\CalibrationBranch;

class SettingScopeLabCalController extends Controller
{
    protected $model;

    public function __construct()
    {
        // ตั้งค่าค่าเริ่มต้นของ model ให้เป็น slug ของ 'bcertify_scope_lab_cal'
        $this->model = str_slug('bcertify_scope_lab_cal', '-');
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 15);
        $calibrationBranchs = CalibrationBranch::paginate($filter['perPage']);

        if(auth()->user()->can('view-'.$this->model)) {
            return view('bcertify.setting_scope_lab_cal.index',[
                'calibrationBranchs' => $calibrationBranchs
            ]);
        }
        abort(403);
    }

    public function create()
    {
        if(auth()->user()->can('add-'.$this->model)) {

            return view('bcertify.setting_scope_lab_cal.create');
        }

        abort(403);
    }

    public function store(Request $request)
    {
        if(auth()->user()->can('add-'.$this->model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();


            CalibrationBranch::create($requestData);
            return redirect('bcertify/setting_scope_lab_cal')->with('flash_message', 'เพิ่ม สาขาการรับรอง เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->model)) {

            $calibrationBranch = CalibrationBranch::findOrFail($id);
            

            return view('bcertify.setting_scope_lab_cal.show',[
                'calibrationBranch' => $calibrationBranch
            ]);
        }
        abort(403);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $calibrationBranch = CalibrationBranch::findOrFail($id);
            return view('bcertify.setting_scope_lab_cal.edit',[
                'calibrationBranch' => $calibrationBranch
            ]);
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $calibrationBranch = CalibrationBranch::findOrFail($id);

            $requestData = $request->all();

            // dd($requestData);

            $calibrationBranch->update($requestData);

            return redirect('bcertify/setting_scope_lab_cal')->with('flash_message', 'แก้ไข สาขาการรับรอง เรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function destroy($id)
    {
        //
    }

    public function update_state(Request $request)
    {
        $model = str_slug('bcertify_scope_lab_cal','-');
        if(auth()->user()->can('edit-'.$model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new CalibrationBranch;
            CalibrationBranch::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
  
          return redirect('bcertify/setting_scope_lab_cal')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
  
        abort(403);
  
    }
}
