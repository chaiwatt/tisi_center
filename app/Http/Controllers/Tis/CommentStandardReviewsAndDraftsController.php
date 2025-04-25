<?php

namespace App\Http\Controllers\Tis;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Basic\Department;
use App\Models\Tis\CommentStandardReview;
use App\Models\Tis\CommentStandardDraft;
use App\Models\Tis\PublicDraft;
use App\Models\Student\Application;
use App\Models\Basic\ProductGroup;
use App\AttachFile;
use App\ProfileOfficial;
use App\Models\Tis\ListenStdDraft;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Tis\NoteStdDraft;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use Yajra\Datatables\Datatables;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; 
use HP;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentStandardReviewsAndDraftsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function index(Request $request)
    {
        $model = str_slug('commentstandardreviewsanddrafts','-');
        if(auth()->user()->can('view-'.$model)) {

            $keyword = $request->get('search');
            $filter = [];  
            $data_public_draft = [];  
            $data_tis_no = []; 
            $data_get_stand = []; 

            $filter['perPage'] = $request->get('perPage', 10);
            $filter['filter_tis_no'] = $request->get('filter_tis_no', '');
            $filter_tis_no = $filter['filter_tis_no'];
            $filter['filter_department'] = $request->get('filter_department', '');
            $filter['filter_branch'] = $request->get('filter_branch', '');
            $filter['filter_search'] = $request->get('filter_search', '');
            $filter['filter_status'] = $request->get('filter_status', '');
    
        //     $review = new CommentStandardReview;
        //     $review = $review->select(
        //                             'id as id', 
        //                             'created_at as created_at',
        //                             'comment as comment',
        //                             'name as name',
        //                             'tel as tel',
        //                             'email as email', 
        //                             'department_id as department_id',
        //                             'department_name as department_name',
        //                             'public_draft_id as public_draft_id'
        //                              )->selectRaw('"tis_comment_standard_reviews" as tables');
        //     if ($filter['filter_search'] != ''){
        //           $review = $review->where(function ($query) use ($filter) {
        //               $search_text = $filter['filter_search'];
        //                             $query->where('name', 'LIKE', "%{$search_text}%");
        //                             $query->orWhere('tel', 'LIKE', "%{$search_text}%");
        //                             $query->orWhere('email', 'LIKE', "%{$search_text}%");
        //                  });
        //     }
        //     if ($filter['filter_tis_no']!='') {
        //         $public_drafts = PublicDraft::where('tis_no', $filter['filter_tis_no'])->pluck('id');
        //         $review = $review->whereIn('public_draft_id', $public_drafts);
        //     }

        //     if ($filter['filter_department']!='') {
        //         $review = $review->where('department_id', $filter['filter_department']);
        //     }

        //     // if ($filter['filter_branch']!='') {
        //     //     $public_groups = ProductGroup::where('id', $filter['filter_branch'])->pluck('id');
        //     //     $review = $review->whereIn('product_group_id', $public_groups);
        //     // }
    
        //     $detail = new CommentStandardDraft;
        //     $detail = $detail->select(
        //                             'id as id', 
        //                             'created_at as created_at',
        //                             'comment as comment',
        //                             'name as name',
        //                             'tel as tel',
        //                             'email as email', 
        //                             'department_id as department_id',
        //                             'department_name as department_name',
        //                             'public_draft_id as public_draft_id'
        //                              )->selectRaw('"tis_comment_standard_drafts" as tables');
        //     if ($filter['filter_search'] != ''){
        //           $detail = $detail->where(function ($query) use ($filter) {
        //               $search_text = $filter['filter_search'];
        //                             $query->where('name', 'LIKE', "%{$search_text}%");
        //                             $query->orWhere('tel', 'LIKE', "%{$search_text}%");
        //                             $query->orWhere('email', 'LIKE', "%{$search_text}%");
        //                  });
        //     }
        //     if ($filter['filter_tis_no']!='') {
        //         $public_drafts = PublicDraft::where('tis_no', $filter['filter_tis_no'])->pluck('id');
        //         $detail = $detail->whereIn('public_draft_id', $public_drafts);
        //     }

        //     if ($filter['filter_department']!='') {
        //         $detail = $detail->where('department_id', $filter['filter_department']);
        //     }

        //     // if ($filter['filter_branch']!='') {
        //     //     $public_groups = ProductGroup::where('id', $filter['filter_branch'])->pluck('id');
        //     //     $detail = $detail->whereIn('product_group_id', $public_groups);
        //     // }      
 
        //    $union  = $review ->union($detail)->get()->toArray();

        //     $currentPage = $request->get('page')?:1 ;
        
        //     $perPage =   $filter['perPage'];
        //     $currentItems = array_slice($union, $perPage * ($currentPage - 1), $perPage);
        //     $result = new LengthAwarePaginator($currentItems, count($union), $perPage, $currentPage, ['path' => url('tis/comment-standard-reviews-and-drafts') ]);
          
             $Query = new ListenStdDraft;
             $Query = $Query->select('*');
            if ($filter['filter_search'] != ''){
                  $Query = $Query->where(function ($query) use ($filter) {
                      $search_text = $filter['filter_search'];
                                    $query->where('name', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('tel', 'LIKE', "%{$search_text}%");
                                    $query->orWhere('email', 'LIKE', "%{$search_text}%");
                         });
            }
            if ($filter['filter_tis_no']!='') {
 
                $Query = $Query->where('note_std_draft_id',$filter['filter_tis_no']);
            }

            if ($filter['filter_department']!='') {
                $Query = $Query->where('department_id', $filter['filter_department']);
            }
            $result =  $Query->orderby('id','desc')
                            ->sortable()
                            ->paginate($filter['perPage']);

            // if ($filter['filter_branch']!='') {
            //     $public_groups = ProductGroup::where('id', $filter['filter_branch'])->pluck('id');
            //     $review = $review->whereIn('product_group_id', $public_groups);
            // }
        
            return view('tis.comment-standard-reviews-and-drafts.index', compact('result', 'filter' ));
        }
        abort(403);

    }

   

}
