<?php

namespace App\Http\Controllers\Config;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use HP;

use App\Models\Config\ConfigsImportHoliday;

use Mpdf\Tag\Tr;

use Illuminate\Support\Facades\Storage;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Models\Basic\Holiday;


class ConfigsImportHolidayController extends Controller
{
    private $attach_path;//ที่เก็บไฟล์แนบ
    private $path_count;

    public function __construct()
    {
        $this->middleware('auth');
        $this->attach_path = 'law_attach/holidayimports/';
        $this->path_count = 'law_attach/holidayimports_count';
   
    }

    public function data_list(Request $request)
    {

        $model = str_slug('configs-import-holiday','-');

        $filter_status = $request->input('filter_status');
        
        $query = ConfigsImportHoliday::query()->when($filter_status, function ($query, $filter_status){
                                                if( $filter_status == 1){
                                                    return $query->where('state', $filter_status);
                                                }else{
                                                    return $query->where('state', '<>', 1)->orWhereNull('state');
                                                }
                                            });
                                          
        return Datatables::of($query)
                            ->addIndexColumn()
                            ->addColumn('checkbox', function ($item) {
                                return '<input type="checkbox" name="item_checkbox[]" class="item_checkbox" value="'. $item->id .'">';
                            })
                            ->addColumn('system', function ($item) {
                                return !empty($item->system)?$item->system:'-';
                            })
                            ->addColumn('created_at', function ($item) {
                                return (!empty($item->CreatedName)?$item->CreatedName:null).(!empty($item->created_at)?'<br>'.HP::DateThaiFull($item->created_at):null);
                            })
                            ->addColumn('status', function ($item) {
                                return !empty($item->StateIcon)?$item->StateIcon:null;
                            })
                            ->addColumn('action', function ($item) {
                                return HP::buttonAction( $item->id, 'config/import-holiday','Config\\ConfigsImportHolidayController@destroy', 'configs-import-holiday');
                            })
                            ->order(function ($query) {
                                $query->orderBy('id', 'DESC');
                            })
                            ->rawColumns(['checkbox', 'action', 'status', 'created_at','title', 'attachment'])
                            ->make(true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = str_slug('configs-import-holiday','-');
        if(auth()->user()->can('view-'.$model)) {

            return view('config/configs-import-holiday.index');
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
        $model = str_slug('configs-import-holiday','-');
        if(auth()->user()->can('view-'.$model)) {
            $id = null;
            return view('config/configs-import-holiday.create',compact('id'));
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
        $model = str_slug('configs-import-holiday','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['created_by' => auth()->user()->getKey()]); //user create
            $requestData = $request->all();
        
            if ($request->hasFile('payin1_file')) {
                $requestData['attach_file']                 =    $this->storeFile($request->attach_file);
                $requestData['attach_file_client_name']     =     HP::ConvertCertifyFileName($request->attach_file->getClientOriginalName());
            }
            $result = ConfigsImportHoliday::create($requestData);

            if($result){
                return redirect('config/format-code')->with('flash_message', 'เพิ่มเรียบร้อยแล้ว!');
            }
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
        $model = str_slug('configs-import-holiday','-');
        if(auth()->user()->can('view-'.$model)) {

            $result = ConfigsImportHoliday::findOrFail($id);

            return view('config/configs-format-code.show',compact('result'));
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            $import_holiday = ConfigsImportHoliday::findOrFail($id);

            return view('config/configs-import-holiday.edit',compact('import_holiday'));
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {

            $request->request->add(['updated_by' => auth()->user()->getKey()]); //user update
            $requestData = $request->all();

            $result = ConfigsImportHoliday::findOrFail($id);
            $result->update($requestData);

            if($result){
                return redirect('config/format-code')->with('flash_message', 'แก้ไขเรียบร้อยแล้ว!');
            }
        
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
        $model = str_slug('configs-format-code','-');
        if(auth()->user()->can('view-'.$model)) {


        }
        abort(403);
    }

    //เลือกลบแบบทั้งหมดได้
    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $result = ConfigsImportHoliday::whereIn('id', $id_array);
        if($result->delete())
        {
            echo 'Data Deleted';
        }
    
    }
    
    //เลือกเผยแพร่สถานะทั้งหมดได้
    public function update_publish(Request $request){
        $arr_publish = $request->input('id_publish');
        $state = $request->input('state');
    
        $result = ConfigsImportHoliday::whereIn('id', $arr_publish)->update(['state' => $state]);
        if($result)
        {
            echo 'success';
        } else {
                echo "not success";
        }
    }
    
    //เลือกเผยแพร่สถานะได้ที่ละครั้ง
    public function update_status(Request $request){
        $id_status = $request->input('id_status');
        $state = $request->input('state');
    
        $result = ConfigsImportHoliday::where('id', $id_status)  ->update(['state' => $state]);
            
        if($result)
        {
            echo 'success';
        } else {
            echo "not success";
        }
    
    }

    public function storeFile($files)
    {
        if ($files) {
            $attach_path  =  $this->attach_path;
            $fullFileName =  str_random(10).'-date_time'.date('Ymd_hms') . '.' . $files->getClientOriginalExtension();
            $storagePath = Storage::putFileAs($attach_path, $files,  str_replace(" ","",$fullFileName) );
            $storageName = basename($storagePath); // Extract the filename
            return  $attach_path.''.$storageName;
        }else{
            return null;
        }
    }

    public function start(Request $request){

        $id = $request->input('id');
		$configs_import_holiday = new ConfigsImportHoliday();
		$result = $configs_import_holiday->import($id);

		if($result['yield']){
			echo 'Success';
		}else{
			echo $result['message'];
		}
		exit;

	}

	public function force(Request $request){

		$id = $request->input('id');

		$model = new ConfigsImportHoliday();

		$result = $model->import($id, true);

		if($result['yield']){
			echo 'Success';
		}else{
			echo $result['message'];
		}
		exit;

	}

	function DataImport2(Request $request){

		$id = $request->input('id');

        $model = new ConfigsImportHoliday();

		$result = $model->DataImport($id);

		header('Content-type: application/json');
		echo json_encode(array(
							'all'=>$result->overall,
							'imported'=>$result->imported,
							'error'=>$result->row_error,
							'status'=>$result->status_import
							));

		exit;
	}

    public function getImport($id){
		return ConfigsImportHoliday::find($id);
	}

	public function import($id, $force=false){

		$result = ['yield'=>false, 'message'=>''];

		$error_cell = array();

		$import_data = $this->getImport($id);//ข้อมูลไฟล์ excel

		// var_dump($import_data); exit;

		Storage::delete($this->path_count.$id.'.txt');//ลบไฟล์ตัวนับจำนวนที่นำเข้า

		if(is_null($import_data)){
			$result['message'] = 'ไม่พบข้อมูลที่ต้องการนำเข้า';
			goto end;
		}

		if($import_data->status_import!=0){
			$result['message'] = 'ข้อมูลไฟล์นี้ได้ดำเนินการนำเข้าแล้ว';
			goto end;
		}

		$file = $import_data->file_path;
		if(!is_file($file)){
			$result['message'] = 'ไม่พบไฟล์ที่ต้องการนำเข้า';
			$this->UpdateStatus($id, -1);
			goto end;
		}

		$mime_type = mime_content_type($file);
		if($mime_type!='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
			$result['message'] = 'ประเภทไฟล์ไม่ถูกต้อง';
			$this->UpdateStatus($id, -1);
			goto end;
		}

		$inputFileType = IOFactory::identify($file);
		$objReader = IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($file);

		$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();

		$headingsArray = $objWorksheet->rangeToArray('A2:'.$highestColumn.'2', null, true, true, true);
		$headingsArray = $headingsArray[2];

		$result_header = $this->CheckExcelHeader($headingsArray);//เช็คหัวตาราง
		if(count($result_header)>0){
			$result['message'] = 'หัวตารางไม่ถูกต้อง';
			$this->UpdateStatus($id, -1);
			$this->UpdateErrorCell($id, $result_header);
			goto end;
		}

		$r = -1;
		$namedDataArray = array();

		// var_dump($highestRow); exit;

		for($row = 3; $row <= $highestRow; ++$row) {
		    $dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row, null, true, true, true);
		    ++$r;

		    foreach($headingsArray as $columnKey => $columnHeading) {
					if($dataRow[$row][$columnKey]==='รวมทั้งสิ้น'){
						break;
					}
		      $namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
		    }
		}

		$this->UpdateStatus($id, 1);//อัพเดทสถานะกำลังนำเข้า

        $update = ConfigsImportHoliday::find($id);
        $update->overall = count($namedDataArray);
        $update->save();

		unset($update->overall);

		//  $db->transactionStart(); //เริ่ม transaction

        DB::beginTransaction(); //เริ่ม transaction

		foreach($namedDataArray as $key=>$data){

			$index = $key+3;

			$object = new Holiday();
			// $object->survey_year = $import_data->survey_year;//ปีที่สำรวจ
			$object->created_by = $import_data->created_by;
			$object->created_date = date('Y-m-d H:i:s');
			$object->state = 1;

			//ชื่อวันหยุด
			$holiday_name = trim($data['ชื่อวันหยุด']);
			if($holiday_name!=''){
				$object->title = $holiday_name;
			}else{
				$error_cell[$index]['B'] = array('data'=>$data['ชื่อวันหยุด'], 'comment'=>'ต้องกรอกชื่อวันหยุด');
			}

			//ชื่อวันหยุดภาษาอังกฤษ
			$holiday_en_name = trim($data['ชื่อวันหยุดภาษาอังกฤษ']);
			if($holiday_en_name!=''){
				$object->title_en = $holiday_en_name;
			}else{
				$error_cell[$index]['C'] = array('data'=>$data['ชื่อวันหยุดภาษาอังกฤษ'], 'comment'=>'ต้องกรอกชื่อวันหยุดภาษาอังกฤษ');
			}

			//ปี ค.ศ.
			$fis_year = trim($data['ปี ค.ศ.']);
			if($fis_year!=''){
				$object->fis_year = $fis_year;
			}else{
				$error_cell[$index]['D'] = array('data'=>$data['ปี ค.ศ.'], 'comment'=>'ต้องกรอกปี ค.ศ.');
			}

			//วันที่ (yyyy-mm-dd)
			$holiday_date = trim($data['วันที่ (yyyy-mm-dd)']);

			// var_dump($holiday_date); exit;

			if($holiday_date!=''){
				$object->holiday_date = $holiday_date;
			}else{
				$error_cell[$index]['E'] = array('data'=>$data['วันที่ (yyyy-mm-dd)'], 'comment'=>'ต้องกรอกวันที่ yyyy-mm-dd');
			}

			// var_dump($object); exit;

			if(count(@$error_cell[$index])==0){//ถ้าไม่มีข้อผิดพลาดให้นำเข้าข้อมูล
				if(!isset($object->id)){
					$result_insert = $object->save();;
				}else{
					$result_insert = null;
				}

				if(!is_null($result_insert) || isset($object->id)){//บันทึกข้อมูลหลักสำเร็จ
					$update->imported = (int)$this->getImport($id)->imported + 1;
					$this->UpdateImported($id, $update->imported);
				}

			}else{
				$this->UpdateError($id);
			}

		}

		if(count($error_cell)>0){//ถ้ามีข้อผิดพลาด
			if($force==false){//ถ้าไม่บังคับนำเข้า
				//$db->transactionRollback();//Roll ฺBack ไปก่อนนำเข้า
                DB::rollBack();//Roll ฺBack ไปก่อนนำเข้า
				$result['message'] = 'การนำเข้าข้อมูลล้มเหลว ข้อมูลบางส่วนหรือทั้งหมดไม่ถูกต้อง';
				$this->UpdateStatus($id, 0);//อัพเดทสถานะเป็นยังไม่ได้นำเข้า
			}else{
				//$db->transactionCommit();
                DB::commit();
				$result['yield'] = true;
				$this->UpdateStatus($id, 2);//อัพเดทสถานะเป็นนำเข้าสำเร็จ
			}
			$this->UpdateErrorCell($id, $error_cell);//อัพเดทข้อผิดพลาด
		}else{
			//$db->transactionCommit();
            DB::commit();
			$result['yield'] = true;
			$this->UpdateStatus($id, 2);//อัพเดทสถานะเป็นนำเข้าสำเร็จ
		}

		end:
		return $result;

	}

	private function UpdateStatus($id, $status){
        $import_holiday = ConfigsImportHoliday::find($id);
        $import_holiday->status_import = $status;
            if ($status == 0) {
                $import_holiday->imported = 0;
            }
        $import_holiday->save();
	}

	private function UpdateImported($id, $imported){

        $import_holiday = ConfigsImportHoliday::find($id);
        $import_holiday->imported = $imported;
        $import_holiday->save();

        Storage::put($this->path_count.$id.'.txt', $imported); //บันทึกจำนวนที่นำเข้าสำเร็จลงไฟล์

	}

	private function UpdateError($id){

        $import_holiday = ConfigsImportHoliday::find($id);
        $import_holiday->row_error = (int)$this->getImport($id)->row_error + 1;
        $import_holiday->save();

        Storage::put($this->path_count.$id.'.-error.txt', $import_holiday->row_error);//บันทึกจำนวนที่ไม่นำเข้าลงไฟล์
	}

	private function UpdateErrorCell($id, $error_cell){
	
        $import_holiday = ConfigsImportHoliday::find($id);
        $import_holiday->error_cell = json_encode($error_cell);
        $import_holiday->save();

	}

	private function CheckExcelHeader($dataHeader){

		$headerList = array('B'=>'ชื่อวันหยุด', 'C'=>'ชื่อวันหยุดภาษาอังกฤษ', 'D'=>'ปี ค.ศ.', 'E'=>'วันที่ (yyyy-mm-dd)');
		$row = array();

		foreach($headerList as $key=>$value){

			if($dataHeader[$key] != $value){
				$row[2][$key] = array('data'=>$dataHeader[$key], 'comment'=>'หัวตารางต้องเป็น "'.$value.'" เท่านั้น');
			}

		}

		return $row;

	}

	public function DataImport($id){

		$import = $this->getImport($id);

		if(!is_null($import)){

			//$session = JFactory::getSession();

			$file = $this->path_count.$import->id.'.txt';
			$file_error = $this->path_count.$import->id.'-error.txt';

			$import->imported = is_file($file)?(int)Storage::get($file):0;
			$import->row_error = is_file($file_error)?(int)Storage::get($file_error):0;

		}

		return $import;

	}

	public function deleteFile($fileList){

		foreach ($fileList as $key => $value) {
			Storage::delete($value);
		}

	}

	public function getHoliday($value, $field, $year){
        $result = Holiday::where($$field,$value)->where('fis_year',$year)->get();
		return  $result;
	}

   
}
