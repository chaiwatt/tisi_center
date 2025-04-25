<?php

namespace App\Http\Controllers\Laws\Books;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Law\Books\LawBookManage;
use App\Models\Law\Basic\LawBookType;
use App\Models\Law\Basic\LawBookGroup;
use App\Models\Law\Books\LawBookManageAccess;
use App\Models\Law\Books\LawBookManageVisit;
class LawBookManageController extends Controller
{
    private $attach_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->attach_path = 'law_attach/book_manage';
    }

    public function data_list(Request $request)
    {
        $filter_condition_search = $request->input('filter_condition_search');
        $filter_search           = $request->input('filter_search');
        $filter_book_group       = $request->input('filter_book_group');
        $filter_book_type        = $request->input('filter_book_type');
        $filter_access           = $request->input('filter_access');
        $filter_checkfile        = $request->input('filter_checkfile');
        $filter_status           = $request->input('filter_status');
        $filter_created_at       = !empty($request->input('filter_created_at'))? HP::convertDate($request->input('filter_created_at'),true):null;

        $query = LawBookManage::query()->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                        switch ( $filter_condition_search ):
                                            case "1":
                                                return $query->Where('title', 'LIKE', '%' . $filter_search . '%');
                                                break;
                                            case "2":
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $book_type   = LawBookType::Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                return $query->whereIn('basic_book_type_id', $book_type);
                                                break;
                                            case "3":
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $book_group  = LawBookGroup::Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                return $query->whereIn('basic_book_group_id', $book_group);
                                                break;
                                            default:
                                                $search_full = str_replace(' ', '', $filter_search);
                                                $book_group  = LawBookGroup::Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                $book_type   = LawBookType::Where(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%")->select('id');
                                                return  $query->where(function ($query2) use($book_type, $book_group, $search_full) {
                                                            $query2->whereIn('basic_book_group_id', $book_group)
                                                                    ->orWhereIn('basic_book_type_id', $book_type)
                                                                    ->orWhere(DB::Raw("REPLACE(title,' ','')"),  'LIKE', "%$search_full%");
                                                        });
                                                break;
                                        endswitch;
                                    })
                                    ->when($filter_status, function ($query, $filter_status){
                                        if( $filter_status == 1){
                                            return $query->where('state', $filter_status);
                                        }else{
                                            return $query->where('state', '<>', 1);
                                        }
                                    })
                                    ->when($filter_book_group, function ($query, $filter_book_group){
                                        return $query->where('basic_book_group_id', $filter_book_group);
                                    })
                                    ->when($filter_book_type, function ($query, $filter_book_type){
                                        return $query->where('basic_book_type_id', $filter_book_type);
                                    })
                                    ->when($filter_access, function ($query, $filter_access){
                                        $book_manage_id = LawBookManageAccess::Where(DB::Raw("REPLACE(access,' ','')"),  'LIKE', "%$filter_access%")->select('law_book_manage_id');
                                        return $query->whereIn('id', $book_manage_id);
                                    })
                                    ->when($filter_checkfile, function ($query, $filter_checkfile){
                                        if( $filter_checkfile == 1){
                                            $query->doesntHave('AttachFileBookManage'); 
                                        }                  
                                    })
                                    ->when($filter_created_at, function ($query, $filter_created_at){
                                        return $query->whereDate('created_at', $filter_created_at);
                                    });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" data-title="'.$item->title.'" value="'. $item->id .'">';
                            })
                            ->addColumn('title', function ($item) {
                                return !empty($item->title)?$item->title:null;
                            })
                            ->addColumn('type_name', function ($item) {
                                return (!empty($item->BookTypeName)?$item->BookTypeName:null).(!empty($item->BookGroupName)?'<br>'.'<p class="text-muted font-16">'.$item->BookGroupName.'</p>':null);
                            })
                            ->addColumn('file_count', function ($item) {
                                return @count($item->AttachFileBookManage);
                            })
                            ->addColumn('manage_access', function ($item) {
                                return !empty($item->ManageAccessName)?$item->ManageAccessName:null;
                            })
                            ->addColumn('manage_visit_view', function ($item) {
                                return @count($item->BookManageVisitView);
                            })
                            ->addColumn('manage_visit_download', function ($item) {
                                return @count($item->BookManageVisitDownload);
                            })
                            ->addColumn('status', function ($item) {
                                return  @$item->StateIcon;
                            })
                            ->addColumn('created_by', function ($item) {
                                $created_by = '';
                                $created_by .= !empty($item->CreatedName)?$item->CreatedName:'-';
                                $created_by .= !empty($item->created_at)?' <br> '.HP::DateThai($item->created_at):null;
                                return $created_by;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonActionLaw( $item->id, 'law/book/manage','Laws\Books\\LawBookManageController@destroy', 'law-book-manage');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_by','manage_access', 'created_at','manage_visit_view','type_name'])
                            ->make(true);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/manage",  "name" => 'จัดการข้อมูลห้องสมุด' ],
            ];
            return view('laws.books.manage.index',compact('breadcrumbs'));
        }
        abort(403);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('add-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/manage",  "name" => 'จัดการข้อมูลห้องสมุด' ],
                [ "link" => "/law/book/manage/create",  "name" => 'เพิ่ม' ],

            ];
            return view('laws.books.manage.create',compact('breadcrumbs'));
        }
        return response(view('403'), 403);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('add-'.$model)) {

            $requestData = $request->all();
            $requestData['access_tisi']   = !empty( $request->input('access_tisi') )?json_encode($request->input('access_tisi')):null;
            $requestData['access']        = !empty( $request->input('access') )?json_encode($request->input('access')):null;
            $requestData['tag']           = !empty( $request->input('tag') )? json_encode(explode(',', $request->input('tag'))):null;
            $requestData['date_publish']  = !empty( $request->date_publish) ?  HP::convertDate($request->date_publish,true) : null;
            $requestData['created_by']    =  auth()->user()->getKey();

            $urls                         = !empty( $request->input('url'))?$request->input('url'):[];
            $url_descriptions             = !empty ($request->input('url_description'))?$request->input('url_description'):[];
            if( !empty($urls) && count($urls) > 0 ){

                foreach ($urls as $key => $url ) {

                    if( array_key_exists( $key , $url_descriptions ) && !empty($url_descriptions[$key]) ){
                        $array[$key]['url_description'] = $url_descriptions[$key];
                    }
                    if( array_key_exists( $key , $urls ) && !empty($urls[$key]) ){
                        $array[$key]['url'] = $url;
                    }

                }
                $requestData['url'] = isset($array)?json_encode($array):null;

            }

            $book_manage = LawBookManage::create($requestData);

            $this->SaveLawBookManageAccess($book_manage,$requestData);

            //ไฟล์ภาพหน้าปก
            $tax_number = (!empty(auth()->user()->reg_13ID) ?  str_replace("-","", auth()->user()->reg_13ID )  : '0000000000000');
            if($request->hasFile('file_image_cover') && $request->file('file_image_cover')->isValid()){
                $file_image_cover = $requestData['file_image_cover'];
                HP::singleFileUploadLaw(
                    $file_image_cover,
                    $this->attach_path,
                    $tax_number,
                    (auth()->user()->FullName ?? null),
                    'Law',
                    ((new LawBookManage)->getTable()),
                    $book_manage->id,
                    'file_image_cover',
                    'ไฟล์ภาพหน้าปก'
                );
            }

            //ไฟล์เเนบ
            if(isset( $requestData['repeater-attach'] ) ){
               $attachs = $requestData['repeater-attach'];
               foreach( $attachs as $file ){
                   if( isset($file['file_book_manage']) && !empty($file['file_book_manage']) ){
                       HP::singleFileUploadLaw(
                           $file['file_book_manage'],
                           $this->attach_path,
                           ( $tax_number),
                           (auth()->user()->FullName ?? null),
                           'Law',
                           (  (new LawBookManage)->getTable() ),
                           $book_manage->id,
                           'file_book_manage',
                           'ไฟล์แนบจัดการข้อมูลห้องสมุด'
                       );
                   }
               }
           }

           return redirect('law/book/manage/'.($book_manage->id).'/edit')->with('success_message', 'เพิ่มข้อมูลเรียบร้อยแล้ว');

        }
        return response(view('403'), 403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('view-'.$model)) {
            $book_manage        = LawBookManage::findOrFail($id);
            $book_manage_access = LawBookManageAccess::where('law_book_manage_id', $book_manage->id )->first();

            $book_manage->tag          =  !empty( $book_manage->tag )?implode(',',json_decode($book_manage->tag,true)):null;
            $book_manage->url          =  !empty( $book_manage->url )?json_decode($book_manage->url,true):null;
            $book_manage->operation_date =  !empty( $book_manage->operation_date)?HP::revertDate($book_manage->operation_date,true):null;
            $book_manage->access       =  !empty( $book_manage_access->access )?json_decode($book_manage_access->access,true):null;
            $book_manage->access_tisi  =  !empty( $book_manage_access->access_tisi )?json_decode($book_manage_access->access_tisi,true):null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/manage",  "name" => 'จัดการข้อมูลห้องสมุด' ],
                [ "link" => "/law/book/manage/$id",  "name" => 'รายละเอียด' ],
            ];

            return view('laws.books.manage.show', compact('book_manage','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('edit-'.$model)) {
            $book_manage        = LawBookManage::findOrFail($id);
            $book_manage_access = LawBookManageAccess::where('law_book_manage_id', $book_manage->id )->first();

            $book_manage->tag          =  !empty( $book_manage->tag )?implode(',',json_decode($book_manage->tag,true)):null;
            $book_manage->url          =  !empty( $book_manage->url )?json_decode($book_manage->url,true):null;
            $book_manage->operation_date =  !empty( $book_manage->operation_date)?HP::revertDate($book_manage->operation_date,true):null;
            $book_manage->access       =  !empty( $book_manage_access->access )?json_decode($book_manage_access->access,true):null;
            $book_manage->access_tisi  =  !empty( $book_manage_access->access_tisi )?json_decode($book_manage_access->access_tisi,true):null;

            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/book/manage",  "name" => 'จัดการข้อมูลห้องสมุด' ],
                [ "link" => "/law/book/manage/$id/edit",  "name" => 'แก้ไข' ],
            ];

            return view('laws.books.manage.edit', compact('book_manage','breadcrumbs'));
        }
        return response(view('403'), 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('edit-'.$model)) {

            $book_manage = LawBookManage::findOrFail($id);
            $requestData = $request->all();
            $requestData['access_tisi']   = !empty( $request->input('access_tisi') )?json_encode($request->input('access_tisi')):null;
            $requestData['access']        = !empty( $request->input('access') )?json_encode($request->input('access')):null;
            $requestData['tag']           = !empty( $request->input('tag') )? json_encode(explode(',', $request->input('tag'))):null;
            $requestData['date_publish']  = !empty( $request->date_publish) ?  HP::convertDate($request->date_publish,true) : null;
            $requestData['updated_by']    =  auth()->user()->getKey();

            $urls                         = !empty( $request->input('url'))?$request->input('url'):[];
            $url_descriptions             = !empty ($request->input('url_description'))?$request->input('url_description'):[];
            if( !empty($urls) && count($urls) > 0 ){

                foreach ($urls as $key => $url ) {

                    if( array_key_exists( $key , $url_descriptions ) && !empty($url_descriptions[$key]) ){
                        $array[$key]['url_description'] = $url_descriptions[$key];
                    }
                    if( array_key_exists( $key , $urls ) && !empty($urls[$key]) ){
                        $array[$key]['url'] = $url;
                    }

                }
                $requestData['url'] = isset($array)?json_encode($array):null;

            }

            $book_manage->update($requestData);
            $this->SaveLawBookManageAccess($book_manage,$requestData);

            //ไฟล์ภาพหน้าปก
            $tax_number = (!empty(auth()->user()->reg_13ID) ? str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');
            if($request->hasFile('file_image_cover') && $request->file('file_image_cover')->isValid()){
                $file_image_cover = $requestData['file_image_cover'];
                HP::singleFileUploadLaw(
                    $file_image_cover,
                    $this->attach_path,
                    $tax_number,
                    (auth()->user()->FullName ?? null),
                    'Law',
                    ((new LawBookManage)->getTable()),
                    $book_manage->id,
                    'file_image_cover',
                    'ไฟล์ภาพหน้าปก'
                );
            }

            //ไฟล์เเนบ
            if(isset( $requestData['repeater-attach'] ) ){
               $attachs = $requestData['repeater-attach'];
               foreach( $attachs as $file ){
                   if( isset($file['file_book_manage']) && !empty($file['file_book_manage']) ){
                       HP::singleFileUploadLaw(
                           $file['file_book_manage'],
                           $this->attach_path,
                           ($tax_number),
                           (auth()->user()->FullName ?? null),
                           'Law',
                           ((new LawBookManage)->getTable()),
                           $book_manage->id,
                           'file_book_manage',
                           'ไฟล์แนบจัดการข้อมูลห้องสมุด'
                       );
                   }
               }
           }

            return redirect('law/book/manage/'.($id).'/edit')->with('success_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
        }
        return response(view('403'), 403);

    }

    public function save_file(Request $request){
        $requestData = $request->all();
        $ids =  !empty($requestData['id'])?$requestData['id']:[];

        if(count($ids) > 0 ){
            foreach ($ids as $id ) {
                //ไฟล์เเนบ
                $tax_number = (!empty(auth()->user()->reg_13ID) ? str_replace("-","", auth()->user()->reg_13ID) : '0000000000000');
                if(isset( $requestData['repeater-attach'] ) ){
                 $attachs = $requestData['repeater-attach'];
                 foreach( $attachs as $file ){
                     if( isset($file['file_book_manage']) && !empty($file['file_book_manage']) ){
                         HP::singleFileUploadLaw(
                             $file['file_book_manage'],
                             $this->attach_path,
                             ($tax_number),
                             (auth()->user()->FullName ?? null),
                             'Law',
                             ((new LawBookManage)->getTable()),
                             $id,
                             'file_book_manage',
                             'ไฟล์แนบจัดการข้อมูลห้องสมุด'
                         );
                     }
                 }
             }
         }

        }

        return redirect('law/book/manage')->with('flash_message', 'บันทึกไฟล์แนบเรียบร้อย');
    
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('delete-'.$model)) {
            LawBookManage::destroy($id);
            return redirect('law/book/manage')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว');
        }
        abort(403);
    }

    /*
      **** Update State ****
    */
    public function update_state(Request $request){

        $model = str_slug('law-book-manage','-');
        if(auth()->user()->can('edit-'.$model)) {

            $requestData = $request->all();

            $id_publish = $requestData['id_publish'];

            $db = new LawBookManage;
            $resulte =  LawBookManage::whereIn($db->getKeyName(), $id_publish)->update(['state' => $requestData['state']]);

            if($resulte ){
                return 'success';
            }else{
                return 'error';
            }

        }

        return response(view('403'), 403);

    }

    public function delete(Request $request)
    {
        $id_publish = $request->input('id_publish');
        $result = LawBookManage::whereIn('id', $id_publish);
        if($result->delete())
        {
            echo 'success';
        }
    }

    public function SaveLawBookManageAccess( $book_manage ,  $requestData )
    {
            if( !empty($book_manage) ){
                $book_manage_access = LawBookManageAccess::where('law_book_manage_id', $book_manage->id )->first();
                if(is_null($book_manage_access)){
                    $book_manage_access = new LawBookManageAccess;
                }
                $book_manage_access->law_book_manage_id   = !empty( $book_manage->id)?$book_manage->id:null;
                $book_manage_access->access               = !empty( $requestData['access'])?$requestData['access']:null;
                $book_manage_access->access_tisi          = !empty( $requestData['access_tisi'] )? $requestData['access_tisi'] :null;
                $book_manage_access->save();
            }
    }

 }
