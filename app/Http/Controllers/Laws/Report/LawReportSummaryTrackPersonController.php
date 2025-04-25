<?php

namespace App\Http\Controllers\Laws\Report;

use HP;
use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use HP_Law;

use App\Models\Law\Track\LawTrackReceiveAssign;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use stdClass;

class LawReportSummaryTrackPersonController extends Controller
{
    private $permission;
    public function __construct()
    {
        $this->middleware('auth');
        $this->permission  = str_slug('law-report-summary-track-person','-');
    }

    public function query(Request $request)
    {
        $filter_condition_search   = $request->input('filter_condition_search');
        $filter_search             = $request->input('filter_search');
        $filter_status             = $request->input('filter_status');

        $filter_law_job_type_id    = $request->input('filter_law_job_type_id');
        $filter_sub_departments_id = $request->input('filter_sub_departments_id');
        $filter_law_deperment_id   = $request->input('filter_law_deperment_id');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_assign_month_start = $request->input('filter_assign_month_start');
        $filter_assign_month_end   = $request->input('filter_assign_month_end');
        $filter_assign_year_start  = $request->input('filter_assign_year_start');  
        $filter_assign_year_end    = $request->input('filter_assign_year_end');

        $filter_month_start        = $request->input('filter_month_start');
        $filter_month_end          = $request->input('filter_month_end');
        $filter_year_start         = $request->input('filter_year_start');  
        $filter_year_end           = $request->input('filter_year_end');

        
        $query = LawTrackReceiveAssign::query()->select('user_id')->groupBy('user_id')
                                            ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                                $search_full = str_replace(' ', '', $filter_search);

                                                switch ( $filter_condition_search ):
                                                    case "1":
                                                        return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(reference_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        });
                                                        break;
                                                    case "2":
                                                        return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(book_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                        break;
                                                    case "3":
                                                        return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(receive_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                        break;
                                                    case "4":
                                                        return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                        break;
                                                    case "5":
                                                        return $query->whereHas('law_track_receive.law_deparment', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        })
                                                                        ->OrwhereHas('law_track_receive.sub_deparment', function($query) use ($search_full){
                                                                            $query->Where(DB::raw("REPLACE(sub_departname,' ','')"), 'LIKE', "%".$search_full."%");
                                                                        });
                                                        break;
                                                    case "6":
                                                        return $query->whereHas('user_staff', function($query) use ($search_full){
                                                            $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                        });
                                                        break;
                                                    default:

                                                        return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(reference_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(book_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(receive_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                            ->OrWhere(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    })
                                                                    ->OrwhereHas('law_track_receive.law_deparment', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    })
                                                                    ->OrwhereHas('law_track_receive.sub_deparment', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(sub_departname,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    })
                                                                    ->OrwhereHas('user_staff', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("CONCAT(REPLACE(reg_fname,' ',''),'', REPLACE(reg_lname,' ',''))"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                      
                                                        break;
                                                endswitch;
                                            })
                                            ->when($filter_law_job_type_id, function ($query, $filter_law_job_type_id){
                                                $query->whereHas('law_track_receive', function($query) use ($filter_law_job_type_id){
                                                            $query->where('law_bs_job_type_id', $filter_law_job_type_id);
                                                        });
                                            })
                                            ->when($filter_sub_departments_id, function ($query, $filter_sub_departments_id){
                                                $query->whereHas('law_track_receive', function($query) use ($filter_sub_departments_id){
                                                    $query->where('sub_departments_id', $filter_sub_departments_id);
                                                });
                                            })
                                            ->when($filter_law_deperment_id, function ($query, $filter_law_deperment_id){
                                                $query->whereHas('law_track_receive', function($query) use ($filter_law_deperment_id){
                                                    $query->where('law_bs_deperment_id', $filter_law_deperment_id);
                                                });
                                            })
                                            ->when($filter_status, function ($query, $filter_status){
                                                $query->whereHas('law_track_receive', function($query) use ($filter_status){
                                                    $query->where('status_job_track_id', $filter_status);
                                                });
                                            })
                                            ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                                $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                                return $query->where('created_at', '>=', $filter_assign_start_date);
                                            })
                                            ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                                $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                                return $query->where('created_at', '<=', $filter_assign_end_date);
                                            })
                                            ->when($filter_start_date, function ($query, $filter_start_date){
                                                $filter_start_date = HP::convertDate($filter_start_date, true);
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_start_date){
                                                                    $query->where('created_at', '>=', $filter_start_date);
                                                                });
                                            })
                                            ->when($filter_end_date, function ($query, $filter_end_date){
                                                $filter_end_date = HP::convertDate($filter_end_date, true);
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_end_date){
                                                                    $query->where('created_at', '<=', $filter_end_date);
                                                                });
                                            })
                                            ->when($filter_assign_month_start, function ($query, $filter_assign_month_start){
                                                return $query->whereMonth('created_at', '>=', $filter_assign_month_start);
                                            })
                                            ->when($filter_assign_month_end, function ($query, $filter_assign_month_end){
                                                return $query->whereMonth('created_at', '<=', $filter_assign_month_end);
                                            })
                                            ->when($filter_assign_year_start, function ($query, $filter_assign_year_start){
                                                return $query->whereYear('created_at', '>=', $filter_assign_year_start);
                                            })
                                            ->when($filter_assign_year_end, function ($query, $filter_assign_year_end){
                                                return $query->whereYear('created_at', '<=', $filter_assign_year_end);
                                            })
                                            ->when($filter_month_start, function ($query, $filter_month_start){
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_month_start){
                                                                    $query->whereMonth('created_at', '>=', $filter_month_start);
                                                                });
                                            })
                                            ->when($filter_month_end, function ($query, $filter_month_end){
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_month_end){
                                                                    $query->whereMonth('created_at', '<=', $filter_month_end);
                                                                });
                                            })
                                            ->when($filter_year_start, function ($query, $filter_year_start){
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_year_start){
                                                                    $query->whereYear('created_at', '>=', $filter_year_start);
                                                                });
                                            })
                                            ->when($filter_year_end, function ($query, $filter_year_end){
                                                return  $query->whereHas('law_track_receive', function($query) use ($filter_year_end){
                                                                    $query->whereYear('created_at', '<=', $filter_year_end);
                                                                });
                                            });
        return $query;
    }

    public function data_list(Request $request)
    {

        $query = $this->query($request);
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('staff_name', function ($item) {
                                return !empty($item->StaffName)?$item->StaffName:null;
                            })   
                            ->addColumn('staff_deparment', function ($item) {
                                return !empty($item->StaffDeparmentName)?$item->StaffDeparmentName:null;
                            })   
                            ->addColumn('last_date', function ($item) {
                                $last_date =  $item->LastDate;
                                return !empty($last_date)?HP::revertDate($last_date,true):null;
                            })   
                            ->addColumn('amount', function ($item) {
                                $amount =  count($item->TrackReceiveAssignParentData);
                                return number_format($amount);
                            })  
                            ->addColumn('action', function ($item) {
                                if(auth()->user()->can('view-'.$this->permission)){
                                    return '<a href="'.url('law/report/summary-track-person/'.$item->user_id).'" class="btn btn-label-info btn-sm btn-circle"><i class="fa fa-eye fa-lg"></i></a>';
                                }else{
                                    return '<button disabled class="btn btn-label-info btn-sm btn-circle"><i class="fa fa-eye fa-lg"></i></button>';
                                }
                            }) 
                            ->rawColumns(['receive_date', 'action', 'status', 'created_by','title', 'created_at','assing', 'book_no','law_deparment'])
                            ->make(true);

    }

    public function data_chart(Request $request)
    {
        $query = $this->query($request);
        $query = $query->get();

        $list = [];
        foreach( $query AS $item ){
            $data = new stdClass;
            $data->name = !empty($item->StaffName)?$item->StaffName:null;
            $data->y    = count($item->TrackReceiveAssignParentData);
            $list[]     =  $data;
        }

        if(  $list == 0){
            $data = new stdClass;
            $data->name = 'ไม่พบข้อมูล';
            $data->y    = 0;
            $list[]     =  $data;
        }

        return response()->json($list);

    }

    public function query_track(Request $request)
    {
        $filter_users_id  = $request->input('filter_users_id');

        $filter_condition_search   = $request->input('filter_condition_search');
        $filter_search             = $request->input('filter_search');
        $filter_status             = $request->input('filter_status');

        $filter_law_job_type_id    = $request->input('filter_law_job_type_id');
        $filter_sub_departments_id = $request->input('filter_sub_departments_id');
        $filter_law_deperment_id   = $request->input('filter_law_deperment_id');

        $filter_start_date         = $request->input('filter_start_date');
        $filter_end_date           = $request->input('filter_end_date');

        $filter_assign_start_date  = $request->input('filter_assign_start_date');
        $filter_assign_end_date    = $request->input('filter_assign_end_date');

        $filter_assign_month_start = $request->input('filter_assign_month_start');
        $filter_assign_month_end   = $request->input('filter_assign_month_end');
        $filter_assign_year_start  = $request->input('filter_assign_year_start');  
        $filter_assign_year_end    = $request->input('filter_assign_year_end');

        $filter_month_start        = $request->input('filter_month_start');
        $filter_month_end          = $request->input('filter_month_end');
        $filter_year_start         = $request->input('filter_year_start');  
        $filter_year_end           = $request->input('filter_year_end');

        $query = LawTrackReceiveAssign::query()
                                        ->where( function($query) use($filter_users_id) {
                                            $query->where('user_id',$filter_users_id);
                                        })
                                        ->when($filter_search, function ($query, $filter_search) use ($filter_condition_search){
                                            $search_full = str_replace(' ', '', $filter_search);

                                            switch ( $filter_condition_search ):
                                                case "1":
                                                    return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(reference_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                case "2":
                                                    return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(book_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                    break;
                                                case "3":
                                                    return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(receive_no,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                    break;
                                                case "4":
                                                    return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                    break;
                                                case "5":
                                                    return $query->whereHas('law_track_receive.law_deparment', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    })
                                                                    ->OrwhereHas('law_track_receive.sub_deparment', function($query) use ($search_full){
                                                                        $query->Where(DB::raw("REPLACE(sub_departname,' ','')"), 'LIKE', "%".$search_full."%");
                                                                    });
                                                    break;
                                                default:

                                                    return $query->whereHas('law_track_receive', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(reference_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(book_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(receive_no,' ','')"), 'LIKE', "%".$search_full."%")
                                                                        ->OrWhere(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                })
                                                                ->OrwhereHas('law_track_receive.law_deparment', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(title,' ','')"), 'LIKE', "%".$search_full."%");
                                                                })
                                                                ->OrwhereHas('law_track_receive.sub_deparment', function($query) use ($search_full){
                                                                    $query->Where(DB::raw("REPLACE(sub_departname,' ','')"), 'LIKE', "%".$search_full."%");
                                                                });
                                                
                                                    break;
                                            endswitch;
                                        })
                                        ->when($filter_law_job_type_id, function ($query, $filter_law_job_type_id){
                                            $query->whereHas('law_track_receive', function($query) use ($filter_law_job_type_id){
                                                        $query->where('law_bs_job_type_id', $filter_law_job_type_id);
                                                    });
                                        })
                                        ->when($filter_sub_departments_id, function ($query, $filter_sub_departments_id){
                                            $query->whereHas('law_track_receive', function($query) use ($filter_sub_departments_id){
                                                $query->where('sub_departments_id', $filter_sub_departments_id);
                                            });
                                        })
                                        ->when($filter_law_deperment_id, function ($query, $filter_law_deperment_id){
                                            $query->whereHas('law_track_receive', function($query) use ($filter_law_deperment_id){
                                                $query->where('law_bs_deperment_id', $filter_law_deperment_id);
                                            });
                                        })
                                        ->when($filter_status, function ($query, $filter_status){
                                            $query->whereHas('law_track_receive', function($query) use ($filter_status){
                                                $query->where('status_job_track_id', $filter_status);
                                            });
                                        })
                                        ->when($filter_assign_start_date, function ($query, $filter_assign_start_date){
                                            $filter_assign_start_date = HP::convertDate($filter_assign_start_date, true);
                                            return $query->where('created_at', '>=', $filter_assign_start_date);
                                        })
                                        ->when($filter_assign_end_date, function ($query, $filter_assign_end_date){
                                            $filter_assign_end_date = HP::convertDate($filter_assign_end_date, true);
                                            return $query->where('created_at', '<=', $filter_assign_end_date);
                                        })
                                        ->when($filter_start_date, function ($query, $filter_start_date){
                                            $filter_start_date = HP::convertDate($filter_start_date, true);
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_start_date){
                                                                $query->where('created_at', '>=', $filter_start_date);
                                                            });
                                        })
                                        ->when($filter_end_date, function ($query, $filter_end_date){
                                            $filter_end_date = HP::convertDate($filter_end_date, true);
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_end_date){
                                                                $query->where('created_at', '<=', $filter_end_date);
                                                            });
                                        })
                                        ->when($filter_assign_month_start, function ($query, $filter_assign_month_start){
                                            return $query->whereMonth('created_at', '>=', $filter_assign_month_start);
                                        })
                                        ->when($filter_assign_month_end, function ($query, $filter_assign_month_end){
                                            return $query->whereMonth('created_at', '<=', $filter_assign_month_end);
                                        })
                                        ->when($filter_assign_year_start, function ($query, $filter_assign_year_start){
                                            return $query->whereYear('created_at', '>=', $filter_assign_year_start);
                                        })
                                        ->when($filter_assign_year_end, function ($query, $filter_assign_year_end){
                                            return $query->whereYear('created_at', '<=', $filter_assign_year_end);
                                        })
                                        ->when($filter_month_start, function ($query, $filter_month_start){
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_month_start){
                                                                $query->whereMonth('created_at', '>=', $filter_month_start);
                                                            });
                                        })
                                        ->when($filter_month_end, function ($query, $filter_month_end){
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_month_end){
                                                                $query->whereMonth('created_at', '<=', $filter_month_end);
                                                            });
                                        })
                                        ->when($filter_year_start, function ($query, $filter_year_start){
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_year_start){
                                                                $query->whereYear('created_at', '>=', $filter_year_start);
                                                            });
                                        })
                                        ->when($filter_year_end, function ($query, $filter_year_end){
                                            return  $query->whereHas('law_track_receive', function($query) use ($filter_year_end){
                                                                $query->whereYear('created_at', '<=', $filter_year_end);
                                                            });
                                        });
        return $query;
    }

    public function data_track_receive_list(Request $request)
    {

        $query = $this->query_track($request);
        
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('receive_no', function ($item) {
                                return !empty($item->law_track_receive)?$item->law_track_receive->receive_no:null;
                            })     
                            ->addColumn('law_job_types', function ($item) {
                                $law_track_receive = $item->law_track_receive;
                                return  !is_null($law_track_receive) && !empty($law_track_receive->law_job_types->title)?$law_track_receive->law_job_types->title:null;
                            })
                            ->addColumn('law_deparment', function ($item) {
                                $law_track_receive = $item->law_track_receive;
                                return !is_null($law_track_receive) ? (($law_track_receive->DeparmentName).('<div><em>('.(@$law_track_receive->DeparmentTypeName).')</em></div>')):null;
                            })
                            ->addColumn('law_deparment', function ($item) {
                                $deperment = '';
                                $law_track_receive = $item->law_track_receive;
                                if($law_track_receive->law_deperment_type=='1'){
                                  $deperment =  $law_track_receive->DeparmentName;
                                }else if($law_track_receive->law_deperment_type=='2'){
                                  $deperment =  $law_track_receive->LawDeparmentName;
                                }
                                return ($deperment).('<div class="text-muted">('.(@$law_track_receive->DeparmentTypeName).')</div>');
                            })
                            ->addColumn('receive_date', function ($item) {
                                $law_track_receive = $item->law_track_receive;
                                return  (!empty($law_track_receive->receive_date)?HP::revertDate($law_track_receive->receive_date,true):null) ;
                            })   
                            ->addColumn('amount', function ($item) {
                                $law_track_receive = $item->law_track_receive;
                                $date = !empty(  $last_date )?  $last_date : $item->created_at;

                                $startDate = \Carbon\Carbon::parse( $law_track_receive->receive_date )->format('Y-m-d');
                                $endDate   = \Carbon\Carbon::parse( !empty($law_track_receive->close_date)?$law_track_receive->close_date:$date )->format('Y-m-d');

                                $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);

                                return  count($lits) ;
                            })   
                            ->addColumn('last_date', function ($item) {
                                $law_track_receive = $item->law_track_receive;
                                $last_date   = !empty($law_track_receive->close_date)?$law_track_receive->close_date:null;
                                $assign_date = $item->created_at;
                                return !empty($last_date)?HP::revertDate($last_date,true):( !empty($assign_date)?HP::revertDate($assign_date,true):null );
                            })   
                            ->addColumn('assign_date', function ($item) {
                                return  (!empty($item->created_at)?HP::revertDate($item->created_at,true):null) ;
                            })   
                            ->addColumn('status', function ($item) {
                                $law_track_receive = $item->law_track_receive;

                                return  !is_null($law_track_receive->law_status_job_tracks) && !empty($law_track_receive->law_status_job_tracks->title)?$law_track_receive->law_status_job_tracks->title:null;
                            }) 
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['receive_date', 'action', 'status', 'created_by','title', 'created_at','assing', 'book_no','law_deparment'])
                            ->make(true);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('law-report-summary-track-person','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-track-person",  "name" => 'รายงานสรุปภาพรวมผลงาน' ],
            ];

            return view('laws.report.summary-track-person.index',compact('breadcrumbs'));
        }
        abort(403);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $model = str_slug('law-report-summary-track-person','-');
        if(auth()->user()->can('view-'.$model)) {
            $breadcrumbs = [
                [ "link" => "/law/dashboard", "name" => "Home", "icon" => "icon-home" ],
                [ "link" => "/law/report/summary-track-person",  "name" => 'รายงานสรุปภาพรวมผลงาน' ],
            ];

            $assign_users = LawTrackReceiveAssign::where('user_id',$user_id)->first();

            return view('laws.report.summary-track-person.show',compact('breadcrumbs','assign_users'));


        }
        abort(403);
    }

    public function export_excel(Request $request)
    {
        $query = $this->query($request);
        $query = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานสรุปภาพรวมผลงาน');
        $sheet->mergeCells('A1:E1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A2:E2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal('center');

        $styleArray_header = [
            'font' => [ // จัดตัวอักษร
                'bold' => true, // กำหนดเป็นตัวหนา
            ],
            'alignment' => [  // จัดตำแหน่ง
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [ // กำหนดเส้นขอบ
                'allBorders' => [ // กำหนดเส้นขอบทั้งหมด
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [ // กำหนดสีพื้นหลัง
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR, // รูปแบบพื้นหลัง
                'rotation' => 90, // กำหนดองศาทิศทางการไล่เฉด
                'startColor' => [ // สีที่ 1
                    'argb' => 'FFA0A0A0',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว 
                ],
                'endColor' => [ // สีที่ 2
                    'argb' => 'FFFFFFFF',  // argb คือ Alpha rgb มี 8 ตัว หรือใช้เป็น rgb มี 6 ตัว FFFFFF
                ],
            ],
        ];

        //หัวตาราง
        $sheet->setCellValue('A4', 'No');
        $sheet->setCellValue('B4', 'เจ้าหน้าที่ได้รับมอบหมาย');
        $sheet->setCellValue('C4', 'หน่วยงาน');
        $sheet->setCellValue('D4', 'จำนวนเรื่อง');
        $sheet->setCellValue('E4', 'วันที่ดำเนินการล่าสุด');

        $sheet->getStyle('A4:E4')->applyFromArray($styleArray_header);

        $row = 4;
        $i = 0;
        foreach($query as $key =>$item){

            $row++;

            $last_date =  $item->LastDate;
            $amount =  count($item->TrackReceiveAssignParentData);

            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, ( !empty($item->StaffName)?$item->StaffName:null ));
            $sheet->setCellValue('C'.$row, ( !empty($item->StaffDeparmentName)?$item->StaffDeparmentName:null ));

            $sheet->setCellValue('D'.$row, ( $amount ));
            $sheet->setCellValue('E'.$row, ( !empty($last_date)?HP::revertDate($last_date,true):null  ));

            $sheet->getStyle('D' . $row)->getNumberFormat()
            ->setFormatCode(
                \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
            );

        }

        $sheet->getStyle("D5:D".$row)->getNumberFormat()->setFormatCode("#,##0");

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $filename = 'รายงานสรุปภาพรวมผลงาน_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;

    }

    public function export_excel_person(Request $request)
    {

        $filter_users_id = $request->input('filter_users_id');
        $query           = $this->query_track($request);
        $query           = $query->get();
        $assign_users    = LawTrackReceiveAssign::where('user_id',$filter_users_id)->first();              
        $spreadsheet     = new Spreadsheet();
        $sheet           = $spreadsheet->getActiveSheet();

        //หัวรายงาน
        $sheet->setCellValue('A1', 'รายงานสรุปภาพรวมผลงาน');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ข้อมูล ณ วันที่ '.HP::formatDateThaiFull(date('Y-m-d')).' เวลา '.(\Carbon\Carbon::parse(date('H:i:s'))->timezone('Asia/Bangkok')->format('H:i')).' น.'   );
        $sheet->mergeCells('A2:I2');

        $sheet->setCellValue('A3', (!empty($assign_users->StaffName)?$assign_users->StaffName:null) );
        $sheet->mergeCells('A3:I3');

        //หัวตาราง
        $sheet->setCellValue('A5', 'No');
        $sheet->setCellValue('B5', 'เลขรับ');
        $sheet->setCellValue('C5', 'ประเภทงาน');
        $sheet->setCellValue('D5', 'หน่วยงานต้นเรื่อง');
        $sheet->setCellValue('E5', 'วันที่รับงานเข้า');
        $sheet->setCellValue('F5', 'วันที่หมอบหมาย');
        $sheet->setCellValue('G5', 'วันที่ดำเนินการล่าสุด');
        $sheet->setCellValue('H5', 'รวมจำนวนวันที่ดำเนินงาน');
        $sheet->setCellValue('I5', 'สถานะ');

        $row = 5;
        $i = 0;
        foreach($query as $key =>$item){
            $row++;
            $law_track_receive = $item->law_track_receive;

            $last_date   = !empty($law_track_receive->close_date)?$law_track_receive->close_date:null;
            $assign_date = $item->created_at;
            
            $startDate = \Carbon\Carbon::parse( $law_track_receive->receive_date )->format('Y-m-d');
            $endDate   = \Carbon\Carbon::parse( !empty($law_track_receive->close_date)?$law_track_receive->close_date: $item->created_at )->format('Y-m-d');

            $lits = HP_Law::dateRangeNotPublicHoliday($startDate,  $endDate);

            $sheet->setCellValue('A'.$row, $key+1);
            $sheet->setCellValue('B'.$row, ( !empty($law_track_receive->receive_no)?$law_track_receive->receive_no:null ));
            $sheet->setCellValue('C'.$row, ( !is_null($law_track_receive) && !empty($law_track_receive->law_job_types->title)?$law_track_receive->law_job_types->title:null ));
            $sheet->setCellValue('D'.$row, ( !empty($law_track_receive->DeparmentName)?$law_track_receive->DeparmentName:null ));
            $sheet->setCellValue('E'.$row, ( !empty($law_track_receive->receive_date)?HP::revertDate($law_track_receive->receive_date,true):null ));

            $sheet->setCellValue('F'.$row, ( !empty($item->created_at)?HP::revertDate($item->created_at,true):null ));
            $sheet->setCellValue('G'.$row, ( !empty($last_date)?HP::revertDate($last_date,true):( !empty($assign_date)?HP::revertDate($assign_date,true):null ) ));
            $sheet->setCellValue('H'.$row, ( count($lits) ));
            $sheet->setCellValue('I'.$row, ( !is_null($law_track_receive) && !empty($law_track_receive->law_status_job_tracks->title)?$law_track_receive->law_status_job_tracks->title:null ));

        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);

        $filename = 'รายงานสรุปภาพรวมผลงาน_'.date('Hi_dmY').'.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save("php://output");
        exit;
    }
}
