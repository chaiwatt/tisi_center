<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\IdeaPublic;
use Illuminate\Http\Request;

use App\Models\Basic\Department;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class IdeaPublicController extends Controller
{
	private $attach_path;//ที่เก็บไฟล์แนบ

	public function __construct()
    {
        // $this->middleware('auth');
        $this->middleware('auth', ['except' => ['getCreateIdeas', 'storeIdeas']]);
	    $this->attach_path = 'tis_attach/idea-public/';
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('view-'.$model)) {

            $filter = [];
            $filter['perPage'] = $request->get('perPage', 10);
	        $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
            $filter['filter_product_group'] = $request->get('filter_product_group', '');

            $Query = new IdeaPublic;

            if ($filter['filter_search']!='') {
		        $Query = $Query->where('product', 'like', $filter['filter_search'])
			        ->orwhere('description', 'like', $filter['filter_search'])
			        ->orwhere('standards_ref', 'like', $filter['filter_search'])
			        ->orwhere('commentator', 'like', $filter['filter_search']);
	        }

            if ($filter['filter_status']!='') {
                $Query = $Query->where('state', $filter['filter_status']);
            }

	        if ($filter['filter_product_group']!='') {
		        $Query = $Query->where('product_groups_id', $filter['filter_product_group']);
	        }

            $ideapublic = $Query->sortable()->with('user_created')
                                                       ->with('user_updated')
                                                       ->paginate($filter['perPage']);

            return view('idea-public.index', compact('ideapublic', 'filter'));
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('add-'.$model)) {
            return view('idea-public.create');
        }
        abort(403);

    }

	public function getCreateIdeas()
	{
		$departments = Department::pluck('title', 'id');
		$departments[9999] = "อื่นๆ";
		$attachs = [(object)['file_note'=>'', 'file_name'=>'']];
		$attach_path = $this->attach_path;
			return view('idea-public.frontend.create-ideas', compact('departments', 'attachs', 'attach_path'));
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('add-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();

            IdeaPublic::create($requestData);
            return redirect('idea-public')->with('flash_message', 'เพิ่ม IdeaPublic เรียบร้อยแล้ว');
        }
        abort(403);
    }

    public function storeIdeas(Request $request)
    {
        $request->request->add(['created_by' => Auth::guest() ? 0 : auth()->user()->getKey()]); //user create
        $requestData = $request->all();

        //ไฟล์แนบ
        $attachs = [];
        if ($files = $request->file('attachs')) {

            foreach ($files as $key => $file) {

                //Upload File
                $storagePath = Storage::put($this->attach_path, $file);
                $storageName = basename($storagePath); // Extract the filename

                $attachs[] = ['file_name' => $storageName, 'file_client_name' => $file->getClientOriginalName(), 'file_note' => $requestData['attach_notes'][$key]];
            }
        }

        $requestData['attach'] = json_encode($attachs);

        IdeaPublic::create($requestData);
        return redirect('idea-public/ideas/create')->with('flash_message', 'เพิ่ม ความคิดเห็น เรียบร้อยแล้ว');
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('view-'.$model)) {
            $ideapublic = IdeaPublic::findOrFail($id);
	        //ไฟล์แนบ
	        $attachs = json_decode($ideapublic['attach']);
	        $attachs = !is_null($attachs)&&count($attachs)>0?$attachs:[(object)['file_note'=>'', 'file_name'=>'']];
	        $attach_path = $this->attach_path;
            return view('idea-public.show', compact('ideapublic', 'attachs', 'attach_path'));
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('edit-'.$model)) {
            $ideapublic = IdeaPublic::findOrFail($id);
            return view('idea-public.edit', compact('ideapublic'));
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('edit-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $ideapublic = IdeaPublic::findOrFail($id);
            $ideapublic->update($requestData);

            return redirect('idea-public')->with('flash_message', 'แก้ไข IdeaPublic เรียบร้อยแล้ว!');
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
        $model = str_slug('idea-public','-');
        if(auth()->user()->can('delete-'.$model)) {

          $requestData = $request->all();

          if(array_key_exists('cb', $requestData)){
            $ids = $requestData['cb'];
            $db = new IdeaPublic;
            IdeaPublic::whereIn($db->getKeyName(), $ids)->delete();
          }else{
            IdeaPublic::destroy($id);
          }

          return redirect('idea-public')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);

    }


    /*
      **** Update State ****
    */
    public function update_state(Request $request){

      $model = str_slug('idea-public','-');
      if(auth()->user()->can('edit-'.$model)) {

        $requestData = $request->all();

        if(array_key_exists('cb', $requestData)){
          $ids = $requestData['cb'];
          $db = new IdeaPublic;
          IdeaPublic::whereIn($db->getKeyName(), $ids)->update(['state' => $requestData['state']]);
        }

        return redirect('idea-public')->with('flash_message', 'แก้ไขข้อมูลเรียบร้อยแล้ว!');
      }

      abort(403);

    }

}
