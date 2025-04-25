<?php

namespace App\Http\Controllers\FuntionCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use HP;
use stdClass;
use Carbon\Carbon;

use App\Models\Elicense\ELoc\RosManufacturerForeign;
use App\Models\Elicense\ELoc\RosManufacturerForeignScope;

use App\Models\Elicense\Tis\RosStandardTisi;


class ElicenseController extends Controller
{
    public function __construct()
    {
        set_time_limit(0);
        ini_set("pcre.backtrack_limit", "1000000000");
    }
    public function ImpDataManufacture()
    {

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'กลุ่มระบบงาน');
        $sheet->setCellValue('B1', 'กลุ่ม');
        $sheet->setCellValue('C1', 'ระบบงาน');
        $sheet->setCellValue('D1', 'url');

        $row = 1;

        foreach (  HP::MenuSidebar() as $section ){
            $row++;
            $group_main =  $row;
            $sheet->setCellValue('A'.$row, $section->_comment);
            foreach ( $section->items as $menu ){
              

                $sheet->setCellValue('B'.$row,  $menu->display );
                if( isset($menu->sub_menus) ){
                    foreach(  $menu->sub_menus as $sub_menus ){
                       
                        $sheet->setCellValue('C'.$row,  $sub_menus->display );
                        $sheet->setCellValue('D'.$row,  url($sub_menus->url)  );

                        $row++;
                    }
                }else{
                    $sheet->setCellValue('C'.$row,  $menu->display );
                    $sheet->setCellValue('D'.$row,  url($menu->url)  );

                    $row++;

                   
                }
                $row++;


            }

        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        
        $filename = ('Menu').date('_Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }

    public static function GenNemberCode($year){

        $Type = 'RMF-';
        $Year = $year;

        $new_run = null;
        $list_code = RosManufacturerForeign::select('factory_number')->whereYear('created',$Year)->where('factory_number',  'LIKE', "%$Type%")->orderBy('factory_number')->pluck('factory_number')->toArray();

        usort($list_code, function($x, $y) {
            return $x > $y;
        });

        $last = end($list_code);

        // $last = $Type.$Year."-"."00001";
        // $cut = explode('-', $last );

        // dd( $cut);
        $number = 0;
        if( count($list_code) > 0 ){

            $cut = explode('-', $last );
            $number = (int)$cut[2];
         
            $Seq = substr("0000".((string)$number + 1),-4,4);
            $new_run = $Type.$Year."-".$Seq;

            $check = RosManufacturerForeign::where('factory_number', $new_run )->first();
            if(!empty($check)){
                $number = (int)$cut[2];
                $Seq = substr("0000".((string)$number + 2),-4,4);
                $new_run = $Type.$Year."-".$Seq;
            }

        }else{
            $Seq = substr("0000".((string)$number + 1),-4,4);
            $new_run = $Type.$Year."-".$Seq;
        }

        return $new_run;
    }

    public function FilterFormatName($txt)
    {
        $string = htmlspecialchars( $txt );
        $string = str_replace(' ', '', $string);

        $last = mb_substr( $string, -1, 1 );

        if( $last == "." ){
            $substr = mb_substr($string, 0, -1);
            $string = $substr;
        }

        if( strpos(  $string  , "–" ) || strpos(  $string  , "-" ) ) {
            $replace = str_replace(array("–","-"),'',htmlspecialchars($string));
            $string = $replace;
        }

        if( strpos(  $string  , "." ) || strpos(  $string  , "," ) ) {
            $replace = str_replace(array(".",","),'',htmlspecialchars($string));
            $string = $replace;
        }

        if( strpos(  $string  , "(" ) || strpos(  $string  , ")" ) ) {
            $replace = str_replace(array("(",")"),'',htmlspecialchars($string));
            $string = $replace;
        }

        $string = strip_tags($string);
        $string = trim($string);

        $string = strtolower( $string );

        return $string;

    }

    public function FilterFormatAddress($txt)
    {
        $string = htmlspecialchars( $txt );
        $string = str_replace(' ', '', $string);
        $string = str_replace(',', '', $string);

        if( strpos(  $string  , "–" ) || strpos(  $string  , "-" ) ) {
            $replace = str_replace(array("–","-"),'',htmlspecialchars($string));
            $string = $replace;
        }

        if( strpos(  $string  , "city" ) ){
            $string = str_replace('city', '', $string);
        }

        $string = strip_tags($string);
        $string = trim($string);

        $string = strtolower( $string );

        return $string;

    }

    public function FilterFormatCountry($txt)
    {
        $string = htmlspecialchars( $txt );

        if( strpos(  $string  , "&amp;" ) ){
            $string = str_replace('&amp;', '', $string);
        }

        if( strpos(  $string  , "amp;" ) ){
            $string = str_replace('amp;', '', $string);
        }
      
        if( strpos(  $string  , "&quot;" ) ){
            $string = str_replace('&quot;', '', $string);
        }

        if( strpos(  $string  , "quot;" ) ){
            $string = str_replace('quot;', '', $string);
        }

        
        $string = str_replace('  ', '', $string);

        $string = strip_tags($string);
        $string = trim($string);
        return $string;
    }

    function FilterNl2( $txt ){

        $string = htmlspecialchars( $txt );
        $string = str_replace(array("\r\n","\r","\n"),'',$string);

        if( strpos(  $string  , "&amp;" ) ){
            $string = str_replace('&amp;', '', $string);
        }

        if( strpos(  $string  , "amp;" ) ){
            $string = str_replace('amp;', '', $string);
        }
      
        if( strpos(  $string  , "&quot;" ) ){
            $string = str_replace('&quot;', '', $string);
        }

        if( strpos(  $string  , "quot;" ) ){
            $string = str_replace('quot;', '', $string);
        }

        $string = str_replace('  ', '', $string);

        $string = strip_tags($string);
        $string = trim($string);

        return $string;

    }
}
