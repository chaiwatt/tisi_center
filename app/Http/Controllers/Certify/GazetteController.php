<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Certify\Standard;
use App\Models\Certify\StandardLog;
use App\Models\Certify\Gazette;
use App\Models\Certify\GazetteStandard;
use Illuminate\Http\Request;
use App\Nac;
use Yajra\Datatables\Datatables;
use HP;
use DB;

class GazetteController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/certify_gazette/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */


    public function index(Request $request)
    {
        $model = str_slug('gazette','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.gazette.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

        $model = str_slug('gazette', '-');
        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');

        $query = Gazette::query()->when($not_admin, function ($query){
                                        return $query->where('created_by', auth()->user()->getKey());
                                    })
                                    ->when($filter_search, function ($query, $filter_search){
                                            $search_full = str_replace(' ', '', $filter_search );
                                            $query->where(function ($query2) use($search_full) {
                                            $query2->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                    ->OrWhere(DB::raw("REPLACE(gazette_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                    ->OrWhere(DB::raw("REPLACE(gazette_book,' ','')"), 'LIKE', "%".$search_full."%")
                                                    ->OrWhere(DB::raw("REPLACE(gazette_space,' ','')"), 'LIKE', "%".$search_full."%");
                                                    });
                                    })->when($filter_state, function ($query, $filter_state){
                                               $query->where('state', $filter_state);
                                         });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                if($item->state == 99){
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                                }else{
                                    return '';
                                }
                            })
                            ->addColumn('title', function ($item) {
                                return   !empty($item->title)? $item->title:'';
                            })
                            ->addColumn('gazette_book', function ($item) {
                                $gazette_book = '';
                                $gazette_book .=  !empty($item->gazette_book)? $item->gazette_book:'-';
                                $gazette_book .=  !empty($item->gazette_no)? ' เล่ม '.$item->gazette_no:'-';
                                $gazette_book .=  !empty($item->gazette_space)? ' ตอน '.$item->gazette_space:'-';

                                return  $gazette_book;
                            })
                            ->addColumn('gazette_date', function ($item) {
                                return  !empty($item->gazette_date)? HP::DateThai($item->gazette_date):'';   
                            })
                            ->addColumn('enforce_date', function ($item) {
                                return  !empty($item->enforce_date)? HP::DateThai($item->enforce_date):'';   
                            })
                            ->addColumn('state', function ($item) {
                                return  $item->stateIcon;
                            })
                            ->addColumn('action', function ($item) use($model) {
                                if($item->state == 99){
                                    return HP::buttonAction( $item->id, 'certify/gazette','Certify\\GazetteController@destroy', 'gazette',true,true,true);
                                }else{
                                    return HP::buttonAction( $item->id, 'certify/gazette','Certify\\GazetteController@destroy', 'gazette',true,true,false);
                                }

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'certificate_type', 'state','action'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('gazette','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('certify.gazette.create');
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
        $model = str_slug('gazette','-');
        if(auth()->user()->can('add-'.$model)) {
            
            $requestData = $request->all();
            $requestData['created_by']       =  auth()->user()->getKey();
            $requestData['gazette_date']    =  !empty($request->gazette_date) ?  HP::convertDate($request->gazette_date,true) : null;
            $requestData['enforce_date']    =  !empty($request->enforce_date)   ?  HP::convertDate($request->enforce_date,true)   : null;
            $requestData['send_tis']        =  !empty($request->send_tis)   ? '1' :  '0';  
            
            $gazette = Gazette::create($requestData);

            $standard_id = !empty( $requestData['standard_id'])?$requestData['standard_id']:null;
            if(!empty($standard_id) && count($standard_id) > 0){
                $this->save_standard($standard_id, $gazette);
            }

            if(isset($requestData['file_gazette'])){
                if ($request->hasFile('file_gazette')) {
                    HP::singleFileUpload(
                        $request->file('file_gazette') ,
                        $this->attach_path,
                        !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new Gazette)->getTable() ),
                         $gazette->id,
                        'file_gazette',
                        'ไฟล์ประกาศในราชกิจจานุเบกษา'
                    );
                }
            }
            
            return redirect('certify/gazette')->with('flash_message', 'เพิ่ม gazette เรียบร้อยแล้ว');
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
        $model = str_slug('gazette','-');
        if(auth()->user()->can('view-'.$model)) {
            $gazette = gazette::findOrFail($id);
            $gazette->gazette_date = HP::revertDate($gazette->gazette_date,true);
            $gazette->enforce_date = HP::revertDate($gazette->enforce_date,true);
            return view('certify.gazette.show', compact('gazette'));
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
        $model = str_slug('gazette','-');
        if(auth()->user()->can('edit-'.$model)) {
            $gazette = Gazette::findOrFail($id);
            $gazette->gazette_date = HP::revertDate($gazette->gazette_date,true);
            $gazette->enforce_date = HP::revertDate($gazette->enforce_date,true);
            
            $gazette_standard  = GazetteStandard::where('gazette_id',$gazette->id)->pluck('standard_id');

            return view('certify.gazette.edit', compact('gazette','gazette_standard'));
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
        $model = str_slug('gazette','-');
        if(auth()->user()->can('edit-'.$model)) {
            $gazette = Gazette::findOrFail($id);

            $requestData = $request->all();
            $requestData['gazette_date']  =  !empty($request->gazette_date) ?  HP::convertDate($request->gazette_date,true) : null;
            $requestData['enforce_date']    =  !empty($request->enforce_date)   ?  HP::convertDate($request->enforce_date,true)   : null;
            $requestData['updated_by']  =  auth()->user()->getKey();
            $requestData['send_tis']        =  !empty($request->send_tis)   ? '1' :  '0'; 
            $gazette->update($requestData);

            $standard_id = !empty( $requestData['standard_id'])?$requestData['standard_id']:null;
            if(!empty($standard_id) && count($standard_id) > 0){
                $this->save_standard($standard_id, $gazette);
            }

            if(isset($requestData['file_gazette'])){
                if ($request->hasFile('file_gazette')) {
                    HP::singleFileUpload(
                        $request->file('file_gazette') ,
                        $this->attach_path,
                        !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new Gazette)->getTable() ),
                         $gazette->id,
                        'file_gazette',
                        'ไฟล์ประกาศในราชกิจจานุเบกษา'
                    );
                }
            }

            return redirect('certify/gazette')->with('flash_message', 'แก้ไข gazette เรียบร้อยแล้ว!');
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
        $model = str_slug('gazette','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new Gazette;
            Gazette::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            Gazette::destroy($id);
          }

          return redirect('certify/gazette')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

        //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = str_slug('gazette', '-');
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $state = $request->input('state');
            $result = Gazette::where('id', $id)->update(['state' => $state]);

            if($result) {
                return 'success';
            } else {
                return "not success";
            }

        }else{
            abort(403);
        }

    }


    //เลือกเผยแพร่หรือไม่เผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');

        $result = Gazette::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('gazette','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new gazette;
          gazette::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('certify/gazette')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

    private function save_standard($standard_id, $gazette){
        if(!empty($standard_id) && count($standard_id) > 0){

            GazetteStandard::where('gazette_id', $gazette->id)->delete();
            foreach($standard_id as $key => $item) {
                $input = [];
                $input['gazette_id']     = $gazette->id;
                $input['standard_id']    = $item;
                $input['created_by']     = auth()->user()->getKey();
                GazetteStandard::create($input);

                $standard = Standard::where('id', $item)->first();
                if(!is_null($standard)){
                    $standard->status_id                = 9;
                    $standard->publish_state            =  2;
                    $standard->gazette_state            =  1;
                    $standard->gazette_govbook          =  $gazette->gazette_govbook;
                    $standard->gazette_book             =  $gazette->gazette_book;
                    $standard->gazette_section          =  $gazette->gazette_space;
                    $standard->gazette_no               =  $gazette->gazette_no;
                    $standard->gazette_post_date        =  $gazette->gazette_date;
                    $standard->gazette_effective_date   =  $gazette->enforce_date;
                    $standard->save();

                    $nac = Nac::where('Nac_no', $standard->std_full)->first();
                    if(is_null($nac)){  
                    $nac = new Nac;
                    }
                    $nac->Nac_shortNo                   =  $standard->std_no;
                    $nac->Nac_no                        =  $standard->std_full;
                    $nac->Nac_Thainame                  =  $standard->std_title;
                    $nac->Nac_Engname                   =  $standard->std_title_en;
                    $nac->Nac_Gazbook                   =  $standard->std_force == 'ท' ? 'ประกาศและงานทั่วไป' : 'ประกาศและงานบังคับ';
                    $nac->Nac_Gazno                     =  $standard->gazette_no;
                    $nac->Nac_Gazspace                  =  $standard->gazette_section;
                    $nac->Nac_Gazdate                   =  $standard->gazette_post_date;
                    $nac->Nac_Govnotifbook              =  $standard->gazette_book;
                    $nac->Nac_Govnotifdate              =  $standard->gazette_effective_date;
                    $nac->Nac_force                     =  $standard->std_force ;
                    // $nac->Nac_file                      =  null;
                    $nac->Nac_thai_abstract             =  $standard->std_abstract;
                    $nac->Nac_eng_abstract              =  $standard->std_abstract_en;
                    // $nac->Nac_enforce                   =   null;
                    // $nac->Nac_productgroup              =   null;
                    // $nac->Nac_type                      =   null;
                    // $nac->Nac_tsic                      =   null;
                    // $nac->Nac_isic                      =   null;
                    // $nac->Nac_udc                       =   null;
                    $nac->Nac_ics                       =  count($standard->standard_ics_many) > 0  ?   implode(",",$standard->StandardIcsTitle) : null;
                    $nac->Nac_isbn                      =   $standard->isbn_no;
                    $nac->Nac_tc                        =   $standard->confirm_time;
                    // $nac->Nac_historyRemark             =   null;
                    // $nac->Nac_std_equivalent            =   null;
                    // $nac->Nac_std_inter                 =   null;
                    $nac->Nac_price                     =  $standard->std_price;
                    $nac->Nac_page                      =  $standard->std_page;
                    // $nac->Quality                       =   null;
                    // $nac->in_catalog                    =   null;
                    // $nac->add_p_by                      =   null;
                    // $nac->bcg                           =   null;
                    // $nac->sustainable                   =   null;
                    $nac->save();

                    $log = new StandardLog;
                    $log->std_id                         =  $standard->id;
                    $log->std_full                       =  $standard->std_full;
                    $log->created_by                     = auth()->user()->getKey();
                    $log->save();
                }
 
    
 


            }

        }
    }

    public function get_json_by_standard($std_type_id=null){
        $lit_id  = GazetteStandard::select('standard_id');
        $certify_standards =  Standard::when($std_type_id, function($query, $std_type_id){
                                        $query->where('std_type', $std_type_id);
                                        })
                                        ->when($lit_id, function ($query, $lit_id){
                                         $query->whereNotIn('id', $lit_id);
                                        })
                                        ->where('status_id',8)
                                        ->get();
    
            return response()->json($certify_standards);
        }
    

}
