<?php

namespace App\Http\Controllers\FuntionCenter;

use HP_Law;
use HP;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Models\Law\Basic\LawBookType;
use App\Models\Law\Listen\LawListenMinistry;
use App\Models\Law\Listen\LawListenMinistryResponse;

class LawMailController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->attach_path = 'law_attach/listen_ministry_response';
    }

    public function accept($id)
    {
        $id =  base64_decode($id);
        $lawlistministry = LawListenMinistry::findOrFail($id);
        $accept = ($lawlistministry->state == 0) ? true : false;

        return view('laws.listen.ministry.form.accept-ministry', compact('lawlistministry','accept'));
    }

    public function accept_save(Request $request)
    {
        $requestData  = $request->all();
        $lawlistministryrsponse = LawListenMinistryResponse::create($requestData);
        $this->upload_file($lawlistministryrsponse,$request);

        $remark = '';
        $remark .= ' ผู้แสดงความเห็น : '.$requestData['name'];
        $remark .= ' เบอร์ : '.$requestData['tel'];
        $remark .= ' อีเมล : '.$requestData['email'];

        HP_Law::InsertLawLogWorking(         
            3,
            ((new LawListenMinistryResponse)->getTable()),
            $lawlistministryrsponse->id,
            $lawlistministryrsponse->RefNo,
            'ตรวจสอบข้อมูลความเห็น',
            'แสดงความคิดเห็นร่างกฏกระทรวง',
            'บันทึกความเห็น',
            $remark
        );

        return redirect('law/listen/ministry/accept/success/'.@$lawlistministryrsponse->id);
    }

    public function accept_save_success($id)
    {
        $lawlistministryrsponse = LawListenMinistryResponse::findOrFail($id);
        return view('laws.listen.ministry.form.accept-ministry-success', compact('lawlistministryrsponse'));
    }

    public function upload_file($lawlistministryrsponse, $request){
        $requestData = $request->all();
        $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');

        if(isset($requestData['file_response'])){
            if ($request->hasFile('file_response')) {
                HP::singleFileUploadLaw(
                    $request->file('file_response') ,
                    $this->attach_path,
                    ( $tax_number),
                    (auth()->user()->FullName ?? null),
                    'Law',
                    (  (new LawListenMinistryResponse)->getTable() ),
                    $lawlistministryrsponse->id,
                    'file_response',
                    'ไฟล์แนบความคิดเห็น'
                );
            }
        }

    }

}