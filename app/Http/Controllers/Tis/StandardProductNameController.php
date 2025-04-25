<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\Standard as standard;
use App\Models\Tis\Appoint;

use Illuminate\Http\Request;

use Carbon\Carbon;
use HP;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class StandardProductNameController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/standard/';
    }

    public function data_list(Request $request)
    {
        
        $filter_state = $request->get('filter_state') == "0"? 99 :$request->get('filter_state');
        $filter_search = $request->get('filter_search');
        $filter_standard_type = $request->get('filter_standard_type');


        $query = Standard::query()->when($filter_search, function ($query, $filter_search){
                         return $query ->where(function ($query) use ($filter_search) {
                                            $query->where('title', 'LIKE', "%{$filter_search}%")
                                                ->orWhere('title_en', 'LIKE', "%{$filter_search}%")
                                                ->orWhereRaw("CONCAT_WS('-',tis_no,tis_year) LIKE '%{$filter_search}%'")
                                                ->orWhereRaw("CONCAT(tis_no,' เล่ม ',tis_book,'-',tis_year) LIKE '%{$filter_search}%'")
                                                //   ->orWhere('tis_year', 'LIKE', "%{$filter['filter_search']}%")
                                                ->orWhere('tis_book', 'LIKE', "%{$filter_search}%");
                                        });
                        })
                        ->when($filter_state, function ($query, $filter_state){
                            if( $filter_state == 1){
                                return $query->where('state', $filter_state);
                            }else{
                                return $query->where('state', '<>', 1)->orWhereNull('state');
                            }
                        })
                        ->when($filter_standard_type, function ($query, $filter_standard_type){
                            return $query->where('standard_type_id', $filter_standard_type);
                        });

        return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('tis_no', function ($item) {
                    return $item->tis_no.(!empty($item->tis_book) ? ' เล่ม '.$item->tis_book : '').'-'.$item->tis_year ;
                })
                ->addColumn('tis_name', function ($item) {
                    return '<small>'.$item->title.'<small>' ;
                })
                ->addColumn('tis_name_en', function ($item) {
                    return '<small>'.$item->title_en.'<small>' ;
                })
                ->addColumn('standard_type', function ($item) {
                    return '<small>'.$item->StandardTypeName.'<small>' ;
                })
                ->addColumn('state', function ($item) {
                    return  $item->state=='1'?'ใช้งาน':'ยกเลิก' ;
                })
                ->addColumn('tis_product_name', function ($item) {
                    $model = str_slug('standardproduct-name','-');
                    if(auth()->user()->can('edit-'.$model)) {
                        $text = '';
                        $text .= '<div class="form-group row">';
                        $text .= '<div class="col-md-12">';
                        $text .= '<textarea type="text" name="description[]" data-id="'.$item->id.'" class="form-control description border_white" rows="2" style="overflow:hidden;">'.$item->tis_product_name.'</textarea>';
                        $text .= '</div>';
                        $text .= '</div>';
                        return $text;
                    }else {
                        return $item->tis_product_name;
                    }
                })
                ->rawColumns(['checkbox', 'action','tis_name','tis_name_en', 'standard_type', 'tis_product_name'])
                ->make(true);

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('tis/standard-product-name.index');
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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('add-'.$model)) {

            // return view('tis.standard-product-name.create');

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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('add-'.$model)) {
            return redirect('tis/product_name')->with('flash_message', 'เพิ่ม standard เรียบร้อยแล้ว');
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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('view-'.$model)) {
            // return view('tis.standard-product-name.show');
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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('edit-'.$model)) {


            // return view('tis.standard-product-name.edit');
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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('edit-'.$model)) {
            return redirect('tis/product_name')->with('flash_message', 'แก้ไข standard เรียบร้อยแล้ว!');
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
        $model = str_slug('standardproduct-name','-');
        if(auth()->user()->can('delete-'.$model)) {
            return redirect('tis/product_name')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    
    public function update_description(Request $request)
    {

        $model = str_slug('standardproduct-name','-');
      
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $description = $request->input('description');
            $result = Standard::where('id', $id)->update(['tis_product_name' => $description]);

            if($result) {
                return 'success';
            } else {
                return "not success";
            }
        }else{
            abort(403);
        }
    }

}
