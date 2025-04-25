<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\Board as board;
use App\Models\Tis\BoardProductGroup;
use App\Models\Tis\BoardBoardType;
use App\Models\Tis\BoardWork;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Storage;
use HP;

class BoardController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/board/';
        $this->attach_path_crop = 'tis_attach/board_crop/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('board','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_department'] = $request->get('filter_department');
            $filter['filter_tel'] = $request->get('filter_tel', '');
            $filter['filter_email'] = $request->get('filter_email', '');
            $filter['perPage'] = $request->get('perPage', 10);
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_product_group'] = $request->get('filter_product_group');

            $Query = new board;

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('first_name', 'LIKE', "%{$filter['filter_search']}%")
                                          ->orWhere('last_name', 'LIKE', "%{$filter['filter_search']}%");
                         });
            }
            if ($filter['filter_department']!='') {
                $departments = BoardWork::whereIn('department_id', $filter['filter_department'])->pluck('board_id');
                $Query = $Query->whereIn('id', $departments);
            }
            if ($filter['filter_tel']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('tel', 'LIKE', "%{$filter['filter_tel']}%");
                         });
            }
            if ($filter['filter_email']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                    $query->where('email', 'LIKE', "%{$filter['filter_email']}%");
                         });
            }
            if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
            }
            if ($filter['filter_product_group']!='') {
                $product_groups = BoardProductGroup::whereIn('product_group_id', $filter['filter_product_group'])->pluck('board_id');
                $Query = $Query->whereIn('id', $product_groups);
            }


            $board = $Query->sortable()->with('user_created')
                                       ->with('user_updated')
                                       ->with('board_type_list')
                                       ->with('product_group_list')
                                       ->with('work_list')
                                       ->paginate($filter['perPage']);

            return view('tis.board.index', compact('board', 'filter'));
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
        $model = str_slug('board','-');
        if(auth()->user()->can('add-'.$model)) {

            $previousUrl = app('url')->previous();

            $board = (object)[];
            $works = [(object)['position'=>'',
                               'department_id'=>'',
                               'responsible'=>'',
                               'abode'=>'',
                               'experience'=>'',
                               'belong_to'=>'',
                               'phone'=>'',
                               'ministry'=>'',
                               'fax'=>'',
                               'status'=>'1']];

            $attach_path = $this->attach_path_crop;

            return view('tis.board.create', compact('board', 'works', 'attach_path', 'previousUrl'));

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
        $model = str_slug('board','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'prefix_name' => 'required',
        			'first_name' => 'required',
        			'last_name' => 'required'
        			// 'birth_date' => 'required',
        			// 'identity_number' => 'required',
        			// 'qualification' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['birth_date'] = $request->birth_date?Carbon::createFromFormat("d/m/Y",$request->birth_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;


            if($picture_origin = $request->file('picture_origin')){

                //ข้อมูลภาพต้นเฉบับ
                $storagePath = Storage::put($this->attach_path, $picture_origin);
                $storageName = basename($storagePath); // Extract the filename

                $requestData['picture']['origin'] = $storageName;

                //ข้อมูลภาพที่ตัดแล้ว
                list($type, $data) = explode(';', $requestData['picture_croppied']);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $image_name = uniqid() . '.png';

                $storagePath = Storage::put($this->attach_path_crop.$image_name, $data);//Upload File

                $requestData['picture']['croppied'] = $image_name;
                $requestData['picture']['top'] = $requestData['pic']['top'];
                $requestData['picture']['left'] = $requestData['pic']['top'];
                $requestData['picture']['bottom'] = $requestData['pic']['top'];
                $requestData['picture']['right'] = $requestData['pic']['top'];
                $requestData['picture']['zoom'] = $requestData['pic']['top'];

                $requestData['picture'] = json_encode($requestData['picture']);

            }

            //บันทึกข้อมูลหลัก
            $board = board::create($requestData);

            //บันทึกสาขา/หน่วยงาน
            $this->SaveDetail($board, $requestData);

            return redirect('tis/board')->with('flash_message', 'เพิ่ม board เรียบร้อยแล้ว');
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
        $model = str_slug('board','-');
        if(auth()->user()->can('view-'.$model)) {
            $board = board::findOrFail($id);
            $board['birth_date'] = $board['birth_date']?Carbon::createFromFormat("Y-m-d",$board['birth_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $board['product_group_ids'] = $board->product_group_list->pluck('product_group_id', 'product_group_id')->toArray();
            $board['board_type_ids'] = $board->board_type_list->pluck('board_type_id', 'board_type_id')->toArray();

            //ข้อมูลการทำงาน
            $works = $board->work_list;

            $attach_path = $this->attach_path_crop;
            return view('tis.board.show', compact('board', 'works', 'attach_path'));
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
        $model = str_slug('board','-');
        if(auth()->user()->can('edit-'.$model)) {

            $previousUrl = app('url')->previous();

            $board = board::findOrFail($id);
            $board['birth_date'] = $board['birth_date']?Carbon::createFromFormat("Y-m-d",$board['birth_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $board['product_group_ids'] = $board->product_group_list->pluck('product_group_id', 'product_group_id')->toArray();
            $board['board_type_ids'] = $board->board_type_list->pluck('board_type_id', 'board_type_id')->toArray();

            //ข้อมูลการทำงาน
            $works = $board->work_list;

            $attach_path = $this->attach_path_crop;

            return view('tis.board.edit', compact('board', 'works', 'attach_path', 'previousUrl'));

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
        $model = str_slug('board','-');
        if(auth()->user()->can('edit-'.$model)) {

          $previousUrl = $request->get('previousUrl');

            $this->validate($request, [
        			'prefix_name' => 'required',
        			'first_name' => 'required',
        			'last_name' => 'required'
        			// 'birth_date' => 'required',
        			// 'identity_number' => 'required',
        			// 'qualification' => 'required'
        		]);

            $board = board::findOrFail($id);//ข้อมูลเดิม

            $requestData = $request->all();
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['birth_date'] = $request->birth_date?Carbon::createFromFormat("d/m/Y",$request->birth_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            if($picture_origin = $request->file('picture_origin')){

                //ข้อมูลภาพต้นเฉบับ
                $storagePath = Storage::put($this->attach_path, $picture_origin);
                $storageName = basename($storagePath); // Extract the filename
                $requestData['picture']['origin'] = $storageName;

                //ข้อมูลภาพที่ตัดแล้ว
                list($type, $data) = explode(';', $requestData['picture_croppied']);
                list(, $data) = explode(',', $data);
                $data = base64_decode($data);
                $image_name = uniqid() . '.png';
                Storage::put($this->attach_path_crop.$image_name, $data);//Upload File
                $requestData['picture']['croppied'] = $image_name;

                $requestData['picture']['top'] = $requestData['pic']['top'];
                $requestData['picture']['left'] = $requestData['pic']['top'];
                $requestData['picture']['bottom'] = $requestData['pic']['top'];
                $requestData['picture']['right'] = $requestData['pic']['top'];
                $requestData['picture']['zoom'] = $requestData['pic']['top'];

                $requestData['picture'] = json_encode($requestData['picture']);

                //ลบภาพเดิม
                $picture = json_decode($board->picture);
                Storage::delete($this->attach_path_crop.$picture->croppied);
                Storage::delete($this->attach_path.$picture->origin);

            }

            $board->update($requestData);

            //บันทึกสาขา/หน่วยงาน
            $this->SaveDetail($board, $requestData);

            if($previousUrl){
              return redirect($previousUrl)->with('flash_message', 'แก้ไข board เรียบร้อยแล้ว!');
            }else{
              return redirect('tis/board')->with('flash_message', 'แก้ไข board เรียบร้อยแล้ว!');
            }

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
        $model = str_slug('board','-');

        $board = board::findOrFail($id);

        if(auth()->user()->getKey()==$board->created_by || auth()->user()->can('delete-'.str_slug('board'))) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new board;
            board::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            board::destroy($id);
          }

          return redirect('tis/board')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('board','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new board;
          board::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/board')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    /*
      **** Save สาขา/หน่วยงาน
    */
    private function SaveDetail($main, $requestData){

        /* ลบข้อมูลผลิตภัณฑ์/สาขา */
        BoardProductGroup::where('board_id', $main->id)->delete();

        /* บันทึกข้อมูลผลิตภัณฑ์/สาขา */
        foreach ((array)@$requestData['product_group_ids'] as $product_group_id) {
          $input = [];
          $input['product_group_id'] = $product_group_id;
          $input['board_id'] = $main->id;
          BoardProductGroup::create($input);
        }

        /* ลบข้อมูลประเภทคณะกรรมการ */
        BoardBoardType::where('board_id', $main->id)->delete();

        /* บันทึกข้อมูลประเภทคณะกรรมการ */
        foreach ((array)@$requestData['board_type_ids'] as $board_type_id) {
          $input = [];
          $input['board_type_id'] = $board_type_id;
          $input['board_id'] = $main->id;
          BoardBoardType::create($input);
        }

        /* ลบข้อมูลการทำงาน */
        BoardWork::where('board_id', $main->id)->delete();

        /* บันทึกข้อมูลการทำงาน */
        foreach ((array)@$requestData['positions'] as $key=>$position) {
          $input = [];
          $input['board_id'] = $main->id;
          $input['position'] = $position;
          $input['department_id'] = $requestData['department_ids'][$key];
          $input['responsible'] = $requestData['responsibles'][$key];
          $input['abode'] = $requestData['abodes'][$key];
          $input['experience'] = $requestData['experiences'][$key];
          $input['belong_to'] = $requestData['belong_tos'][$key];
          $input['phone'] = $requestData['phones'][$key];
          $input['ministry'] = $requestData['ministrys'][$key];
          $input['fax'] = $requestData['faxs'][$key];
          $input['status'] = @$requestData['status'][$key];
          BoardWork::create($input);
        }

    }

    public function save_board(Request $request)
    {
      $requestData = $request->all();
      $requestData['created_by'] = auth()->user()->getKey(); //user create
      $board = board::create($requestData);
      $last_id = $board->id;
      $last_insert_data = board::where('id',$last_id)->first();

      $input = [];
      $input['board_id'] = $last_id;
      $input['status'] = 1;
      BoardWork::create($input);

      if($board){
          return response()->json([
          'status' => 'success',
          'id' => $last_insert_data->id,
          'title' => $last_insert_data->FullName
          ]);
      } else {
          return response()->json([
          'status' => 'error'
          ]);
      }
    }

}
