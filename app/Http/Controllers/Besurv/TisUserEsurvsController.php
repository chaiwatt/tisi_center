<?php

namespace App\Http\Controllers\Besurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\Basic\SubDepartment;
use App\Models\Basic\Tis;
use App\Models\Besurv\TisSubDepartment;

use Illuminate\Http\Request;

class TisUserEsurvsController extends Controller
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
        $model = str_slug('tisuseresurvs','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['search'] = $request->get('search', '');
            $filter['department'] = $request->get('department', '');
            $filter['tisno'] = $request->get('tisno', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new SubDepartment;

            if ($filter['search']!='') {
                $Query = $Query->where('reg_fname', 'LIKE', '%'.$filter['search'].'%')
                               ->orWhere('reg_lname', 'like', '%' . $filter['search'] . '%');
            }

            if ($filter['department']!='') {
                $Query = $Query->where('did', $filter['department']);
            }

            if ($filter['tisno']!='') {
                $tis = Tis::select('tb3_Tisno')->find($filter['tisno']);
                $Query = $Query->whereHas('tis_users', function($query) use ($tis) {
                    $query->where('tb3_Tisno', $tis->tb3_Tisno);
                });
            }

            $sub_departments = $Query->sortable()
                           ->with('department')
                           ->paginate($filter['perPage']);

            return view('besurv.tis-user-esurvs.index', compact('sub_departments', 'filter'));
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
        $model = str_slug('tisuseresurvs','-');
        if(auth()->user()->can('view-'.$model)) {
            $tisuseresurv = SubDepartment::findOrFail($id);
            return view('besurv.tis-user-esurvs.show', compact('tisuseresurv'));
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
        $model = str_slug('tisuseresurvs','-');
        if(auth()->user()->can('edit-'.$model)) {

            $tisuseresurv = SubDepartment::findOrFail($id);
            $tb3_tisnos = TisSubDepartment::where('sub_id', $id)->pluck('tb3_Tisno');

            return view('besurv.tis-user-esurvs.edit', compact('tisuseresurv', 'tb3_tisnos'));

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
        $model = str_slug('tisuseresurvs','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request, []);

            $requestData = $request->all();

            TisSubDepartment::where('sub_id', $id)->delete();

            //บันทึกมาตรฐานที่รับแจ้งได้
            if(array_key_exists('tb3_Tisno', $requestData)){

              foreach ($requestData['tb3_Tisno'] as $key => $tb3_Tisno) {

                TisSubDepartment::insert(
                                    ['sub_id' => $id,
                                     'tb3_Tisno' => $tb3_Tisno,
                                     'created_by' => auth()->user()->getKey(),
                                     'created_at' => date('Y-m-d H:i:s'),
                                     'updated_at' => date('Y-m-d H:i:s')
                                    ]
                              );
              }

            }

            if(array_key_exists('tisno_all', $requestData)){

              TisSubDepartment::insert(
                                  ['sub_id' => $id,
                                   'tb3_Tisno' => 'All',
                                   'created_by' => auth()->user()->getKey(),
                                   'created_at' => date('Y-m-d H:i:s'),
                                   'updated_at' => date('Y-m-d H:i:s')
                                  ]
                            );

            }

            return redirect('besurv/tis-user-esurvs')->with('flash_message', 'แก้ไขการตั้งค่าการรับแจ้งตามมาตรฐานเรียบร้อยแล้ว!');
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
        $model = str_slug('tisuseresurvs','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new TisSubDepartment;
            TisSubDepartment::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            TisSubDepartment::destroy($id);
          }

          return redirect('besurv/tis-user-esurvs')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }

}
