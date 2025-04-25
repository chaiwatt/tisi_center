<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\Standard as standard;
use App\Models\Tis\Appoint;
use App\Models\Tis\NoteStdDraft;
use App\Models\Basic\StandardType;
use App\Models\Basic\StandardFormat;
use App\Models\Basic\SetFormat;
use App\Models\Basic\Method;
use App\Models\Basic\ProductGroup;
use App\Models\Basic\IndustryTarget;
use App\Models\Basic\StaffGroup;
use App\Models\Tis\SetStandard;

use Illuminate\Http\Request;

use App\Models\Tis\BoardProductGroup;
use App\Models\Tis\PublicDraft as public_draft;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use HP;
// use SHP;
use File;
use ZipArchive;
use stdClass;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class StandardController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/standard/';
    }

    public function data_list(Request $request)
    {

        
        $filter_state = $request->get('filter_state') == "0"? 99 :$request->get('filter_state');
        $filter_search = $request->get('filter_search');

        $filter_publish_date_start = $request->get('filter_publish_date_start');
        $filter_publish_date_end = $request->get('filter_publish_date_end');
        $filter_refer = $request->get('filter_refer');
        $filter_set_format = $request->get('filter_set_format');
        $filter_review_status = $request->get('filter_review_status');
        $filter_product_group = $request->get('filter_product_group');
        $filter_board_type = $request->get('filter_board_type');
        $filter_staff_group = $request->get('filter_staff_group');
        $filter_staff_responsible = $request->get('filter_staff_responsible');
        $filter_gazette = $request->get('filter_gazette');

        $query = Standard::query()->when($filter_search, function ($query, $filter_search){
                         return $query ->where(function ($query) use ($filter_search) {
                                            $query->where('title', 'LIKE', "%{$filter_search}%")
                                                ->orWhere('title_en', 'LIKE', "%{$filter_search}%")
                                                ->orWhereRaw("CONCAT_WS('-',tis_no,tis_year) LIKE '%{$filter_search}%'")
                                                ->orWhereRaw("CONCAT(tis_no,' เล่ม ',tis_book,'-',tis_year) LIKE '%{$filter_search}%'")
                                                //   ->orWhere('tis_year', 'LIKE', "%{$filter['filter_search']}%")
                                                ->orWhere('tis_book', 'LIKE', "%{$filter_search}%");
                                        });
                        })
                        ->when($filter_state, function ($query, $filter_state){
                            if( $filter_state == 1){
                                return $query->where('state', $filter_state);
                            }else{
                                return $query->where('state', '<>', 1)->orWhereNull('state');
                            }
                        })
                        ->when($filter_publish_date_start, function ($query, $filter_publish_date_start){
                            return $query->whereRaw('(CASE
                                                        WHEN announce_compulsory="y" THEN issue_date_compulsory
                                                        ELSE issue_date
                                                    END) >= "'.Carbon::createFromFormat("d/m/Y",$filter_publish_date_start )->addYear(-543)->formatLocalized('%Y-%m-%d').'"');
                        })
                        ->when($filter_publish_date_end, function ($query, $filter_publish_date_end){
                            return $query->whereRaw('(CASE
                                                        WHEN announce_compulsory="y" THEN issue_date_compulsory
                                                        ELSE issue_date
                                                    END) <= "'.Carbon::createFromFormat("d/m/Y",$filter_publish_date_end )->addYear(-543)->formatLocalized('%Y-%m-%d').'"');
                        })
                        ->when($filter_refer, function ($query, $filter_refer){
                            return $query->where('refer', 'LIKE', $filter_refer);
                        })
                        ->when($filter_set_format, function ($query, $filter_set_format){
                            return $query->where('set_format_id', $filter_set_format);
                        })
                        ->when($filter_review_status, function ($query, $filter_review_status){
                            return $query->where('review_status', $filter_review_status);
                        })
                        ->when($filter_product_group, function ($query, $filter_product_group){
                            return $query->where('product_group_id', $filter_product_group);
                        })
                        ->when($filter_board_type, function ($query, $filter_board_type){
                            $board_types = Appoint::whereIn('id', $filter_board_type)->select('id');
                            return $query->whereIn('board_type_id', $board_types);
                        })
                        ->when($filter_staff_group, function ($query, $filter_staff_group){
                            return $query->whereIn('staff_group_id', $filter_staff_group);
                        })
                        ->when($filter_staff_responsible, function ($query, $filter_staff_responsible){
                            return $query->whereIn('staff_responsible', 'LIKE', $filter_staff_responsible);
                        })
                        ->when($filter_gazette, function ($query, $filter_gazette){
                            return $query->where('government_gazette', $filter_gazette);
                        });

        return Datatables::of($query)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($item) {
                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'.$item->id.'">';
                })
                ->addColumn('tis_no', function ($item) {
                    return $item->tis_no.(!empty($item->tis_book) ? ' เล่ม '.$item->tis_book : '').'-'.$item->tis_year ;
                })
                ->addColumn('tis_name', function ($item) {
                    $title_en = !empty($item->title_en)?'<br>('.$item->title_en.')':'';
                    $gazette_status = !empty($item->government_gazette) && $item->government_gazette=='w'?'<br><span style="color: red">('.$item->GovernmentGazetteName.')</span>':'';
                    return '<small>'.$item->title.$title_en.$gazette_status.'<small>' ;
                })
                ->addColumn('IsoCodeName', function ($item) {
                    return  $item->IsoCodeName ;
                })
                ->addColumn('issue_date', function ($item) {

                    $issue_date = null;
                    if( !is_null( $item->issue_date )  ){
                        $issue_date = $item->issue_date;
                    }

                    $issue_date_compulsory = null;
                    if( ($item->announce_compulsory == 'y') && !empty($item->issue_date_compulsory)  ){
                        $issue_date_compulsory = $item->issue_date_compulsory;
                    }

                    if( !is_null($issue_date) && !is_null($issue_date_compulsory) ){
                        return (!is_null($issue_date)?'ทั่วไป: '.HP::DateThai($issue_date):null).'<br>'.(!is_null($issue_date_compulsory)?'บังคับ: '.HP::DateThai($issue_date_compulsory):null);
                    }elseif ( !is_null($issue_date) && is_null($issue_date_compulsory) ){
                        return !is_null($issue_date)?'ทั่วไป: '.HP::DateThai($issue_date):null;
                    }elseif ( is_null($issue_date) && !is_null($issue_date_compulsory) ){
                        return !is_null($issue_date_compulsory)?'บังคับ: '.HP::DateThai($issue_date_compulsory):null;
                    }else{
                        return 'N/A';
                    }
                })
                ->addColumn('refer', function ($item) {
                    return  !empty(json_decode($item->refer)[0])?implode(', ', json_decode($item->refer)):'-' ;
                })
                ->addColumn('StandardFormatName', function ($item) {
                    return  @$item->StandardFormatName;
                })
                ->addColumn('product_group', function ($item) {
                    return  isset($item->product_group) ? $item->product_group->title : '' ;
                })
                ->addColumn('StaffGroupName', function ($item) {
                    return  isset($item->StaffGroupName) ? $item->StaffGroupName : '';
                })
                ->addColumn('ReviewStatusName', function ($item) {
                    return  @$item->ReviewStatusName ;
                })
                ->addColumn('state', function ($item) {
                    return  $item->state=='1'?'ใช้งาน':'ยกเลิก' ;
                })
                ->addColumn('action', function ($item) {

                    $btn = '';
                    $model = str_slug('standard','-');
                    if(auth()->user()->can('view-'.$model)) {
                        $btn .= '<a href="'. url('/tis/standard/download-filezip/' . $item->id) .'" title="Download PDF" class="btn btn-success btn-xs"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>';

                        $btn .= ' <a href="'. url('/tis/standard/' . $item->id) .'" title="Download PDF" title="View standard" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                    }

                    if( auth()->user()->getKey()==$item->created_by || auth()->user()->can('edit-'.$model) ) {
                        $btn .= ' <a href="'. url('/tis/standard/' . $item->id . '/edit') .'"  title="Edit standard" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-pencil-square-o" aria-hidden="true"> </i></a>';
                    }

                    if( auth()->user()->getKey()==$item->created_by || auth()->user()->can('delete-'.$model) ){

                        $btn .=  '<form action="' . action('Tis\StandardController@destroy', ['id' => $item->id ]) . '" method="POST" style="display:inline">' . csrf_field() . method_field('DELETE') . '
                                        <button type="submit" class="btn btn-danger btn-xs" title="Delete ' . substr('standard', 0, -1) . '" onclick="return confirm_delete()"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                                    </form>';
                    }


                    return  $btn;
                })
                ->addColumn('total_age', function ($item) {

                    $issue_date = null;
                    if( !is_null( $item->issue_date )  ){
                        $issue_date = $item->issue_date;
                    }

                    $issue_date_compulsory = null;
                    if( ($item->announce_compulsory == 'y') && !empty($item->issue_date_compulsory)  ){
                        $issue_date_compulsory = $item->issue_date_compulsory;
                    }

                    $total_age = 0;
                    if( !is_null($issue_date) && !is_null($issue_date_compulsory) ){
                        $total_age = @HP::YearCal($issue_date);
                    }else if( !is_null($issue_date) && is_null($issue_date_compulsory) ){
                        $total_age = @HP::YearCal($issue_date);
                    }else if( is_null($issue_date) && !is_null($issue_date_compulsory) ){
                        $total_age = @HP::YearCal($issue_date_compulsory);
                    }

                    $year_age = null;
                    if( !is_null($issue_date) || !is_null($issue_date_compulsory)  ){

                        if( !is_null($issue_date) && !is_null($issue_date_compulsory) ){
                            $dates = $issue_date;
                        }else if( !is_null($issue_date) && is_null($issue_date_compulsory) ){
                            $dates = $issue_date;
                        }else if( is_null($issue_date) && !is_null($issue_date_compulsory) ){
                            $dates = $issue_date_compulsory;
                        }

                        $today = date("Y-m-d");

                        if( $dates <= $today ){
                            list($byear, $bmonth, $bday) = explode("-", $dates);
                            list($tyear, $tmonth, $tday) = explode("-", $today);

                            $mk_birthday = mktime(0, 0, 0, $bmonth, $bday, $byear);
                            $mk_now = mktime(0, 0, 0, $tmonth, $tday, $tyear);
                            $mk_age = ($mk_now - $mk_birthday);

                            $year_ages = date("Y", $mk_age) - 1970;
                            $month_age = date("m", $mk_age) - 1;
                            $day_age = date("d", $mk_age) - 1;

                            if( $year_ages != 0 && $year_ages > 0  ){
                                $year_age .= "{$year_ages} ปี ";
                            }

                            if( $month_age != 0 && $month_age > 0 ){
                                $year_age .= "{$month_age} เดือน ";
                            }

                            if(  $day_age != 0 && $day_age > 0 ){
                                $year_age .= "{$day_age} วัน";
                            }else if( $year_ages == 0 && $month_age == 0 && $day_age == 0 ){
                                $year_age .= "{$day_age} วัน";
                            }
                        }else{
                            $year_age .= "N/A";
                        }

                    }else{
                        $year_age .= "N/A";
                    }
                    
                    if(  $total_age >= 0 && !empty($year_age) ){

                        if( $year_age == 'N/A' ){
                            return $year_age;
                        }else{
                            return '<span class="label '.(($total_age>=5)?'label-danger' : 'label-success') .'">'.( $year_age).'</span>';
                        }

                    }
                })
                ->rawColumns(['checkbox', 'action','tis_name','IsoCodeName', 'issue_date', 'StandardFormatName','product_group','StaffGroupName', 'ReviewStatusName', 'total_age'])
                ->make(true);

    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standard','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);
            // $filter['filter_number_book_year'] = $request->get('filter_number_book_year', '');
            $filter['filter_publish_date_start'] = $request->get('filter_publish_date_start', '');
            $filter['filter_publish_date_end'] = $request->get('filter_publish_date_end', '');
            $filter['filter_refer'] = $request->get('filter_refer', '');
            $filter['filter_set_format'] = $request->get('filter_set_format', '');
            $filter['filter_review_status'] = $request->get('filter_review_status', '');
            $filter['filter_product_group'] = $request->get('filter_product_group', '');
            $filter['filter_board_type'] = $request->get('filter_board_type', '');
            $filter['filter_staff_group'] = $request->get('filter_staff_group', '');
            $filter['filter_staff_responsible'] = $request->get('filter_staff_responsible', '');
            $filter['filter_gazette'] = $request->get('filter_gazette', '');

            $Query = new standard;

            if ($filter['filter_search']!='') {
                $Query = $Query->where(function ($query) use ($filter) {
                                $query->where('title', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhere('title_en', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhereRaw("CONCAT_WS('-',tis_no,tis_year) LIKE '%{$filter["filter_search"]}%'")
                                      ->orWhereRaw("CONCAT(tis_no,' เล่ม ',tis_book,'-',tis_year) LIKE '%{$filter["filter_search"]}%'")
                                      //   ->orWhere('tis_year', 'LIKE', "%{$filter['filter_search']}%")
                                      ->orWhere('tis_book', 'LIKE', "%{$filter['filter_search']}%");
                         });
            }

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            // if ($filter['filter_number_book_year']!='') {
            //     $Query = $Query->where(function ($query) use ($filter) {
            //                     $query->where('tis_no', 'LIKE', "%{$filter['filter_number_book_year']}%")
            //                           ->orWhere('tis_year', 'LIKE', "%{$filter['filter_number_book_year']}%")
            //                           ->orWhere('tis_book', 'LIKE', "%{$filter['filter_number_book_year']}%");
            //              });
            // }

            if ($filter['filter_publish_date_start']!='') {
                $Query = $Query->whereRaw('(CASE
                                            WHEN announce_compulsory="y" THEN issue_date_compulsory
                                            ELSE issue_date
                                          END) >= "'.Carbon::createFromFormat("d/m/Y",$filter['filter_publish_date_start'])->addYear(-543)->formatLocalized('%Y-%m-%d').'"');
            }

            if ($filter['filter_publish_date_end']!='') {
                $Query = $Query->whereRaw('(CASE
                                            WHEN announce_compulsory="y" THEN issue_date_compulsory
                                            ELSE issue_date
                                          END) <= "'.Carbon::createFromFormat("d/m/Y",$filter['filter_publish_date_end'])->addYear(-543)->formatLocalized('%Y-%m-%d').'"');
            }

            if ($filter['filter_refer']!='') {
                $Query = $Query->where('refer', 'LIKE', "%{$filter['filter_refer']}%");
            }

            if ($filter['filter_set_format']!='') {
                $Query = $Query->where('set_format_id', $filter['filter_set_format']);
            }

            if ($filter['filter_review_status']!='') {
                $Query = $Query->where('review_status', $filter['filter_review_status']);
            }

            if ($filter['filter_product_group']!='') {
                $Query = $Query->whereIn('product_group_id', $filter['filter_product_group']);
            }

            if ($filter['filter_board_type']!='') {
                $board_types = Appoint::whereIn('id', $filter['filter_board_type'])->pluck('id');
                $Query = $Query->whereIn('board_type_id', $board_types);
            }

            if ($filter['filter_staff_group']!='') {
                $Query = $Query->whereIn('staff_group_id', $filter['filter_staff_group']);
            }

            if ($filter['filter_staff_responsible']!='') {
                $Query = $Query->where('staff_responsible', 'LIKE', "%{$filter['filter_staff_responsible']}%");
            }

            if ($filter['filter_gazette']!='') {
                $Query = $Query->where('government_gazette', $filter['filter_gazette']);
            }

            $standard = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('tis.standard.index', compact('standard', 'filter'));
        }
        abort(403);

    }

    public function apiGetStandards() {
        $standards = standard::get();
        foreach ($standards as $standard) {
            $attachs = json_decode($standard['attach']);
            if (!is_null($attachs)&&count($attachs)>0) {
                foreach ($attachs as $attach) {
                    $attach->check = HP::checkFileStorage($this->attach_path.$attach->file_name);
                    $attach->href = HP::getFileStorage($this->attach_path.$attach->file_name);
                }
            } else {
                $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            }
            $standard->attaches = $attachs;
            $standard->refers = json_decode($standard['refer']);
        }
        return response()->json(compact('standards'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('standard','-');
        if(auth()->user()->can('add-'.$model)) {

            $refers = [''];
            $attachs = [['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;

            return view('tis.standard.create', compact('attachs', 'attach_path', 'refers'));

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
        $model = str_slug('standard','-');
        if(auth()->user()->can('add-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required',
        			// 'tis_force' => 'required',
        			// 'issue_date' => 'required'
		        ]);

            if ($request->has('announce_compulsory')) {
                $request->merge(['announce_compulsory' => 'y']);
            } else {
                $request->merge(['announce_compulsory' => 'n']);
            }

            if ($request->has('government_gazette')) {
                $request->merge(['government_gazette' => 'y']);
            } else {
                $request->merge(['government_gazette' => 'w']);
            }

            $requestData = $request->all();
            // dd($requestData['announce_compulsory']);
            $requestData['ics'] = !empty($request->ics)?json_encode($request->ics, JSON_UNESCAPED_UNICODE):null;
            $requestData['created_by'] = auth()->user()->getKey();//user create
            $requestData['refer'] = ($requestData['refer'][0]==null)?'[""]':json_encode($requestData['refer'], JSON_UNESCAPED_UNICODE);//ข้อมูลอ้างอิง

            $requestData['minis_dated'] = $request->minis_dated?Carbon::createFromFormat("d/m/Y",$request->minis_dated)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['issue_date'] = $request->issue_date?Carbon::createFromFormat("d/m/Y",$request->issue_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['gaz_date'] = $request->gaz_date?Carbon::createFromFormat("d/m/Y",$request->gaz_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            if($requestData['announce_compulsory']=='y'){

              $requestData['minis_dated_compulsory'] = $request->minis_dated_compulsory?Carbon::createFromFormat("d/m/Y",$request->minis_dated_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
              $requestData['issue_date_compulsory'] = $request->issue_date_compulsory?Carbon::createFromFormat("d/m/Y",$request->issue_date_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
              $requestData['gaz_date_compulsory'] = $request->gaz_date_compulsory?Carbon::createFromFormat("d/m/Y",$request->gaz_date_compulsory)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            } else {
              $requestData['minis_dated_compulsory'] = null;
              $requestData['issue_date_compulsory'] = null;
              $requestData['gaz_date_compulsory'] = null;

            }

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

            $requestData['attach'] = json_encode($attachs, JSON_UNESCAPED_UNICODE);

            $requestData['cancel_date'] = $request->cancel_date?Carbon::createFromFormat("d/m/Y",$request->cancel_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            if(  $request->hasfile('cancel_attach') ){

                $filecancel = $request->file('cancel_attach');

                //Upload File
                $storagePathCancel = Storage::put($this->attach_path, $filecancel );
                $storageNameCancel = basename($storagePathCancel); // Extract the filename


                $attachs_cancel[] = ['file_name'=>$storageNameCancel,
                                    'file_client_name' => $filecancel->getClientOriginalName(),
                                    'file_note'=> ''
                                ];
                $requestData['cancel_attach'] = json_encode(array_values($attachs_cancel), JSON_UNESCAPED_UNICODE);
            }


            standard::create($requestData);
            return redirect('tis/standard')->with('flash_message', 'เพิ่ม standard เรียบร้อยแล้ว');
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
        $model = str_slug('standard','-');
        if(auth()->user()->can('view-'.$model)) {
            $standard = standard::findOrFail($id);

            $standard['minis_dated'] = $standard['minis_dated']?Carbon::createFromFormat("Y-m-d",$standard['minis_dated'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['issue_date'] = $standard['issue_date']?Carbon::createFromFormat("Y-m-d",$standard['issue_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['gaz_date'] = $standard['gaz_date']?Carbon::createFromFormat("Y-m-d",$standard['gaz_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['cancel_date'] = $standard['cancel_date']?Carbon::createFromFormat("Y-m-d",$standard['cancel_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;

            if($standard['announce_compulsory']=='y'){

              $standard['minis_dated_compulsory'] = $standard['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
              $standard['issue_date_compulsory'] = $standard['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
              $standard['gaz_date_compulsory'] = $standard['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;

            }

            //ไฟล์แนบ
            $attachs = json_decode($standard['attach'], true);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;
            $refers = json_decode($standard['refer']);
            $set_std =   SetStandard::where(function ($query) use($standard) {
                                            $query->where('id', $standard->set_std_id);
                                        })
                                        ->get();
            return view('tis.standard.show', compact('standard', 'refers', 'attachs', 'attach_path','set_std'));
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
        $model = str_slug('standard','-');
        if(auth()->user()->can('edit-'.$model)) {

            $standard = standard::findOrFail($id);

            $standard['ics'] = !empty($standard['ics'])?json_decode($standard['ics'], true):[''];
            $standard['minis_dated'] = $standard['minis_dated']?Carbon::createFromFormat("Y-m-d",$standard['minis_dated'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['issue_date'] = $standard['issue_date']?Carbon::createFromFormat("Y-m-d",$standard['issue_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['gaz_date'] = $standard['gaz_date']?Carbon::createFromFormat("Y-m-d",$standard['gaz_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
            $standard['cancel_date'] = $standard['cancel_date']?Carbon::createFromFormat("Y-m-d",$standard['cancel_date'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;

            if($standard){
                $note_std_draft = NoteStdDraft::Where('standard_id',$standard->id)->first();

                if($standard['announce_compulsory']=='y' ){
                    // /* ** ประกาศกฤษฎีกา/ประกาศกฎกระทรวง ** */
                    // //ลงวันที่
                    // $standard['minis_dated_compulsory'] = $note_std_draft['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    // //วันที่มีผลบังคับใช้
                    // $standard['issue_date_compulsory'] = $note_std_draft['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    // //จำนวนวัน
                    // $standard['amount_date_compulsory'] = $note_std_draft['amount_date_compulsory']??null;

                    // /* ** ราชกิจจานุเบกษา ** */
                    // //วันที่ประกาศ
                    // $standard['gaz_date_compulsory'] = $note_std_draft['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    // //เล่ม
                    // $standard['gaz_no_compulsory'] = $note_std_draft['gaz_no_compulsory']??null;
                    // //ตอนที่
                    // $standard['gaz_space_compulsory'] = $note_std_draft['gaz_space_compulsory']??null;
                     //ลงวันที่
                    $standard['minis_dated_compulsory'] = $standard['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //วันที่มีผลบังคับใช้
                    $standard['issue_date_compulsory'] = $standard['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //วันที่ประกาศ
                    $standard['gaz_date_compulsory'] = $standard['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                }else{
                    //ลงวันที่
                    $standard['minis_dated_compulsory'] = $standard['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //วันที่มีผลบังคับใช้
                    $standard['issue_date_compulsory'] = $standard['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //วันที่ประกาศ
                    $standard['gaz_date_compulsory'] = $standard['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$standard['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                }
            }

            // dd($standard['announce_compulsory']);
            //ไฟล์แนบ
            $attachs = json_decode($standard['attach'], true);
            // dd($attachs);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];
            $attach_path = $this->attach_path;

            if(!empty($standard['refer'])){
                $pattern = array('/\v/','/\s\s+/');
                $preg_refer = preg_replace($pattern, "", $standard['refer']);
                $refers = json_decode($preg_refer, true);
            } else {
                $refers = [''];
            }

            // dd($refers);

            $set_std =   SetStandard::where(function ($query) use($standard) {
                                            $query->where('id', $standard->set_std_id);
                                        })
                                        ->get();

            return view('tis.standard.edit', compact('standard', 'refers', 'attachs', 'attach_path', 'set_std'));
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
        $model = str_slug('standard','-');
        if(auth()->user()->can('edit-'.$model)) {
            $this->validate($request, [
        			'title' => 'required',
        			'title_en' => 'required',
        			// 'tis_force' => 'required',
        			// 'issue_date' => 'required'
            ]);

            if ($request->has('announce_compulsory')) {
                $request->merge(['announce_compulsory' => 'y']);
            } else {
                $request->merge(['announce_compulsory' => 'n']);
            }

            if ($request->has('government_gazette')) {
                $request->merge(['government_gazette' => 'y']);
            } else {
                $request->merge(['government_gazette' => 'w']);
            }

            $standard = standard::findOrFail($id);

            $requestData = $request->all();
            $requestData['ics'] = !empty($request->ics)?json_encode($request->ics, JSON_UNESCAPED_UNICODE):null;
            $requestData['updated_by'] = auth()->user()->getKey();//user update
            $requestData['refer'] = ($requestData['refer'][0]==null)?'[""]':json_encode($requestData['refer'], JSON_UNESCAPED_UNICODE);//ข้อมูลอ้างอิง

            $requestData['minis_dated'] = $request->minis_dated?Carbon::createFromFormat("d/m/Y",$request->minis_dated)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['issue_date'] = $request->issue_date?Carbon::createFromFormat("d/m/Y",$request->issue_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;
            $requestData['gaz_date'] = $request->gaz_date?Carbon::createFromFormat("d/m/Y",$request->gaz_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            if($standard){
                $note_std_draft = NoteStdDraft::Where('standard_id',$standard->id)->first();

                if($standard['announce_compulsory']=='y' && !is_null($note_std_draft) ){
                    /* ** ประกาศกฤษฎีกา/ประกาศกฎกระทรวง ** */
                    //ลงวันที่
                    $standard['minis_dated_compulsory'] = $note_std_draft['minis_dated_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['minis_dated_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //วันที่มีผลบังคับใช้
                    $standard['issue_date_compulsory'] = $note_std_draft['issue_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['issue_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //จำนวนวัน
                    $standard['amount_date_compulsory'] = $note_std_draft['amount_date_compulsory']??null;

                    /* ** ราชกิจจานุเบกษา ** */
                    //วันที่ประกาศ
                    $standard['gaz_date_compulsory'] = $note_std_draft['gaz_date_compulsory']?Carbon::createFromFormat("Y-m-d",$note_std_draft['gaz_date_compulsory'])->addYear(543)->formatLocalized('%d/%m/%Y'):null;
                    //เล่ม
                    $standard['gaz_no_compulsory'] = $note_std_draft['gaz_no_compulsory']??null;
                    //ตอนที่
                    $standard['gaz_space_compulsory'] = $note_std_draft['gaz_space_compulsory']??null;
                }
            }
            // dd($standard);
          //  echo "<pre>"; var_dump($requestData);

            //ข้อมูลไฟล์แนบ
            $attachs = array_values((array)json_decode($standard->attach));

            // dd($attachs);

            //ไฟล์แนบ ที่ถูกกดลบ
            foreach ($attachs as $key => $attach) {

              if(in_array($attach->file_name, $requestData['attach_filenames'])===false){//ถ้าไม่มีไฟล์เดิมกลับมา
                unset($attachs[$key]);
                Storage::delete($this->attach_path.$attach->file_name);
              }
            }

           // ไฟล์แนบ ข้อความที่แก้ไข
            foreach ($attachs as $key => $attach) {
              $search_key = array_search($attach->file_name, $requestData['attach_filenames']);
              if($search_key!==false){
                $attach->file_note = $requestData['attach_notes'][$search_key];
              }
            }

            //ไฟล์แนบ เพิ่มเติม
            if ($files = $request->file('attachs')) {

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

            $requestData['cancel_date'] = $request->cancel_date?Carbon::createFromFormat("d/m/Y",$request->cancel_date)->addYear(-543)->formatLocalized('%Y-%m-%d'):null;

            if(  $request->hasfile('cancel_attach') ){

                if(!empty( $standard->cancel_attach )){
                    $odl =   !empty($standard->cancel_attach)?json_decode($standard->cancel_attach):null;
                    if( !is_null($odl) &&  !empty($odl->file_name) ){
                        Storage::delete($this->attach_path.$odl->file_name);
                    }
                }

                $filecancel = $request->file('cancel_attach');

                //Upload File
                $storagePathCancel = Storage::put($this->attach_path, $filecancel );
                $storageNameCancel = basename($storagePathCancel); // Extract the filename


                $attachs_cancel[] = ['file_name'=>$storageNameCancel,
                                    'file_client_name' => $filecancel->getClientOriginalName(),
                                    'file_note'=> ''
                                ];
                $requestData['cancel_attach'] = json_encode(array_values($attachs_cancel), JSON_UNESCAPED_UNICODE);
            }

            $requestData['attach'] = json_encode(array_values($attachs), JSON_UNESCAPED_UNICODE);

            $standard->update($requestData);

            return redirect('tis/standard')->with('flash_message', 'แก้ไข standard เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    public function downloadfileZip($id){
      $previousUrl = app('url')->previous();
      $public_dir = public_path("uploads/tis_attach/standard");
      $zipFileName = 'AllDocuments.zip';
      if(is_file($public_dir.'/'.$zipFileName)){
        File::delete($public_dir.'/'.$zipFileName);//ลบไฟล์ชั่วคราว
      }
        $standard = standard::findOrFail($id);
        //ข้อมูลไฟล์แนบ
        $attachs = array_values((array)json_decode($standard->attach));
        if($attachs) {
          // Create ZipArchive Obj
          $zip = new ZipArchive;
            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
              // Add Multiple file
              foreach($attachs as $attach) {
                  $zip->addFile($public_dir.'/'.$attach->file_name, $attach->file_name);
              }
              $zip->close();
            }
          return response()->download(public_path("uploads/tis_attach/standard/{$zipFileName}"));
        } else {
            return redirect($previousUrl)->with('error_message', 'ไม่พบไฟล์แนบในมาตรฐานนี้!');
        }
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
        $model = str_slug('standard','-');
        if(auth()->user()->can('delete-'.$model)) {

            $requestData = $request->all();

            if(array_key_exists('item_checkbox', $requestData)){
                $ids = $requestData['item_checkbox'];
                $db = new standard;
                standard::whereIn($db->getKeyName(), $ids)->delete();
            }else{
                standard::destroy($id);
            }

            return redirect('tis/standard')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('standard','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new standard;
          standard::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/standard')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    public function add_method_detail(Request $request)
    {
        $data_details = DB::table('basic_methods')->select('details')->where('id', $request->get('method_id'))->first();
          $data = explode(",",$data_details->details);
        return response()->json([
            "status" => "success",
            'data' => $data,
        ]);
    }


     public function export_excel(Request $request)
    {

        ini_set('memory_limit','1024M');

        $model = str_slug('standard', '-');
        if (auth()->user()->can('view-' . $model)) {

            //Create Spreadsheet Object
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            //หัวรายงาน
            $sheet->setCellValue('A1', 'ข้อมูลดิบตารางมาตรฐาน');
            $sheet->mergeCells('A1:AO1');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            $sheet->getStyle("A1")->getFont()->setSize(18);


            //แสดงวันที่
            $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ ' . HP::DateTimeFullThai(date('Y-m-d H:i')));
            $sheet->mergeCells('A2:AO2');
            $sheet->getStyle('A2:AO2')->getAlignment()->setHorizontal('right');

            //หัวตาราง
            $sheet->setCellValue('A3', 'id');
            $sheet->setCellValue('B3', 'title');
            $sheet->setCellValue('C3', 'title_en');
            $sheet->setCellValue('D3', 'tis_force');
            $sheet->setCellValue('E3', 'issue_date');
            $sheet->setCellValue('F3', 'amount_date');
            $sheet->setCellValue('G3', 'gaz_date');
            $sheet->setCellValue('H3', 'gaz_no');
            $sheet->setCellValue('I3', 'gaz_space');
            $sheet->setCellValue('J3', 'tis_no');
            $sheet->setCellValue('K3', 'tis_year');
            $sheet->setCellValue('L3', 'tis_book');
            $sheet->setCellValue('M3', 'remark');
            $sheet->setCellValue('N3', 'board_type_id');
            $sheet->setCellValue('O3', 'standard_type_id');
            $sheet->setCellValue('P3', 'standard_format_id');
            $sheet->setCellValue('Q3', 'set_format_id');
            $sheet->setCellValue('R3', 'method_id');
            $sheet->setCellValue('S3', 'method_id_detail');
            $sheet->setCellValue('T3', 'product_group_id');
            $sheet->setCellValue('U3', 'industry_target_id');
            $sheet->setCellValue('V3', 'staff_group_id');
            $sheet->setCellValue('W3', 'staff_responsible');
            $sheet->setCellValue('X3', 'refer');
            $sheet->setCellValue('Y3', 'attach');
            $sheet->setCellValue('Z3', 'state');
            $sheet->setCellValue('AA3', 'created_at');
            $sheet->setCellValue('AB3', 'updated_at');
            $sheet->setCellValue('AC3', 'created_by');
            $sheet->setCellValue('AD3', 'updated_by');
            $sheet->setCellValue('AE3', 'review_status');
            $sheet->setCellValue('AF3', 'ics');
            $sheet->setCellValue('AG3', 'isbn');
            $sheet->setCellValue('AH3', 'minis_dated');
            $sheet->setCellValue('AI3', 'minis_dated_compulsory');
            $sheet->setCellValue('AJ3', 'issue_date_compulsory');
            $sheet->setCellValue('AK3', 'minis_no_compulsory');
            $sheet->setCellValue('AL3', 'gaz_date_compulsory');
            $sheet->setCellValue('AM3', 'gaz_no_compulsory');
            $sheet->setCellValue('AN3', 'gaz_space_compulsory');
            $sheet->setCellValue('AO3', 'announce_compulsory');
            $sheet->getStyle('A3:AO3')->getAlignment()->setHorizontal('center');
            $sheet->getStyle('A3:AO3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('95DCFF');

            //รายการมาตรฐานทั้งหมด
            $Query = new standard;
            $items = $Query->sortable()->get()->toArray();

            $appoint_arr = Appoint::selectRaw('CONCAT(board_position," ",title) As title, id')->pluck('title','id')->toArray();
            $standard_type_arr = StandardType::selectRaw('CONCAT(title," (",acronym,")") As title, id')->pluck('title', 'id')->toArray();
            $standard_format_arr = StandardFormat::pluck('title', 'id')->toArray();
            $set_format_arr = SetFormat::pluck('title', 'id')->toArray();
            $method_arr = Method::pluck('title', 'id')->toArray();

            $product_group_arr = ProductGroup::pluck('title', 'id')->toArray();
            $industry_target_arr = IndustryTarget::pluck('title', 'id')->toArray();
            $staff_group_arr = StaffGroup::pluck('order', 'id')->toArray();
            // dd($items);

            $row = 3; //start row
            foreach ($items as $item) {
              $method_details = Method::where('id',$item['method_id'])->first();
              // dd($method_details->details);
              $method_details_value = $method_details->details??null;
              $method_detail_arr = !empty($method_details_value)?explode(",",$method_details_value):[];
              // dd($method_detail_arr);
                $row++;
                $sheet->setCellValue('A' . $row, $item['id']);
                $sheet->setCellValue('B' . $row, $item['title']);
                $sheet->setCellValue('C' . $row, $item['title_en']);
                $sheet->setCellValue('D' . $row, $item['tis_force']);
                $sheet->setCellValue('E' . $row, $item['issue_date']);
                $sheet->setCellValue('F' . $row, $item['amount_date']);
                $sheet->setCellValue('G' . $row, $item['gaz_date']);
                $sheet->setCellValue('H' . $row, $item['gaz_no']);
                $sheet->setCellValue('I' . $row, $item['gaz_space']);
                $sheet->setCellValue('J' . $row, $item['tis_no']);
                $sheet->setCellValue('K' . $row, $item['tis_year']);
                $sheet->setCellValue('L' . $row, $item['tis_book']);
                $sheet->setCellValue('M' . $row, $item['remark']);
                $sheet->setCellValue('N' . $row, array_key_exists($item['board_type_id'],$appoint_arr)?$appoint_arr[$item['board_type_id']]:'n/a');
                $sheet->setCellValue('O' . $row, array_key_exists($item['standard_type_id'],$standard_type_arr)?$standard_type_arr[$item['standard_type_id']]:'n/a');
                $sheet->setCellValue('P' . $row, array_key_exists($item['standard_format_id'],$standard_format_arr)?$standard_format_arr[$item['standard_format_id']]:'n/a');
                $sheet->setCellValue('Q' . $row, array_key_exists($item['set_format_id'],$set_format_arr)?$set_format_arr[$item['set_format_id']]:'n/a');
                $sheet->setCellValue('R' . $row, array_key_exists($item['method_id'],$method_arr)?$method_arr[$item['method_id']]:'n/a');
                $sheet->setCellValue('S' . $row, array_key_exists($item['method_id_detail'],$method_detail_arr)?$method_detail_arr[$item['method_id_detail']]:'n/a');
                $sheet->setCellValue('T' . $row, array_key_exists($item['product_group_id'],$product_group_arr)?$product_group_arr[$item['product_group_id']]:'n/a');
                $sheet->setCellValue('U' . $row, array_key_exists($item['industry_target_id'],$industry_target_arr)?$industry_target_arr[$item['industry_target_id']]:'n/a');
                $sheet->setCellValue('V' . $row, array_key_exists($item['staff_group_id'],$staff_group_arr)?$staff_group_arr[$item['staff_group_id']]:'n/a');
                $sheet->setCellValue('W' . $row, $item['staff_responsible']);
                $sheet->setCellValue('X' . $row, $item['refer']);
                $sheet->setCellValue('Y' . $row, $item['attach']);
                $sheet->setCellValue('Z' . $row, $item['state']);
                $sheet->setCellValue('AA' . $row, $item['created_at']);
                $sheet->setCellValue('AB' . $row, $item['updated_at']);
                $sheet->setCellValue('AC' . $row, $item['created_by']);
                $sheet->setCellValue('AD' . $row, $item['updated_by']);
                $sheet->setCellValue('AE' . $row, $item['review_status']);
                $sheet->setCellValue('AF' . $row, $item['ics']);
                $sheet->setCellValue('AG' . $row, $item['isbn']);
                $sheet->setCellValue('AH' . $row, $item['minis_dated']);
                $sheet->setCellValue('AI' . $row, $item['minis_dated_compulsory']);
                $sheet->setCellValue('AJ' . $row, $item['issue_date_compulsory']);
                $sheet->setCellValue('AK' . $row, $item['minis_no_compulsory']);
                $sheet->setCellValue('AL' . $row, $item['gaz_date_compulsory']);
                $sheet->setCellValue('AM' . $row, $item['gaz_no_compulsory']);
                $sheet->setCellValue('AN' . $row, $item['gaz_space_compulsory']);
                $sheet->setCellValue('AO' . $row, $item['announce_compulsory']);
            }

            //Set Border Style
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ]
            ];
            $sheet->getStyle('A3:' . 'AO' . $row)->applyFromArray($styleArray);

            //Set Text Top
            $sheet->getStyle('A3:' . 'AO' . $row)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

            //Set Column Width
            $sheet->getColumnDimension('A')->setAutoSize(true);
            $sheet->getColumnDimension('B')->setAutoSize(true);
            $sheet->getColumnDimension('C')->setAutoSize(true);
            $sheet->getColumnDimension('D')->setAutoSize(true);
            $sheet->getColumnDimension('E')->setAutoSize(true);
            $sheet->getColumnDimension('F')->setAutoSize(true);
            $sheet->getColumnDimension('G')->setAutoSize(true);
            $sheet->getColumnDimension('H')->setAutoSize(true);
            $sheet->getColumnDimension('I')->setAutoSize(true);
            $sheet->getColumnDimension('J')->setAutoSize(true);
            $sheet->getColumnDimension('K')->setAutoSize(true);
            $sheet->getColumnDimension('L')->setAutoSize(true);
            $sheet->getColumnDimension('M')->setAutoSize(true);
            $sheet->getColumnDimension('N')->setAutoSize(true);
            $sheet->getColumnDimension('O')->setAutoSize(true);
            $sheet->getColumnDimension('P')->setAutoSize(true);
            $sheet->getColumnDimension('Q')->setAutoSize(true);
            $sheet->getColumnDimension('R')->setAutoSize(true);
            $sheet->getColumnDimension('S')->setAutoSize(true);
            $sheet->getColumnDimension('T')->setAutoSize(true);
            $sheet->getColumnDimension('U')->setAutoSize(true);
            $sheet->getColumnDimension('V')->setAutoSize(true);
            $sheet->getColumnDimension('W')->setAutoSize(true);
            $sheet->getColumnDimension('X')->setAutoSize(true);
            $sheet->getColumnDimension('Y')->setAutoSize(true);
            $sheet->getColumnDimension('Z')->setAutoSize(true);
            $sheet->getColumnDimension('AA')->setAutoSize(true);
            $sheet->getColumnDimension('AB')->setAutoSize(true);
            $sheet->getColumnDimension('AC')->setAutoSize(true);
            $sheet->getColumnDimension('AD')->setAutoSize(true);
            $sheet->getColumnDimension('AE')->setAutoSize(true);
            $sheet->getColumnDimension('AF')->setAutoSize(true);
            $sheet->getColumnDimension('AG')->setAutoSize(true);
            $sheet->getColumnDimension('AH')->setAutoSize(true);
            $sheet->getColumnDimension('AI')->setAutoSize(true);
            $sheet->getColumnDimension('AJ')->setAutoSize(true);
            $sheet->getColumnDimension('AK')->setAutoSize(true);
            $sheet->getColumnDimension('AL')->setAutoSize(true);
            $sheet->getColumnDimension('AM')->setAutoSize(true);
            $sheet->getColumnDimension('AN')->setAutoSize(true);
            $sheet->getColumnDimension('AO')->setAutoSize(true);

            $filename = 'Standard_' . date('Hi_dmY') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
            $writer->save("php://output");

            exit;

        }

    }

     private function getQuery($request)
    {

        $filter = [];
        $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['perPage'] = $request->get('perPage', 10);
            $filter['filter_publish_date_start'] = $request->get('filter_publish_date_start', '');
            $filter['filter_publish_date_end'] = $request->get('filter_publish_date_end', '');
            $filter['filter_refer'] = $request->get('filter_refer', '');
            $filter['filter_set_format'] = $request->get('filter_set_format', '');
            $filter['filter_review_status'] = $request->get('filter_review_status', '');
            $filter['filter_product_group'] = $request->get('filter_product_group', '');
            $filter['filter_board_type'] = $request->get('filter_board_type', '');
            $filter['filter_staff_group'] = $request->get('filter_staff_group', '');
            $filter['filter_staff_responsible'] = $request->get('filter_staff_responsible', '');
            $filter['filter_gazette'] = $request->get('filter_gazette', '');


        $Query = new Standard();

        return $Query;

    }


}
