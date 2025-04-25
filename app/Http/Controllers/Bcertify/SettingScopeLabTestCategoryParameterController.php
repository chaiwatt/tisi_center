<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\TestBranchParam;
use App\Models\Bcertify\TestBranchCategory;

class SettingScopeLabTestCategoryParameterController extends Controller
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
       
        $testBranchParams = TestBranchParam::where('test_branch_category_id',$id)
        ->where('name','LIKE','%'.$filter['filter_search'].'%')
            ->paginate($filter['perPage']);

        $testBranchCategory = TestBranchCategory::find($id);
        // dd($testBranchCategory);
        if(auth()->user()->can('view-'.$this->model)) {
            return view('bcertify.setting_scope_lab_test.category.parameter.index',[
                'testBranchParams' => $testBranchParams,
                'testBranchCategory' => $testBranchCategory,
                'filter' => $filter
            ]);
        }
        abort(403);
    }

    public function create($id)
    {
 
        if(auth()->user()->can('add-'.$this->model)) {
             $testBranchCategory = TestBranchCategory::find($id);
            return view('bcertify.setting_scope_lab_test.category.parameter.create',[
                'testBranchCategory' => $testBranchCategory,
            ]);
        }

        abort(403);
    }

    public function store(Request $request,$id)
    {
        
        if(auth()->user()->can('add-'.$this->model)) {
            $this->validate($request, [
        			'name' => 'required',
                    'name_eng' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['test_branch_category_id'] = $id;

            
            TestBranchParam::create($requestData);
            return redirect('bcertify/setting_scope_lab_test/category/parameter/'.$id)->with('flash_message', 'เพิ่ม พารามิเตอร์ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $testBranchParam = TestBranchParam::findOrFail($id);

            return view('bcertify.setting_scope_lab_test.category.parameter.edit',[
                'testBranchParam' => $testBranchParam
            ]);
        }
        abort(403);
    }

    public function update(Request $request, $id)
    {
        
        if(auth()->user()->can('edit-'.$this->model)) {
            $this->validate($request, [
        			'name' => 'required',
                    'name_eng' => 'required',
        		]);

            $testBranchParam = TestBranchParam::findOrFail($id);

            $requestData = $request->all();
            
            $testBranchParam->update($requestData);
            return redirect('bcertify/setting_scope_lab_test/category/parameter/'.$testBranchParam->testBranchCategory->id)->with('flash_message', 'แก้ไข พารามิเตอร์ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->model)) {

            $testBranchParam = TestBranchParam::findOrFail($id);
  

            return view('bcertify.setting_scope_lab_test.category.parameter.show',[
                'testBranchParam' => $testBranchParam
            ]);
        }
        abort(403);
    }

    public function update_state(Request $request,$id){

        if(auth()->user()->can('edit-'.$this->model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new TestBranchParam;
            TestBranchParam::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }

          return redirect('bcertify/setting_scope_lab_test/category/parameter/'.$id)->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
  
        abort(403);
  
      }
}
