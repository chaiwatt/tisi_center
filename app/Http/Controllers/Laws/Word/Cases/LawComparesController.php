<?php

namespace App\Http\Controllers\Laws\Word\Cases;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use HP_Law;
use Storage;
use App\User;  
use App\Models\Law\Cases\LawCasesForm;  
use App\Models\Law\Cases\LawCasesCompare;
use App\Models\Law\Cases\LawCasesCompareAmounts; 
use App\Models\Law\Cases\LawCasesCompareBook;
use App\Models\Law\Cases\LawCasesCompareCalculate;

use App\Models\Law\Cases\LawCasesPayments;
use App\Models\Law\Cases\LawCasesPaymentsDetail;
use App\Models\Certify\CertiSettingPayment;
use App\Models\Law\File\AttachFileLaw;

use App\Models\Law\Basic\LawResource;

use Illuminate\Support\Facades\Mail; 
use App\Mail\Mail\Law\Cases\MailCompares;

use App\Models\Law\Offense\LawOffender;
use App\Models\Law\Offense\LawOffenderCases;

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\SimpleType\TblWidth;

class LawComparesController extends Controller
{
  
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/compares/';
    }

    //พิมพ์หนังสือเปรียบเทียบ
    public function word_cases_compare(Request $request)
    {
        $id                = $request->input('id');
        $law_cases          = LawCasesForm::findOrFail($id);

        $phpWord           = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle         = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/LAW-Book-Compares.docx'));

        $compare_book      = $law_cases->compare_book;
    

        $book_date = null;

        if( !empty($compare_book->book_date) ){

            $book_date .= !empty($compare_book->book_date['book_day'])?$compare_book->book_date['book_day']:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($compare_book->book_date['book_month'])?HP_Law::getMonthThais()[$compare_book->book_date['book_month']]:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($compare_book->book_date['book_year'])?$compare_book->book_date['book_year'] + 543:null;

        }

        $refer = null;

        if( !empty($compare_book->refer) && is_array($compare_book->refer) ){

            $count_row = count( $compare_book->refer );
            $i = 0;
            foreach( $compare_book->refer AS $Irefer  ){
                $refer .= $Irefer;
                $i++;
                if( $i > 0 && $count_row != $i ){
                    $refer .= '<w:br/>';
                }
                
            }

        }

        $products = null;
        $keep     = 0;
        if( !empty($law_cases->impound_products) && count($law_cases->impound_products) >= 1 ){
            $products = $law_cases->impound_products->pluck('detail','detail')->implode(', ');
            $keep     = $law_cases->impound_products->sum('amount_keep');
        }

        $offend_power = null;
        if( !empty( $law_cases->offend_power ) ){

            $count_row = count( $law_cases->offend_power );
            $i = 0;
            foreach( $law_cases->offend_power AS $Ipower  ){
                $i++;

                if( $count_row == $i && $i >=2 ){
                    $offend_power .= ' และ';
                }
                $offend_power .= $Ipower;
        
                if( $i >= 0 && $count_row < $i ){
                    $offend_power .= ' ';
                } 

            }

        }

        if( !empty($law_cases->offend_name ) && !empty( $offend_power) ){
            $offend_power .= ' กรรมการผู้จัดการ'.(!empty($law_cases->offend_name)?HP::toThaiNumber($law_cases->offend_name):null);
        }  

        $offend_impound = null;
        if( !empty($law_cases->offend_impound_type) && in_array( $law_cases->offend_impound_type, [1]) ){
            $offend_impound = 'สำหรับผลิตภัณฑ์อุตสาหกรรมที่พนักงานเจ้าหน้าที่อายัดไว้นั้น สำนักงานจักได้ดำเนินการนำเสนอคณะกรรมการมาตรฐานผลิตภัณฑ์อุตสาหกรรมมีคำสั่งให้ท่านดำเนินการกับผลิตภัณฑ์อุตสาหกรรม ตามมาตรา ๔๖ แห่งพระราชบัญญัติมาตรฐานผลิตภัณฑ์อุตสาหกรรม พ.ศ. ๒๕๑๑ ต่อไป ';
        }

        $templateProcessor->setValue('book_date',      !empty($book_date)?HP::toThaiNumber($book_date):null  ); 
        $templateProcessor->setValue('book_numbers',   !empty($compare_book->book_number)?HP::toThaiNumber($compare_book->book_number):'อก ๐๗๐๒/'   );
        $templateProcessor->setValue('title',          !empty($compare_book->title)?HP::toThaiNumber($compare_book->title):null  );
        $templateProcessor->setValue('send_to',        !empty($compare_book->send_to)?HP::toThaiNumber($compare_book->send_to):null  );
        $templateProcessor->setValue('refer',          !empty($refer)?HP::toThaiNumber($refer):null  );
        $templateProcessor->setValue('offend_name',    !empty($compare_book->offend_name)?$compare_book->offend_name:null  );
        $templateProcessor->setValue('offend_address', !empty($compare_book->offend_address)?HP::toThaiNumber($compare_book->offend_address):null  );
        $templateProcessor->setValue('products',       !empty($products)?HP::toThaiNumber($products):null  );
        $templateProcessor->setValue('keep',           !empty($keep)?HP::toThaiNumber(number_format($keep)):HP::toThaiNumber(0)  );
        $templateProcessor->setValue('section',        !empty($law_cases->SectionListName)?HP::toThaiNumber($law_cases->SectionListName):null  );

        $templateProcessor->setValue('amount',         !empty($compare_book->amount)?HP::toThaiNumber(number_format($compare_book->amount)):HP::toThaiNumber(0)  );
        $templateProcessor->setValue('amount_txt',     !empty($compare_book->amount)?HP_Law::TextBathFormat(number_format($compare_book->amount)):HP::toThaiNumber(0)  );


        $templateProcessor->setValue('re_section',     !empty($law_cases->result_section)?HP::toThaiNumber($law_cases->result_section->pluck('SectionNumber')->implode(', ')):null  );
        $templateProcessor->setValue('re_punish',      !empty($law_cases->result_section)?HP::toThaiNumber($law_cases->result_section->pluck('PunishNumber')->implode(', ')):null  );
        $templateProcessor->setValue('offend_power',   !empty($offend_power)?HP::toThaiNumber($offend_power):null  ); 
        $templateProcessor->setValue('offend_impound', !empty($offend_impound)?HP::toThaiNumber($offend_impound):null  ); 
        $templateProcessor->setValue('contact_name',   !empty($law_cases->user_lawyer_to->FullName)?$law_cases->user_lawyer_to->FullName:null  ); 
        $templateProcessor->setValue('department_name',!empty($law_cases->user_lawyer_to->DepartName)?$law_cases->user_lawyer_to->DepartName:null  ); 

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

        $templateProcessor->saveAs(storage_path('/Temp-file/'.'แจ้งเปรียบเทียบปรับ_'.$law_cases->ref_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'แจ้งเปรียบเทียบปรับ_'.$law_cases->ref_no .'_'. $date_time  . '.docx'));
    }


    public function word_cases_fact(Request $request)
    {

        $phpWord           = new \PhpOffice\PhpWord\PhpWord();
        $fontStyle         = new \PhpOffice\PhpWord\Style\Font();
        $templateProcessor = new TemplateProcessor(public_path('/word/LAW-Book-fact.docx'));

        $id                = $request->input('id');
        $lawcases          = LawCasesForm::findOrFail($id);
        $fact_books        = $lawcases->fact_books;
        $result_section    = $lawcases->result_section;
        $cases_impound     = $lawcases->law_cases_impound_to;


        // $offender_cases    = $lawcases->offender_cases()->where( function($query) use($lawcases){
        //                                                     $query->whereNotIn('law_cases_id', [ $lawcases->id ] );
        //                                                 } )
        //                                                 ->get();

        //วันที่                                           
        $book_date         = null;
        if( !empty($fact_books->fact_book_date) ){

            $book_date .= !empty($fact_books->fact_book_date['book_day'])?$fact_books->fact_book_date['book_day']:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }
            $book_date .= !empty($fact_books->fact_book_date['book_month'])? HP_Law::getMonthThais()[$fact_books->fact_book_date['book_month']]:null;
            if( !is_null( $book_date ) ){
                $book_date .= ' ';
            }  
            $book_date .= !empty($fact_books->fact_book_date['book_year'])?$fact_books->fact_book_date['book_year'] + 543:null;

        }

        //มาตรความผิด
        $section = null;
        if( !empty( $result_section ) ){

            $count_row = count( $result_section );
            $i = 0;
            foreach( $result_section AS $Isection  ){
                $i++;

                if( $count_row == $i &&  $i >= 2){
                    $section .= ' และ';
                }
                $section .= $Isection->SectionNumber;
        
                if( $i >= 0 && $count_row < $i ){
                    $section .= ' ';
                } 

            }

        }

        $offend_power = '';
        if( !empty( $lawcases->offend_power ) ){

            $count_row = count( $lawcases->offend_power );
            $i = 0;
            $i = 0;
            foreach( $lawcases->offend_power AS $Ipower  ){
                $i++;

                if( $count_row == $i && $i >=2 ){
                    $offend_power .= ' และ';
                }
                $offend_power .= $Ipower;
        
                if( $i >= 0 && $count_row < $i ){
                    $offend_power .= ' ';
                } 

            }

        }
    
        $offend_name = !empty($lawcases->offend_name)?$lawcases->offend_name:''; 
        if(!empty( $offend_power) ){
            $offend_name .= ' และ '.$offend_power;
        }
 
        //เป็นผู้ได้รับอนุญาตจาก สมอ.
        $license_tisi       = ( $lawcases->offend_license_type == 1 )?'<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings" w:char="F0A8"/>';
        //ไม่ได้เป็นผู้ได้รับอนุญาตจาก สมอ. 
        $license_other      = ( $lawcases->offend_license_type == 2 )?'<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings" w:char="F0A8"/>';
        //ปัจจุบันได้รับใบอนุญาตแล้ว
        $license_currently1 = !empty($fact_books->fact_license_currently) && $fact_books->fact_license_currently == 1? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        //ปัจจุบันยังไม่ได้รับใบอนุญาต
        $license_currently2 = !empty($fact_books->fact_license_currently) && $fact_books->fact_license_currently == 2? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';

        $templateProcessor->setValue('offend_power',   !empty($offend_power)?HP::toThaiNumber($offend_power):null  );

        //ส่วนหัว 
        $templateProcessor->setValue('book_number',   !empty($fact_books->fact_book_numbers)?$fact_books->fact_book_numbers:null  );  
        $templateProcessor->setValue('book_date',     !empty($book_date)?$book_date:null  );
        // $templateProcessor->setValue('name',          !empty($fact_books->fact_offend_name)?$fact_books->fact_offend_name:null  );
        $templateProcessor->setValue('lawyer',        !empty($fact_books->fact_lawyer_by)?$fact_books->fact_lawyer_by:null  );
        $templateProcessor->setValue('section',       !empty($section)?$section:null  );

        //1. ข้อมูลเกี่ยวกับผู้กระทำผิด
        $templateProcessor->setValue('offend_name',   !empty($lawcases->offend_name)?($lawcases->offend_name.' / '.$lawcases->offend_taxid):null  );

        //2. ข้อมูลเกี่ยวกับใบอนุญาต
        $templateProcessor->setValue('license_tisi',  !empty($license_tisi)?$license_tisi:null  );
        $templateProcessor->setValue('license_other', !empty($license_other)?$license_other:null  );
        $templateProcessor->setValue('license_cur1',  !empty($license_currently1)?$license_currently1:null  );
        $templateProcessor->setValue('license_cur2',  !empty($license_currently2)?$license_currently2:null  );

        //3. ข้อเท็จจริง / ข้อมูลเกี่ยวกับผลิตภัณฑ์อุตสาหกรรม 
        $templateProcessor->setValue('tisi_name',     !empty($lawcases->tis)?$lawcases->tis->tb3_TisThainame:null  );
        $templateProcessor->setValue('tisi_no',       !empty($lawcases->tis)?$lawcases->tis->tb3_Tisno:null  );

        //ตารางผลิตภัณฑ์อุตสาหกรรม 
        $table_product = new Table( array('borderSize' => 0, 'borderColor' => 'blank', 'width' => 9000, 'unit' => TblWidth::TWIP) );
        $sum_product = 0;
        $sum_price   = 0;

        if( !empty($lawcases->impound_products) && count($lawcases->impound_products) >= 1 ){

            $myFontStyle        = [ 'name' => 'TH SarabunPSK', 'size' => 16] ;
            $myParagraphLeft    = [ 'align'=>'left', 'spaceBefore'=>50, 'spaceafter' => 50 ];
            $myParagraphCeneter = [ 'align'=>'center', 'spaceBefore'=>50, 'spaceafter' => 50 ];

            $styleCell          = [ 'borderTopColor' =>'blank', 'borderBottomColor' =>'blank', 'borderLeftColor' =>'blank', 'borderRightColor' =>'blank', 'borderSize' => 5 ];

            // $table_product->addRow();
            // $table_product->addCell(1000, $styleCell)->addText(  'ผลิตภัณฑ์',  $myFontStyle, $myParagraphLeft  );
            // $table_product->addCell(1000, $styleCell)->addText(  'จำนวน',     $myFontStyle, $myParagraphCeneter  );
            // $table_product->addCell(1000, $styleCell)->addText(  'ราคา/หน่วย', $myFontStyle, $myParagraphCeneter  );
            // $table_product->addCell(1000, $styleCell)->addText(  'รวมราคา',   $myFontStyle, $myParagraphCeneter  );

            foreach( $lawcases->impound_products AS  $products ){
                $product = 0;
                //จำนวนที่ยึด
                $product += (int)$products->amount_impounds;
                //จำนวนที่อายัด
                $product += (int)$products->amount_keep;

                $sum_product +=  $product;
                $sum_price   +=  $products->total_price;

                // $table_product->addRow();
                // $table_product->addCell(1000, $styleCell)->addText(  !empty($products->detail)?$products->detail:null , $myFontStyle, $myParagraphLeft    );
                // $table_product->addCell(1000, $styleCell)->addText(  number_format($product) , $myFontStyle, $myParagraphCeneter  );
                // $table_product->addCell(1000, $styleCell)->addText(  number_format($products->price,2) , $myFontStyle, $myParagraphCeneter  );
                // $table_product->addCell(1000, $styleCell)->addText(  number_format($products->total_price,2) , $myFontStyle, $myParagraphCeneter  );

            }

        }
        // $templateProcessor->setComplexBlock('table_product', $table_product);
        $templateProcessor->setValue('products',      number_format($sum_product)  );
        $templateProcessor->setValue('total',         number_format($sum_price,2)  );
        $templateProcessor->setValue('sum_price',     HP_Law::TextBathFormat((!empty($sum_price)?$sum_price:null)) );
        //แหล่งอ้างอิง
        $list_resource = '';
        foreach( LawResource::Where('state',1)->orderbyRaw('CONVERT(title USING tis620)')->get() AS $kr =>  $resource ){
            $kr++;
            $cheked = !empty($cases_impound->law_basic_resource_id) && $cases_impound->law_basic_resource_id == $resource->id ? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
            $list_resource .= ( $kr >= 2?str_repeat("\n\n", 15):'' ).$cheked.' '.( $resource->title ).( LawResource::count() != $kr ?'<w:br/>':'');
        }
        $templateProcessor->setValue('list_resource',  $list_resource  );

        //การยึด/อายัดผลิตภัณฑ์ฯ
        $impound_status1 =  $lawcases->impound_status == "1" || !empty($cases_impound)  ? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $impound_status2 =  $lawcases->impound_status == "0" || empty($cases_impound) ? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $templateProcessor->setValue('imp_s1',  !empty($impound_status1)?$impound_status1:null  );
        $templateProcessor->setValue('imp_s2',  !empty($impound_status2)?$impound_status2:null  );

        //แสดงเครื่องหมายผลิตภัณฑ์ฯ
        $fact_product_marking_1 = !empty($fact_books->fact_product_marking) && $fact_books->fact_product_marking == '1'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $fact_product_marking_2 = !empty($fact_books->fact_product_marking) && $fact_books->fact_product_marking == '2'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $templateProcessor->setValue('marking_1',  !empty($fact_product_marking_1)?$fact_product_marking_1:null  );
        $templateProcessor->setValue('marking_2',  !empty($fact_product_marking_2)?$fact_product_marking_2:null  );

        //การจำหน่ายผลิตภัณฑ์ฯ
        $fact_product_sell_1 = !empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == '1'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $fact_product_sell_2 = !empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == '2'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $fact_product_sell_3 = !empty($fact_books->fact_product_sell) && $fact_books->fact_product_sell == '3'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $templateProcessor->setValue('sell1',  !empty($fact_product_sell_1)?$fact_product_sell_1:null  );
        $templateProcessor->setValue('sell2',  !empty($fact_product_sell_2)?$fact_product_sell_2:null  );
        $templateProcessor->setValue('sell3',  !empty($fact_product_sell_3)?$fact_product_sell_3:null  );

        //ผลิตภัณฑ์ฯที่เรียกคืนได้
        $fact_product_reclaim_1 = !empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == '1'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $fact_product_reclaim_2 = !empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == '2'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $fact_product_reclaim_3 = !empty($fact_books->fact_product_reclaim) && $fact_books->fact_product_reclaim == '3'? '<w:sym w:font="Wingdings 2" w:char="0055"/>':'<w:sym w:font="Wingdings 2" w:char="0099"/>';
        $templateProcessor->setValue('recl1',  !empty($fact_product_reclaim_1)?$fact_product_reclaim_1:null  );
        $templateProcessor->setValue('recl2',  !empty($fact_product_reclaim_2)?$fact_product_reclaim_2:null  );
        $templateProcessor->setValue('recl3',  !empty($fact_product_reclaim_3)?$fact_product_reclaim_3:null  );

        // วันที่พบการกระทำความผิด
        $templateProcessor->setValue('offend_date',   !empty($lawcases->offend_date)?HP::formatDateThaiFullPoint($lawcases->offend_date):null  );  

         // สถานที่เกิดเหตุ
        $templateProcessor->setValue('adress',   !empty($lawcases->OffendDataAdress)?$lawcases->OffendDataAdress:null  ); 

        // ชื่อกระทำความผิด
        $templateProcessor->setValue('name',   !empty($lawcases->offend_name)?$lawcases->offend_name:null  ); 

        $templateProcessor->setValue('offend_name_and_power',   !empty($offend_name)?$offend_name:null  ); 

 
        
        $consider =  [];
  
        if(!empty($lawcases->offend_books->offend_act) && count($lawcases->offend_books->offend_act) > 0){
            foreach($lawcases->offend_books->offend_act as $key => $item){
                $array           = [];
                $array['no']     = '7.'.($key+1);
                $array['act']    =  $item;
                $consider[]      =  $array;
            }
        }
        

        $templateProcessor->cloneBlock('consider', 0, true, false, $consider);

        $offender_cases =  $lawcases->offender_cases_many ;
        $templateProcessor->setValue('circlex', '<w:sym w:font="Wingdings 2" w:char="0055"/>');   
        $templateProcessor->setValue('boxcheck', '<w:sym w:font="Wingdings 2" w:char="0052"/>');   
        $templateProcessor->setValue('box1', '<w:sym w:font="Wingdings 2" w:char="00A3"/>');   
        $templateProcessor->setValue('offender1',  count($offender_cases) == 0 ? '<w:sym w:font="Wingdings 2" w:char="0052"/>':'<w:sym w:font="Wingdings 2" w:char="00A3"/>' );  
        $templateProcessor->setValue('offender2',  count($offender_cases) >= 1  ? '<w:sym w:font="Wingdings 2" w:char="0052"/>':'<w:sym w:font="Wingdings 2" w:char="00A3"/>' );  
        $templateProcessor->setValue('cases_count', !empty($lawcases->offender) ?  count($lawcases->offender->offender_cases->where('law_cases_id','<=',$id)) :  ''   );     

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



        $templateProcessor->saveAs(storage_path('/Temp-file/'.'หนังสือข้อเท็จจริง_'.$lawcases->ref_no.'_'. $date_time  . '.docx'));
        $fontStyle->setName('THSarabunPSK');
        return response()->download(storage_path('/Temp-file/'.'หนังสือข้อเท็จจริง_'.$lawcases->ref_no .'_'. $date_time  . '.docx'));

    }
    
}
