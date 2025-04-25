<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Certify\ApplicantCB\CertiCBFormulas;
use Illuminate\Http\Request;
use File;
class FormulasController extends Controller
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
        // $model = str_slug('standardformulas','-');
        // if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_title'] = $request->get('filter_title', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CertiCBFormulas;
            if ($filter['filter_search']!='') {
                $Query = $Query->where('formulas_id',$filter['filter_search']);
            }
            if ($filter['filter_title'] != '') {
                $Query = $Query->where('title','LIKE', '%'.$filter['filter_title'].'%');
            }

            $standardformulas = $Query->orderby('id','desc') ->paginate($filter['perPage']);

            return view('certify.formulas.index', compact('standardformulas', 'filter'));
        // }
        // abort(403);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // $model = str_slug('standardformulas','-');
        // if(auth()->user()->can('add-'.$model)) {
            $formula = new CertiCBFormulas;
            return view('certify.formulas.create',['formula'=> $formula]);
        // }
        // abort(403);

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
        // $model = str_slug('standardformulas','-');
        // if(auth()->user()->can('add-'.$model)) {
            
            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
            if ($file = $request->file('image')) {
                $extension = $file->extension() ?: 'png';
                $destinationPath = public_path() . '/plugins/formulas/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //save new file path into db
                $requestData['image'] = $safeName;
            }
            if ($file = $request->file('imagery')) {
                $extension = $file->extension() ?: 'png';
                $destinationPath = public_path() . '/plugins/formulas/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //save new file path into db
                $requestData['imagery'] = $safeName;
            }
            
            CertiCBFormulas::create($requestData);
            return redirect('certify/formulas')->with('flash_message', 'เพิ่ม CertiCBFormulas เรียบร้อยแล้ว');
        // }
        // abort(403);
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
        $model = str_slug('standardformulas','-');
        if(auth()->user()->can('view-'.$model)) {
            $formula = CertiCBFormulas::findOrFail($id);
            return view('certify.formulas.show', compact('formula'));
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
        // $model = str_slug('standardformulas','-');
        // if(auth()->user()->can('edit-'.$model)) {
            $formula = CertiCBFormulas::findOrFail($id);
            return view('certify.formulas.edit', compact('formula'));
        // }
        // abort(403);
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
        // $model = str_slug('standardformulas','-');
        // if(auth()->user()->can('edit-'.$model)) {
            
            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $formula = CertiCBFormulas::findOrFail($id);
            $requestData = $request->all();
            if ($file = $request->file('image')) {
                $extension = $file->extension() ?: 'png';
                $destinationPath = public_path() . '/plugins/formulas/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                // if (File::exists($destinationPath . $formula->image)) {
                //     File::delete($destinationPath . $formula->image);
                // }
                //save new file path into db
                $requestData['image'] = $safeName;
            }
            if ($file = $request->file('imagery')) {
                $extension = $file->extension() ?: 'png';
                $destinationPath = public_path() . '/plugins/formulas/';
                $safeName = str_random(10) . '.' . $extension;
                $file->move($destinationPath, $safeName);
                //delete old pic if exists
                // if (File::exists($destinationPath . $formula->imagery)) {
                    // File::delete($destinationPath . $formula->imagery);
                // }
                //save new file path into db
                $requestData['imagery'] = $safeName;
            }
            $formula->update($requestData);

            return redirect('certify/formulas')->with('flash_message', 'แก้ไข CertiCBFormulas เรียบร้อยแล้ว!');
        // }
        // abort(403);

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
 
        $model = str_slug('standardformulas','-');
        if(auth()->user()->can('delete-'.$model)) {
            $formula = CertiCBFormulas::findOrFail($id);
            // $destinationPath = public_path() . '/plugins/formulas/';
                //delete old pic if exists
            //  if (File::exists($destinationPath . $formula->image)) {
            //      File::delete($destinationPath . $formula->image);
            //  }

            CertiCBFormulas::destroy($id);

          return redirect('certify/formulas')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }



}
