<?php

namespace App\Http\Controllers\Certify;

use App\Models\Bcertify\AuditorExpertise;
use App\Models\Bcertify\AuditorInformation;
use App\Models\Bcertify\StatusAuditor;
use App\Models\Certify\BoardAuditor;
use App\Models\Certify\BoardAuditorGroup;
use App\Models\Certify\BoardAuditorInformation;
use App\Models\Certify\BoardReview;
use App\Models\Certify\BoardReviewGroup;
use App\Models\Certify\BoardReviewInformation;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class BoardReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $model = str_slug('board_review','-');
        if(auth()->user()->can('view-'.$model)) {
//
        $keyword = $request->get('search');
        $filter = [];
        $filter['filter_state'] = $request->get('filter_state', '');
        $filter['filter_search'] = $request->get('filter_search', '');
        $filter['filter_start_date'] = $request->get('filter_start_date', '');
        $filter['filter_start_date'] = $request->get('filter_start_date', '');
        $filter['filter_end_date'] = $request->get('filter_end_date', '');
        $filter['perPage'] = $request->get('perPage', 10);
//
        $Query = new BoardReview();
        if ($filter['filter_state'] != '') {
            $Query = $Query->where('type', $filter['type']);
        }

        if ($filter['filter_search'] != '') {
            $Query = $Query->where('taxid', 'LIKE', '%' . $filter['filter_search'] . '%');
        }

        if ($filter['filter_start_date'] != null && $filter['filter_end_date'] != null) {
            $start = Carbon::createFromFormat('d/m/Y', $filter['filter_start_date']);
            $end = Carbon::createFromFormat('d/m/Y H:i:s', $filter['filter_end_date'] . '23:59:59');
            $Query = $Query->whereBetween('judgement_date', [$start->toDateString(), $end]);

        } elseif ($filter['filter_start_date'] != null && $filter['filter_end_date'] == null) {
            $start = Carbon::createFromFormat('d/m/Y', $filter['filter_start_date']);
            $Query = $Query->whereDate('judgement_date', $start->toDateString());
        }
//
        $boardReviews = $Query->sortable()->with('user_created')
            ->with('user_updated')
            ->paginate($filter['perPage']);

        return view('certify/board_review/index', compact('boardReviews', 'filter'));
        }
        abort(403);
    }

    public function create()
    {
        $model = str_slug('board_review','-');
        if(auth()->user()->can('add-'.$model)) {
        $status_auditor = array();
        foreach (StatusAuditor::where('kind', 2)->get() as $sa) {
            $status_auditor[$sa->id] = $sa->title;
        }
        return view('certify/board_review/create', [
            'status_auditor' => $status_auditor
        ]);
        }
        abort(403);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'taxid' => 'required|max:255',
            'judgement_date' => 'date-format:"d/m/Y"|required',
            'other_attach' => 'required|file',
            'group' => 'required|array',
            'group.*.status' => 'required',
            'group.*.users' => 'required',
        ]);
        $input = [
            'taxid' => $request->taxid,
            'judgement_date' => Carbon::createFromFormat('d/m/Y', $request->judgement_date),
            'other_attach' => $this->storeFile($request->file('other_attach')),
            'type' => null,
            'branch' => null,
            'token' => str_random(16),
            'created_by' => auth()->user()->runrecno,
        ];

        if ($baId = $this->savingBoard($input)) {
            if ($this->storeGroup($baId, $request->group)) {
                return redirect('certify/board_review')->with('flash_message', 'เพิ่ม board review เรียบร้อยแล้ว');
            }
        }
    }

    public function savingBoard($input)
    {
        $input['created_at'] = $input['updated_at'] = Carbon::now();
        $id = BoardReview::insertGetId($input);
        if ($id) {
            return $id;
        }
        return false;
    }

    public function storeGroup($baId, $groupInput)
    {
        $ba = BoardReview::findOrFail($baId);
        foreach ($ba->groups as $group) {
            $group->reviewers()->delete();
            $group->delete();
        }

        foreach ($groupInput as $group) {
            $sa = StatusAuditor::find($group['status']);
            if ($sa) {
                $input = [
                    'board_review_id' => $baId,
                    'status_auditor_id' => $sa->id,
                ];
                if ($groupId = $this->savingGroup($input)) {
                    if (!$this->storeReviewer($groupId, $group['users'])) {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function savingGroup($input)
    {
        $input['created_at'] = $input['updated_at'] = now();
        $id = BoardReviewGroup::insertGetId($input);
        if ($id) {
            return $id;
        }
        return false;
    }

    public function storeReviewer($groupId, $id)
    {
        $auditorIds = explode(";", $id);
        foreach ($auditorIds as $auditorId) {
            $ai = AuditorInformation::find($auditorId);
            if ($ai) {
                $input = [
                    'group_id' => $groupId,
                    'auditor_id' => $auditorId
                ];
                if (!$this->savingReviewer($input)) {
                    return false;
                }
            } else {
                return false;
            }
        }
        return true;
    }

    public function savingReviewer($input)
    {
        $input['created_at'] = $input['updated_at'] = now();
        $id = BoardReviewInformation::insertGetId($input);
        if ($id) {
            return $id;
        }
        return false;
    }

    public function storeFile($files, $ba = null)
    {
        if ($files) {
            $destinationPath = storage_path('/files/board_review_files/');
            $fileOriginal = $files->getClientOriginalName();
            $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
            $path = $filename . '-' . time() . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $path);
            $file_certificate_toDB = $path;

            if ($ba != null && $ba->file != null) {
                $path = $destinationPath . $ba->file;
                File::delete($path);
            }

            return $file_certificate_toDB;
        }
        return $ba->file ?? null;
    }

    public function destroy(BoardReview $ba)
    {
        $model = str_slug('board_review','-');
        if(auth()->user()->can('delete-'.$model)) {

        $this->deleting($ba);

        return redirect('certify/board_review')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
        }
        abort(403);
    }

    public function deleting(BoardReview $ba)
    {
        try {
            foreach ($ba->groups as $group) {
                $group->reviewers()->delete();
                $group->delete();
            }

            $destinationPath = storage_path('/files/board_review_files/');
            $path = $destinationPath . $ba->file;
            if (File::exists($path)) {
                File::delete($path);
            }

            $ba->delete();
            return true;
        } catch (Exception $x) {
            return false;

        }
    }

    public function show(BoardReview $board)
    {
        return view('certify/board_review/show', compact('board'));
    }

    public function edit(BoardReview $board)
    {
        $status_auditor = array();
        foreach (StatusAuditor::get() as $sa) {
            $status_auditor[$sa->id] = $sa->title;
        }
        return view('certify/board_review/edit', compact('board', 'status_auditor'));
    }

    public function getAuditors($sa) {
        $auditors = array();
        foreach (AuditorExpertise::get() as $ae) {
            if (in_array($sa->id, $ae->status) && !in_array($ae->auditor->id, Arr::pluck($auditors, 'id'))) {
                $auditor = $ae->auditor;
                $auditor->department;
                array_push($auditors, $auditor);
            }
        }
        return $auditors;
    }

    public function update(Request $request, BoardReview $board)
    {
        $this->validate($request, [
            'taxid' => 'required|max:255',
            'judgement_date' => 'date-format:"d/m/Y"|required',
            'other_attach' => 'nullable|file',
            'group' => 'required|array',
            'group.*.status' => 'required',
            'group.*.users' => 'required',
        ]);

        $input = [
            'taxid' => $request->taxid,
            'judgement_date' => Carbon::createFromFormat('d/m/Y',$request->judgement_date),
            'other_attach' => $this->storeFile($request->file('other_attach'), $board),
            'updated_by' => auth()->user()->runrecno,
        ];
        if ($board->update($input)) {
            if ($this->storeGroup($board->id, $request->group)) {
                return redirect('certify/board_review')->with('flash_message', 'แก้ไข board review เรียบร้อยแล้ว');
            }
        }
        return back()->withInput();
    }

    public function destroyMultiple(Request $request)
    {
        foreach ($request->cb as $baId) {
            $ba = BoardReview::findOrFail($baId);
            $this->deleting($ba);

        }

        return redirect('certify/board_review')->with('flash_message', 'ลบข้อมูลเรียบร้อยแล้ว!');
    }
}
