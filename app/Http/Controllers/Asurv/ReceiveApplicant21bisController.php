<?php

namespace App\Http\Controllers\Asurv;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\accept_import;
use App\Models\Asurv\Applicant21Bis;
use App\Models\Asurv\Applicant21BisProductDetail;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiveApplicant21bisController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'esurv_attach/applicant_21bis/';
    }

    public function index(Request $request)
    {
        $filter = [];
        $filter['perPage'] = $request->get('perPage', 10);
        $filter['filter_start_month'] = $request->get('filter_start_month', '');
        $filter['filter_start_year'] = $request->get('filter_start_year', '');
        $filter['filter_end_month'] = $request->get('filter_end_month', '');
        $filter['filter_end_year'] = $request->get('filter_end_year', '');
        $filter['filter_notify'] = $request->get('filter_notify', '');
        $filter['filter_request'] = $request->get('filter_request', '');

        $Query = new Applicant21Bis;

        if ($filter['filter_request']!='') {
            $Query = $Query->where('state', $filter['filter_request']);
        }
        if ($filter['filter_notify']!='') {
            $Query = $Query->where('state_check', $filter['filter_notify']);
        }
        if ($filter['filter_start_month']!='') {
            $Query = $Query->where('created_at', '>=', $filter['filter_start_year'].'-'.$filter['filter_start_month'].'-01'.' 00:00:00');
        }

        if ($filter['filter_end_month']!='') {
            $Query = $Query->where('created_at', '<=', $filter['filter_end_year'].'-'.$filter['filter_end_month'].'-31'.' 00:00:00');
        }

        $items = $Query->sortable()->paginate($filter['perPage']);
        $temp_num = $items->firstItem();

        return view('asurv.receive_applicant_21bis.index', compact('items', 'filter', 'temp_num'));
    }

    public function create()
    {

    }

    public function edit($id)
    {
        $user = auth()->user();
        
        $data = Applicant21Bis::findOrFail($id);
        $data->consider = $user->reg_fname.' '.$user->reg_lname;
        $data_detail = $data->detail_list;

        //ไฟล์แนบ
        if ($data->attach_other!='[]' and $data->attach_other!=null){
            $attachs = json_decode($data->attach_other);
        }else{
            $attachs = [];
        }

        $attach_path = $this->attach_path; //path ไฟล์แนบ

        return view('asurv.receive_applicant_21bis.edit', ["data"=>$data, 'data_detail'=>$data_detail], compact('attachs', 'attach_path'));
    }

    //บันทึกข้อมูลการพิจารณา
    public function update(Request $request, $id)
    {
        $model = str_slug('receive-applicant-21bi','-');
        if(auth()->user()->can('edit-'.$model)) {

            $this->validate($request, [
                 'state' => 'required'
            ]);

            $requestData = $request->all();
            $requestData['consider'] = auth()->user()->getKey();

            $receive_volume = Applicant21Bis::findOrFail($id);
            $receive_volume->update($requestData);

            return redirect('asurv/receive_applicant_21bis')->with('flash_message', 'บันทึกรับคำขอการทำผลิตภัณฑ์เพื่อใช้ในประเทศเป็นการเฉพาะคราว (20 ทวิ) เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    //ดูรายละเอียดหลังอนุมัติหรือไม่อนุมัติ
    public function show($id){

        $data = Applicant21Bis::findOrFail($id);
        $data_detail = $data->detail_list;

        //ไฟล์แนบ
        if ($data->attach_other!='[]' and $data->attach_other!=null){
            $attachs = json_decode($data->attach_other);
        }else{
            $attachs = [];
        }

        $attach_path = $this->attach_path; //path ไฟล์แนบ

        return view('asurv.receive_applicant_21bis.show', ["data"=>$data, 'data_detail'=>$data_detail], compact('attachs', 'attach_path'));

    }

}
