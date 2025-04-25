<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Models\Bcertify\TestBranch;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\CalibrationBranch;

class SettingScopeLabTestController extends Controller
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
        $testBranchs = TestBranch::paginate($filter['perPage']);

        // dd($testBranchs);

        $model = str_slug('bcertify_scope_lab_test','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bcertify/setting_scope_lab_test.index',[
                'testBranchs' => $testBranchs
            ]);
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
        if(auth()->user()->can('add-'.$this->model)) {

            return view('bcertify.setting_scope_lab_test.create');
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
        
        if(auth()->user()->can('add-'.$this->model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();


            TestBranch::create($requestData);
            return redirect('bcertify/setting_scope_lab_test')->with('flash_message', 'เพิ่ม สาขาการรับรอง เรียบร้อยแล้ว');
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
        if(auth()->user()->can('view-'.$this->model)) {

            $testBranch = TestBranch::findOrFail($id);
            

            return view('bcertify.setting_scope_lab_test.show',[
                'testBranch' => $testBranch
            ]);
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
        if(auth()->user()->can('edit-'.$this->model)) {
            $testBranch = TestBranch::findOrFail($id);
            return view('bcertify.setting_scope_lab_test.edit',[
                'testBranch' => $testBranch
            ]);
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
        if(auth()->user()->can('edit-'.$this->model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required'
        		]);

            $testBranch = TestBranch::findOrFail($id);

            $requestData = $request->all();

            // dd($requestData);

            $testBranch->update($requestData);

            return redirect('bcertify/setting_scope_lab_test')->with('flash_message', 'แก้ไข สาขาการรับรอง เรียบร้อยแล้ว!');
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
        //
    }

    
    public function update_state(Request $request)
    {
        // dd('ok');
        $model = str_slug('bcertify_scope_lab_test','-');
        if(auth()->user()->can('edit-'.$model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new TestBranch;
            TestBranch::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
  
          return redirect('bcertify/setting_scope_lab_test')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
  
        abort(403);
  
    }
}
