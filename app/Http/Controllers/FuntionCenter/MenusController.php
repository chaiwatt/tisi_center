<?php

namespace App\Http\Controllers\FuntionCenter;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use stdClass;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use HP;
class MenusController extends Controller
{

    public function AllMenuCertify()
    {
        $menu = [];
        if( Auth::check() ){

            $laravelMenu = [];
            if (File::exists(base_path('resources/laravel-admin/new-menu-certify.json'))) {
                $laravelMenu = json_decode(File::get(base_path('resources/laravel-admin/new-menu-certify.json')));
            }

            //รับรองระบบงาน LAB
            if( isset( $laravelMenu->menus[0]->items[1]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[1]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับรองระบบงาน LAB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[1]->sub_menus, 'certify_lab', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //รับรองระบบงาน CB
            if( isset( $laravelMenu->menus[0]->items[2]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[2]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับรองระบบงาน CB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[2]->sub_menus, 'certify_cb', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //รับรองระบบงาน IB
            if( isset( $laravelMenu->menus[0]->items[3]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[3]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับรองระบบงาน IB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[3]->sub_menus, 'certify_ib', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //รับรองระบบงาน Other
            if( isset( $laravelMenu->menus[0]->items[4]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[4]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับรองระบบงาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[4]->sub_menus, 'certify_other', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //ตรวจติดตามใบรับรอง LAB
            if( isset( $laravelMenu->menus[0]->items[6]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[6]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ตรวจติดตามใบรับรอง LAB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[6]->sub_menus, 'certify_tracking', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //ตรวจติดตามใบรับรอง CB
            if( isset( $laravelMenu->menus[0]->items[7]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[7]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ตรวจติดตามใบรับรอง CB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[7]->sub_menus, 'certify_tracking_cb', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }

            //ตรวจติดตามใบรับรอง IB
            if( isset( $laravelMenu->menus[0]->items[8]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[8]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ตรวจติดตามใบรับรอง IB';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[8]->sub_menus, 'certify_tracking_ib', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }


            //ลงนามอิเล็กทรอนิกส์
            if( isset( $laravelMenu->menus[0]->items[5]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[5]->sub_menus)  ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ลงนามอิเล็กทรอนิกส์';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[5]->sub_menus, 'certify_certificates', 'bg-dashboard5' );
                $menu[] = $menuItem;
            }


            //Report
            if( isset( $laravelMenu->menus[0]->items[9]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[9]->sub_menus)  ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รายงาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[9]->sub_menus, 'certify_report', 'bg-dashboard5'  );
                $menu[] = $menuItem;
            }

            //Basic
            if( isset( $laravelMenu->menus[0]->items[0]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[0]->sub_menus)  ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ข้อมูลพื้นฐาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[0]->sub_menus, 'certify_basic', 'bg-dashboard8');
                $menu[] = $menuItem;
            }

        }
        return $menu;

    }

    public function AllMenuESurv()
    {
        $menu = [];
        if( Auth::check() ){

            $laravelMenu = [];
            if (File::exists(base_path('resources/laravel-admin/new-menu-e-surv.json'))) {
                $laravelMenu = json_decode(File::get(base_path('resources/laravel-admin/new-menu-e-surv.json')));
            }

            //ข้อมูลพื้นฐาน
            if( isset( $laravelMenu->menus[0]->items[0]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[0]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ข้อมูลพื้นฐาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[0]->sub_menus, 'esurv_basic', 'bg-dashboard5');
                $menu[] = $menuItem;
            }

            //รับแจ้งตามเงื่อนไข
            if( isset( $laravelMenu->menus[0]->items[1]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[1]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับแจ้งตามเงื่อนไข';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[1]->sub_menus, 'esurv_esurv_condition', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //Self Declaration
            if( isset( $laravelMenu->menus[0]->items[2]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[2]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'Self Declaration';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[2]->sub_menus, 'esurv_esurv_self_declaration', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //การตรวจติดตาม
            if( isset( $laravelMenu->menus[0]->items[3]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[3]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'การตรวจติดตาม';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[3]->sub_menus, 'esurv_esurv', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //ข้อมูลอื่นๆ
            if( isset( $laravelMenu->menus[0]->items[4]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[4]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ข้อมูลอื่นๆ';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[4]->sub_menus, 'esurv_esurv_other', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //รับคําขอ/รับแจ้ง 20 ตรี, 20 ทวิ
            if( isset( $laravelMenu->menus[0]->items[5]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[5]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับคําขอ/รับแจ้ง 20 ตรี, 20 ทวิ';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[5]->sub_menus, 'esurv_esurv_20', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //รับคําขอ/รับแจ้ง 21 ตรี, 21 ทวิ
            if( isset( $laravelMenu->menus[0]->items[6]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[6]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รับคําขอ/รับแจ้ง 21 ตรี, 21 ทวิ';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[6]->sub_menus, 'esurv_esurv_21', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //รายงาน
            if( isset( $laravelMenu->menus[0]->items[7]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[7]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รายงาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[7]->sub_menus, 'esurv_report', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //ระบบงานคดี
            $laravelMenuLaw = [];
            if (File::exists(base_path('resources/laravel-admin/new-menu-law.json'))) {
                $laravelMenuLaw = json_decode(File::get(base_path('resources/laravel-admin/new-menu-law.json')));
            }

            if( isset( $laravelMenuLaw->menus[0]->items[4]->sub_menus ) && HP::CheckMenuItem($laravelMenuLaw->menus[0]->items[4]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ระบบงานคดี';
                $menuItem->submenu = $this->SetMenuESurvLaw($laravelMenuLaw->menus[0]->items[4]->sub_menus, 'case_laws', 'bg-primary');
                $menu[] = $menuItem;
            }

        }

        return $menu;
    }

    public function AllMenuTis()
    {
        $menu = [];
        if( Auth::check() ){
            $laravelMenu = [];
            if (File::exists(base_path('resources/laravel-admin/new-menu-standards.json'))) {
                $laravelMenu = json_decode(File::get(base_path('resources/laravel-admin/new-menu-standards.json')));
            }

            //กำหนดมาตรฐาน
            if( isset( $laravelMenu->menus[0]->items[1]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[1]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'กำหนดมาตรฐาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[1]->sub_menus, 'standards', 'bg-dashboard3');
                $menu[] = $menuItem;
            }

            //รายงาน
            if( isset( $laravelMenu->menus[0]->items[2]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[2]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'รายงาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[2]->sub_menus, 'report', 'bg-dashboard2');
                $menu[] = $menuItem;
            }

            //ข้อมูลพื้นฐาน
            if( isset( $laravelMenu->menus[0]->items[0]->sub_menus ) && HP::CheckMenuItem($laravelMenu->menus[0]->items[0]->sub_menus) ){
                $menuItem          = new stdClass;
                $menuItem->title   = 'ข้อมูลพื้นฐาน';
                $menuItem->submenu = $this->SetMenu($laravelMenu->menus[0]->items[0]->sub_menus, 'bs_standard', 'bg-dashboard8');
                $menu[] = $menuItem;
            }
        }

        return $menu;
    }


    public function SetMenu($menu, $type = '', $color = 'bg-success')
    {

        $submenu = [];

        foreach( $menu AS $item ){

            if( isset( $item->title ) &&  auth()->user()->can('view-'.str_slug(  $item->title )) ){

                $submenu[] =  [
                                "short" => $item->display,
                                "title" => 'ระบบ'.$item->display,
                                "slug"  => $item->title,
                                "name"  => !empty($item->name)?$item->name:null,
                                "icon"  => $item->icon,
                                "url"   => $item->url,
                                "color" =>  $color,
                                "class" => $this->ClassNameInfo( $item->display )

                            ];
            }else if( !isset( $item->title )  ) {
                $submenu[] =  [
                            "short" => $item->display,
                            "title" => 'ระบบ'.$item->display,
                            // "slug"  => $item->title,
                            "name"  => !empty($item->name)?$item->name:null,
                            "icon"  => $item->icon,
                            "url"   => $item->url,
                            "color" =>  $color,
                            "class" => $this->ClassNameInfo( $item->display )

                        ];
            }

        }

        return $submenu;

    }

    public function SetMenuESurvLaw($menu, $type = '', $color = 'bg-success')
    {
 
        $submenu = [];
        foreach( $menu AS $key => $item ){
            if(in_array($key,[0,1,9,11])){
                if( isset( $item->title ) &&  auth()->user()->can('view-'.str_slug(  $item->title )) ){

                    $submenu[] =  [
                                    "short" => $item->display,
                                    "title" => 'ระบบ'.$item->display,
                                    "slug"  => $item->title,
                                    "name"  => !empty($item->name)?$item->name:null,
                                    "icon"  => $item->icon,
                                    "url"   => $item->url,
                                    "color" =>  $color,
                                    "class" => $this->ClassNameInfo( $item->display )
    
                                ];
                }else if( !isset( $item->title )  ) {
                    $submenu[] =  [
                                "short" => $item->display,
                                "title" => 'ระบบ'.$item->display,
                                // "slug"  => $item->title,
                                "name"  => !empty($item->name)?$item->name:null,
                                "icon"  => $item->icon,
                                "url"   => $item->url,
                                "color" =>  $color,
                                "class" => $this->ClassNameInfo( $item->display )
    
                            ];
                }
    
            }

        }

        return $submenu;

    }
    
    public function ClassNameInfo($string)
    {
        $count =  mb_strlen( $string, 'UTF-8' );

        if( $count >= 25 && $count < 33  ){
            $class = 'info-count2';
        }else if( $count >= 33   ){
            $class = 'info-count3';
        }else{
            $class = 'info-count';
        }

        return $class;
    }

}
