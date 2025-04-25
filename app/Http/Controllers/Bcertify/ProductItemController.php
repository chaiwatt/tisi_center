<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\ProductItem as product_item;
use App\Models\Bcertify\TestBranch;
use App\Models\Bcertify\ProductCategory;
use Illuminate\Http\Request;

class ProductItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('product_item','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_formula'] = $request->get('filter_formula', '');
            $filter['filter_test_branch'] = $request->get('filter_test_branch', '');
            $filter['filter_product_category'] = $request->get('filter_product_category', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new product_item;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_formula']!='') {
                $Query = $Query->where('formula_id', $filter['filter_formula']);
            }

            if ($filter['filter_test_branch']!='') {
                $Query = $Query->where('test_branch_id', $filter['filter_test_branch']);
            }

            if ($filter['filter_product_category']!='') {
                $Query = $Query->where('product_category_id', $filter['filter_product_category']);
            }

            $product_item = $Query->sortable()->with('user_created')
                                              ->with('user_updated')
                                              ->paginate($filter['perPage']);

            return view('bcertify.product_item.index', compact('product_item', 'filter'));
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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('add-'.$model)) {

            $test_branchs = [];
            $product_categorys = [];

            return view('bcertify.product_item.create', compact('test_branchs', 'product_categorys'));

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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required',
        			'formula_id' => 'required',
        			'test_branch_id' => 'required',
        			'product_category_id' => 'required'
        		]);

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            product_item::create($requestData);
            return redirect('bcertify/product_item')->with('flash_message', 'เพิ่ม product_item เรียบร้อยแล้ว');
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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('view-'.$model)) {

            $product_item = product_item::findOrFail($id);

            return view('bcertify.product_item.show', compact('product_item'));

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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('edit-'.$model)) {

            $product_item = product_item::findOrFail($id);

            $test_branchs = TestBranch::where('formula_id', $product_item->formula_id)->pluck('title', 'id');

            $product_categorys = ProductCategory::where('test_branch_id', $product_item->test_branch_id)->pluck('title', 'id');

            return view('bcertify.product_item.edit', compact('product_item', 'test_branchs', 'product_categorys'));

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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required',
        			'formula_id' => 'required',
        			'test_branch_id' => 'required',
        			'product_category_id' => 'required'
        		]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $product_item = product_item::findOrFail($id);
            $product_item->update($requestData);

            return redirect('bcertify/product_item')->with('flash_message', 'แก้ไข product_item เรียบร้อยแล้ว!');
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
        $model = str_slug('product_item','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new product_item;
            product_item::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            product_item::destroy($id);
          }

          return redirect('bcertify/product_item')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('product_item','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new product_item;
          product_item::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/product_item')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
