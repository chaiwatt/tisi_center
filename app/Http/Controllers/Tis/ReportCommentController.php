<?php

namespace App\Http\Controllers\Tis;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/report_comment/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('report_comment','-');
        if(auth()->user()->can('view-'.$model)) {


            return view('tis.report_comment.index');
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('report_comment','-');
        if(auth()->user()->can('edit-'.$model)) {
  
          $requestData = $request->all();
  
          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new public_draft;
            public_draft::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
          }
  
          return redirect('tis/report_comment')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
        }
  
        abort(403);
  
    }
}
