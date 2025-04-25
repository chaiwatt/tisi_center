<?php

namespace App\Http\Controllers\Section5;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;
use App\Models\Section5\ApplicationIbcbStaff;
use App\Models\Section5\ApplicationIbcb;
use App\Models\Section5\ApplicationIbcbScope;
use App\Models\Section5\ApplicationIbcbScopeDetail;
use App\Models\Section5\ApplicationIbcbAccept;
use App\Models\Section5\ApplicationIbcbAudit;
use App\Models\Tis\Standard;

use App\Mail\Section5\ApplicationIBCBAcceptMail;
use Illuminate\Support\Facades\Mail;

use App\Models\Basic\BranchGroup;
use App\Models\Basic\Branch;
use App\Models\Bsection5\WorkGroupIB;
use App\Models\Elicense\RosUsers;
use App\Models\Elicense\Rform\FactoryDetail;

class ReportFactoryInspectionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = str_slug('report-factory-inspection','-');
        if(auth()->user()->can('view-'.$model)) {
            return view('section5.report-factory-inspection.index', compact('filter'));
        }
        abort(403);
    }

    //รายการข้อมูล
    public function data_list(Request $request)
    {

        $config = HP::getConfig();
        $elicense_url = $config->url_elicense_trader.'/media/com_rform/factoryresult';

        $filter_search = $request->input('filter_search');
        $filter_tis_number = $request->input('filter_tis_number');

        $filter_status = $request->input('filter_status');
        $filter_inspect_status = $request->input('filter_inspect_status');
        $filter_inspect_result = $request->input('filter_inspect_result');

        $filter_start_checking_date = $request->input('filter_start_checking_date');
        $filter_end_checking_date   = $request->input('filter_end_checking_date');

        $filter_start_inspect_date = $request->input('filter_start_inspect_date');
        $filter_end_inspect_date   = $request->input('filter_end_inspect_date');

        $filter_start_report_date = $request->input('filter_start_report_date');
        $filter_end_report_date   = $request->input('filter_end_report_date');

        $db_e = DB::connection('mysql_elicense');

        //select วันที่เริ่มตรวจ เพื่อเอารายการล่าสุด
        $start_inspect_date = '(SELECT inspection.start_inspect_date FROM ros_rform_factory_inspection AS inspection WHERE factory_detail_id=a.id ORDER BY inspection.id DESC LIMIT 0, 1) AS start_inspect_date';

        //select วันที่สิ้นสุดการตรวจ เพื่อเอารายการล่าสุด
        $end_inspect_date = '(SELECT inspection.end_inspect_date FROM ros_rform_factory_inspection AS inspection WHERE factory_detail_id=a.id ORDER BY inspection.id DESC LIMIT 0, 1) AS end_inspect_date';

        $factory_detail_model = new FactoryDetail;
        $query =   $db_e->table($factory_detail_model->getTable().' AS a')
                        ->leftJoin((new RosUsers)->getTable().' AS user', 'user.id', '=', 'a.auditor_id')
                        ->leftJoin('ros_rform_factory AS factory', 'factory.id', '=', 'a.factory_id')
                        ->select(DB::Raw("a.*, user.name AS auditor, factory.tis_number, factory.refno, factory.tax_number, $start_inspect_date, $end_inspect_date"))

                        ->when($filter_search, function ($query, $filter_search){
                                            $query->Where('factory.refno', 'LIKE', "%".$filter_search."%")
                                                  ->OrWhere('user.name', 'LIKE', "%".$filter_search."%");
                        })
                        ->when($filter_tis_number , function ($query, $filter_tis_number){
                            return $query->where('factory.tis_number', $filter_tis_number);
                        })
                        ->when($filter_status, function ($query, $filter_status){
                            return $query->where('a.status', $filter_status);
                        })
                        ->when($filter_inspect_status, function ($query, $filter_inspect_status){
                            return $query->where('a.inspect_status', $filter_inspect_status);
                        })
                        ->when($filter_inspect_result, function ($query, $filter_inspect_result){
                            return $query->where('a.inspect_result', $filter_inspect_result);
                        })
                        ->when($filter_start_checking_date, function ($query, $filter_start_checking_date){
                            $filter_start_checking_date = HP::convertDate($filter_start_checking_date, true);
                            return $query->where('a.checking_date', '>=', $filter_start_checking_date);
                        })
                        ->when($filter_end_checking_date, function ($query, $filter_end_checking_date){
                            $filter_end_checking_date = HP::convertDate($filter_end_checking_date, true);
                            return $query->where('a.checking_date', '<=', $filter_end_checking_date);
                        })
                        ->when((!empty($filter_start_inspect_date) || !empty($filter_end_inspect_date)), function ($query) use ($filter_start_inspect_date, $filter_end_inspect_date){

                            if(!empty($filter_start_inspect_date) && !empty($filter_end_inspect_date)){//ถ้ากรองทั้งเริ่มต้น-สิ้นสุด

                                $start_inspect_date = HP::convertDate($filter_start_inspect_date, true);
                                $end_inspect_date = HP::convertDate($filter_end_inspect_date, true);

                                return $query->havingRaw("(DATE(start_inspect_date) >= '$start_inspect_date' AND DATE(end_inspect_date) <= '$start_inspect_date') OR (DATE(start_inspect_date) >= '$end_inspect_date' AND DATE(end_inspect_date) <= '$end_inspect_date')");

                            }elseif(!empty($filter_start_inspect_date)){ //ถ้ากรองเริ่มต้น
                                $start_inspect_date = HP::convertDate($filter_start_inspect_date, true);
                                return $query->having('start_inspect_date', '>=', $start_inspect_date)
                                             ->having('end_inspect_date', '<=', $start_inspect_date);
                            }else if(!empty($filter_end_inspect_date)){ //ถ้ากรองสิ้นสุด
                                $end_inspect_date = HP::convertDate($filter_end_inspect_date, true);
                                return $query->having('start_inspect_date', '>=', $end_inspect_date)
                                             ->having('end_inspect_date', '<=', $end_inspect_date);
                            }

                        })
                        ->when($filter_start_report_date, function ($query, $filter_start_report_date){
                            $filter_start_report_date = HP::convertDate($filter_start_report_date, true);
                            return $query->where('a.inspect_date', '>=', $filter_start_report_date);
                        })
                        ->when($filter_end_report_date, function ($query, $filter_end_report_date){
                            $filter_end_report_date = HP::convertDate($filter_end_report_date, true);
                            return $query->where('a.inspect_date', '<=', $filter_end_report_date);
                        });

        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('auditor', function ($item) {
                                return $item->auditor;
                            })
                            ->addColumn('tis_number', function ($item) {
                                return $item->tis_number;
                            })
                            ->addColumn('status', function ($item) use ($factory_detail_model) {
                                $status_list = $factory_detail_model->status_list();
                                return array_key_exists($item->status, $status_list) ? $status_list[$item->status] : '-';
                            })
                            ->addColumn('checking_date', function ($item) {
                                return HP::DateThai($item->checking_date);
                            })
                            ->addColumn('inspect_status', function ($item) use ($factory_detail_model) {
                                $status_list = $factory_detail_model->inspect_status_list();
                                return array_key_exists($item->inspect_status, $status_list) ? $status_list[$item->inspect_status] : '-';
                            })
                            ->addColumn('inspect_date_period', function ($item) {
                                return !is_null($item->start_inspect_date) ? HP::DateThai($item->start_inspect_date).' - '.HP::DateThai($item->end_inspect_date) : '-' ;
                            })
                            ->addColumn('inspect_result', function ($item) use ($factory_detail_model){
                                $status_list = $factory_detail_model->inspect_result_list();
                                return array_key_exists($item->inspect_result, $status_list) ? $status_list[$item->inspect_result] : '-';
                            })
                            ->addColumn('report_date', function ($item) {
                                return HP::DateThai($item->inspect_date);
                            })
                            ->addColumn('inspect_report_file', function ($item) use ($elicense_url) {

                                $inspect_report_files = json_decode($item->inspect_report_file);

                                $links = [];
                                foreach ((array)$inspect_report_files as $key => $inspect_report_file) {
                                    $links[] = '<a href="'.$elicense_url.'/'.$item->tax_number.'/'.$inspect_report_file->realfile.'" target="_blank"><i class="mdi mdi-file-pdf text-danger font-20"></i></a>';
                                }

                                return implode('', $links);

                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['inspect_report_file'])
                            ->make(true);
    }

}
