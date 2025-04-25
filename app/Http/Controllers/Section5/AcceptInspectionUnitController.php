<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sso\ApplicationInspectionUnit;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

class AcceptInspectionUnitController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบเต็ม
    private $attach_path_crop;//ที่เก็บไฟล์แนบเต็ม

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'tis_attach/accept_inspection_unit/';
        $this->attach_path_crop = 'tis_attach/accept_inspection_unit_crop/';
    }


    public function data_list(Request $request)
    {

        $model = str_slug('accept-inspection-unit','-');

        $filter_search = $request->input('filter_search');
        $filter_status = $request->input('filter_status');
        
        $query = ApplicationInspectionUnit::query()->when($filter_search, function ($query, $filter_search){
                                                        $search_full = str_replace(' ', '', $filter_search );

                                                        if( strpos( $search_full , 'IB-' ) !== false){
                                                            $query->where('refno_application',  'LIKE', "%$search_full%");
                                                        }else{
                                                            $query->where(function ($query2) use($search_full) {
                                                                        $query2->Where(DB::raw("REPLACE(authorized_name,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(authorized_taxid,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                        }

                                                    })
                                                    ->when($filter_status, function ($query, $filter_status){
                                                        $query->where('status_application', $filter_status);
                                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-app_no="'. $item->refno_application .'" value="'. $item->id .'">';
                            })
                            ->addColumn('refno_application', function ($item) {
                                return $item->refno_application;
                            })
                            ->addColumn('authorized_name', function ($item) {
                                return !empty($item->authorized_name)?$item->authorized_name:'-';
                            })
                            ->addColumn('authorized_taxid', function ($item) {
                                return !empty($item->authorized_taxid)?$item->authorized_taxid:'-';
                            })
                            ->addColumn('standards', function ($item) {
                                return @$item->UnitsStandard;
                            })
                            ->addColumn('date_application', function ($item) {
                                return !empty($item->date_application)?HP::DateThai($item->date_application):'-';
                            })
                            ->addColumn('status_application', function ($item) {
                                return !empty($item->AppStatus)?$item->AppStatus:'-';
                            })
                            ->addColumn('assign_by', function ($item) {
                                return !empty($item->assign_by)?$item->AssignName:'รอดำเนินการ'.(!empty($item->assign_date)?'<br>'.HP::DateThaiFull($item->assign_date):null);
                            })
                            ->addColumn('action', function ($item) use($model) {

                                $btn = '';
                                if( auth()->user()->can('view-'.$model) ){
                                    $btn =  ' <a href="'. url('section5/accept-inspection-unit/'.$item->id) .'" class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                                }

                                if( auth()->user()->can('edit-'.$model) ){

                                    $btn_disabled = 'disabled';
                                    if( $item->status_application == 1 || $item->status_application == 2 || $item->status_application == 4 || $item->status_application == 6 ){
                                        $btn_disabled = '';
                                    } 

                                    $btn_disableds = 'disabled';
                                    if( $item->status_application >= 3 && $item->status_application != 7  ){
                                        $btn_disableds = '';
                                    } 

                                    $btn .= ' <a '. $btn_disabled .' class="btn btn-warning btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/accept-inspection-unit/'.$item->id.'/edit') .'" ><i class="fa fa-search" aria-hidden="true"></i></a>';
                                    $btn .= ' <a '. $btn_disableds .' class="btn btn-primary btn-xs waves-effect waves-light btn_assign_sigle" href="'. url('section5/accept-inspection-unit/approve/'.$item->id) .'" ><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                                  
                                }

                                return $btn;
 
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];

            // $filter['filter_search'] = $request->get('filter_search', '');

            // $filter['filter_status'] = $request->get('filter_status', '');
            // $filter['perPage'] = $request->get('perPage', 10);

            // $Query = new ApplicationInspectionUnit;

            // if ($filter['filter_status']!='') {
            //     $Query = $Query->where('status_application', $filter['filter_status']);
            // }
            
            // if ($filter['filter_search']!='') {

            //     $search_full = str_replace(' ', '', $filter['filter_search']);

            //     if( strpos( $search_full , 'IB-' ) !== false){
            //         $Query = $Query->where('refno_application',  'LIKE', "%$search_full%");
            //     }else{
            //         $Query =  $Query->where(function ($query2) use($search_full) {
            //                     $query2->Where(DB::raw("REPLACE(authorized_name,' ','')"), 'LIKE', "%".$search_full."%")
            //                         ->OrWhere(DB::raw("REPLACE(authorized_taxid,' ','')"), 'LIKE', "%".$search_full."%");
            //                 });
            //     }

            // }

            // $data_list = $Query->sortable()->with('user_created')
            //                                            ->with('user_updated')
            //                                            ->paginate($filter['perPage']);

            return view('section5.accept-inspection-unit.index', compact('filter'));
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
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('add-'.$model)) {

        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('view-'.$model)) {
            $application_inspection_unit = ApplicationInspectionUnit::findOrFail($id);

            $application_inspection_unit->show  = true;

            return view('section5/accept-inspection-unit.show', compact('application_inspection_unit'));
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $application_inspection_unit = ApplicationInspectionUnit::findOrFail($id);

            $application_inspection_unit->edited  = true;

            return view('section5/accept-inspection-unit.edit', compact('application_inspection_unit'));
        }
        abort(403);
    }

    public function approve($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {
            $application_inspection_unit = ApplicationInspectionUnit::findOrFail($id);

            $application_inspection_unit->approve  = true;

            return view('section5/accept-inspection-unit.approve', compact('application_inspection_unit'));
        }
        abort(403);
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
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationInspectionUnit::findOrFail($id);
            $requestData = $request->all();

            $requestData['checking_date'] = date('Y-m-d');
            $requestData['checking_by'] = auth()->user()->getKey();
            $requestData['status_application'] = $requestData['checking_status'];
        
            $application->update($requestData);

            return redirect('section5/accept-inspection-unit')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');


        }
        abort(403);
    }

    public function approve_save(Request $request, $id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $application = ApplicationInspectionUnit::findOrFail($id);
            $requestData = $request->all();

            $requestData['approve_date'] = date('Y-m-d');
            $requestData['approve_by'] = auth()->user()->getKey();
            $requestData['status_application'] = $requestData['approve_status'];
        
            $application->update($requestData);

            return redirect('section5/accept-inspection-unit')->with('flash_message', 'บันทึก เรียบร้อยแล้ว!');


        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('delete-'.$model)) {

        }
        abort(403);
    }

    public function assing_data_update(Request $request){

        $model = str_slug('accept-inspection-unit','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $arr_publish = $request->input('id');
    
            $assignData['assign_by'] = $requestData['assign_by'];
            $assignData['assign_comment'] = !empty($requestData['assign_commen'])?$requestData['assign_commen']:null;
            $assignData['assign_date'] = date('Y-m-d H:i:s');
    
            $result = ApplicationInspectionUnit::whereIn('id', $arr_publish)->update($assignData);
    
            if($result) {
                return 'success';
            } else {
                return "not success";
            }
    
        }
        abort(403);

    }
}
