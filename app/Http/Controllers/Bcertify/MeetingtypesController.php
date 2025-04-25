<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\Meetingtype;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use HP;
use DB;

class MeetingtypesController extends Controller
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

        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('bcertify/meetingtypes.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $model = str_slug('meetingtypes', '-');
        $filter_search = $request->input('filter_search');
        $query = Meetingtype::query()->when($filter_search, function ($query, $filter_search){
                                                        $search_full = str_replace(' ', '', $filter_search );
                                                            $query->where(function ($query2) use($search_full) {
                                                                        $query2->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return   !empty($item->title)? $item->title:'';
                            })
                            ->addColumn('created_name', function ($item) {
                                return   !empty($item->CreatedName)? $item->CreatedName:'';
                            })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->created_at)? HP::DateThai($item->created_at):'';
                            })
                            ->addColumn('state', function ($item) {
                                return  $item->stateIcon;
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn .=  ' <a href="'. url('bcertify/meetingtypes/'.$item->id) .'" class="btn btn-info btn-xs">   <i class="fa fa-eye" aria-hidden="true"></i> </a>';
                                }

                                if(auth()->user()->getKey()==$item->created_by ||  auth()->user()->can('edit-'.$model) ){
                                    $btn .=  ' <a href="'. url('bcertify/meetingtypes/'.$item->id. '/edit') .'" class="btn btn-primary btn-xs">     <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> </a>';
                                }


                                if(auth()->user()->getKey()==$item->created_by ||  auth()->user()->can('delete-'.$model) ){
                                    $btn .=  ' <a href="'. url('bcertify/meetingtypes/destroy/'.$item->id) .'"   title="ลบ" class="btn  btn-danger  btn-xs" onclick="return confirm_delete()">  <i class="fa fa-trash-o" aria-hidden="true"></i> </a>';
                                }


                                return $btn;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['operation_result_name','state', 'checkbox', 'action'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('bcertify.meetingtypes.create');
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
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();
            $requestData['state'] =   !empty($request->state) ? 1 : 0;
            $requestData['created_by'] =  auth()->user()->getKey();

            Meetingtype::create($requestData);
            return redirect('bcertify/meetingtypes')->with('flash_message', 'เพิ่ม meetingtype เรียบร้อยแล้ว');
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
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('view-'.$model)) {
            $meetingtype = Meetingtype::findOrFail($id);
            return view('bcertify.meetingtypes.show', compact('meetingtype'));
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
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('edit-'.$model)) {
            $meetingtype = Meetingtype::findOrFail($id);
            return view('bcertify.meetingtypes.edit', compact('meetingtype'));
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
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();
            $requestData['state'] =   !empty($request->state) ? 1 : 0;
            $requestData['updated_by'] =  auth()->user()->getKey();

            $meetingtype = Meetingtype::findOrFail($id);
            $meetingtype->update($requestData);

            return redirect('bcertify/meetingtypes')->with('flash_message', 'แก้ไข meetingtype เรียบร้อยแล้ว!');
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
    public function destroy($id)
    {
        $model = str_slug('meetingtypes','-');
        if(auth()->user()->can('delete-'.$model)) {
            Meetingtype::destroy($id);
            return redirect('bcertify/meetingtypes')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $student = Meetingtype::whereIn('id', $id_array);
        if($student->delete())
        {
            echo 'Data Deleted';
        }

    }


    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = str_slug('meetingtypes', '-');
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $state = $request->input('state');
            $result = Meetingtype::where('id', $id)->update(['state' => $state]);

            if($result) {
                return 'success';
            } else {
                return "not success";
            }

        }else{
            abort(403);
        }

    }


    //เลือกเผยแพร่หรือไม่เผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');

        $result = Meetingtype::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

}
