<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Tis\EstandardOffers;
use App\Models\Tis\TisiEstandardDraft;
use App\Models\Tis\TisiEstandardDraftBoard;
use App\Models\Tis\TisiEstandardDraftCommittee;
use App\Models\Tis\TisiEstandardDraftPlan;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use HP;
use DB;
use Storage;
use App\Models\Bcertify\Reason;
class StandardDraftsController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/standarddrafts';
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('certify.standard-drafts.index');
        }
        abort(403);

    }

    public function data_list(Request $request)
    {
        $roles =  !empty(auth()->user()->roles) ? auth()->user()->roles->pluck('id')->toArray() : []; 
        $not_admin = (!in_array(1, $roles) && !in_array(25, $roles));  // ไม่ใช่ Admin หรือไม่ใช่ ผอ.

        $model = str_slug('standarddrafts', '-');
        $filter_search = $request->input('filter_search');
        $filter_year = $request->input('filter_year');
        $filter_standard_type = $request->input('filter_standard_type');
        $filter_method_id = $request->input('filter_method_id');
        $filter_status = $request->input('filter_status');
        
        $query = TisiEstandardDraft::query()->with([
                                                'TisiEstandardDraftCommitteeMany.committee_specials'
                                            ])
                                            ->when($not_admin, function ($query){
                                                return $query->where(function ($query){
                                                    return $query->whereHas('TisiEstandardDraftPlanMany', function($query){
                                                                    return $query->where('assign_id', auth()->user()->getKey());
                                                                })->orWhere('created_by', auth()->user()->getKey());
                                                });
                                            })
                                            ->when($filter_search, function ($query, $filter_search){
                                                 $search_full = str_replace(' ', '', $filter_search );
                                                  $query->where(function ($query2) use($search_full) {
                                                        $draft_plan = TisiEstandardDraftPlan::select('draft_id')
                                                                    ->Where(DB::raw("REPLACE(tis_number,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tis_book,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tis_year,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tis_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(tis_name_eng,' ','')"), 'LIKE', "%".$search_full."%")
                                                                    ->OrWhere(DB::raw("REPLACE(confirm_time,' ','')"), 'LIKE', "%".$search_full."%") ;
                                                        $query2->whereIn('id', $draft_plan)
                                                        ->orWhereHas('TisiEstandardDraftCommitteeMany.committee_specials', function($query) use($search_full){
                                                            $query->where(DB::raw("REPLACE(committee_group,' ','')"), 'LIKE', "%".$search_full."%");
                                                        });
                                                  });
                                              })
                                            ->when($filter_year, function ($query, $filter_year){
                                                  $query->where('draft_year', $filter_year);
                                              })
                                              ->when($filter_standard_type, function ($query, $filter_standard_type){
                                                    $draft_plan = TisiEstandardDraftPlan::select('draft_id')->where('std_type', $filter_standard_type);
                                                    $query->whereIn('id', $draft_plan);
                                              })
                                              ->when($filter_method_id, function ($query, $filter_method_id){
                                                    $draft_plan = TisiEstandardDraftPlan::select('draft_id')->where('method_id', $filter_method_id);
                                                    $query->whereIn('id', $draft_plan);
                                               })
                                               ->when($filter_status, function ($query, $filter_status){
                                                     $query->where('status_id', $filter_status);
                                               })
                                              ;


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                $draft_plan = TisiEstandardDraftPlan::where('draft_id', $item->id)->where('status_id','>','1')->first();
                                if(!is_null($draft_plan)){
                                    return '';
                                }else{
                                    return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                                }

                            })
                            ->addColumn('committee_group', function ($item) {
                                return   !empty($item->CommitteeTitle)? $item->CommitteeTitle:'';
                            })
                            ->addColumn('draft_year', function ($item) {
                                return   !empty($item->draft_year)? $item->draft_year:'';
                            })
                            ->addColumn('draft_year', function ($item) {
                                return   !empty($item->draft_year)? $item->draft_year:'';
                            })
                            ->addColumn('quantity', function ($item) {
                                return  count($item->TisiEstandardDraftPlanMany).' รายการ';
                            })
                            ->addColumn('assign', function ($item) {
                                return @$item->AssignName;
                            })
                            ->addColumn('created_name', function ($item) {
                                $users =   !empty($item->user_created->FullName)? $item->user_created->FullName:'';
                                $users .= !empty($item->created_at)?   '<br/>'.HP::DateThai($item->created_at):'';
                                return $users;
                            })
                            ->addColumn('status_name', function ($item) {
                                return !empty($item->StatusName)? $item->StatusName:'';
                            })
                            ->addColumn('action', function ($item) use($model) {
                                   $draft_plan = TisiEstandardDraftPlan::where('draft_id', $item->id)->where('status_id','>','1')->first();
                                   $edit = true;
                                   $delete = true;
                                   if(!is_null($draft_plan)){
                                        $delete = false;
                                   }
                                   if($item->status_id != 1){
                                        $edit = false;
                                   }
                                return HP::buttonAction( $item->id, 'certify/standard-drafts','Certify\\StandardDraftsController@destroy', 'standarddrafts',true, $edit, $delete);

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns([ 'checkbox','created_name', 'action', 'assign'])
                            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('add-'.$model)) {
            $estandard_draft_plans = collect([new TisiEstandardDraftPlan]);
            return view('certify.standard-drafts.create', compact('estandard_draft_plans'));
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
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData               = $request->all();
            $requestData['created_by'] = auth()->user()->getKey();
            $draft                     = TisiEstandardDraft::create($requestData);

            // คณะกรรมการเฉพาะด้าน
            self::save_tisi_estandard_draft_board($request->board, $draft->id);

            self::save_tisi_estandard_draft_plan($requestData['list'], $draft->id);

            return redirect('certify/standard-drafts')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว');
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
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('view-'.$model)) {
            $standarddraft = TisiEstandardDraft::findOrFail($id);

            if(count($standarddraft->TisiEstandardDraftCommitteeMany) > 0){
                $standarddraft->board = $standarddraft->TisiEstandardDraftCommitteeMany->pluck('committee_id');
            }else{
                $standarddraft->board = null;
            }

            $estandard_draft_plans = $standarddraft->TisiEstandardDraftPlanMany;//รายการมาตรฐาน
            $estandard_draft_plans = count($estandard_draft_plans) > 0 ? $estandard_draft_plans : collect([new TisiEstandardDraftPlan]) ;
            return view('certify.standard-drafts.show',compact('standarddraft', 'estandard_draft_plans'));
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
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('edit-'.$model)) {

            $standarddraft = TisiEstandardDraft::findOrFail($id);

            if(count($standarddraft->TisiEstandardDraftCommitteeMany) > 0){
                $standarddraft->board = $standarddraft->TisiEstandardDraftCommitteeMany->pluck('committee_id');
            }else{
                $standarddraft->board = null;
            }

            $estandard_draft_plans = $standarddraft->TisiEstandardDraftPlanMany;//รายการมาตรฐาน
            $estandard_draft_plans = count($estandard_draft_plans) > 0 ? $estandard_draft_plans : collect([new TisiEstandardDraftPlan]) ;

            return view('certify.standard-drafts.edit', compact('standarddraft', 'estandard_draft_plans'));
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
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();
            $requestData['updated_by'] =  auth()->user()->getKey();
            $standarddraft = TisiEstandardDraft::findOrFail($id);
            $standarddraft->update($requestData);

            // คณะกรรมการเฉพาะด้าน
            self::save_tisi_estandard_draft_board($request->board, $standarddraft->id);

            self::save_tisi_estandard_draft_plan($requestData['list'], $standarddraft->id);

            return redirect('certify/standard-drafts')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
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

    public function destroy($id)
    {
        $model = str_slug('standarddrafts','-');
        if(auth()->user()->can('delete-'.$model)) {
            TisiEstandardDraft::destroy($id);
            TisiEstandardDraftBoard::where('draft_id', $id)->delete();
            TisiEstandardDraftPlan::where('draft_id', $id)->delete();
            return redirect('certify/standard-drafts')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $student = TisiEstandardDraft::whereIn('id', $id_array);
        TisiEstandardDraftBoard::whereIn('draft_id', $id_array)->delete();
        TisiEstandardDraftPlan::whereIn('draft_id', $id_array)->delete();
        if($student->delete())
        {
            echo 'Data Deleted';
        }

    }


    //เลือกเผยแพร่สถานะได้ทีละครั้ง
    public function update_status(Request $request)
    {
        $model = str_slug('standarddrafts', '-');
        if(auth()->user()->can('edit-'.$model)) {

            $id = $request->input('id');
            $state = $request->input('state');
            $result = TisiEstandardDraft::where('id', $id)->update(['state' => $state]);

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

        $result = TisiEstandardDraft::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }

    public function update_assign(Request $request)
    {
        $arr_publish = $request->input('id_publish');
        $assign      = $request->input('assign');
        $result     = TisiEstandardDraftPlan::whereIn('draft_id', $arr_publish)->update(['assign_id' => $assign,'assign_date'=> date('Y-m-d H:i:s')]);
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }

    }


    // คณะกรรมการเฉพาะด้าน
    public function save_tisi_estandard_draft_board($datas, $id){
        TisiEstandardDraftCommittee::where('draft_id', $id)->delete();
        if(is_array($datas) && count($datas) > 0){
            foreach($datas as $key => $item) {
                $input = [];
                $input['draft_id']        = $id;
                $input['committee_id']    = $item;
                $input['created_by']      = auth()->user()->runrecno;
                TisiEstandardDraftCommittee::create($input);
            }
        }
    }

          // คณะกรรมการเฉพาะด้าน
    public function save_tisi_estandard_draft_plan($datas, $id){

        foreach($datas['estandard_draft_plan_id'] as $key => $item) {
            $input = [];
            $input['draft_id']        = $id;
            $input['std_type']        = array_key_exists($key, $datas['std_type'])        ? $datas['std_type'][$key] : null ;
            $input['start_std']       = !empty($datas['start_std'][$key]) && array_key_exists($key, $datas['start_std'])   ? $datas['start_std'][$key] : 1 ;
            $input['ref_std']         = !empty($datas['ref_std'][$key]) &&  array_key_exists($key,$datas['ref_std'])        ? $datas['ref_std'][$key] : null ;
            $input['tis_number']      = array_key_exists($key, $datas['tis_number'])      ? $datas['tis_number'][$key] : null ;
            $input['tis_book']        = array_key_exists($key, $datas['tis_book'])        ? $datas['tis_book'][$key] : null ;
            $input['tis_year']        = array_key_exists($key, $datas['tis_year'])        ? $datas['tis_year'][$key] : null ;
            $input['tis_name']        = array_key_exists($key, $datas['tis_name'])        ? $datas['tis_name'][$key] : null ;
            $input['tis_name_eng']    = array_key_exists($key, $datas['tis_name_eng'])    ? $datas['tis_name_eng'][$key] : null ;
            $input['method_id']       = array_key_exists($key, $datas['method_id'])       ? $datas['method_id'][$key] : null ;
            $input['ref_document']    = array_key_exists($key, $datas['ref_document'])       ? $datas['ref_document'][$key] : null ;
            $input['reason']          = array_key_exists($key, $datas['reason'])       ? $datas['reason'][$key] : null ;
            $input['confirm_time']    = array_key_exists($key, $datas['confirm_time'])    ? $datas['confirm_time'][$key] : null ;
            $input['industry_target'] = array_key_exists($key, $datas['industry_target']) ? $datas['industry_target'][$key] : null ;
            $input['assign_id']       = array_key_exists($key, $datas['assign_id'])       ? $datas['assign_id'][$key] : null ;
            $input['assign_date']     = date('Y-m-d H:i:s') ;

            $draft_plan = TisiEstandardDraftPlan::find($item);
            if(!is_null($draft_plan)){
                $draft_plan->update($input);
            }else{
                $input['status_id'] = 1; //ร่างแผน
                $input['created_by'] = auth()->user()->runrecno;

                $draft_plan = TisiEstandardDraftPlan::create($input);
            }

            //ความเห็นการกำหนดมาตรฐาน
            TisiEstandardDraftBoard::where('draft_id', $id)
                                   ->where('draft_plan_id', $draft_plan->id)
                                   ->whereNotIn('id', $datas['board_id'][$key])
                                   ->delete(); //ลบความเห็นที่ถูกกดลบ
            foreach ($datas['board'][$key] as $board) {
                if(!empty($board)){
                    $draft_board = [];
                    $draft_board['draft_id']      = $id;
                    $draft_board['draft_plan_id'] = $draft_plan->id;
                    $draft_board['offer_id']      = $board;
                    if(TisiEstandardDraftBoard::where('draft_id', $id)->where('draft_plan_id', $draft_plan->id)->where('offer_id', $board)->count() == 0){
                        TisiEstandardDraftBoard::create($draft_board);
                    }
                }
            }

            //ไฟล์แนบ
            if(!empty($datas['attach']) && array_key_exists($key, $datas['attach']) && is_file($datas['attach'][$key])){

                $attach = $draft_plan->AttachFileAttachTo;
                if(!is_null($attach)){
                    if(Storage::exists($attach->url)){//มีไฟล์
                        Storage::delete($attach->url);
                    }
                    $attach->delete();//ลบข้อมูลไฟล์แนบ
                }

                $result = HP::singleFileUpload(
                                $datas['attach'][$key],
                                $this->attach_path,
                                !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                                (auth()->user()->FullName ?? null),
                                'Center',
                                ((new TisiEstandardDraftPlan)->getTable()),
                                 $draft_plan->id,
                                'attach',
                                array_key_exists($key, $datas['document_details']) ? $datas['document_details'][$key]  : null
                          );
            }

            // 
            if( !empty($datas['bcertify_reason'][$key]) &&  array_key_exists($key,$datas['bcertify_reason'])  ){
                     $reason =  Reason::where('draft_plan_id',$draft_plan->id)->first();
                  if(is_null($reason)){
                    $reason                = new Reason;
                    $reason->created_by     = auth()->user()->runrecno;
                  }else{
                    $reason->updated_by     = auth()->user()->runrecno;
                  }
                    $reason->draft_plan_id = $draft_plan->id;
                    $reason->title         = $datas['bcertify_reason'][$key];
                    $reason->condition     = 2;
                    $reason->state         = 1;
            
                    $reason->save();  
            }
            
        }
      }

      public function get_bcertify_reason(Request $request)
      {
          $id = $request->input('id');
          $reason =  Reason::where('id',$id)->where('condition',1)->value('id');
          if(!is_null($reason)){
            $message = true;
          }else{
            $message = false;
          }
          return response()->json(['message'=> $message]);
      }
}
