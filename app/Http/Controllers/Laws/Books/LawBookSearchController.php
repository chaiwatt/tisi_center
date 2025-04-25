<?php

namespace App\Http\Controllers\Laws\Books;

use HP;
use ZipArchive;
use Storage;
use File;

use App\Http\Requests;
use App\Models\Tis\Standard;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Models\Law\Books\LawBookManage;
use Illuminate\Support\Facades\Session;
use App\Models\Law\Books\LawBookManageVisit;

class LawBookSearchController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search    = $request->input('filter_search');
        $filter_group     = $request->input('filter_group');
        $filter_type      = $request->input('filter_type');
        $filter_tap_type  = $request->input('filter_tap_type');

        $query = LawBookManage::query()->where('state',1)
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    return $query->where( function($query) use($search_full) {
                                                                $query->Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                            });
                                                    break;
                                                case "2":
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    return $query->where( function($query) use($search_full) {
                                                                $query->Where(DB::Raw("REPLACE(important,' ','')"),  'LIKE', "%$search_full%");
                                                            });
                                                    break;
                                                case "3":
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    return $query->where( function($query) use($search_full) {
                                                                $query->Where(DB::Raw("REPLACE(description,' ','')"),  'LIKE', "%$search_full%");
                                                            });
                                                break;
                                                case "4":
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    return $query->where( function($query) use($search_full) {
                                                                $query->whereJsonContains('tag', $search_full);
                                                            });
                                                    break;
                                                default:
                                                    $search_full = str_replace(' ', '', $filter_search );
                                                    return $query->where( function($query) use($search_full) {
                                                                $query->Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")
                                                                        ->Orwhere( function($query) use($search_full) {
                                                                            $query->Where(DB::Raw("REPLACE(important,' ','')"),  'LIKE', "%$search_full%");
                                                                        })
                                                                        ->Orwhere( function($query) use($search_full) {
                                                                            $query->Where(DB::Raw("REPLACE(description,' ','')"),  'LIKE', "%$search_full%");
                                                                        })
                                                                        ->Orwhere( function($query) use($search_full) {
                                                                            $query->whereJsonContains('tag', $search_full);
                                                                        });
                                                            });
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_group, function ($query, $filter_group){
                                            return $query->where('basic_book_group_id', $filter_group);
                                        })
                                        ->when($filter_type, function ($query, $filter_type){
                                            return $query->where('basic_book_type_id', $filter_type);
                                        })
                                        ->when($filter_tap_type, function ($query, $filter_tap_type){
                                            return $query->WhereIn('basic_book_type_id', $filter_tap_type );
                                        })
                                        ->where( function($query){
                                            $query->whereHas('manage_access', function ($query) {
                                                        // $query->whereJsonContains('access',  2);
                                                        $query->where(DB::Raw("REPLACE(access,' ','')"),  'LIKE', "%2%");
                                                    });
                                        })
                                        ->where(function($query){
                                            $query->whereHas('manage_access', function ($query) {   
                                                    $reg_subdepart = auth()->user()->reg_subdepart;
                                                    $query->where(DB::Raw("REPLACE(access_tisi,' ','')"),  'LIKE', "%$reg_subdepart%")
                                                          ->orwhereNull('access_tisi');
                                            });
                                        });

        return Datatables::of($query)
                            ->addColumn('title', function ($item) {

                                $cover_url = 'images/logo01.png' ;
								$file_image_cover = $item->FileImageCoverBookManage;
								if(!is_null($file_image_cover)){
									$file_cover_url = HP::getFileStorage($file_image_cover->url);
									if (!empty($file_cover_url)){
										$cover_url = $file_cover_url;
									}
								}
                                //ไฟล์แนบห้องสมุด
                                $attachs = $item->AttachFileBookManage;
                                $file_properties = (!empty($attachs) && count($attachs) > 0) ? $attachs->pluck('file_properties')->implode(', '):'-';

								$html  = '';
								$html .= '<div class="col-md-1 col-xs-4"><img src="'.asset($cover_url).'" class="img-responsive img-rounded" width="80%"/></div>';

								$html .= '<div class="col-md-9 col-xs-8">';
                                $html .= 	'<div class="font-20"><a href="'.url('law/book/search/'.$item->id).'">'.$item->title.'</a></div>';
								$html .= 	'<div class="font-15">วันที่เผยแพร่: '.HP::revertDate($item->date_publish, true).' | ชนิดไฟล์: '.($file_properties).' | ดาวน์โหลด: '.$item->BookManageVisitDownload->count().' | เข้าชม: '.$item->BookManageVisitView->count().' | หมวดหมู่: '.(!empty($item->BookGroupName)?$item->BookGroupName:' - ').' | ประเภท: '.(!empty($item->BookTypeName)?$item->BookTypeName:' - ').'</div>';
								$html .= '</div>';

                                if(!empty($attachs) ){
                                    if( count($attachs) == 1){
                                        foreach ($attachs as $attach){
                                            $html .= '<div class="col-md-1 col-xs-12 ">';
                                            $html .= 	'<a href="'.url('law/book/search/'.$item->id).'" class="btn btn-light-info pull-right">อ่านต่อ</a>';
                                            $html .= '</div>';
                                            $html .= '<div class="col-md-1 col-xs-12 ">';
                                            $html .= 	'<a href="'. HP::getFileStorage($attach->url) .'" class="btn btn-light-primary" target="_blank">ดาวน์โหลด</a>';
                                            $html .= '</div>';
                                       }
                                    }else if( count($attachs) > 1){
                                        $html .= '<div class="col-md-1 col-xs-12 ">';
                                        $html .= 	'<a href="'.url('law/book/search/'.$item->id).'" class="btn btn-light-info pull-right">อ่านต่อ</a>';
                                        $html .= '</div>';
                                        $html .= '<div class="col-md-1 col-xs-12 ">';
                                        $html .= 	'<a href="'. url('/law/book/search/download/' .base64_encode($item->id) ) . '" class="btn btn-light-primary" target="_blank">ดาวน์โหลด</a>';
                                        $html .= '</div>';
                                    }else{
                                        $html .= '<div class="col-md-2 col-xs-12 ">';
                                        $html .= 	'<a href="'.url('law/book/search/'.$item->id).'" class="btn btn-light-info pull-right">อ่านต่อ</a>';
                                        $html .= '</div>';
                                    }
                    
                                }

                                return $html;

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['title'])
                            ->make(true);
    }

    public function download($id)
    {      
        $id =   base64_decode($id);
        $book_manage = LawBookManage::findOrFail($id);

        $public_dir = public_path("uploads/law_attach/book_manage");
        $zipFileName = 'AllDocuments.zip';
  
        //ไฟล์แนบห้องสมุด
        $attachs = $book_manage->AttachFileBookManage;
        if(!empty($attachs) && count($attachs) > 0){
 
            // Create ZipArchive Obj
            $zip = new ZipArchive;
                 if ($zip->open($public_dir . '/' . $zipFileName, \ZipArchive::CREATE | ZipArchive::OVERWRITE ) === TRUE) {

                        foreach($attachs as $item){
                            if(HP::checkFileStorage($item->url)){
                                $file = public_path('uploads/'.$item->url);
                                $zip->addFile($file, basename($file));
                            }    
                        }

                    $zip->close();
                }

                if(is_file(public_path("uploads/law_attach/book_manage/{$zipFileName}"))){
                    return response()->download(public_path("uploads/law_attach/book_manage/{$zipFileName}"));
                }else{
                    return redirect('law/book/search')->with('error_message');
                }

         } else {
              return redirect('law/book/search')->with('error_message');
         }
   }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/search",  "name" => 'สืบค้นข้อมูลห้องสมุด' ],
            ];
            return view('laws.books.search.index',compact('breadcrumbs'));

        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {
            $book_manage = LawBookManage::findOrFail($id);
            $this->SaveVisitView($book_manage->id);
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/search",  "name" => 'สืบค้นข้อมูลห้องสมุด' ],
                [ "link" => "/law/book/search/$id",  "name" => 'รายละเอียด' ],
            ];

            return view('laws.books.search.show',compact('book_manage','breadcrumbs'));

        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = str_slug('law-book-search','-');
        if(auth()->user()->can('view-'.$model)) {

        }
        abort(403);
    }

    public function SaveVisitView($law_book_manage_id)
    {
        $sessionId = Session::getId();
        $requestData['section_id'] = $sessionId;
        $requestData['law_book_manage_id'] = $law_book_manage_id;
        $requestData['system_type'] = 2;
        $requestData['action'] = 1;
        $requestData['visit_at'] = date('Y-m-d H:i:s');
        LawBookManageVisit::create($requestData);
    }

    public function Details(Request $request)
    {

        $id = $request->input('id');

        $item = LawBookManage::findOrFail($id);

        return view('laws.books.search.table.accordion',compact('item'));
    }
}
