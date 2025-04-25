<?php

namespace App\Http\Controllers\Basic;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Basic\Holiday;

use App\Models\Elicense\Basic\Holiday AS RosHoliday;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

use HP;
use HP_Law;
use stdClass;

class HolidayController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_search     = $request->input('filter_search');
        $filter_state     = $request->input('filter_state');
        $filter_holiday_date     = $request->input('filter_holiday_date');

        //$model = str_slug('basic-holiday','-');
        //ผู้ใช้งาน
        // $user = auth()->user();

        $query = Holiday::query()
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search);
                                            return $query->where(function($query) use ($search_full){
                                                                $query->where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->orWhere(DB::raw("REPLACE(title_en,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                })
                                ->when($filter_holiday_date, function ($query, $filter_holiday_date){
                                    return $query->where('holiday_date', HP::convertDate($filter_holiday_date, true));
                                })
                                ->when($filter_state, function ($query, $filter_state){
                                    return $query->where('state', $filter_state);
                                });
                                  
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('title_en', function ($item) {
                                return $item->title_en;
                            })
                            ->addColumn('holiday_date', function ($item) {
                                return !empty($item->holiday_date)?HP::DateThaiFull($item->holiday_date):null;
                            })
                            ->addColumn('fis_year', function ($item) {
                                return $item->fis_year+543;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br><span class="text-muted">('.HP::DateThai($item->created_at).')</span>':null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'basic/holiday','Basic\\HolidayController@destroy', 'basic-holiday',true, true, true, false);
                            })
                            ->order(function ($query) {
                                $query->orderBy('holiday_date', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'state', 'created_by'])
                            ->make(true);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('view-'.$model)) {

          $breadcrumbs = [
            [ "link" => "basic/holiday",  "name" => 'ปฏิทินวันหยุด' ],
          ];
            return view('basic.holiday.index', compact('breadcrumbs'));
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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('add-'.$model)) {

          return view('basic.holiday.create');

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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('add-'.$model)) {

            $this->validate($request, [
        			'title' => 'required'
        		]);

            $requestData = $request->all();
            $requestData['holiday_date'] = !empty($requestData['holiday_date'])?HP::convertDate( $requestData['holiday_date'],true):null;
            $requestData['created_by'] = auth()->user()->getKey();//user create

            $holiday = Holiday::create($requestData);

            RosHoliday::updateOrCreate(
                [  
                    'title'                        => $holiday->title,
                    'holiday_date'                 => $holiday->holiday_date
                ],
                [
                    'title'                        => $holiday->title,
                    'title_en'                     => $holiday->title_en,
                    'holiday_date'                 => $holiday->holiday_date,
                    'fis_year'                     => \Carbon\Carbon::parse( $holiday->holiday_date )->format('Y'),
                    'state'                        => $holiday->state,

                    //ผู้บันทึก
                    'created_by'                  => 163,
                ]
            );  

            return redirect('basic/holiday')->with('flash_message', 'เพิ่ม holiday เรียบร้อยแล้ว');
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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('view-'.$model)) {
            $holiday = Holiday::findOrFail($id);
            return view('basic.holiday.show', compact('holiday'));
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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('edit-'.$model)) {

            $holiday = holiday::findOrFail($id);
            $holiday->holiday_date = !empty($holiday->holiday_date) ? HP::revertDate($holiday->holiday_date, true) : null;

            return view('basic.holiday.edit', compact('holiday'));
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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
			          'title' => 'required'
		    ]);

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();
            $requestData['holiday_date'] = !empty($requestData['holiday_date'])?HP::convertDate( $requestData['holiday_date'],true):null;

            $holiday = Holiday::findOrFail($id);
            $holiday->update($requestData);

            return redirect('basic/holiday')->with('flash_message', 'แก้ไข holiday เรียบร้อยแล้ว!');
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
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Holiday;
            Holiday::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Holiday::destroy($id);
          }

          return redirect('basic/holiday')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('basic-holiday','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new Holiday;
          Holiday::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('basic/holiday')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function data_google_holiday_list(Request $request)
    {

        $filter_modal_year  = $request->input('filter_modal_year');

        $query              = HP_Law::GoogleCalendars(  $filter_modal_year );

        $holiday            = Holiday::pluck('holiday_date', 'holiday_date')->toArray();

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('title', function ($item) {
                                return $item->title;
                            })
                            ->addColumn('date', function ($item) {
                                return !empty($item->startDate)?HP::DateThaiFull($item->startDate):null;
                            })
                            ->addColumn('action', function ($item) use( $holiday ) {

                                if( !array_key_exists( $item->startDate , $holiday) ){

                                    $data_btn =  'data-title="'.($item->title).'"';
                                    $data_btn .= 'data-holiday_date="'.($item->startDate).'"';
                                    $data_btn .= 'data-fis_year="'.(\Carbon\Carbon::parse( $item->startDate )->format('Y')).'"';

                                    return '<button class="btn btn-sm btn-info btn_update_holiday" '.( $data_btn).'>อัพเดท</button>';
                                }else{
                                    return '<em class="text-muted">มีในระบบแล้ว</em>';
                                }

                            })
                            ->rawColumns([ 'action'])
                            ->make(true);

    }

    public function update_holiday(Request $request)
    {
        $model = str_slug('basic-holiday','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData                 = $request->all();
            $requestData['created_by']   = auth()->user()->getKey();//user create
            $requestData['state']        = 1;

            $holiday = Holiday::create($requestData);

            RosHoliday::updateOrCreate(
                [  
                    'title'                        => $holiday->title,
                    'holiday_date'                 => $holiday->holiday_date
                ],
                [
                    'title'                        => $holiday->title,
                    'title_en'                     => $holiday->title_en,
                    'holiday_date'                 => $holiday->holiday_date,
                    'fis_year'                     => \Carbon\Carbon::parse( $holiday->holiday_date )->format('Y'),
                    'state'                        => $holiday->state,

                    //ผู้บันทึก
                    'created_by'                  => 163,
                ]
            );  
            echo 'success';
            exit;
        }
    }

    public function elicense_holiday(Request $request){

        $type        = $request->get('type');

        $bs_holiday  = Holiday::whereNotNull('holiday_date')
                                ->select( DB::raw('holiday_date'), DB::raw('TRIM(title) AS title'), DB::raw('TRIM(title_en) AS title_en'), 'created_by' , DB::raw("CONCAT(REPLACE(title,' ',''),'_', holiday_date) AS keys_item"), 'state'  )
                                ->orderBy('holiday_date')
                                ->get()
                                ->keyBy('keys_item')
                                ->toArray();
        
        $ros_holiday = RosHoliday::whereNotNull('holiday_date')
                                    ->select( DB::raw('holiday_date'), DB::raw('TRIM(title) AS title'), DB::raw('TRIM(title_en) AS title_en'), 'created_by', DB::raw("CONCAT(REPLACE(title,' ',''),'_', holiday_date) AS keys_item"), 'state' )
                                    ->orderBy('holiday_date')
                                    ->get()
                                    ->keyBy('keys_item');

        $mgs  = 'error'; 

        $list = [];
        foreach( $ros_holiday AS $keys_item => $item ){

            if( !array_key_exists( $keys_item,  $bs_holiday)  ){
                $data = new stdClass;
                $data->title        = $item->title;
                $data->title_en     = $item->title_en;
                $data->holiday_date = $item->holiday_date;
                $data->holiday_txt  = !empty($item->holiday_date)?HP::DateThaiFull($item->holiday_date):null;
                $data->fis_year     = \Carbon\Carbon::parse( $item->holiday_date )->format('Y');
                $list[]             = $data;

                if( $type == 'update'){
                    Holiday::updateOrCreate(
                        [  
                            'title'                        => $item->title,
                            'holiday_date'                 => $item->holiday_date
                        ],
                        [
                            'title'                        => $item->title,
                            'title_en'                     => $item->title_en,
                            'holiday_date'                 => $item->holiday_date,
                            'fis_year'                     => \Carbon\Carbon::parse( $item->holiday_date )->format('Y'),
                            'state'                        => $item->state,

                            //ผู้บันทึก
                            'created_by'                  => auth()->user()->getKey(),
                        ]
                    );  

                    $mgs  = 'success'; 
                }
            }

        }
        if( $type  == 'get' ){
            return response()->json($list, JSON_UNESCAPED_UNICODE);  
        }else{
            return response()->json($mgs, JSON_UNESCAPED_UNICODE);
        }

    }

}
