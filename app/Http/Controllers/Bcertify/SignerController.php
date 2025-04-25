<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\Signer as signer;
use Illuminate\Http\Request;

use Storage;

class signerController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'bcertify_attach/signer/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('signer','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new signer;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $signer = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            $attach_path = $this->attach_path;//path ไฟล์แนบ

            return view('bcertify.signer.index', compact('signer', 'filter', 'attach_path'));
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

        $model = str_slug('signer','-');
        if(auth()->user()->can('add-'.$model)) {
            $signer = [];
            $attach_path = $this->attach_path;
            return view('bcertify.signer.create', compact('signer', 'attach_path'));
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
        $model = str_slug('signer','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'position1' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();

            if ($file = $request->file('signature_img')) {

              //Upload File
              $storagePath = Storage::put($this->attach_path, $file);
              $storageName = basename($storagePath);// Extract the filename

              $requestData['signature_img'] = $storageName;

            }

            signer::create($requestData);
            return redirect('bcertify/signer')->with('flash_message', 'เพิ่ม signer เรียบร้อยแล้ว');
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
        $model = str_slug('signer','-');
        if(auth()->user()->can('view-'.$model)) {

            $signer = signer::findOrFail($id);
            
            $attach_path = $this->attach_path;//path ไฟล์แนบ

            return view('bcertify.signer.show', compact('signer', 'attach_path'));
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
        $model = str_slug('signer','-');
        if(auth()->user()->can('edit-'.$model)) {
            $signer = signer::findOrFail($id);
            $attach_path = $this->attach_path;
            return view('bcertify.signer.edit', compact('signer', 'attach_path'));
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
        $model = str_slug('signer','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'position1' => 'required'
        		]);

            $signer = signer::findOrFail($id);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update

            if ($file = $request->file('signature_img')) {

              //Upload File
              $storagePath = Storage::put($this->attach_path, $file);
              $storageName = basename($storagePath);// Extract the filename

              Storage::delete($this->attach_path.$signer->signature_img);//Delete Old

              $requestData['signature_img'] = $storageName;

            }

            $signer->update($requestData);

            return redirect('bcertify/signer')->with('flash_message', 'แก้ไข signer เรียบร้อยแล้ว!');
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
        $model = str_slug('signer','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new signer;
            signer::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            signer::destroy($id);
          }

          return redirect('bcertify/signer')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('signer','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new signer;
          signer::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('bcertify/signer')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
