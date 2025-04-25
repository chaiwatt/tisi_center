<?php

namespace App\Http\Controllers\Laws\Word\Cases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Law\Cases\LawCasesBookOffend;
use PhpOffice\PhpWord\TemplateProcessor;
use HP;
use HP_Law;
class LawResultController extends Controller
{

    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/compares/';
    }
    //ไฟล์หนังสือการกระทำผิด
    public function word_book_charges(Request $request)
    {

        $id                = $request->input('id');
        $bookoffend        = LawCasesBookOffend::findOrFail($id);
        $lawcases          = $bookoffend->law_cases;

        $phpWord           = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle         = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/LAW-Book-Charges.docx'));

        $book_date         = null;
        if( !empty($bookoffend->book_date) ){

            $book_date .= !empty($bookoffend->book_date['book_day'])?HP::toThaiNumber($bookoffend->book_date['book_day']):null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($bookoffend->book_date['book_month'])?HP_Law::getMonthThais()[$bookoffend->book_date['book_month']]:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($bookoffend->book_date['book_year'])?HP::toThaiNumber($bookoffend->book_date['book_year'] + 543):null;

        }

        //ผลิตภัณฑ์
        $products = null;
        if( !empty($lawcases->tis) ){
            $products = ( !empty($lawcases->tis)?$lawcases->tis->tb3_Tisno:null ).( !empty($lawcases->tis)?' : '.$lawcases->tis->tb3_TisThainame:null );
            // $products = $lawcases->impound_products->pluck('detail','detail')->implode(', ');
        }
        //ความผิดตาม พรบ. 2511 ดังนี้
        $offend_act = null;  
        if( !empty($bookoffend->offend_act) && is_array($bookoffend->offend_act) ){
            $count_row = count( $bookoffend->offend_act );
            $i = 0;
            foreach( $bookoffend->offend_act AS $Iact  ){
                $offend_act .= ( $i >= 1?str_repeat("\n\n", 10):'' ).trim($Iact);
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $offend_act .= '<w:br/>';
                }
            }
        }
        //สิ่งที่ส่งมาด้วย
        $enclosure = null;  
        if( !empty($bookoffend->book_enclosure) && is_array($bookoffend->book_enclosure) ){
            $count_row = count( $bookoffend->book_enclosure );
            $i = 0;
            foreach( $bookoffend->book_enclosure AS $Ienclosure  ){
                $enclosure .= ( $i >= 1?str_repeat("\n\n", 0):'' ).trim($Ienclosure);
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $enclosure .= '<w:br/>';
                }
            }
        }

        $offend_impound = null;
        if( !empty($lawcases->offend_impound_type) && in_array( $lawcases->offend_impound_type, [1]) ){
            $offend_impound =  'สำหรับกรณีผลิตภัณฑ์อุตสาหกรรมซึ่งพนักงานเจ้าหน้าที่ได้ดำเนินการยึด อายัดไว้นั้น สำนักงานจะเสนอเรื่องต่อคณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรม โดยอาศัยอำนาจตามมาตรา ๔๖ แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. ๒๕๑๑ ออกคำสั่งให้ดำเนินการกับผลิตภัณฑ์ อุตสาหกรรมดังกล่าวต่อไป';
        }

        $templateProcessor->setValue('book_num',         !empty($bookoffend->book_number)?HP::toThaiNumber($bookoffend->book_number):null  );
        $templateProcessor->setValue('book_date',        !empty($book_date)?HP::toThaiNumber($book_date):null  );
        $templateProcessor->setValue('book_title',       !empty($bookoffend->book_title)?HP::toThaiNumber($bookoffend->book_title):null  );
        $templateProcessor->setValue('offend_name',      !empty($lawcases->offend_name) ? HP::toThaiNumber($lawcases->offend_name) : null  );
        $templateProcessor->setValue('enclosure',        !empty($enclosure)?HP::toThaiNumber($enclosure):null  );
        $templateProcessor->setValue('products',         !empty($products)?HP::toThaiNumber($products):null  );
        $templateProcessor->setValue('offend_act',       !empty($offend_act)?HP::toThaiNumber($offend_act):null  );
        $templateProcessor->setValue('user_lawyer',      !empty($bookoffend->user_lawyer->FullName)   ? HP::toThaiNumber($bookoffend->user_lawyer->FullName): null  );
        $templateProcessor->setValue('user_lawyer_case', !empty($lawcases->user_lawyer_to->FullName)   ? HP::toThaiNumber($lawcases->user_lawyer_to->FullName): null  );
        $templateProcessor->setValue('offend_impound',   !empty($offend_impound)?HP::toThaiNumber($offend_impound):null  );

        $date_time =  date('His_dmY');

        $directory = storage_path('/Temp-file');

        if( !is_dir($directory) ){
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), array('.', '..'));

        foreach( $files as $filename){
            $time   = date ("Y-m-d H:i:s", filemtime( $directory.'/'.$filename));

            $remain = intval( (strtotime(date("Y-m-d H:i:s")) - strtotime($time)) );
            $wan    = floor($remain/86400); // วัน
            $l_wan  = $remain%86400;
            $hour   = floor($l_wan/3600); // ชั่วโมง
            $l_hour = $l_wan%3600;
            $minute = floor($l_hour/60);// นาที
            $second = $l_hour%60;

            if( round($minute) > 1 ){ // มากกว่า 10 นาที
                unlink( $directory.'/'.$filename );
            }

        }

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'หนังสือการกระทำผิด_'.$lawcases->ref_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'หนังสือการกระทำผิด_'.$lawcases->ref_no .'_'. $date_time  . '.docx'));
    }

    //ไฟล์หนังสือบันทึกคำให้การ
    public function word_book_statements(Request $request)
    {

        $id                = $request->input('id');
        $bookoffend        = LawCasesBookOffend::findOrFail($id);

        $lawcases          = $bookoffend->law_cases;

        $phpWord           = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle         = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/LAW-Book-Statements.docx'));

        $book_date = null;

        if( !empty($bookoffend->book_date) ){

            $book_date .= !empty($bookoffend->book_date['book_day'])?$bookoffend->book_date['book_day']:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($bookoffend->book_date['book_month'])?HP_Law::getMonthThais()[$bookoffend->book_date['book_month']]:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($bookoffend->book_date['book_year'])?$bookoffend->book_date['book_year'] + 543:null;

        }

        $products = null;
        if( !empty($lawcases->impound_products) && count($lawcases->impound_products) >= 1 ){
            // $products = $lawcases->impound_products->pluck('detail','detail')->implode(', ');
            $products = ( !empty($lawcases->tis)?$lawcases->tis->tb3_Tisno:null ).( !empty($lawcases->tis)?' : '.$lawcases->tis->tb3_TisThainame:null );
        }

        $offend_act = null;  
        if( !empty($bookoffend->offend_act) && is_array($bookoffend->offend_act) ){
            $count_row = count( $bookoffend->offend_act );
            $i = 0;
            foreach( $bookoffend->offend_act AS $Iact  ){
                $offend_act .= ( $i >= 1?str_repeat("\n\n", 10):'' ).trim($Iact);
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $offend_act .= '<w:br/>';
                }
            }
        }

        $offend_report = null;  
        if( !empty($bookoffend->offend_report) && is_array($bookoffend->offend_report) ){
            $count_row = count( $bookoffend->offend_report );
            $i = 0;
            foreach( $bookoffend->offend_report AS $Ireport  ){
                $offend_report .= ( $i >= 1?str_repeat("\n\n", 10):'' ).trim($Ireport);
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $offend_report .= '<w:br/>';
                }
            }
        }

        $offend_power = null;  
        if( !empty($lawcases->offend_power) && is_array($lawcases->offend_power) ){

            $count_row = count( $lawcases->offend_power );
            $i = 0;
            foreach( $lawcases->offend_power AS $Ipower  ){
                $offend_power .= 'ลงชื่อ………….....……...........………....……….………ผู้พิมพ์';
                $offend_power .= '<w:br/>';
                $offend_power .= '( '.trim($Ipower).' )';
                $offend_power .= '<w:br/>';
                $offend_power .= 'กรรมการบริษัท';
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $offend_power .= '<w:br/>';
                }
            }

        }

        $templateProcessor->setValue('book_date',        !empty($book_date)?HP::toThaiNumber($book_date):null  );
        $templateProcessor->setValue('offend_name',      !empty($lawcases->offend_name) ? HP::toThaiNumber($lawcases->offend_name) : null  );
        $templateProcessor->setValue('offend_address',   !empty($lawcases->OffendDataAdress) ? HP::toThaiNumber($lawcases->OffendDataAdress) : null  );
        $templateProcessor->setValue('products',         !empty($products)?HP::toThaiNumber($products):null  );
        $templateProcessor->setValue('offend_date',      !empty($lawcases->offend_date) ? HP::toThaiNumber(HP::formatDateThaiFull($lawcases->offend_date)) : null  );
        $templateProcessor->setValue('tis_no',           !empty($lawcases->tis)?HP::toThaiNumber($lawcases->tis->tb3_Tisno):null  );
        $templateProcessor->setValue('offend_act',       !empty($offend_act)?HP::toThaiNumber($offend_act):null  );
        $templateProcessor->setValue('offend_report',    !empty($offend_report)?HP::toThaiNumber($offend_report):null  );
        $templateProcessor->setValue('user_lawyer_case', !empty($lawcases->user_lawyer_to->FullName)   ? HP::toThaiNumber($lawcases->user_lawyer_to->FullName): null  );
        $templateProcessor->setValue('offend_power',     !empty($offend_power)?HP::toThaiNumber($offend_power):null  );

        $date_time =  date('His_dmY');

        $directory = storage_path('/Temp-file');

        if( !is_dir($directory) ){
            mkdir($directory);
        }

        $files = array_diff(scandir($directory), array('.', '..'));

        foreach( $files as $filename){
            $time = date ("Y-m-d H:i:s", filemtime( $directory.'/'.$filename));

            $remain = intval( (strtotime(date("Y-m-d H:i:s")) - strtotime($time)) );
            $wan = floor($remain/86400); // วัน
            $l_wan = $remain%86400;
            $hour = floor($l_wan/3600); // ชั่วโมง
            $l_hour = $l_wan%3600;
            $minute = floor($l_hour/60);// นาที
            $second = $l_hour%60;

            if( round($minute) > 1 ){ // มากกว่า 10 นาที
                unlink( $directory.'/'.$filename );
            }

        }

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'หนังสือบันทึกคำให้การ_'.$lawcases->ref_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'หนังสือบันทึกคำให้การ_'.$lawcases->ref_no .'_'. $date_time  . '.docx'));

    }
}