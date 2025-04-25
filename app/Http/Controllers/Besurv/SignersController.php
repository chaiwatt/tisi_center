<?php

namespace App\Http\Controllers\Besurv;

use HP;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\Besurv\Signer;
use App\Http\Controllers\Controller;

class SignersController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'files/signers';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('signers','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_main_group'] = $request->get('filter_main_group', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new Signer;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            if ($filter['filter_main_group']!='') {
                $Query = $Query->where('main_group', 'LIKE', "%{$filter['filter_main_group']}%");
            }

            $signers = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('besurv.signers.index', compact('signers', 'filter'));
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('besurv.signers.create');
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
			'name' => 'required',
			'position' => 'required',
			'main_group' => 'required',
            'tax_number' => 'required',
		]);

            $signer = User::where('reg_13ID',$request->tax_number)->first();
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            $requestData['main_group'] = !empty($request->main_group)?json_encode($request->main_group, JSON_UNESCAPED_UNICODE):null;
            $requestData['tax_number'] = !empty($request->tax_number)?(preg_replace("/[^a-z\d]/i", '',$request->tax_number)):null;
            $requestData['signed'] = !isset($request->signed)?0:1;
            if($signer !== null){
                $requestData['user_register_id'] = $signer->runrecno;
            }

            $besurv_signers =   Signer::create($requestData);
            if(isset($requestData['attach'])){
                if ($request->hasFile('attach')) {
                    HP::singleFileUpload(
                        $request->file('attach') ,
                        $this->attach_path,
                        !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new Signer)->getTable() ),
                         $besurv_signers->id,
                        'attach',
                        'ไฟล์แนบลายเซ็น'
                    );
                }
            }

            
            return redirect('besurv/signers')->with('flash_message', 'เพิ่ม Signer เรียบร้อยแล้ว');
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('view-'.$model)) {
            $signer = Signer::findOrFail($id);
            return view('besurv.signers.show', compact('signer'));
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('edit-'.$model)) {
            $signer = Signer::findOrFail($id);
            $signer['main_group'] = !empty($signer['main_group'])?json_decode($signer['main_group'], true):[''];


            return view('besurv.signers.edit', compact('signer'));
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			'name' => 'required',
			'position' => 'required',
			'main_group' => 'required'
		]);
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['main_group'] = !empty($request->main_group)?json_encode($request->main_group, JSON_UNESCAPED_UNICODE):null;
            $requestData['tax_number'] = !empty($request->tax_number)?(preg_replace("/[^a-z\d]/i", '',$request->tax_number)):null;
            $requestData['signed'] = !isset($request->signed)?0:1;

            $signer = Signer::findOrFail($id);
            $signer->update($requestData);

             if(isset($requestData['attach'])){
                if ($request->hasFile('attach')) {
                    HP::singleFileUpload(
                        $request->file('attach') ,
                        $this->attach_path,
                        !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new Signer)->getTable() ),
                         $signer->id,
                        'attach',
                        'ไฟล์แนบลายเซ็น'
                    );
                }
            }
            return redirect('besurv/signers')->with('flash_message', 'แก้ไข Signer เรียบร้อยแล้ว!');
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
        $model = str_slug('signers','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Signer;
            Signer::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Signer::destroy($id);
          }

          return redirect('besurv/signers')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('signers','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Signer;
          Signer::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('besurv/signers')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
