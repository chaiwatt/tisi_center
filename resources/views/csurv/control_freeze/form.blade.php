@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .justify-content-between {
            justify-content: space-between !important;
        }
        .d-flex {
            display: flex !important;
        }
    </style>
@endpush


<div class="white-box">

    <center>
        <h3 class="text-center">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม</h3>
    </center>

    <input name="id" type="hidden"  value="{{$data->id}}">

    <div class="row">
        <div class="form-group">
            <div class="col-md-offset-7">
                {!! Form::label('number', 'เลขที่รัน:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! !empty($data->auto_id_doc)?$data->auto_id_doc:'Auto' !!}
                </div>
            </div>
        </div>
    </div>
 
    <div class="row">
        <div class="form-group">
            <div class="col-md-offset-7">
                {!! Form::label('document_number', 'เลขที่เอกสาร:', ['class' => 'col-md-4 control-label']) !!}
                <div class="col-md-8">
                    {!! Form::text('document_number', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                    {!! $errors->first('document_number', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10 required">
        <div class="form-group">
            {!! Form::label('tis_standard', 'มาตรฐาน ', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-6">
                {!! Form::select('tis_standard', HP::TisListSample(),null, ['class' => 'form-control list_select', 'required' => 'required', 'autocomplete' => "off", 'placeholder' =>'-เลือกมาตรฐาน-'] )!!}
                {!! $errors->first('tis_standard', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10 required">
        <div class="form-group">
            {!! Form::label('tradeName', 'ชื่อผู้รับใบอนุญาต ', ['class' => 'col-md-2 control-label']) !!}
             <div class="col-md-6">
                {!! Form::select('tradeName', [],null, ['class' => 'form-control list_select', 'id' => 'filter_tb4_License','required' => 'required', 'autocomplete' => "off", 'placeholder' =>'-เลือกผู้รับใบอนุญาต-'] )!!}
                {!! $errors->first('tradeName', '<p class="help-block">:message</p>') !!}
            </div>
       </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group">
            {!! Form::label('owner', 'ชื่อเจ้าของ/ผู้แทน', ['class' => 'col-md-2 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('owner', null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('address_no', !empty($data->address_no)?$data->address_no:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_village_no', 'หมู่ที่', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('address_village_no', !empty($data->address_village_no)?$data->address_village_no:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_alley', 'ตรอก/ซอย', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('address_alley', !empty($data->address_alley)?$data->address_alley:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    @php
        $AmphurArr   = !empty($data->address_province)?App\Models\Basic\Amphur::whereNull('state')->where('PROVINCE_ID',@$data->address_province)->orderbyRaw('CONVERT(AMPHUR_NAME USING tis620)')->pluck('AMPHUR_NAME', 'AMPHUR_ID'):[];
        $DistrictArr = !empty($data->address_amphoe)?App\Models\Basic\District::whereNull('state')->where('AMPHUR_ID',@$data->address_amphoe)->orderbyRaw('CONVERT(DISTRICT_NAME USING tis620)')->pluck('DISTRICT_NAME', 'DISTRICT_ID'):[];
    @endphp
    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('address_road', 'ถนน', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('address_road', !empty($data->address_road)?$data->address_road:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_province', 'จังหวัด', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('address_province',  HP::get_address_province(), !empty($data->address_province)?$data->address_province:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกจังหวัด-' ]) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_amphoe', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('address_amphoe', $AmphurArr, !empty($data->address_amphoe)?$data->address_amphoe:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกอำเภอ/เขต-' ]) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('address_district', 'ตำบล/แขวง', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::select('address_district', $DistrictArr, !empty($data->address_district)?$data->address_district:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกตำบล/แขวง-' ]) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_zip_code', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('address_zip_code', !empty($data->address_zip_code)?$data->address_zip_code:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('address_phone', 'เบอร์โทร', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('address_phone', !empty($data->address_phone)?$data->address_phone:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    
    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="d-flex justify-content-between">
                <div>
                    <label>รายการยึด</label>
                </div>
                <div>
                    <button class="btn btn-success btn-sm waves-effect waves-light"  name="add_data_seize" id="add_data_seize" onClick="return false;">
                        <span class="btn-label"><i  class="fa fa-plus"></i></span><b>เพิ่ม</b>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="table-responsive text-center">
                <table class="table table-bordered" id="myTable">
                    <thead>
                        <tr bgcolor="#DEEBF7">
                            <th style="width: 1%;">#</th>
                            <th style="width: 15%;">รายการ</th>
                            <th style="width: 5%;">จำนวน</th>
                            <th style="width: 5%;">หน่วย</th>
                            <th style="width: 8%;">มูลค่า</th>
                            <th style="width: 2%;" class="data_hide">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_seizure as $key => $list1)
                            <tr class="sub_input">
                                <td>
                                    <input type="hidden" name="num_row1[]"/><span class="running-no">{{ $loop->iteration}}</span>.
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%;"  class="form-control" name="list_seizure[]"  value="{{$list1->list_seizure}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text"  style="width: 80%; text-align: right;" class="form-control" name="amount_seizure[]" OnKeyPress="return chkNumber(this)" value="{{$list1->amount_seizure}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%;" class="form-control" name="unit_seizure[]" value="{{$list1->unit_seizure}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%; text-align: right;" class="form-control pages" name="value_seizure[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)" oninput="totalPgs()" value="{{$list1->value_seizure}}">
                                </td>
                                <td class="data_hide">
                                    <a class="btn btn-small btn-danger btn-sm remove-data_seize" onclick="remove_row2({{ $loop->iteration}})"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="form-group text-center">
                <label class="m-r-5">รวมรายการยึดทั้งหมด จำนวน</label>
                <span id="sum_row22" style="width:5%;text-decoration: underline dotted;">{{$data->total_list_seizure}}</span>
                <input name="total_list_seizure"  id="total_list_seizure" value="{{$data->total_list_seizure}}" hidden>
                <span id="sum_row2"></span>
                <label class="m-r-20">รายการ</label>
                <label class="m-r-5">รวมมูลค่า</label>
                <input style="width: 15%; text-decoration: underline dotted; text-align: right;" class="m-r-5 input-custom text-center" id="total_value_seizure" value="{{$data->total_value_seizure}}" disabled>
                <input name="total_value_seizure" id="total_value_seizure2" value="{{$data->total_value_seizure}}" hidden>
                <label class="m-l-5">บาท</label>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="d-flex justify-content-between">
                <div>
                    <label>รายการอายัด</label>
                </div>
                <div>
                    <button class="btn btn-success btn-sm waves-effect waves-light"  name="add_data_freeze" id="add_data_freeze" onClick="return false;">
                        <span class="btn-label"><i  class="fa fa-plus"></i></span><b>เพิ่ม</b>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="table-responsive">
                <table class="table table-bordered" id="myTable2">
                    <thead>
                        <tr bgcolor="#DEEBF7">
                            <th style="width: 1%;">#</th>
                            <th style="width: 15%;">รายการ</th>
                            <th style="width: 5%;">จำนวน</th>
                            <th style="width: 5%;">หน่วย</th>
                            <th style="width: 8%;">มูลค่า</th>
                            <th style="width: 2%;" class="data_hide">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data_freeze as $list2)
                            <tr class="sub_input2">
                                <td>
                                    <input type="hidden"  name="num_row2[]"/><span class="running-no2">{{ $loop->iteration}}</span>.
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text"  style="width: 80%;" class="form-control" name="list_freeze[]" value="{{$list2->list_freeze}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%; text-align: right;" class="form-control" name="amount_freeze[]" OnKeyPress="return chkNumber(this)" value="{{$list2->amount_freeze}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%;" class="form-control" name="unit_freeze[]" value="{{$list2->unit_freeze}}">
                                </td>
                                <td style="text-align: -webkit-center;">
                                    <input type="text" style="width: 80%; text-align: right;"  class="form-control pages2"  name="value_freeze[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)"oninput="totalPgs2()" value="{{$list2->value_freeze}}">
                                </td>
                                <td class="data_hide">
                                    <a class="btn btn-small btn-danger btn-sm remove-data_freeze" onclick="remove_row({{ $loop->iteration}})"><span class="fa fa-trash"></span></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="col-md-offset-1 col-md-10">
            <div class="form-group text-center">
                <label class="m-r-5">รวมรายการอายัดทั้งหมด  จำนวน</label>
                <span id="sum_row11"  style="width:5%;text-decoration: underline dotted;">{{$data->total_list_freeze}}</span>
                <input name="total_list_freeze"  id="total_list_freeze"  value="{{$data->total_list_freeze}}"  hidden>
                <span id="sum_row"></span>
                <label class="m-r-20">รายการ</label>
                <label class="m-r-5">รวมมูลค่า</label>
                <input style="width: 15%;text-decoration: underline dotted; text-align: right; " class="m-r-5 input-custom text-center"  id="total_value_freeze" value="{{$data->total_value_freeze}}" disabled>
                <input name="total_value_freeze" id="total_value_freeze2" value="{{$data->total_value_freeze}}" hidden>
                <label class="m-l-5">บาท</label>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group">
            <label class="col-md-offset-1">สถานที่เก็บผลิตภัณฑ์ที่ยึด/อายัด</label>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_no', 'ตั้งอยู่เลขที่', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('keep_product_address_no', !empty($data->keep_product_address_no)?$data->keep_product_address_no:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_village_no', 'หมู่ที่', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('keep_product_address_village_no', !empty($data->keep_product_address_village_no)?$data->keep_product_address_village_no:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_alley', 'ตรอก/ซอย', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('keep_product_address_alley', !empty($data->keep_product_address_alley)?$data->keep_product_address_alley:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
    @php
        $KeepAmphurArr   = !empty($data->keep_product_address_province)?App\Models\Basic\Amphur::whereNull('state')->where('PROVINCE_ID',@$data->keep_product_address_province)->orderbyRaw('CONVERT(AMPHUR_NAME USING tis620)')->pluck('AMPHUR_NAME', 'AMPHUR_ID'):[];
        $KeepDistrictArr = !empty($data->keep_product_address_amphoe)?App\Models\Basic\District::whereNull('state')->where('AMPHUR_ID',@$data->keep_product_address_amphoe)->orderbyRaw('CONVERT(DISTRICT_NAME USING tis620)')->pluck('DISTRICT_NAME', 'DISTRICT_ID'):[];
    @endphp
    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_road', 'ถนน', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('keep_product_address_road', !empty($data->keep_product_address_road)?$data->keep_product_address_road:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_province', 'จังหวัด', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('keep_product_address_province',  HP::get_address_province(), !empty($data->keep_product_address_province)?$data->keep_product_address_province:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกจังหวัด-' ]) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_amphoe', 'อำเภอ/เขต', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::select('keep_product_address_amphoe', $KeepAmphurArr, !empty($data->keep_product_address_amphoe)?$data->keep_product_address_amphoe:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกอำเภอ/เขต-' ]) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_district', 'ตำบล/แขวง', ['class' => 'col-md-6 control-label']) !!}
            <div class="col-md-6">
                {!! Form::select('keep_product_address_district', $KeepDistrictArr, !empty($data->keep_product_address_district)?$data->keep_product_address_district:null, ['class' => 'form-control', 'autocomplete' => "off",  'placeholder' =>'-เลือกตำบล/แขวง-' ]) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_zip_code', 'รหัสไปรษณีย์', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('keep_product_address_zip_code', !empty($data->keep_product_address_zip_code)?$data->keep_product_address_zip_code:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-group col-md-4">
            {!! Form::label('keep_product_address_phone', 'เบอร์โทร', ['class' => 'col-md-4 control-label']) !!}
            <div class="col-md-8">
                {!! Form::text('keep_product_address_phone', !empty($data->keep_product_address_phone)?$data->keep_product_address_phone:null, ('' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div class="form-group col-md-4">
            <label class="col-md-6 control-label">ไฟล์แนบ :</label>
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-success" id="attach-add">
                    <i class="icon-plus"></i>&nbsp;เพิ่ม
                </button>
            </div>
        </div>
    </div>

    <div class="row m-b-10">
        <div id="other_attach-box">
            @foreach ($attachs as $key => $attach)
                <div class="form-group other_attach_item">
                    <div class="col-md-offset-2 col-md-4 text-right">
                        @if( $attach->file_note!='' )
                            <div class="files_notes_upload">
                                {{$attach->file_note }} : 
                            </div>
                            <div class="file_notes_upload"></div>
                        @else 
                            <div class="files_notes_upload">
                                {!! Form::text('attach_notes[]', null, ['class' => 'form-control', 'placeholder' => 'คำอธิบายไฟล์แนบ(ถ้ามี)']) !!}
                            </div>
                            <div class="file_notes_upload"></div>
                        @endif
                    </div>
                    <div class="col-md-4">
                        {!! Form::hidden('attach_filenames[]', $attach->file_name); !!}
                        @if($attach->file_name!='')
                            <div class="files_attachs_upload">
                                <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach" title=" {{ !empty($attach->file_client_name)?$attach->file_client_name:'' }}">
                                    {{ !empty($attach->file_client_name)?$attach->file_client_name:'' }}
                                </a>
                            </div>
                           <div class="file_attachs_upload"></div>
                        @else 
                            <div class="files_attachs_upload">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                        <span class="fileinput-new">เลือกไฟล์</span>
                                        <span class="fileinput-exists">เปลี่ยน</span>
                                        <input type="file" name="attachs[]" class="check_max_size_file">
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                    {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                            <div class="file_attachs_upload"></div>
                        @endif
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-danger btn-sm attach-remove" type="button"><i class="icon-close"></i></button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if( !empty($data->check_officer) )
        <div class="row m-b-10">
            <div class="form-group col-md-4">
                <label class="col-md-6 control-label">ผู้ตรวจประเมิน :</label>
                {!! Form::label('check_officer', 'ผู้ตรวจประเมิน :', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('check_officer', (!empty($data->check_officer)? $data->check_officer  : auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname), [ 'class' => 'form-control', 'disabled' => true]) !!}
                </div>
            </div>
        </div>
        <div class="row m-b-10">
            <div class="form-group col-md-4">
                <label class="col-md-6 control-label">วันที่ตรวจประเมิน :</label>
                {!! Form::label('date_now', 'วันที่ตรวจประเมิน :', ['class' => 'col-md-6 control-label']) !!}
                <div class="col-md-6">
                    {!! Form::text('date_now', !empty($data->created_at)?  HP::DateThai($data->created_at) : null, [ 'class' => 'form-control', 'disabled' => true]) !!}
                </div>
            </div>
        </div>
    @endif
</div>

@if( !empty($data->id) )
    <div class="white-box">
        <div class="row m-b-10">
            <div class="col-md-offset-1 col-md-11">
                <legend> การถอนยึด/อายัด</legend>
            </div>
        </div>

        <div class="row m-b-10">
            <div class="form-group">
                <div class="col-sm-8" style="margin-left: 8%;" align="right">
                    <div class="col-sm-1">
                        <input class="check child_checkbox" name="check_status" value="1" type="checkbox" data-checkbox="icheckbox_square-green" id="check_checkbox"    <?php echo ($data->status == 'ถอนยึด/อายัด') ? 'checked' : '' ?> >
                    </div>
                    <div class="col-sm-2" align="right"> ถอนยึด/อายัด</div>
                    <div class="col-sm-3">
                        <input type="text" name="date_freeze" id="datepicker-time-freeze"  class="col-sm-3 form-control child_checkbox mydatepicker" disabled value="{{   !empty($data->date_freeze)? HP::revertDate($data->date_freeze)  : HP::revertDate(date('Y-m-d'))  }}" >
                    </div>
                    <div class="col-sm-1" align="right"> โดย</div>
                    <div class="col-sm-5">
                        <input type="text" name="officer_freeze" id="not_premise" class="col-sm-5 form-control child_checkbox" value="{{ !empty($data->officer_freeze)? $data->officer_freeze  : auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname  }}  "disabled>
                    </div>
                    <input value="{{!empty($data->officer_freeze)? $data->officer_freeze  : auth()->user()->reg_fname . ' ' . auth()->user()->reg_lname }}" name="premise" hidden>
                </div>
            </div>
        </div>

        <div id="status_btn"></div>

        <div class="form-group text-center">
            <div class="col-sm-12" style="margin-bottom: 20px"></div>
            <button class="btn btn-info btn-sm waves-effect waves-light" style="font-size: 14px;" type="submit">บันทึก</button>
            <a class="btn btn-default btn-sm waves-effect waves-light"  style="font-size: 14px;" href="{{ url('/csurv/control_freeze') }}">
                <i class="fa fa-undo"></i><b> ยกเลิก</b>
            </a>
        </div>

    </div>
@else
    <div class="row m-b-10">
        <div class="form-group text-center">
            <button class="btn btn-info btn-sm waves-effect waves-light m-r-30"type="submit" >
                <b>บันทึก</b>
            </button>
            <a class="btn btn-default btn-sm waves-effect waves-light" href="{{ url('/csurv/control_freeze') }}">
                <b>ยกเลิก</b>
            </a>
        </div>
    </div>
@endif

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">


             $('.mydatepicker').datepicker({
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd/mm/yyyy'
                });


        function add_status_btn(status) {
            $('#status_btn').html('<input type="text" name="status" value="' + status + '" hidden>');
        }


        var temp_row2 = $('.sub_input').length + 1;

        function add_input_seize() {
            $('#sum_row22').remove()
            var next_num = $('.sub_input').length + 1;
            var html_add_item = '<tr class="sub_input">';
            $('#sum_row2').html('<label id="sum_row_val2" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + temp_row2 + '</label><input id="sum_row_val2" type="text" name="total_list_seizure"  value="' + temp_row2 + '" hidden>');

            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row1[]"/><span class="running-no">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="list_seizure[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control" name="amount_seizure[]" OnKeyPress="return chkNumber(this)" value="0"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="unit_seizure[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control pages" name="value_seizure[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)" oninput="totalPgs()" value="0"></td>';
            html_add_item += '<td>' +
                '<a class="btn btn-small btn-danger btn-sm remove-data_seize" onclick="remove_row2(' + temp_row2 + ')"><span class="fa fa-trash"></span></a>' +
                '</td>';
            html_add_item += '</tr>';
            $('#myTable tbody').append(html_add_item);
            temp_row2++;
        }

        function remove_row2(row) {
            $('#sum_row22').remove()
            temp_row2--;
            $('#sum_row_val2').val(temp_row2 - 1)
            var num = temp_row2 - 1;
            $('#sum_row2').html('<label id="sum_row_val2" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + num + '</label><input id="sum_row_val2" type="text" name="total_list_seizure"  value="' + num + '" hidden>');
        }

        $('#add_data_seize').click(function () {
            add_input_seize();
        });

        function chkNumber(ele) {
            var vchar = String.fromCharCode(event.keyCode);
            if ((vchar < '0' || vchar > '9') && (vchar != '.')) return false;
            ele.onKeyPress = vchar;
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + ',' + '$2');
            }
            return x1 + x2;
        }

        function chkNum(ele) {
            var num = parseFloat(ele.value.replace(/,/g, ''));
            ele.value = addCommas(num);
        }

        function totalPgs() {
            var out = document.getElementById('total_value_seizure');
            var pgs = document.querySelectorAll('.pages');
            var arr = Array.prototype.map.call(pgs, function (pg) {
                var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                return cnt;
            });

            var total = sum.apply(sum, arr);
            if (total != "NaN") {
                out.value = addCommas(total);
                document.getElementById('total_value_seizure2').value = addCommas(total);
                return total;
            }
        }

        function sum() {
            var res = 0;
            var i = 0;
            var qty = arguments.length;
            while (i < qty) {
                res += arguments[i];
                i++;
            }
            return res;
        }

        $(document).on('click', '.remove-data_seize', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(100);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input').each(function (index, el) {
                    $(el).find('.running-no').text(index + 1);
                });
                var pgs = document.querySelectorAll('.pages');
                var out = document.getElementById('total_value_seizure');
                var arr = Array.prototype.map.call(pgs, function (pg) {
                    var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                    return cnt;
                });
                var total = sum.apply(sum, arr);
                out.value = addCommas(total);
                document.getElementById('total_value_seizure2').value = addCommas(total);
                return total;

            }, 500);

        });

        var temp_row = $('.sub_input2').length + 1;

        function add_input_freeze() {
            $('#sum_row11').remove()

            var next_num = $('.sub_input2').length + 1;
            var html_add_item = '<tr class="sub_input2">';
            $('#sum_row').html('<label id="sum_row_val" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + temp_row + '</label><input id="sum_row_val" type="text" name="total_list_freeze"  value="' + temp_row + '" hidden>');

            html_add_item += '<td><input type="hidden" value="' + next_num + '" name="num_row2[]"/><span class="running-no2">' + next_num + '</span>.</td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="list_freeze[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control" name="amount_freeze[]" OnKeyPress="return chkNumber(this)" value="0"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%;" class="form-control" name="unit_freeze[]"></td>';
            html_add_item += '<td style="text-align: -webkit-center;"><input type="text" style="width: 80%; text-align: right;" class="form-control pages2" name="value_freeze[]" OnKeyPress="return chkNumber(this)" OnChange="chkNum(this)" oninput="totalPgs2()" value="0"></td>';
            html_add_item += '<td>' +
                '<a class="btn btn-small btn-danger btn-sm remove-data_freeze" onclick="remove_row(' + temp_row + ')"><span class="fa fa-trash"></span></a>' +
                '</td>';
            html_add_item += '</tr>';
            $('#myTable2 tbody').append(html_add_item);
            temp_row++;
        }

        function remove_row(row) {
            $('#sum_row11').remove()
            var tem_num = $('.sub_input2').length - 1
            temp_row--;
            $('#sum_row_val').val(temp_row - 1)
            var num = temp_row - 1;
            $('#sum_row').html('<label id="sum_row_val" type="text" name="sum" style="width: 5%; text-align: center;text-decoration: underline dotted;">' + tem_num + '</label><input id="sum_row_val" type="text" name="total_list_freeze"  value="' + tem_num + '" hidden>');
        }

        $('#add_data_freeze').click(function () {
            add_input_freeze();
        });

        function totalPgs2() {
            var out = document.getElementById('total_value_freeze');
            var pgs = document.querySelectorAll('.pages2');
            var arr = Array.prototype.map.call(pgs, function (pg) {
                var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                return cnt;
            });
            var total = sum2.apply(sum2, arr);
            if (total != "NaN") {
                out.value = addCommas(total);
                document.getElementById('total_value_freeze2').value = addCommas(total);
                return total;
            }
        }

        function sum2() {
            var res = 0;
            var i = 0;
            var qty = arguments.length;
            while (i < qty) {
                res += arguments[i];
                i++;
            }
            return res;
        }

        $(document).on('click', '.remove-data_freeze', function () {
            var row_remove = $(this).parent().parent();
            row_remove.fadeOut(300);
            setTimeout(function () {
                row_remove.remove();
                $('.sub_input2').each(function (index, el) {
                    $(el).find('.running-no2').text(index + 1);
                });
                var pgs = document.querySelectorAll('.pages2');
                var out = document.getElementById('total_value_freeze');
                var arr = Array.prototype.map.call(pgs, function (pg) {
                    var cnt = parseInt(pg.value.replace(/,/g, ''), 10);
                    return cnt;
                });
                var total = sum.apply(sum, arr);
                out.value = addCommas(total);
                document.getElementById('total_value_freeze2').value = addCommas(total);
                return total;

            }, 500);
        });


        jQuery(document).ready(function() {
        $('#tis_standard').change(function(){
            $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                if($(this).val()!=""){
                     var tradeName = '<?php  echo !empty($data->tradeName) ? $data->tradeName:null ?>';
                    $.ajax({
                        url: "{!! url('ssurv/save_example/get_filter_tb4_License/list') !!}/" + $(this).val()
                    }).done(function( object ) {
                        console.log(object);
                        
                        $.each(object, function( index, data ) {
                            var selected = (index == tradeName)?'selected="selected"':'';
                             $('#filter_tb4_License').append('<option value="'+index+'" '+ selected +'>'+data+'</option>');
                         });
                         $('#filter_tb4_License').select2();
                    });
                  
                }else{
                    $('#filter_tb4_License').html('<option>-เลือกผู้รับใบอนุญาต-</option>').select2();
                }
            });
            $('#tis_standard').change();


                //เพิ่มไฟล์แนบ
                $('#attach-add').click(function(event) {
                // $('.other_attach_item:first').clone().appendTo('#other_attach-box');
                $('.other_attach_item:first').clone().insertAfter(".other_attach_item:last");

                var row = $(".other_attach_item:last");
                    row.find('.files_notes_upload').html('');       
                    row.find('.file_notes_upload').html('<input type="text" name="attach_notes[]" class="form-control" placeholder="คำอธิบายไฟล์แนบ(ถ้ามี)">');
                    row.find('.files_attachs_upload').html('');
                var html = [];
      
                            html  +=  '<div class="fileinput attachment fileinput-new input-group" data-provides="fileinput">';
                            html  +=   '<div class="form-control" data-trigger="fileinput">';
                            html  +=      '<i class="glyphicon glyphicon-file fileinput-exists"></i>';
                            html  +=     '<span class="fileinput-filename"></span>';
                            html  +=     '</div>';
                            html  +=     '<span class="input-group-addon btn btn-default btn-file">';
                            html  +=    '<span class="fileinput-new">เลือกไฟล์</span>';
                            html  +=    '<span class="fileinput-exists">เปลี่ยน</span>';
                            html  +=    '<input type="file" name="attachs[]" class="check_max_size_file" >';                                        
                            html  +=    '</span>';
                            html  +=    ' <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>';
                            html  +=     '</div>';   
                    row.find('.file_attachs_upload').append(html);
                    check_max_size_file();
                // $('.other_attach_item:last').find('input').val('');
                // $('.other_attach_item:last').find('a.fileinput-exists').click();
                // $('.other_attach_item:last').find('a.view-attach').remove();
                // $('.other_attach_item:last').find('.view-filename').text('');

                ShowHideRemoveBtn();

                });

                //ลบไฟล์แนบ
                $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().remove();
                ShowHideRemoveBtn();
                });

                ShowHideRemoveBtn();

                function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ
                        if ($('.other_attach_item').length > 1) {
                        $('.attach-remove').show();
                        } else {
                        $('.attach-remove').hide();
                        }

                }

                        
                $('#address_province').change(function(){
                $('#address_amphoe').html('<option value=""> -เลือกอำเภอ/เขต- </option>').select2();
                $('#address_district').html('<option value=""> -เลือกตำบล/แขวง- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('csurv/control_freeze/add_filter_address_province') !!}" + "/" + $(this).val()
                    }).done(function( object ) {   
                        $.each(object, function( index, data ) {
                            $('#address_amphoe').append('<option value="'+index+'">'+data+'</option>');
                        });

                    });
                }
                });
                $('#address_amphoe').change(function(){
                $('#address_district').html('<option value=""> -เลือกตำบล/แขวง- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('csurv/control_freeze/add_filter_address_district') !!}" + "/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#address_district').append('<option value="'+index+'">'+data+'</option>');
                        });

                    });
                }
                });

                $('#keep_product_address_province').change(function(){
                $('#keep_product_address_amphoe').html('<option value=""> -เลือกอำเภอ/เขต- </option>').select2();
                $('#keep_product_address_district').html('<option value=""> -เลือกตำบล/แขวง- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('csurv/control_freeze/add_filter_address_province') !!}" + "/" + $(this).val()
                    }).done(function( object ) {   
                        $.each(object, function( index, data ) {
                            $('#keep_product_address_amphoe').append('<option value="'+index+'">'+data+'</option>');
                        });

                    });
                }
                });
                $('#keep_product_address_amphoe').change(function(){
                $('#keep_product_address_district').html('<option value=""> -เลือกตำบล/แขวง- </option>').select2();
                if($(this).val()!=""){
                    $.ajax({
                        url: "{!! url('csurv/control_freeze/add_filter_address_district') !!}" + "/" + $(this).val()
                    }).done(function( object ) {
                        $.each(object, function( index, data ) {
                            $('#keep_product_address_district').append('<option value="'+index+'">'+data+'</option>');
                        });

                    });
                }
                });
            
                      // เลือกทั้งหมด checkbox
  

                    $("#check_checkbox").on("ifChanged", function(){
                        if($(this).is(':checked')) {
                            $('#datepicker-time-freeze').prop('disabled', false);
                            $('#not_premise').prop('disabled', false);
                            $('.child_checkbox').iCheck('update');
                        }
                        else{
                            $('#datepicker-time-freeze').prop('disabled', true);
                            $('#not_premise').prop('disabled', true);
                            $('.child_checkbox').iCheck('update');

                   
                         
                        }
                    
                });
      
        });
   
    </script>
@endpush
