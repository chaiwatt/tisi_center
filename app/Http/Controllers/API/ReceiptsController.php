<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Law\Reward\LawlRewardRecepts;  
use HP;
use App\Models\Law\File\AttachFileLaw;
use Illuminate\Support\Facades\Storage;
class ReceiptsController extends Controller
{
        
    public function index(Request $request, $id)
    {

        return view('api.receipts.index',compact('id'));
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $ids =   base64_decode($id);
        $receipt = LawlRewardRecepts::findOrFail($ids);
        if(!is_null($receipt)){ 
            $receipt->status = '2'; 
            $receipt->send_status = '1';
            $receipt->send_date =  date("Y-m-d H:i:s");
            $receipt->send_remark =  !empty($request->send_remark) ? $request->send_remark : null;
            $receipt->save();    
            
            $case_number = (!empty($receipt->lawl_reward_recepts_details_to->case_number) ?  $receipt->lawl_reward_recepts_details_to->case_number  : '0000000000000');
            
            //ไฟล์เเนบ
            if(isset( $request->attach )  && $request->hasFile('attach') ){
                       $attach_path = 'law_attach/receipts/';
                       self::singleFileUploadLaw(
                            $request->file('attach') ,
                            $attach_path.$case_number,
                            '0000000000000',
                             null,
                              'Law',
                            ( (new LawlRewardRecepts)->getTable() ),
                             $receipt->id,
                            'evidence',
                            'ไฟล์แนบหลักฐานใบสำคัญรับ'
                        );
             }
           

             return response()->json([ 'message' =>  true  ]);
        }else{    
            return response()->json([ 'message' =>  false  ]);
        }

    }

    
    public static function singleFileUploadlaw($request_file, $attach_path = '', $tax_number='0000000000000', $username='0000000000000', $systems = "Center", $table_name = null , $ref_id = null, $section = null, $attach_text = null, $setting_file_id = null, $gen_taxid = false){

        $attach             = $request_file;
        $file_size          = (method_exists($attach, 'getSize')) ? $attach->getSize() : 0;
        $file_extension     = $attach->getClientOriginalExtension();
        $fullFileName       = str_random(10).'-date_time'.date('Ymd_hms') . '.' .$file_extension ;
        
        $path_full          = $attach_path.($gen_taxid ?'/'.$tax_number:'');
        $path               = Storage::putFileAs( $path_full, $attach,  str_replace(" ","",$fullFileName) );
        $file_name          = HP::ConvertCertifyFileName($attach->getClientOriginalName());

       $request =  AttachFileLaw::create([
                            'tax_number'        => $tax_number,
                            'username'          => $username,
                            'systems'           => $systems,
                            'ref_table'         => $table_name,
                            'ref_id'            => $ref_id,
                            'url'               => $path,
                            'filename'          => $file_name,
                            'new_filename'      => $fullFileName,
                            'caption'           => $attach_text,
                            'size'              => $file_size,
                            'file_properties'   => $file_extension,
                            'section'           => $section,
                            'setting_file_id'   => $setting_file_id,
                            'created_at'        => date('Y-m-d H:i:s')
                          ]);
        return $request;
    }
}
