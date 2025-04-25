<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\Appoint as appoint;
use App\Models\Tis\AppointBoardType;
use App\Models\Tis\AppointBoard;
use App\Models\Basic\BoardType;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Storage;
use HP;

class AppointController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/appoint/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('appoint','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_board_type'] = $request->get('filter_board_type', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_publish_date_start'] = $request->get('filter_publish_date_start', '');
            $filter['filter_publish_date_end'] = $request->get('filter_publish_date_end', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new appoint;

            if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
            }

            if ($filter['filter_board_type']!='') {
                $board_types = BoardType::where('id', $filter['filter_board_type'])->where('state',1)->pluck('id');
                $Query = $Query->whereIn('board_type_id', $board_types);
            }

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('title', 'LIKE', "%{$filter['filter_search']}%")
                                          ->orWhere('command', 'LIKE', "%{$filter['filter_search']}%")
                                          ->orWhere('board_position', 'LIKE', "%{$filter['filter_search']}%")
                                          ->orWhere('subject', 'LIKE', "%{$filter['filter_search']}%");
                         });
            }

            if ($filter['filter_publish_date_start']!='') {
                $Query = $Query->where('publish_date', '>=', HP::convertDate($filter['filter_publish_date_start']));
            }

            if ($filter['filter_publish_date_end']!='') {
                $Query = $Query->where('publish_date', '<=', HP::convertDate($filter['filter_publish_date_end']));
            }

            $appoint = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('tis.appoint.index', compact('appoint', 'filter'));
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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('add-'.$model)) {

            $department_sets = [(array)['department_set'=>'','board_ids'=>'','appoint_department_ids'=>'']];

            $attachs = [(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;

            $amphurs = [];
            $districts = [];

            return view('tis.appoint.create', compact('department_sets', 'attachs', 'attach_path', 'amphurs', 'districts'));

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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'product_group_id' => 'required',
        			'command' => 'required',
        			'subject' => 'required',
        			'publish_date' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['publish_date'] = $request->publish_date?Carbon::createFromFormat("d/m/Y",$request->publish_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

              foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $storageName = basename($storagePath); // Extract the filename

                $attachs[] = ['file_name'=>$storageName,
                              'file_client_name'=>$file->getClientOriginalName(),
                              'file_note'=>$requestData['attach_notes'][$key]
                             ];
              }

            }

            $requestData['attach'] = json_encode($attachs);

            // dd($requestData);

            $appoint = appoint::create($requestData);

            //บันทึกข้อมูลประเภทคณะกรรมการ
            $this->SaveDetail($appoint, $requestData);

            return redirect('tis/appoint')->with('flash_message', 'เพิ่ม appoint เรียบร้อยแล้ว');
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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('view-'.$model)) {
            $appoint = appoint::findOrFail($id);
            $appoint['publish_date'] = $appoint['publish_date']?Carbon::createFromFormat("Y-m-d",$appoint['publish_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $appoint['appoint_department_ids'] = $appoint->board_list->pluck('appoint_department_id', 'appoint_department_id')->toArray();
            $department_set_array = AppointBoard::select('department_set')->where('appoint_id', $id)->orderBy('id', 'ASC')->pluck('department_set', 'department_set')->toArray();
            $appoint_test = AppointBoard::select('department_set', 'appoint_department_id', 'board_id')->where('appoint_id', $id)->orderBy('id', 'ASC')->get();
            $appoint['board_ids'] = $appoint->board_list->pluck('board_id', 'board_id')->toArray();

            $department_sets = [];

              foreach(array_values($department_set_array) as $key=>$val){
                  foreach($appoint_test as $key2=>$val2){
                      if($appoint_test[$key2]->department_set==$val){
                        $department_sets[$key]['department_set'][] = $val2->department_set;
                        $department_sets[$key]['board_ids'][] = $val2->board_id;
                        $department_sets[$key]['appoint_department_ids'][] = $val2->appoint_department_id ?? null;
                      } else {
                        continue;
                      }
                  }
              }

              if(count($department_sets)==0){
                $department_sets =  [(array)['department_set'=>'','board_ids'=>'','appoint_department_ids'=>'']];
              }

              // dd($department_sets);

               //ไฟล์แนบ
            $attachs = json_decode($appoint['attach']);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;

            return view('tis.appoint.show', compact('appoint', 'department_sets', 'attachs', 'attach_path'));
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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('edit-'.$model)) {

            $appoint = appoint::findOrFail($id);
            $appoint['publish_date'] = $appoint['publish_date']?Carbon::createFromFormat("Y-m-d",$appoint['publish_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            // $appoint['department_sets'] = $appoint->board_list->pluck('department_set', 'department_set')->toArray();
            $appoint['appoint_department_ids'] = $appoint->board_list->pluck('appoint_department_id', 'appoint_department_id')->toArray();
            // $appoint['board_ids'] = $appoint->board_list->pluck('board_id', 'board_id')->toArray();

            $department_set_array = AppointBoard::select('department_set')->where('appoint_id', $id)->orderBy('id', 'ASC')->pluck('department_set', 'department_set')->toArray();

            $appoint_test = AppointBoard::select('department_set', 'appoint_department_id', 'board_id')->where('appoint_id', $id)->orderBy('id', 'ASC')->get();
            // $appoint['board_type_ids'] = $appoint->board_type_list->pluck('board_type_id', 'board_type_id')->toArray();
            $appoint['board_ids'] = $appoint->board_list->pluck('board_id', 'board_id')->toArray();

            // array_values($department_set_array);

            $department_sets = [];

              // foreach((array)$appoint['department_sets'] as $key=>$val){
              foreach(array_values($department_set_array) as $key=>$val){
                  foreach($appoint_test as $key2=>$val2){
                      if($appoint_test[$key2]->department_set==$val){
                        $department_sets[$key]['department_set'][] = $val2->department_set;
                        $department_sets[$key]['board_ids'][] = $val2->board_id;
                        $department_sets[$key]['appoint_department_ids'][] = $val2->appoint_department_id ?? null;
                      } else {
                        continue;
                      }
                  }
              }

              if(count($department_sets)==0){
                $department_sets =  [(array)['department_set'=>'','board_ids'=>'','appoint_department_ids'=>'']];
              }

              // var_dump($department_sets);
              // dd($department_sets);

            //ไฟล์แนบ
            $attachs = json_decode($appoint['attach']);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;

            $amphurs = [];
            $districts = [];

            // dd($appoint);



            return view('tis.appoint.edit', compact('appoint', 'department_sets', 'attachs', 'attach_path', 'amphurs', 'districts'));

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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'product_group_id' => 'required',
        			'command' => 'required',
        			'subject' => 'required',
        			'publish_date' => 'required'
        		]);

            $appoint = appoint::findOrFail($id);

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['publish_date'] = $request->publish_date?Carbon::createFromFormat("d/m/Y",$request->publish_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            //ข้อมูลไฟล์แนบ
            $attachs = array_values((array)json_decode($appoint->attach));

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {

              if(in_array($attach->file_name, $requestData['attach_filenames'])===false){//ถ้าไม่มีไฟล์เดิมกลับมา
                unset($attachs[$key]);
                Storage::delete($this->attach_path.$attach->file_name);
              }
            }

            //ไฟล์แนบ ข้อความที่แก้ไข
            foreach ($attachs as $key => $attach) {
              $search_key = array_search($attach->file_name, $requestData['attach_filenames']);
              if($search_key!==false){
                $attach->file_note = $requestData['attach_notes'][$search_key];
              }
            }

            //ไฟล์แนบ เพิ่มเติม
            if ($files = $request->file('attachs')) {

              $dir = $this->attach_path;
              foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $newFile = basename($storagePath); // Extract the filename

                if($requestData['attach_filenames'][$key]!=''){//ถ้าเป็นแถวเดิมที่มีในฐานข้อมูลอยู่แล้ว

                  //วนลูปค้นหาไฟล์เดิม
                  foreach ($attachs as $key2 => $attach) {

                    if($attach->file_name == $requestData['attach_filenames'][$key]){//ถ้าเจอแถวที่ตรงกันแล้ว

                      Storage::delete($this->attach_path.$attach->file_name);//ลบไฟล์เก่า

                      $attach->file_name = $newFile;//แก้ไขชื่อไฟล์ใน object
                      $attach->file_client_name = $file->getClientOriginalName();//แก้ไขชื่อไฟล์ของผู้ใช้ใน object

                      break;
                    }
                  }

                }else{//แถวที่เพิ่มมาใหม่

                  $attachs[] = ['file_name'=>$newFile,
                                'file_client_name'=>$file->getClientOriginalName(),
                                'file_note'=>$requestData['attach_notes'][$key]
                               ];
                }

              }

            }

            $requestData['attach'] = json_encode($attachs);

            $appoint->update($requestData);

            // dd($requestData);

            //บันทึกประเภทคณะกรรมการ / รายชื่อคณะกรรมการ
            $this->SaveDetail($appoint, $requestData);

            return redirect('tis/appoint')->with('flash_message', 'แก้ไข appoint เรียบร้อยแล้ว!');
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
        $model = str_slug('appoint','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new appoint;
            appoint::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            appoint::destroy($id);
          }

          return redirect('tis/appoint')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('appoint','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new appoint;
          appoint::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/appoint')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save สาขา/หน่วยงาน
    */
    private function SaveDetail($main, $requestData){

        /* ลบข้อมูลคณะกรรมการ */
        AppointBoard::where('appoint_id', $main->id)->delete();

        /* บันทึกข้อมูลคณะกรรมการ */
        // foreach ((array)@$requestData['board_ids'] as $key=>$board_id) {
        //   $input = [];
        //   $input['board_id'] = $board_id;
        //   $input['appoint_department_id'] = @$requestData['appoint_department_ids'][$key];
        //   $input['appoint_id'] = $main->id;
        //   AppointBoard::create($input);
        // }
        // dd($requestData);
        foreach ((array)@$requestData['department_set'] as $key=>$department_set) {
          $input = [];
          $input['appoint_id'] = $main->id;
          $input['department_set'] = $department_set;
          // dd(($requestData['board_ids'][$key+1]));
          foreach ($requestData['board_ids'][$key] as $key2 => $board_id) {
              $input['appoint_department_id'] = @$requestData['appoint_department_ids'][$key];
              $input['board_id'] =  @$board_id;
              AppointBoard::create($input);
          }
        }

        /* ลบข้อมูลประเภทคณะกรรมการ */
        // AppointBoardType::where('appoint_id', $main->id)->delete();

        /* บันทึกข้อมูลประเภทคณะกรรมการ */
        // foreach ((array)@$requestData['board_type_ids'] as $board_type_id) {
        //   $input = [];
        //   $input['board_type_id'] = $board_type_id;
        //   $input['appoint_id'] = $main->id;
        //   AppointBoardType::create($input);
        // }
    }

}
