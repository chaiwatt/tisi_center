<?php

namespace App\Http\Controllers\Bcertify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Bcertify\StdTypeAssignRoles;
use App\Models\Bcertify\StandardTypeAssign;
use App\Models\Tis\EstandardOffers;
use App\Models\Tis\EstandardOffersAsign;
use App\Models\Bcertify\Standardtype;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\AttachFile;
use App\User;
use App\RoleUser;
use HP;
use DB;



class StandardsOffersController extends Controller
{

    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'files/standardsoffers';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('standardsoffers','-');
        if(auth()->user()->can('view-'.$model)) {
            $select_users  = [];
            if(is_array(auth()->user()->BasicRoleUser)){
                $roles = auth()->user()->BasicRoleUser;

                if(in_array(25,$roles)){ // ผู้อำนวยการกอง ของ สก.
                    $user_ids = RoleUser::select('user_runrecno')->where('role_id',44);
                    $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                                        ->whereIn('runrecno',$user_ids)
                                        ->orderbyRaw('CONVERT(title USING tis620)')
                                        ->pluck('title','runrecno');
                }else if(in_array(44,$roles)){ // ผก. - กำหนดมาตรฐานการตรวจสอบและรับรอง
                    $user_ids = RoleUser::select('user_runrecno')->where('role_id',45);
                    $select_users  = User::select(DB::raw("CONCAT(reg_fname,' ',reg_lname) AS title"),'runrecno')
                                        ->whereIn('runrecno',$user_ids)
                                        ->where('reg_subdepart', (auth()->user()->reg_subdepart ?? ''))
                                        ->orderbyRaw('CONVERT(title USING tis620)')
                                        ->pluck('title','runrecno');
                }
            }


            return view('bcertify.standards-offers.index', compact('select_users'));
        }
        abort(403);

    }


