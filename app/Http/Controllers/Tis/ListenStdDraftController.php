<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Tis\ListenStdDraft;
use App\Models\Tis\ListenStdDraftDetail;
use App\Models\Tis\NoteStdDraft;
use App\Models\Tis\PublicDraft;
use App\Models\Basic\ProductGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;

class ListenStdDraftController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('auth', ['except' => ['create','store','form', 'save', 'edit', 'update', 'success']]);
        $this->attach_path = 'tis_attach/listen_std_draft/';
        $this->attach_std_path = 'tis_attach/standard/';
        $this->attach_set_std_path = 'tis_attach/set_standard/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {

        $model = str_slug('listen-std-draft','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];
            $filter['perPage'] = $request->get('perPage', 10);
            $filter['filter_tis_no'] = $request->get('filter_tis_no', '');
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_branch'] = $request->get('filter_branch', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');

            $Query = new ListenStdDraft;

            if ($filter['filter_search'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_search'];
                                    $query->where('name', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('tel', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('email', 'LIKE', "%{$search_text}%");
                         });
            }

            if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
            }

            if ($filter['filter_tis_no']!='') {
                $public_drafts = PublicDraft::where('tis_no', $filter['filter_tis_no'])->pluck('id');
                $Query = $Query->whereIn('public_draft_id', $public_drafts);
            }

            if ($filter['filter_department']!='') {
                $Query = $Query->where('department_id', $filter['filter_department']);
            }

            if ($filter['filter_branch']!='') {
                $public_groups = ProductGroup::where('id', $filter['filter_branch'])->pluck('id');
                $Query = $Query->whereIn('product_group_id', $public_groups);
            }


            $listen_std_draft = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('tis/listen_std_draft/index', compact('listen_std_draft', 'filter'));
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
    public function form($id)
    {
        $model = str_slug('listen-std-draft','-');
        if(auth()->user() || Auth::guest()) {

           $note_std_draft = NoteStdDraft::findOrFail($id);

            $note_std_draft->standard_name = $note_std_draft->title??'n/a';
            $note_std_draft->standard_no = $note_std_draft->tis_no??'n/a';
            $note_std_draft->product_group = $note_std_draft->ProductGroupName;

            $attach_path = $this->attach_path;
            $attach_std_path = $this->attach_std_path;
            $attach_set_std_path = $this->attach_set_std_path;
            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $note_std_draft_attachs = json_decode($note_std_draft['attach']);


            return view('tis/listen_std_draft/edit', compact('note_std_draft', 'attachs', 'note_std_draft_attachs', 'attach_path', 'attach_std_path', 'attach_set_std_path'));
        }
        abort(403);
    }

    /* บันทึกความเห็นในร่างมาตรฐาน */
    public function save(Request $request, $id)
    {

        $model = str_slug('listen-std-draft', '-');
        if (auth()->user() || Auth::guest()) {

            $created_by = !empty(auth()->user())?auth()->user()->getKey():null;
            $request->request->add(['created_by' => $created_by]); //user create
            $requestData = $request->all();

            $note_std_draft = NoteStdDraft::where('id', $id)->first(); //ตารางหลักรับฟังความคิดเห็นต่อร่างกฏกระทรวง

            //ไฟล์แนบ
            $attachs = [];
            if ($files = $request->file('attachs')) {

                foreach ($files as $key => $file) {

                    //Upload File
                    $storagePath = Storage::put($this->attach_path, $file);
                    $storageName = basename($storagePath); // Extract the filename

                    $attachs[] = [
                        'file_name' => $storageName,
                        'file_client_name' => $file->getClientOriginalName(),
                        'file_note' => $requestData['attach_notes'][$key]
                    ];
                }
            }

            $requestData['attach'] = json_encode($attachs);
            $requestData['note_std_draft_id'] = $note_std_draft->id;

            $listen_std_draft = ListenStdDraft::create($requestData);

            //บันทึกรายการความคิดเห็น
            if ($requestData['comment'] != 'confirm_standard') {

                $detail_comments = $requestData['detail_comment'];
                foreach ($detail_comments['comment_detail'] as $key => $item) {
                    $listen_std_draft_detail = new ListenStdDraftDetail();
                    $listen_std_draft_detail->listen_std_draft_id = $listen_std_draft->id;
                    $listen_std_draft_detail->comment_detail = $item;

                    $detail_files = $request->file('detail_comment');
                    if (!is_null($detail_files)) {
                        $file = $detail_files['attachs'][$key];
                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $detail_comment_attachs = [
                            'file_name' => $storageName,
                            'file_client_name' => $file->getClientOriginalName(),
                            'file_note' => $item
                        ];
                    } else {
                        $detail_comment_attachs = [
                            'file_name' => '',
                            'file_client_name' => '',
                            'file_note' => ''
                        ];
                    }

                    $listen_std_draft_detail->attach = json_encode($detail_comment_attachs);
                    $listen_std_draft_detail->created_by = @$created_by;
                    $listen_std_draft_detail->created_at = date('Y-m-d H:s:i');
                    $listen_std_draft_detail->save();
                }
            }

            return view('tis/listen_std_draft/success');

            // return redirect('tis/listen_std_draft/success');
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
        $model = str_slug('listen-std-draft','-');
        // if(auth()->user() || Auth::guest()) {
            $attachs = [(object)['file_note'=>'', 'file_name'=>'']];
            $attach_path = $this->attach_path;
            $public_draft  = [];
            return view('tis/listen_std_draft/create', compact('public_draft', 'attachs', 'attach_path'));
        // }
        // abort(403);

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
        $model = str_slug('listen-std-draft','-');
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

            $comment_standard_review = ListenStdDraft::create($requestData);

            $detail_comments = $requestData['detail_comment'];

            foreach($detail_comments['comment_detail'] as $key => $item){
                $comment_standard_review_detail = new ListenStdDraftDetail();
                $comment_standard_review_detail->comment_standard_review_id = $comment_standard_review->id;
                $comment_standard_review_detail->comment_detail = $item;
                if($detail_files = $request->file('detail_comment')){
                    $file = $detail_files['attachs'][$key];
                        //Upload File
                        $storagePath = Storage::put($this->attach_path, $file);
                        $storageName = basename($storagePath); // Extract the filename

                        $detail_comment_attachs = ['file_name'=>$storageName,
                            'file_client_name'=>$file->getClientOriginalName(),
                            'file_note'=> $detail_comments['comment_detail'][$key]
                        ];
                }
                $comment_standard_review_detail->attach = json_encode($detail_comment_attachs);
                $comment_standard_review_detail->created_by = auth()->user()->getKey();
                $comment_standard_review_detail->created_at = date('Y-m-d H:s:i');
                $comment_standard_review_detail->save();

            }

            return redirect('tis/listen_std_draft/create')->with('flash_message', 'เพิ่ม ListenStdDraft เรียบร้อยแล้ว');
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
        $model = str_slug('listen-std-draft','-');
        if(auth()->user()->can('view-'.$model)) {
            $listen_std_draft = ListenStdDraft::findOrFail($id);

            $listen_std_draft_detail = ListenStdDraftDetail::where('listen_std_draft_id',$listen_std_draft->id)->get();
            //ไฟล์แนบ
            $attachs = json_decode($listen_std_draft['attach']);
            $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_name'=>'', 'file_client_name'=>'', 'file_note'=>'']];

            $attach_path = $this->attach_path;

           $note_std_draft = NoteStdDraft::where('id', $listen_std_draft->note_std_draft_id)->first();

            $listen_std_draft->standard_name = $note_std_draft->title??'n/a';
            $listen_std_draft->standard_no = $note_std_draft->tis_no??'n/a';
            $listen_std_draft->product_group = $note_std_draft->ProductGroupName??'n/a';

            return view('tis/listen_std_draft/show', compact('listen_std_draft', 'listen_std_draft_detail', 'attachs', 'attach_path'));
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
        $model = str_slug('listen-std-draft','-');
        if(auth()->user() || Auth::guest()) {
            $comment_standard_review = ListenStdDraft::findOrFail($id);

            $comment_standard_review_detail = ListenStdDraftDetail::where('comment_standard_review_id', $comment_standard_review->id)->get();
            //ไฟล์แนบ
            $attachs = json_decode($comment_standard_review['attach']);
            $attachs = !is_null($attachs) && count($attachs) > 0 ? $attachs : [(object) ['file_note' => '', 'file_name' => '']];

            $attach_path = $this->attach_path;
            return view('tis/listen_std_draft/edit', compact('comment_standard_review', 'comment_standard_review_detail', 'attachs', 'attach_path'));
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
        $model = str_slug('listen-std-draft','-');
        if(auth()->user() || Auth::guest()) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $ListenStdDraft = ListenStdDraft::findOrFail($id);
            $ListenStdDraft->update($requestData);

            return redirect('tis/listen_std_draft')->with('flash_message', 'แก้ไข ListenStdDraft เรียบร้อยแล้ว!');
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
        $model = str_slug('listen-std-draft','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new ListenStdDraft;
            ListenStdDraft::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            ListenStdDraft::destroy($id);
          }

          return redirect('tis/listen_std_draft')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('listen-std-draft','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new ListenStdDraft;
          ListenStdDraft::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('tis/listen_std_draft')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
