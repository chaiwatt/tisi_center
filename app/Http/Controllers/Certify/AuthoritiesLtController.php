<?php

namespace App\Http\Controllers\Certify;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Certify\CertiEmailLt;
use Illuminate\Http\Request;

class AuthoritiesLtController extends Controller
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
        $model = str_slug('certify-authorities-lt','-');
        if(auth()->user()->can('view-'.$model)) {

            $certi =   CertiEmailLt::get();
            if(count($certi) == 0){
                $certi = [ new CertiEmailLt];
            }
            return view('certify.authorities_lt.index', compact('certi'));
        }
        abort(403);

    }


    public function store(Request $request)
    {
        $model = str_slug('certify-authorities-lt','-');
        if(auth()->user()->can('add-'.$model)) {
 
            $requestData = $request->all();
            $data = (array)$requestData['data'];
            //ลบที่ถูกกดลบ
            $data_id = array_diff($data['id'], [null]);
            CertiEmailLt::when($data_id, function ($query, $data_id){
                            return $query->whereNotIn('id', $data_id);
                        })->delete();
            foreach($data['certi'] as $key => $item) {
                if($item != ''){
                        $certi = CertiEmailLt::where('id', $data['id'][$key])->first();
                    if(is_null($certi)){
                        $certi = new CertiEmailLt;
                        $certi->created_by =  auth()->user()->getKey();
                    }else{
                        $certi->updated_by =  auth()->user()->getKey();
                    }
                        $certi->certi = $item;
                        $certi->roles =  $data['roles'][$key] ?? null;
                        $certi->cc =  isset($data['cc'][$key])?1:null;
                        $certi->reply_to =  isset($data['reply_to'][$key])?1:null;
                        $certi->emails =  !empty(str_replace(" ","", $data['emails'][$key]))?str_replace(" ","",$data['emails'][$key]):null;  
                        $certi->save();
                }
            }
            return redirect('certify/authorities-lt')->with('flash_message', 'เพิ่ม  เรียบร้อยแล้ว');
        }
        abort(403);
    }

}
