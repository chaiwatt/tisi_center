<?php


namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Config\ConfigsEvidenceSystem;

class ConfigsEvidenceSystemController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function data_list(Request $request)
    {

        $model = str_slug('configs-evidence-systems','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        
        $query = ConfigsEvidenceSystem::query()->when($filter_search, function ($query, $filter_search){
                                                        $search_full = str_replace(' ', '', $filter_search );
                                                        $query->where('title',  'LIKE', "%$search_full%");
                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        if( $filter_status == 1){
                                                            return $query->where('state', $filter_status);
                                                        }else{
                                                            return $query->where('state', '<>', 1)->orWhereNull('state');
                                                        }
                                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('created_at', function ($item) {
                                return (!empty($item->CreatedName)?$item->CreatedName:null).(!empty($item->created_at)?'<br>'.HP::DateThaiFull($item->created_at):null);
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'config/evidence/system','Config\\ConfigsEvidenceSystemController@destroy', 'configs-evidence-systems');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_at'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-evidence-system.index');
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('config/configs-evidence-system.create');
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            ConfigsEvidenceSystem::create($requestData);

            return redirect('config/evidence/system')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('view-'.$model)) {

            $result = ConfigsEvidenceSystem::findOrFail($id);

            return view('config/configs-evidence-system.show',compact('result'));
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('edit-'.$model)) {

            $result = ConfigsEvidenceSystem::findOrFail($id);

            return view('config/configs-evidence-system.edit',compact('result'));
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $result = ConfigsEvidenceSystem::findOrFail($id);
            $result->update($requestData);

            return redirect('config/evidence/system')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
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
        $model = str_slug('configs-evidence-systems','-');
        if(auth()->user()->can('delete-'.$model)) {
            ConfigsEvidenceSystem::destroy($id);
            return redirect('config/evidence/system')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result = ConfigsEvidenceSystem::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }

    }

    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');

        $result = ConfigsEvidenceSystem::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request)
    {
        $id_status = $request->input('id_status');
        $state = $request->input('state');
        $result = ConfigsEvidenceSystem::where('id', $id_status)  ->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

}
