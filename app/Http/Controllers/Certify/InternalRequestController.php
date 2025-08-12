<?php

namespace App\Http\Controllers\Certify;
use HP;
use Illuminate\Http\Request;
use App\Models\Basic\Department;
use App\Models\Tis\EstandardOffers;
use App\Http\Controllers\Controller;

class InternalRequestController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    public function __construct()
    {
        // $this->attach_path  = 'files/applicants/standard_offers/';
        $this->attach_path  = 'files/standardsoffers';
    }


    public function index(Request $request)
    {
        $model = str_slug('internalrequest','-');

        // dd($model);
        if(auth()->user()->can('view-'.$model)) {
 
            $filter['perPage'] = $request->get('perPage', 10);
            $offers = EstandardOffers::where('request_owner',2)
            ->paginate($filter['perPage']);
            return view('certify.internalrequest.index',[
                'filter'     => [],
                'offers' => $offers
            ]);
        }
        abort(403);

    }

    public function create()
    {

        $amphurs = [];
        $districts = [];
        return view('certify.internalrequest.create',compact('amphurs','districts'));
    }

        public function store(Request $request)
    {

        $requestData = $request->all();
//  dd($requestData );
        $department = Department::create($requestData);
 
       
        // $data_session     =    HP::CheckSession();
        $user = auth()->user();
       
        $requestData['state'] = 1;
        $requestData['ip_address'] =  $request->ip();
        $requestData['user_agent'] = $request->server('HTTP_USER_AGENT');
        $requestData['created_by'] =  !empty(auth()->user()) ? auth()->user()->id : null;
        $requestData['telephone'] = $user->reg_wphone;
        $requestData['owner'] = $user->reg_fname . " " .  $user->reg_lname;
        $requestData['department_id'] = $department->id;
        $requestData['department'] = $department->title;
        $requestData['objectve'] = $request->objectve;
        $requestData['request_owner'] = 2;

        // ที่เพิ่มเข้ามา
        $requestData['proposer_type'] = $request->proposer_type;
        $requestData['meeting_count'] = $request->meeting_count;
        $requestData['iso_number'] = $request->iso_number;
        $requestData['standard_name'] = $request->standard_name;
        $requestData['national_strategy'] = $request->national_strategy;
        $requestData['reason'] = $request->reason;

        $offers = EstandardOffers::create($requestData);

        if ($request->attach_file && $request->hasFile('attach_file')) {
            HP::singleFileUpload(
                $request->file('attach_file') ,
                $this->attach_path,
                (auth()->user()->tax_number ?? null),
                (auth()->user()->name ?? null),
                'E-ACC',
                (  (new EstandardOffers)->getTable() ),
                 $offers->id,
                'attach_file',
                 ( !empty($request->caption) ? $request->caption : null)
            ); 
        }
 
        return redirect('certify/internalrequest')->with('message',  'เสนอความเห็นฯ เรียบร้อย'  );

    }

    public function update(Request $request, $id)
{
    // dd($request->all());
    $requestData = $request->all();
    $offer = EstandardOffers::findOrFail($id);
    $department = Department::findOrFail($offer->department_id);

      $user = auth()->user();

    $department->update($requestData);
    $requestData['department'] = $department->title;
    $requestData['department_id'] = $department->id;
    $requestData['owner'] = $user->reg_fname . " " .  $user->reg_lname;
    $requestData['ip_address'] = $request->ip();
    $requestData['user_agent'] = $request->server('HTTP_USER_AGENT');
    $requestData['created_by'] = auth()->user()->id ?? null;
    $requestData['telephone'] = $user->reg_wphone;
    $requestData['objectve'] = $request->objectve;
    $requestData['proposer_type'] = $request->proposer_type;
    $requestData['meeting_count'] = $request->meeting_count;
    $requestData['iso_number'] = $request->iso_number;
    $requestData['standard_name'] = $request->standard_name;
    $requestData['national_strategy'] = $request->national_strategy;
    $requestData['reason'] = $request->reason;
    $requestData['state'] = 1;

    $offer->update($requestData);

    if ($request->hasFile('attach_file')) {
        HP::singleFileUpload(
            $request->file('attach_file'),
            $this->attach_path,
            auth()->user()->tax_number ?? null,
            auth()->user()->name ?? null,
            'E-ACC',
            (new EstandardOffers)->getTable(),
            $offer->id,
            'attach_file',
            $request->caption ?? null
        );
    }

    return redirect('certify/internalrequest')->with('message', 'แก้ไขความเห็นฯ เรียบร้อย');
}

    public function view($id)
    {
        $offer = EstandardOffers::findOrFail($id);
        $department = Department::findOrFail($offer->department_id);
        $districts = []; // Populate as needed
        $amphurs = []; // Populate as needed
        $addressInfo = $this->getAddress($offer->department_id);

        return view('certify.internalrequest.view', compact('offer', 'department', 'districts', 'amphurs', 'addressInfo'));
    }

    function address_department(Request $request){
        
         $department =  Department::where('id',$request->select)->first();
         $address = '';
        if(!is_null($department)){
            $address .= @$department->address;
            if(!empty($department->province->PROVINCE_NAME)){
                 $PROVINCE_NAME = $department->province->PROVINCE_NAME;
                if($PROVINCE_NAME ==' กรุงเทพมหานคร'){

                    if(!empty($department->district->DISTRICT_NAME)){
                        $address .= " แขวง".$department->district->DISTRICT_NAME;
                    }

                    if(!empty($department->amphur->AMPHUR_NAME)){
                        $address .= " ตำบล".$department->amphur->AMPHUR_NAME;
                    }
                         $address .= " ".$PROVINCE_NAME;
                }else{
                    if(!empty($department->district->DISTRICT_NAME)){
                        $address .= " แขวง".$department->district->DISTRICT_NAME;
                    }

                    if(!empty($department->amphur->AMPHUR_NAME)){
                        $address .= " อำเภอ".$department->amphur->AMPHUR_NAME;
                    }
                         $address .= " จังหวัด".$PROVINCE_NAME;
                }
            }
        }

        return response()->json(['address'=> $address,'tel'=> $department->tel,'email'=> $department->email]);
    }

     public function edit($id)
    {
        $offer = EstandardOffers::findOrFail($id);
        $department = Department::findOrFail($offer->department_id);
        $districts = []; // Populate as needed
        $amphurs = []; // Populate as needed
            $addressInfo = $this->getAddress($offer->department_id);

        // dd($department);

        return view('certify.internalrequest.edit', compact('offer', 'department', 'districts', 'amphurs','addressInfo'));
    }

    function getAddress($id){
        
         $department =  Department::find($id);
         $address = '';
        if(!is_null($department)){
            $address .= @$department->address;
            if(!empty($department->province->PROVINCE_NAME)){
                 $PROVINCE_NAME = $department->province->PROVINCE_NAME;
                if($PROVINCE_NAME ==' กรุงเทพมหานคร'){

                    if(!empty($department->district->DISTRICT_NAME)){
                        $address .= " แขวง".$department->district->DISTRICT_NAME;
                    }

                    if(!empty($department->amphur->AMPHUR_NAME)){
                        $address .= " ตำบล".$department->amphur->AMPHUR_NAME;
                    }
                         $address .= " ".$PROVINCE_NAME;
                }else{
                    if(!empty($department->district->DISTRICT_NAME)){
                        $address .= " แขวง".$department->district->DISTRICT_NAME;
                    }

                    if(!empty($department->amphur->AMPHUR_NAME)){
                        $address .= " อำเภอ".$department->amphur->AMPHUR_NAME;
                    }
                         $address .= " จังหวัด".$PROVINCE_NAME;
                }
            }
        }

        return $address;
    }


    public function save_department(Request $request)
    {
      $requestData = $request->all();
      $requestData['created_by'] = 448; //user create
      $appoint_department = Department::create($requestData);
      $last_id = $appoint_department->id;
      $last_insert_data = Department::where('id',$last_id)->first();
      if($appoint_department){
          return response()->json([
          'status' => 'success',
          'id' => $last_insert_data->id,
          'title' => $last_insert_data->title
          ]);
      } else {
          return response()->json([
          'status' => 'error'
          ]);
      }
    }

}
