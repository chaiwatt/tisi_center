<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\CommentStandardDraft;
use App\Models\Tis\CommentStandardDraftDetail;
use App\Models\Tis\PublicDraft;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

class CommentStandardDraftsController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('auth', ['except' => ['create','store','form', 'save', 'edit', 'update', 'success']]);

        $this->attach_path = 'tis_attach/comment_standard_draft/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['filter_state'] = $request->get('filter_state', '');
            $filter['perPage'] = $request->get('perPage', 10);

            $Query = new CommentStandardDraft;

            if ($filter['filter_state']!='') {
                $Query = $Query->where('state', $filter['filter_state']);
            }

            $comment_standard_drafts = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('tis.comment_standard_drafts.index', compact('comment_standard_drafts', 'filter'));
        }
        abort(403);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function form($token)
    {

        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user() || Auth::guest()) {

            $public_draft = PublicDraft::where('token', $token)->first();
            $set_stadard = $public_draft->getStandard_Name();//กำหนดมาตรฐาน
            $product_group = $public_draft->getStand_Branch();//กลุ่มผลิตภัณฑ์/สาขา

            $public_draft->standard_name = @$set_stadard->title;
            $public_draft->standard_no = @$set_stadard->tis_no.' - '.@$set_stadard->start_year;
            $public_draft->product_group = @$product_group->title;

            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;

            return view('tis.comment_standard_drafts.edit', compact('public_draft', 'attachs', 'attach_path'));
        }
        abort(403);
    }

    /* บันทึกความเห็นในร่างมาตรฐาน */
    public function save(Request $request, $token){

        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user() || Auth::guest()) {

            $created_by = !empty(auth()->user())?auth()->user()->getKey():null;

            $request->request->add(['created_by' => $created_by]); //user create
            $requestData = $request->all();

            $public_draft = PublicDraft::where('token', $token)->first();//ตารางหลักเวียนร่างมาตรฐาน

            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

                foreach ($files as $key => $file) {

                    //Upload File
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename

                    $attachs[] = ['file_name'=>$storageName,
                        'file_client_name'=>$file->getClientOriginalName(),
                        'file_note'=>$requestData['attach_notes'][$key]
                    ];
                }

            }

            $requestData['attach'] = json_encode($attachs);
            $requestData['public_draft_id'] = $public_draft->id;

            $comment_standard_draft = CommentStandardDraft::create($requestData);

            //บันทึกรายการความคิดเห็น
            if($requestData['comment']!='all_agree'){

                $detail_comments = $requestData['detail_comment'];
                foreach($detail_comments['page'] as $key => $item){
                    $comment_standard_draft_detail = new CommentStandardDraftDetail();
                    $comment_standard_draft_detail->comment_standard_draft_id = $comment_standard_draft->id;
                    $comment_standard_draft_detail->page = $detail_comments['page'][$key];
                    $comment_standard_draft_detail->no = $detail_comments['no'][$key];
                    $comment_standard_draft_detail->comment_detail = $detail_comments['comment_detail'][$key];
                    $comment_standard_draft_detail->reason = $detail_comments['reason'][$key];

                    $detail_files = $request->file('detail_comment');
                    if(!is_null($detail_files)){
                        $file = $detail_files['attachs'][$key];
                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $detail_comment_attachs = ['file_name'=>$storageName,
                                                   'file_client_name'=>$file->getClientOriginalName(),
                                                   'file_note'=> $detail_comments['page'][$key]
                                                  ];
                    }else{
                      $detail_comment_attachs = ['file_name'=>'',
                                                 'file_client_name'=>'',
                                                 'file_note'=> ''
                                                ];
                    }
                    $comment_standard_draft_detail->attach = json_encode($detail_comment_attachs);
                    $comment_standard_draft_detail->created_by = $created_by;
                    $comment_standard_draft_detail->created_at = date('Y-m-d H:s:i');
                    $comment_standard_draft_detail->save();

                }
            }

            // return redirect('tis/comment_standard_drafts/success');
            return view('tis/comment_standard_drafts/success');

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
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user() || Auth::guest()) {

            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;
            $public_draft  = [];
            return view('tis.comment_standard_drafts.create', compact('public_draft', 'attachs', 'attach_path'));

        }
        abort(403);

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
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user() || Auth::guest()) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

                foreach ($files as $key => $file) {

                    //Upload File
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename

                    $attachs[] = ['file_name'=>$storageName,
                        'file_client_name'=>$file->getClientOriginalName(),
                        'file_note'=>$requestData['attach_notes'][$key]
                    ];
                }

            }

            $requestData['attach'] = json_encode($attachs);

            $comment_standard_draft = CommentStandardDraft::create($requestData);

            $detail_comments = $requestData['detail_comment'];

            foreach($detail_comments['page'] as $key => $item){
                $comment_standard_draft_detail = new CommentStandardDraftDetail();
                $comment_standard_draft_detail->comment_standard_draft_id = $comment_standard_draft->id;
                $comment_standard_draft_detail->page = $detail_comments['page'][$key];
                $comment_standard_draft_detail->no = $detail_comments['no'][$key];
                $comment_standard_draft_detail->comment_detail = $detail_comments['comment_detail'][$key];
                $comment_standard_draft_detail->reason = $detail_comments['reason'][$key];
                if($detail_files = $request->file('detail_comment')){
                    $file = $detail_files['attachs'][$key];
                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $detail_comment_attachs = ['file_name'=>$storageName,
                            'file_client_name'=>$file->getClientOriginalName(),
                            'file_note'=> $detail_comments['page'][$key]
                        ];
                }
                $comment_standard_draft_detail->attach = json_encode($detail_comment_attachs);
                $comment_standard_draft_detail->created_by = auth()->user()->getKey();
                $comment_standard_draft_detail->created_at = date('Y-m-d H:s:i');
                $comment_standard_draft_detail->save();

            }

            return redirect('tis/comment_standard_drafts/create')->with('flash_message', 'เพิ่ม CommentStandardDraft เรียบร้อยแล้ว');
        }
        abort(403);
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
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user()->can('view-'.$model)) {
            $commentstandarddraft = CommentStandardDraft::findOrFail($id);
            return view('tis.comment_standard_drafts.show', compact('commentstandarddraf'));
        }
        abort(403);
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
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user()->can('edit-'.$model)) {
            $commentstandarddraft = CommentStandardDraft::findOrFail($id);
            return view('tis.comment_standard_drafts.edit', compact('commentstandarddraf'));
        }
        abort(403);
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
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user() || Auth::guest()) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $commentstandarddraft = CommentStandardDraft::findOrFail($id);
            $commentstandarddraft->update($requestData);

            return redirect('tis/comment_standard_drafts')->with('flash_message', 'แก้ไข CommentStandardDraft เรียบร้อยแล้ว!');
        }
        abort(403);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id, Request $request)
    {
        $model = str_slug('comment-standard-drafts','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new CommentStandardDraft;
            CommentStandardDraft::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            CommentStandardDraft::destroy($id);
          }

          return redirect('tis/comment_standard_drafts')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('comment-standard-drafts','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new CommentStandardDraft;
          CommentStandardDraft::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/comment_standard_drafts')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
