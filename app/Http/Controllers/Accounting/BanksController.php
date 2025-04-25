<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Accounting\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Yajra\Datatables\Datatables;
use HP;

class BanksController extends Controller
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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('accounting.banks.index');
        }
        abort(403);

    }

    /* Data List Display For Datatables*/
    public function data_list(Request $request){

        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');
        $filter_date_start = !empty($request->get('filter_date_start'))?HP::convertDate($request->get('filter_date_start'),true):null;
        $filter_date_end = !empty($request->get('filter_date_end'))?HP::convertDate($request->get('filter_date_end'),true):null;

        $query = Bank::query()->when($filter_search, function ($query, $filter_search){
                                    return $query->where('title',  'LIKE', "%$filter_search%")->orWhere('title_en',  'LIKE', "%$filter_search%")->orWhere('title_short',  'LIKE', "%$filter_search%");
                                })
                                ->when($filter_state, function ($query, $filter_state){
                                    if( $filter_state == 1){
                                        return $query->where('state', $filter_state);
                                    }else{
                                        return $query->where('state', '<>', 1)->orWhereNull('state');
                                    }
                                })
                                ->when($filter_date_start, function ($query, $filter_date_start) use($filter_date_end){

                                    if(!is_null($filter_date_start) && !is_null($filter_date_end) ){

                                    return $query->whereBetween('created_at',[$filter_date_start,$filter_date_end]);

                                    }else if(!is_null($filter_date_start) && is_null($filter_date_end)){

                                    return $query->whereDate('created_at',$filter_date_start);

                                    }
                                });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'.$item->id.'">';
                            })
                            ->addColumn('image', function ($item) {
                                // if (!is_null($item->image) && File::exists( public_path().'/images/banks/'.$item->image)){
                                //     return '<img src="'.url('images/banks/'.$item->image).'"  alt="logo" height="50px" width="50px"> ';
                                // }else{
                                //     return '<img src="'.url('\plugins\images\logo-placeholder.jpg').'"  alt="logo" height="50px" width="50px"> ';
                                // }
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title.'<br>'.$item->title_en;
                            })
                            ->addColumn('title_short', function ($item) {
                                return $item->title_short;
                            })
                            ->addColumn('created_at', function ($item) {
                                return HP::DateThai($item->created_at);
                            })
                            ->addColumn('created_by', function ($item) {
                                return $item->CreatedName;
                            })
                            ->addColumn('state', function ($item) {
                                return $item->stateIcon;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction($item->id, 'accounting/basic/banks', 'Accounting\\BanksController@destroy', 'accounting-basic-banks');
                            })
                            ->rawColumns(['checkbox','image', 'state', 'action', 'title'])
                            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('add-'.$model)) {

            return view('accounting.banks.create');
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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
                'title' => 'required',
                'title_en' => 'required',
                'title_short' => 'required'
            ]);



            $requestData = $request->all();
           // รูปภาพ
            if ($file = $request->file('image')) {
              $extension = $file->extension() ?: 'png';
              $destinationPath = public_path() . '/images/banks/';
              $safeName = date('Ymd_His').'.'.$extension;
              $file->move($destinationPath, $safeName);
              //save new file path into db
              $requestData['image'] = $safeName;
            }
            $requestData['created_by'] = auth()->user()->getKey();
            $requestData['state'] = $request->has('state')?1:0;

            Bank::create($requestData);
            return redirect('accounting/basic/banks')->with('flash_message', 'Bank added!');
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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('view-'.$model)) {
            $bank = Bank::findOrFail($id);

            return view('accounting.banks.show', compact('bank'));
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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('edit-'.$model)) {
            $bank = Bank::findOrFail($id);


            return view('accounting.banks.edit', compact('bank'));
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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
                'title' => 'required',
                'title_en' => 'required',
                'title_short' => 'required'
            ]);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();
            $requestData['state'] = $request->has('state')?1:0;
            // รูปภาพ
            if ($file = $request->file('image')) {
                $extension = $file->extension() ?: 'png';
                $destinationPath = public_path() . '/images/banks/';
                $safeName =   date('Ymd_His').'.'.$extension;
                $file->move($destinationPath, $safeName);
                //save new file path into db
                $requestData['image'] = $safeName;
            }

            $bank = Bank::findOrFail($id);
            $bank->update($requestData);

            return redirect('accounting/basic/banks')->with('flash_message', 'Bank updated!');
        }
        abort(403);

    }

    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $state = $request->input('state');
            $result = Bank::where('id', $id)->update(['state' => $state]);

            if($result) {
                return 'success';
            } else {
                return "not success";
            }

        }else{
            abort(403);
        }

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
        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('delete-'.$model)) {
            Bank::destroy($id);
            return redirect('accounting/basic/banks')->with('flash_message', 'Bank deleted!');
        }
        abort(403);

    }

                /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = Str::slug('accounting-basic-banks','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new Bank;
            $resulte =  Bank::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        abort(403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result = Bank::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function save_and_copy(Request $request)
    {
        $model = Str::slug('accounting-basic-banks','-');

        $msg = 'error';
        $data = null;
        if(auth()->user()->can('add-'.$model)) {
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $data =  Bank::create($requestData);
        
            $msg = 'success';
        }

        return response()->json(['msg' =>  $msg, 'data' => $data ]);
    }
}
