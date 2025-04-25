<?php

namespace App\Http\Controllers\Esurv;

use App\Http\Requests;

use App\Http\Controllers\Controller;

use App\Models\Esurv\LicenseNotification;
use Illuminate\Http\Request;
use App\Models\Esurv\ReceiveVolume as receive_volume;
use App\Models\Esurv\ReceiveVolumeLicense;
use App\Models\Esurv\ReceiveVolumeLicenseDetail;
use App\Models\Esurv\TisiLicenseDetail;
use App\Models\Esurv\Tis;
use App\Models\Besurv\HsCode;

use App\Models\Basic\SubDepartment;
use App\Models\Besurv\TisSubDepartment;


class TisiLicenseNotificationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/notification/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('tisi-license-notification','-');
        if(auth()->user()->can('view-'.$model)) {

            $user_id = auth()->user()->getKey();

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['filter_start_month'] = $request->get('filter_start_month', '');
            $filter['filter_start_year'] = $request->get('filter_start_year', '');
            $filter['filter_end_month'] = $request->get('filter_end_month', '');
            $filter['filter_end_year'] = $request->get('filter_end_year', '');
            $filter['filter_tb3_tisno'] = $request->get('filter_tb3_tisno', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_sub_department'] = $request->get('filter_sub_department', '');
            $filter['filter_created_by'] = $request->get('filter_created_by', '');
            $filter['filter_tis'] = $request->get('filter_tis', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new LicenseNotification;

            if ($filter['filter_created_by']!='') {
                $Query = $Query->where('created_by', $filter['filter_created_by']);
            }
            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }
            if ($filter['filter_tb3_tisno']!='') {
                $Query = $Query->where('tb3_Tisno', $filter['filter_tb3_tisno']);
            }

            if ($filter['filter_start_month']!='') {
                $Query = $Query->whereMonth('created_at', '>=', $filter['filter_start_month']);
            }

            if ($filter['filter_start_year']!='') {
                $Query = $Query->whereYear('created_at', '>=', $filter['filter_start_year']);
            }

            if ($filter['filter_end_month']!='') {
                $Query = $Query->whereMonth('created_at', '<=', $filter['filter_end_month']);
            }

            if ($filter['filter_end_year']!='') {
                $Query = $Query->whereYear('created_at', '<=', $filter['filter_end_year']);
            }

            if ($filter['filter_department']!='') {
                $sub_departments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_id');
                $tis_subdepartments = TisSubDepartment::whereIn('sub_id', $sub_departments)->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
                $subDepartments = SubDepartment::where('did', $filter['filter_department'])->pluck('sub_departname','sub_id');
            }else{
                $subDepartments = [];
            }

            if ($filter['filter_sub_department']!='') {
                $tis_subdepartments = TisSubDepartment::where('sub_id', $filter['filter_sub_department'])->pluck('tb3_Tisno');
                $Query = $Query->whereIn('tb3_Tisno', $tis_subdepartments);
            }

            if ($filter['filter_tis']!='') {
                $Query = $Query->where('tb3_Tisno', $filter['filter_tis']);
            }
            $inform_volume = $Query->sortable()
                                    ->whereNotIn('state',[0])

                                    ->orderby('id','desc')
                                    ->paginate($filter['perPage']);

            return view('esurv.tisi-license-notification.index', compact('inform_volume','filter','subDepartments'));
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
        $model = str_slug('tisi-license-notification','-');
        if(auth()->user()->can('view-'.$model)) {
            $previousUrl = app('url')->previous();
            $license = LicenseNotification::findOrFail($id);
             //ไฟล์แนบ
             $attachs = json_decode($license['attach']);
             $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];

            $attach_path = $this->attach_path;
            return view('esurv.tisi-license-notification.show', compact('license','attachs','attach_path','previousUrl'));
        }
        abort(403);
    }

    public function edit($id)
    {
        $model = str_slug('tisi-license-notification','-');
        if(auth()->user()->can('edit-'.$model)) {
            $previousUrl = app('url')->previous();
            $license = LicenseNotification::findOrFail($id);
             //ไฟล์แนบ
             $attachs = json_decode($license['attach']);
             $attachs = !is_null($attachs)&&count((array)$attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];

            $attach_path = $this->attach_path;
            // return $license;
            return view('esurv.tisi-license-notification.edit',  compact('license','attachs','attach_path','previousUrl'));

        }
        abort(403);
    }
        public function update(Request $request, $id)
        {
            $model = str_slug('tisi-license-notification','-');
            if(auth()->user()->can('edit-'.$model)) {

                $receive_volume = LicenseNotification::findOrFail($id);
                $receive_volume->update([
                    'state' => $request->state,
                    'remake'=> !empty($request->remake) ? $request->remake : null,
                    'updated_by' =>  auth()->user()->getKey()
                ]);

                if($request->previousUrl){
                    return redirect("$request->previousUrl")->with('flash_message', 'แก้ไข receive_volume เรียบร้อยแล้ว!');
                }else{
                    return redirect('esurv/tisi_license_notification')->with('flash_message', 'แก้ไข receive_volume เรียบร้อยแล้ว!!');
                }


            }
            abort(403);

        }

}
