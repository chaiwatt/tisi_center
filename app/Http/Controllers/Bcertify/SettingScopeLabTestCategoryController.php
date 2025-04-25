<?php

namespace App\Http\Controllers\Bcertify;

use Illuminate\Http\Request;
use App\Models\Bcertify\TestBranch;
use App\Http\Controllers\Controller;
use App\Models\Bcertify\TestBranchCategory;

class SettingScopeLabTestCategoryController extends Controller
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

        

        $testBranchCategories = TestBranchCategory::where('bcertify_test_branche_id',$id)
            ->where('name','LIKE','%'.$filter['filter_search'].'%')
            ->paginate($filter['perPage']);
        $testBranch = TestBranch::find($id);
        
        if(auth()->user()->can('view-'.$this->model)) {
            return view('bcertify.setting_scope_lab_test.category.index',[
                'testBranchCategories' => $testBranchCategories,
                'testBranch' => $testBranch,
                'filter' => $filter,
            ]);
        }
        abort(403);
    }
    public function create($id)
    {
       
        if(auth()->user()->can('add-'.$this->model)) {
             $testBranch = TestBranch::find($id);
            return view('bcertify.setting_scope_lab_test.category.create',[
                'testBranch' => $testBranch,
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
            $requestData['bcertify_test_branche_id'] = $id;

            
            TestBranchCategory::create($requestData);
            return redirect('bcertify/setting_scope_lab_test/category/'.$id)->with('flash_message', 'เพิ่ม หมวดหมู่ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function show($id)
    {
        if(auth()->user()->can('view-'.$this->model)) {

            $testBranchCategory = TestBranchCategory::findOrFail($id);
            

            return view('bcertify.setting_scope_lab_test.category.show',[
                'testBranchCategory' => $testBranchCategory
            ]);
        }
        abort(403);
    }

    public function edit($id)
    {
        if(auth()->user()->can('edit-'.$this->model)) {
            $testBranchCategory = TestBranchCategory::findOrFail($id);
            return view('bcertify.setting_scope_lab_test.category.edit',[
                'testBranchCategory' => $testBranchCategory
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

            $testBranchCategory = TestBranchCategory::findOrFail($id);

            $requestData = $request->all();


            $testBranchCategory->update($requestData);

            return redirect('bcertify/setting_scope_lab_test/category/'.$testBranchCategory->testBranch->id)->with('flash_message', 'เพิ่ม หมวดหมู่ เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function update_state(Request $request,$id){

        if(auth()->user()->can('edit-'.$this->model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('item-selection', $requestData)){
            $ids = $requestData['item-selection'];
            $db = new TestBranchCategory;
            TestBranchCategory::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }

          return redirect('bcertify/setting_scope_lab_test/category/'.$id)->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
  
        abort(403);
  
      }
}