public function data_list(Request $request)
    {
        $model = str_slug('standardsoffers', '-');
        $filter_search = $request->input('filter_search');
        $filter_state = $request->input('filter_state');

        $filter_date_s = !empty($request->input('filter_start_date'))?HP::convertDate($request->input('filter_start_date'),true):null;
        $filter_date_e = !empty($request->input('filter_end_date'))?HP::convertDate($request->input('filter_end_date'),true):null;
        $bc_standard_type_assign = StandardTypeAssign::whereHas('bcertify_std_type_assign_roles', function ($query){
                                                        $query->whereIn('roles', auth()->user()->RoleIds);
                                                    })->where('ordering', 1)
                                                    ->get();
        $bc_standard_type_ids = $bc_standard_type_assign->pluck('bc_standard_type_id')->toArray();
        $orderings = $bc_standard_type_assign->pluck('ordering')->toArray();

        $query = EstandardOffers::query()
                                ->with([
                                    'tisi_estandard_offers_asigns'
                                ])
                                ->where(function ($query) use($bc_standard_type_ids, $orderings){
                                    if(in_array(1, $orderings)){
                                        $query->where(function ($query) use($bc_standard_type_ids){
                                            $query->whereIn('std_type', $bc_standard_type_ids)->orWhereNull('std_type');
                                        });
                                        if((in_array(2, $orderings) || in_array(3, $orderings))){
                                            $query->orWhereHas('tisi_estandard_offers_asigns', function ($query){
                                                $query->where('user_id', auth()->id());
                                            });
                                        }
                                    }else if((in_array(2, $orderings) || in_array(3, $orderings)) || empty($bc_standard_type_ids)){
                                        $query->whereHas('tisi_estandard_offers_asigns', function ($query){
                                            $query->where('user_id', auth()->id());
                                        });
                                    }
                                })
                                ->when($filter_search, function ($query, $filter_search){
                                    $search_full = str_replace(' ', '', $filter_search );
                                        $query->where(function ($query2) use($search_full) {
                                                    $query2->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(title_eng,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(scope,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(objectve,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(stakeholders,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ->OrWhere(DB::raw("REPLACE(name,' ','')"), 'LIKE', "%".$search_full."%")
                                                        ;
                                        });
                                })
                                ->when($filter_state, function ($query, $filter_state){
                                    $query->where('state', $filter_state);
                                })
                                ->when($filter_date_s, function ($query, $filter_date_s) use($filter_date_e){
                                    if(!is_null($filter_date_s) && !is_null($filter_date_e) ){
                                        return $query->whereBetween('created_at',[$filter_date_s,$filter_date_e]);
                                    }else if(!is_null($filter_date_s) && is_null($filter_date_e)){
                                        return $query->whereDate('created_at',$filter_date_s);
                                    }
                                });


        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox"  value="'. $item->id .'">';
                            })
                            ->addColumn('refno', function ($item) {
                                return   !empty($item->refno)?$item->refno:(($item->state == 1)?'<i style="color: grey;">รอพิจารณา</i>':'-');
                            })
                            ->addColumn('created_at', function ($item) {
                                return   !empty($item->created_at)? HP::DateThai($item->created_at):'';
                            })
                            ->addColumn('title', function ($item) {
                                return   !empty($item->title)? $item->title:'';
                            })
                            ->addColumn('objectve', function ($item) {

                                return   !empty($item->objectve)? $item->objectve:'';
                            })
                            ->addColumn('name', function ($item) {
                                 $name =   !empty($item->name)? $item->name:'';
                                 $name .= !empty($item->email)? '<br/>'.$item->email:'';
                                return   $name;
                            })
                            ->addColumn('department', function ($item) {
                                return   !empty($item->department)? $item->department:'';
                            })
                            ->addColumn('standard_type', function ($item) {
                                return   !empty($item->standard_type_to->title)? $item->standard_type_to->title:'';
                            })
                            ->addColumn('state', function ($item) {
                                return  $item->StateTitle;
                            })
                            ->addColumn('asigns', function ($item) {
                                // if(is_array($roles)){
                                //     if(in_array(45,$roles) || in_array(46,$roles)){ //ผก. , จนท. - กำหนดมาตรฐานการตรวจสอบและรับรอง
                                //         $asigns =  count($item->Asigns3Title) > 0 ?  implode(",",$item->Asigns3Title)  : '';
                                //     }else {
                                //         $asigns =  count($item->Asigns2Title) > 0 ?  implode(",",$item->Asigns2Title)  : '';
                                //     }
                                // }else{
                                //     $asigns =  count($item->Asigns2Title) > 0 ?  implode(",",$item->Asigns2Title)  : '';
                                // }

                                // return $asigns;
                                return @$item->EstandardOffersAsignNameAll;
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('edit-'.$model) && $item->state == 1){
                                    $btn .=  ' <a href="'. url('bcertify/standards-offers/'.$item->id. '/edit') .'" class="btn btn-warning btn-xs">     <i class="fa fa-pencil-square-o" aria-hidden="true"> </i> </a>';
                                }
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn .=  ' <a href="'. url('bcertify/standards-offers/'.$item->id) .'" class="btn btn-info btn-xs">   <i class="fa fa-eye" aria-hidden="true"></i> </a>';
                                }
                                return $btn;
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['operation_result_name','name','state', 'checkbox', 'action', 'refno'])
                            ->make(true);
    }



    public function show($id)
    {
        $model = str_slug('standardsoffers','-');
        if(auth()->user()->can('view-'.$model)) {
            $estandardoffers = EstandardOffers::findOrFail($id);

            return view('bcertify.standards-offers.show', compact('estandardoffers'));
        }
        abort(403);
    }

    public function edit($id)
    {
        $model = str_slug('standardsoffers','-');
        if(auth()->user()->can('edit-'.$model)) {
            $estandardoffers = EstandardOffers::findOrFail($id);
            return view('bcertify.standards-offers.edit', compact('estandardoffers'));
        }
        abort(403);
    }


    public function update(Request $request, $id)
    {
        $model = str_slug('standardsoffers','-');
        if(auth()->user()->can('edit-'.$model)) {


            $requestData = $request->all();
            $requestData['updated_by'] =  auth()->user()->getKey();
            $standardsoffer = EstandardOffers::findOrFail($id);
            $standardsoffer->update($requestData);

            if(isset($requestData['attach'])){
                if ($request->hasFile('attach')) {
                    HP::singleFileUpload(
                        $request->file('attach') ,
                        $this->attach_path,
                        !empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000',
                        (auth()->user()->FullName ?? null),
                        'Center',
                        (  (new EstandardOffers)->getTable() ),
                         $standardsoffer->id,
                        'attach',
                        !empty($request->document_details) ? $request->document_details : null
                    );
                }
            }

            return redirect('bcertify/standards-offers')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    // รหัสความเห็น
    public function data_refno(Request $request)
    {
        $refno = 'Req';

        $refno .=   (date('y')+43);

        $standard_code 	= Standardtype::select('standard_code')->where('id',$request->standard_types)->value('standard_code');
        if(!is_null($standard_code)){
            $refno .= $standard_code;
        }else{
            $refno .= '0';
        }


        $max_nos 	= EstandardOffers::where('state',$request->state)
                                        ->whereYear('updated_at',date('Y'))
                                        ->whereNotNull('refno')->pluck('refno','refno')
                                        ->toArray();
        $amount = 4;
        if(count($max_nos) > 0){
            $list_code = [];
            foreach($max_nos as $max_no ){
                $new_run =  substr($max_no,-$amount);
                $new_run = str_replace('-', '',  $new_run);

                if(strlen($new_run) == $amount){
                 $list_code[$new_run] = $new_run;
                }
            }

            if(count($list_code) > 0){
                usort($list_code, function($x, $y) {
                    return $x > $y;
                   });
                $last = end($list_code);
                $max_new = ((int)$last  + 1);; //บวกค่า 1
            }else{
                $max_new = 1;
            }

        }else{
            $max_new = 1;
        }

        $refno .=  str_pad($max_new, $amount, '0', STR_PAD_LEFT);



        return response()->json([
                                  'refno'=> $refno
                               ]);
    }

    public function save_assign(Request $request)
    {
        $offers = $request->input('id_publish');
        $assigns = $request->input('assigns');
        foreach ($offers as $offer_id) {
            $offer = EstandardOffers::find($offer_id);
            if (!is_null($offer)){
                $this->save_estandard_offers_asign($assigns,$offer->id);
            }
        }

        return response()->json([
                                  'message'=> true
                               ]);
    }
    private function save_estandard_offers_asign($assigns, $id){
        if(is_array(auth()->user()->BasicRoleUser)){
            $roles = auth()->user()->BasicRoleUser;
            if(in_array(25,$roles)){ // ผู้อำนวยการกอง ของ สก.
                $ordering = 2;
            }else if(in_array(44,$roles)){ // ผก. - กำหนดมาตรฐานการตรวจสอบและรับรอง
                $ordering = 3;
            }
            if(isset($ordering)){
                EstandardOffersAsign::where('comment_id', $id)->where('ordering',$ordering)->update(['status'=>2]);
                foreach($assigns as $key => $item) {
                    $input = [];
                    $input['comment_id']      = $id;
                    $input['user_id']         = $item;
                    $input['status']          = 1;
                    $input['ordering']        = $ordering;
                    $input['assign_by']       = auth()->user()->runrecno;
                    $input['assign_date']     =  date('Y-m-d H:i:s');
                    EstandardOffersAsign::create($input);
                }
           }

        }

    }

}
