@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/croppie.css') }}">
    <style type="text/css">
        .img{
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มคณะกรรมการเฉพาะด้าน</h3>
{{--                    @can('view-'.str_slug('board'))--}}
{{--                        <a class="btn btn-success pull-right" href="{{url('/certify/committee')}}">--}}
{{--                            <i class="icon-arrow-left-circle"></i> กลับ--}}
{{--                        </a>--}}
                    {!! Form::open(['method' => 'POST','route' => 'committee.store', 'class' => 'form-horizontal', 'files' => true]) !!}
                
                    {{--@endcan--}}
                    <div class="clearfix"></div>
                    <hr>
                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
 
                    <div class="form-group required{{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'เรื่อง', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('title', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group required{{ $errors->has('faculty') ? 'has-error' : ''}}">
                        {!! Form::label('faculty', 'ชื่อคณะกรรมการ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('faculty', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('faculty', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('faculty_no') ? 'has-error' : ''}}">
                        {!! Form::label('faculty_no', 'คณะที่', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('faculty_no', null,  ['class' => 'form-control']) !!}
                            {!! $errors->first('faculty_no', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('product_group_id') ? 'has-error' : ''}}">
                        {!! Form::label('product_group_id', 'กลุ่มผลิตภัณฑ์/สาขา', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::select('product_group_id', 
                            App\Models\Basic\ProductGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                            null,
                             ['class' => 'form-control',
                             'id'=>'product_group_id',
                              'placeholder'=>'- เลือกกลุ่มผลิตภัณฑ์/สาขา -',
                              'required' => false]) !!}
                            {!! $errors->first('product_group_id', '<p class="help-block">:product_group_id</p>') !!}
                        </div>
                    </div>
                    
                    <div class="form-group required{{ $errors->has('appoint_number') ? 'has-error' : ''}}">
                        {!! Form::label('appoint_number', 'เลขที่คำสั่งแต่งตั้ง', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('appoint_number', null, ('required' == 'required') ? ['class' => 'form-control', 'required' => 'required'] : ['class' => 'form-control']) !!}
                            {!! $errors->first('appoint_number', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group required{{ $errors->has('birth_date') ? 'has-error' : ''}}">
                        {!! Form::label('appoint_date', 'วันที่มีคำสั่ง', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('appoint_date', null, ['class' => 'form-control mydatepicker', 'required' => 'required']) !!}
                            {!! $errors->first('appoint_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group  {{ $errors->has('note') ? 'has-error' : ''}}">
                        {!! Form::label('message', 'หมายเหตุ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::textarea('message', null,   ['class' => 'form-control', 'rows'=>'4']) !!}
                            {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group required{{ $errors->has('expert_group_id') ? 'has-error' : ''}}">
                        {!! Form::label('expert_group_id', 'หมวดหมู่คณะกรรมการ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::select('expert_group_id',
                             App\Models\Basic\ExpertGroup::orderbyRaw('CONVERT(title USING tis620)')->pluck('title','id'),
                            null, 
                            ['class' => 'form-control',
                            'id'=>'expert_group_id', 
                            'placeholder'=>'- เลือกหมวดหมู่คณะกรรมการ -',
                            'required' => 'required']) !!}
                            {!! $errors->first('expert_group_id', '<p class="help-block">:expert_group_id</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('authorize_file', 'หนังสือแต่งตั้งคณะกรรมการ (ไฟล์แนบ):', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                <div class="form-control" data-trigger="fileinput">
                                    <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                    <span class="fileinput-filename"></span>
                                </div>
                                <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('authorize_file', null) !!}
                                    </span>
                                <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                            </div>
                            {!! $errors->first('authorize_file', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('attach', 'ไฟล์แนบอื่นๆ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            <button type="button" class="btn btn-sm btn-success" id="attach-add">
                                <i class="icon-plus"></i>&nbsp;เพิ่ม
                            </button>
                        </div>
                    </div>

                    <div id="other_attach-box">
                        <div class="form-group other_attach_item">
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                {!! Form::text('attach_filenames[]', null, ['class' => 'form-control', 'placeholder' => 'ชื่อไฟล์']) !!}
                            </div>
                            <div class="col-md-4">
                                <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                    <div class="form-control" data-trigger="fileinput">
                                        <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                        <span class="fileinput-filename"></span>
                                    </div>
                                    <span class="input-group-addon btn btn-default btn-file">
                                    <span class="fileinput-new">เลือกไฟล์</span>
                                    <span class="fileinput-exists">เปลี่ยน</span>
                                        {!! Form::file('attachs[]', null) !!}
                                    </span>
                                    <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">ลบ</a>
                                </div>
                                {!! $errors->first('attachs', '<p class="help-block">:message</p>') !!}

                            </div>

                            <div class="col-md-2 text-left" style="margin-top: 3px">
                                <button class="btn btn-danger btn-sm attach-remove" type="button">
                                    <i class="icon-close"></i>
                                </button>
                            </div>

                        </div>
                    </div>

                        <div class="form-row form-horizontal" >
                            <div class="white-box" style="border: solid 1px">
                                <h3 id="headDepartment">รายชื่อคณะกรรมการ/บุคคลที่เกี่ยวข้อง</h3>
                                <hr>
                                <div class="form-group required {{ $errors->has('expert_id') ? 'has-error' : ''}}">
                                    {!! Form::label('expert_id', 'รายชื่อคณะกรรมการ/บุคคลที่เกี่ยวข้อง', ['class' => 'col-md-4 control-label']) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('expert_id',App\Models\Certify\RegisterExpert::pluck('head_name', 'id'),null,['class' => 'form-control expert_id','id'=>'expert_id', 'placeholder'=>'- เลือกรายชื่อ -'])  !!}
                                        {!! $errors->first('expert_id', '<p class="help-block">:expert_group_id</p>') !!}
                                    </div>
                                    <div class="col-xs-12 col-md-2">
                                        <button type="button" id="btn-add" class="btn btn-info pull-right btn-block"><i class="icon-plus"></i> เลือกรายชื่อ</button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table_committee_lists">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white text-center">#</th>
                                            <th class="text-white text-center">ชื่อคณะกรรมการ/บุคคลที่เกี่ยวข้อง</th>
                                            <th class="text-white text-center">หน่วยงาน</th>
                                            <th class="text-white text-center">คุณสมบัติคณะกรรมการ</th>
                                            <th class="text-white text-center">ตำแหน่ง</th>
                                            <th class="text-white text-center">ลบ</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody-table_committee_lists">
                                        </tbody>
                                    </table>
                                </div>
                    
                                <div class="clearfix"></div>
                            </div>
                        </div>

                    <input type="hidden" id="departmentInputDetail" name="departmentInputDetail">

                    <div class="form-group">
                        <div class="col-md-offset-5 col-md-5">
                    
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-paper-plane"></i> บันทึก
                            </button>
                            <a class="btn btn-default" href="{{route('committee.index')}}">
                                <i class="fa fa-rotate-left"></i> ยกเลิก
                            </a>
                            
                        </div>
                    </div>
                    {!! Form::close() !!}

          


 

                    {{-- <hr style="">
                    <div class="text-right">
                        <button class="btn btn-primary m-b-15" type="button" onclick="callDepartment();">
                            <i class="icon-plus"></i> เพิ่มหน่วยงาน
                        </button>
                    </div>

                    <div class="form-row form-horizontal" id="department_div" style="display: none">
                        <div class="white-box" style="border: solid 1px">
                            <h3 id="headDepartment">เพิ่มหน่วยงาน</h3>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-12 col-md-10">
                                        <label for="department" class="control-label">หน่วยงาน:</label>
                                        <select name="department" id="department" class="form-control">
                                            <option value="" selected>-เลือกหน่วยงาน-</option>
                                            @foreach ($departments as $department)
                                                <option value="{{$department->id}}">{{$department->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xs-12 col-md-2" style="margin-top: 27px">
                                        <button type="button" id="managerBTN" class="btn btn-info pull-right btn-block" disabled><i class="icon-plus"></i> เพิ่มกรรมการ</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" id="department_detail" style="display: none">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="peopleName_departure" class="col-md-2 text-right text-nowrap">ชื่อ-สกุล:</label>
                                            <div class="col-md-10">
                                                <input id="peopleName_departure" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group main_Back" style="display: none">
                                            <label for="legate" class="col-md-2 text-right text-nowrap">กลุ่มผู้แทน:</label>
                                            <div class="col-md-10">
                                                <select name="legate" id="legate" class="form-control">
                                                    <option value="" selected>-เลือกกลุ่มผู้แทน-</option>
                                                    <option value="นักวิชาการ/ผู้เชี่ยวชาญ">นักวิชาการ/ผู้เชี่ยวชาญ</option>
                                                    <option value="หน่วยตรวจสอบ/รับรอง">หน่วยตรวจสอบ/รับรอง</option>
                                                    <option value="ผู้ประกอบการ">ผู้ประกอบการ</option>
                                                    <option value="ผู้บริโภค">ผู้บริโภค</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group main_Back professional secretary" style="display: none">
                                            <label for="address" class="col-md-2 text-right text-nowrap">ที่อยู่:</label>
                                            <div class="col-md-10">
                                                <textarea id="address" type="text" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group main_Back secretary" style="display: none">
                                            <label for="email" class="col-md-2 text-right text-nowrap">email:</label>
                                            <div class="col-md-10">
                                                <input id="email" type="email" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="committee_type" class="col-md-4 text-right">ประเภทคณะกรรมการ:</label>
                                            <div class="col-md-8">
                                                <div class="row">
                                                    <div class="col-md-12" id="committee_type_div">
                                                        <select name="committee_type" id="committee_type" class="form-control">
                                                            <option value="" selected>-เลือกประเภท-</option>
                                                            <option value="ผู้ทรงวุฒิ">ผู้ทรงวุฒิ</option>
                                                            <option value="ผู้แทนหลัก">ผู้แทนหลัก</option>
                                                            <option value="ผู้แทนสำรอง">ผู้แทนสำรอง</option>
                                                            <option value="ฝ่ายเลขานุการ">ฝ่ายเลขานุการ</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-6" id="level_div" style="display: none">
                                                        <select name="level" id="level" class="form-control">
                                                            <option value="" selected>-เลือกลำดับ-</option>
                                                            @for ($i = 1 ; $i <= 10 ;$i++)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group main_Back secretary" style="display: none">
                                            <label for="position" class="col-md-4 text-right">ตำแหน่ง:</label>
                                            <div class="col-md-8">
                                                <input id="position" type="text" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group main_Back secretary" style="display: none">
                                            <label for="telephone" class="col-md-4 text-right">โทรศัพท์:</label>
                                            <div class="col-md-8">
                                                <input id="telephone" type="tel" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group main_Back secretary" style="display: none">
                                            <label for="fax" class="col-md-4 text-right">โทรสาร:</label>
                                            <div class="col-md-8">
                                                <input id="fax" type="text" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group" id="committee_save_div">
                                            <button id="committee_save" class="btn btn-sm btn-success" style="width: 60px">บันทึก</button>
                                        </div>
                                        <div id="committee_edit_div" class="form-group" style="display: none">
                                            <button id="committee_edit" class="btn btn-sm btn-warning text-white" style="width: 60px">แก้ไข</button>
                                        </div>
                                        <div id="department_edit_div" class="form-group" style="display: none">
                                            <button id="department_edit" class="btn btn-sm btn-warning text-white" style="width: 60px">แก้ไข</button>
                                        </div>
                                        <div id="committee_cancle_div" class="form-group">
                                            <button id="committee_cancel" class="btn btn-sm btn-danger" style="width: 60px">ยกเลิก</button>
                                        </div>
                                        <div id="department_cancle_div" class="form-group" style="display: none">
                                            <button id="department_cancel" class="btn btn-sm btn-danger" style="width: 60px">ยกเลิก</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div id="committee_table" style="display: none">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white text-center">ชื่อ-สกุล</th>
                                            <th class="text-white text-center">ประเภทคณะกรรมการ</th>
                                            <th class="text-white text-center">โทรศัพท์</th>
                                            <th class="text-white text-center">อีเมล์</th>
                                            <th class="text-white text-center">เครื่องมือ</th>
                                        </tr>
                                        </thead>
                                        <tbody id="committee_table_tbody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="pull-right" id="div_committee_btn" style="display: none">
                                <button type="button" id="department_addSave" class="btn btn-sm btn-primary">บันทึก</button>
                                <button type="button" id="department_addCancel" class="btn btn-sm btn-danger">ยกเลิก</button>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>

                    <div id="department_table" style="display: none">
                        <div class="white-box" style="border: solid 1px">
                            <h3>รายชื่อคณะกรรมการเฉพาะด้าน</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th class="text-white text-center">หน่วยงาน</th>
                                        <th class="text-white text-center">ชื่อ-สกุลคณะกรรมการ</th>
                                        <th class="text-white text-center">ประเภทคณะกรรมการ</th>
                                        <th class="text-white text-center">โทรศัพท์</th>
                                        <th class="text-white text-center">อีเมล์</th>
                                        <th class="text-center text-white">เครื่องมือ</th>
                                    </tr>
                                    </thead>
                                    <tbody id="department_table_tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>  --}}


                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <!-- input calendar -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

    <!-- Crop Image -->
    <script src="{{ asset('js/croppie.js') }}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>

    <script type="text/javascript">

 
 

        var submitted = false;
        var $uploadCrop;
        var departments = [];
        var committeeInDepartment = [];
        var committeeDepartmentCache = [];
        var editCommitteeCache = null;
        var editDepartment = null;
        var existDepartmentarr = [];
        
        $(document).ready(function() {

            
              //เพิ่มลงตาราง
            $('#btn-add').click(function(event) {

                if($('#expert_id').val()==''){
                    alert('กรุณาเลือก "รายชื่อคณะกรรมการ/บุคคลที่เกี่ยวข้อง"');
                    return false;
                }

                var input_expert    = $('#expert_id');
                var expert_id       = $(input_expert).val();
                var expert_name     = $(input_expert).find('option:selected').text();
                var expert_all      = $('#table_committee_lists').find(".input_expert_id").map(function(){return $(this).val(); }).get();
     
                if( expert_all.indexOf( String(expert_id) ) == -1 ){     
                   
                    var url = '{{ url('/committee/get_position_name') }}/'+expert_id +'/'+ $('#expert_group_id').val();

                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function (data) {

                            var department_name = '';
                            if(checkNone(data.title)){
                                department_name = data.title;
                            }
                            var input_expert_ids        = '';
                                input_expert_ids       += '<input type="hidden" class="input_expert_id" name="expert_id[]" value="' + expert_id + '" />';
                            var input_expert_names      = '';
                                input_expert_names     += '<input type="hidden" class="input_expert_name" name="expert_name[]" value="' + expert_name + '" />';
                            var input_department_names  = '';
                                input_department_names += '<input type="hidden" class="input_department_name" name="department_name[]" value="' + department_name + '" />';

                            var committee_qualified  = '';
                                committee_qualified += '<select name="committee_qualified[]" class="form-control select2"required>';
                                committee_qualified +=          '<option value="">-เลือกประเภทคณะกรรมการ-</option>';
                                committee_qualified +=          '<option value="1">ผู้ทรงคุณวุฒิ</option>';
                                committee_qualified +=          '<option value="2">ผู้แทนหลัก</option>';
                                committee_qualified +=          '<option value="3">ผู้แทนสำรอง</option>';
                                committee_qualified +=          '<option value="4">ฝ่ายเลขานุการ</option>';
                                committee_qualified +=          '<option value="5">เลขานุการ</option>';
                                committee_qualified +=          '<option value="6">รองเลขาธิการ</option>';
                                committee_qualified +=          '<option value="7">ผู้แทนรอง</option>';
                                committee_qualified += '</select>';
                            var committee_position   = '';
                                committee_position  += '<select name="committee_position[]" class="form-control select2 committee_position" required>';
                                    committee_position  +=          '<option value="">-เลือกประธาน/กรรมการ-</option>';
                                    if(data.board_types.length > 0){
                                        $.each(data.board_types, function(index, item) {
                                                  committee_position  +=          '<option value="'+item.id+'">'+item.title+'</option>';
                                         });
                                    }
                                  
                                // committee_position  +=          '<option value="">-เลือกประธาน/กรรมการ-</option>';
                                // committee_position  +=          '<option value="1">ประธาน</option>';
                                // committee_position  +=          '<option value="2">กรรมการ</option>';
                                // committee_position  +=          '<option value="3">กรรมการและเลขานุการ</option>';
                                // committee_position  +=          '<option value="4">กรรมการและผู้ช่วยเลขานุการ</option>';
                                committee_position  += '</select>';
                    
                            var tr  = '<tr>';
                                tr += '    <td class="text-center"></td>';
                                tr += '    <td>' + expert_name  + input_expert_ids + input_expert_names + '</td>';
                                tr += '    <td>' + department_name  + input_department_names + '</td>';
                                tr += '    <td class="text-center">' + committee_qualified +'</td>';
                                tr += '    <td class="text-center">' + committee_position +'</td>';
                                tr += '    <td class="text-center"><button type="button" class="btn btn-icon rounded-circle btn-danger mr-1 mb-1 btn-remove "><i class="fa fa-trash"></i></i></button></td>';
                                tr += '</tr>';

                        $('#tbody-table_committee_lists').append(tr);
                        $('#tbody-table_committee_lists').find('tr:last').find('select').select2();
      
                        resetOrdertable();

                        $(input_expert).val('').select2();
                        $('#expert_id').children('option[value="'+expert_id+'"]:not(:selected):not([value=""])').prop('disabled',true);
                       
                        }
                    });
                         

                }else{
                    alert('"รายชื่อคณะกรรมการ/บุคคลที่เกี่ยวข้อ" ซ้ำ');
                }

            });


            $('#expert_group_id').change(function() {
                if(checkNone($(this).val())){
                    $('.committee_position').children('option[value!=""]').remove();
                    var url = '{{ url('/committee/get_expert_groups') }}/'+ $(this).val() ;
                    $.ajax({
                        type: 'GET',
                        url: url,
                        success: function (data) {
                            if(data.board_types.length > 0){
                                        $.each(data.board_types, function(index, item) {
                                            $('.committee_position').append('<option value="'+item.id+'">'+item.title+'</option>');   
                                         });
                                         $('#tbody-table_committee_lists').find('select').select2();
                               }
                                  
                        }
                    });
                }else{
                    $('.committee_position').children('option[value!=""]').remove();
                }
            }); 

            //ลบออกจากตาราง
            $(document).on('click', '.btn-remove', function(event) {

                if( confirm("ยืนยันการลบข้อมูล") ){
                    var row =  $(this).parent().parent();
                    $('#expert_id').children('option[value="'+$(row).find('.input_expert_id').val()+'"]:not(:selected):not([value=""])').prop('disabled',false);
                    $(this).closest('tr').remove();
                    resetOrdertable();
                    
                }

            });

            $("form").submit(function() {
                submitted = true;
            });

            window.onbeforeunload = function () {
                if (!submitted) {
                    return 'คุณต้องการออกจากหน้านี้ใช่หรือไม่?';
                }
            };

            //ปฎิทิน
            $('.mydatepicker').datepicker({
                autoclose: true,
                todayHighlight: true,
                format: 'dd/mm/yyyy',
                orientation: 'bottom'
            });

            //เพิ่มตำแหน่งงาน
            $('#work-add').click(function() {

                $('#work-box').children(':first').clone().appendTo('#work-box'); //Clone Element

                var last_new = $('#work-box').children(':last');

                //Clear value text
                $(last_new).find('input[type="text"]').val('');

                //Clear value select
                $(last_new).find('select').val('');
                $(last_new).find('select').prev().remove();
                $(last_new).find('select').removeAttr('style');
                $(last_new).find('select').select2();

                //Clear Radio
                $(last_new).find('.check').each(function(index, el) {
                    $(el).prependTo($(el).parent().parent());
                    $(el).removeAttr('style');
                    $(el).parent().find('div').remove();
                    $(el).iCheck();
                    $(el).parent().addClass($(el).attr('data-radio'));
                });

                //Change Button
                $(last_new).find('button').removeClass('btn-success');
                $(last_new).find('button').addClass('btn-danger work-remove');
                $(last_new).find('button').html('<i class="icon-close"></i> ลบ');

                resetOrder();

            });

            //ลบตำแหน่ง
            $('body').on('click', '.work-remove', function() {

                $(this).parent().parent().parent().parent().remove();

                resetOrder();

            });

            //Crop image
            $uploadCrop = $('#upload-demo').croppie({

                enableExif: true,

                viewport: {

                    width: 140,

                    height: 140,

                },

                boundary: {

                    width: 200,

                    height: 200

                }

            });

            $('#upload').on('change', function () {

                $('#upload-demo').removeClass('hide');
                $('#image-show').addClass('hide');

                var reader = new FileReader();

                reader.onload = function (e) {

                    $uploadCrop.croppie('bind', {

                        url: e.target.result

                    }).then(function(){

                        console.log('jQuery bind complete');

                    });

                };

                reader.readAsDataURL(this.files[0]);

            });

            $('#form-save').click(function(event) {

                //เลื่อนมาแถบแรก
                $('.tab-pane').removeClass('active in');
                $('#home1').addClass('active in');

                //คัดลอกข้อมูลภาพที่ Crop
                CropFile();

            });


            //เพิ่มไฟล์แนบ
            $('#attach-add').click(function(event) {
                $('.other_attach_item:first').clone().appendTo('#other_attach-box');

                $('.other_attach_item:last').find('input').val('');
                $('.other_attach_item:last').find('a.fileinput-exists').click();
                $('.other_attach_item:last').find('a.view-attach').remove();

                ShowHideRemoveBtn();

            });

            //ลบไฟล์แนบ
            $('body').on('click', '.attach-remove', function(event) {
                $(this).parent().parent().remove();
                ShowHideRemoveBtn();
            });
            
            $('#department').on('change',function () {
                let select = $(this).find('option:selected').val();
                if ( select !== "" && select !== null){
                    $('#managerBTN').attr('disabled',false);
                }else{
                    $('#managerBTN').attr('disabled',true);
                    $('#department_detail').hide(300);
                }
            });

            $('#managerBTN').on('click',function () {
               $('#department_detail').toggle(300);
            });

            $('#committee_type').on('change',function () {
               let select_type = $(this).find('option:selected').val();
               showCommitteeInput(select_type);
            });

            $('#committee_save').on('click',function () {
                checkCommitteeInput('cache','add');
            });

            $('#committee_cancel').on('click',function () {
                $('#committee_save_div').show(300);
                $('#committee_edit_div').hide(300);
                $('#department_edit_div').hide(300);
                resetCommiteeInput();
                hideAllInput();
            });

            $('#department_addSave').on('click',function () {
                $.each(committeeDepartmentCache, function (n, val) {
                    committeeInDepartment.push(val);
                });
                setToDepartmentTable();
                committeeDepartmentCache = [];
                $('#div_committee_btn').hide(300);
                $('#committee_table').hide(300);
                $('#committee_table_tbody').empty();
                $('#department').val("").change();

            });

            $('#department_addCancel').on('click',function () {
                committeeDepartmentCache = [];
                $('#div_committee_btn').hide(300);
                $('#committee_table').hide(300);
                $('#committee_table_tbody').empty();
                $('#department').val("").change();
            });

            $(document).on('click', '.cacheRemove', function () {
                let this_click = $(this).attr('data-value');
                let find = committeeDepartmentCache.find(value => value.token === this_click);
                let index_find = committeeDepartmentCache.indexOf(find);
                committeeDepartmentCache.splice(index_find,1);

                let index_from_level_Exist = getIndexToRemove(existDepartmentarr,find); // เอาออกจาก array ที่เช็คว่ามีอยู่รึป่าว
                existDepartmentarr.splice(index_from_level_Exist,1);
                setToCommitteeTable();
            });

            $(document).on('click', '.allDepartmentRemove', function () {
                let this_cancel = $(this).attr('data-value');
                let find = committeeInDepartment.find(value => value.token === this_cancel);
                let index_find = committeeInDepartment.indexOf(find);
                committeeInDepartment.splice(index_find,1);

                let index_from_level_Exist = getIndexToRemove(existDepartmentarr,find); // เอาออกจาก array ที่เช็คว่ามีอยู่รึป่าว
                existDepartmentarr.splice(index_from_level_Exist,1);
                setToDepartmentTable();
            });

            $(document).on('click', '.cacheEdit', function () {
                let this_edit = $(this).attr('data-value');
                let find = committeeDepartmentCache.find(value => value.token === this_edit);
                editCommitteeCache = find;
                $('#committee_save_div').hide(300);
                $('#committee_edit_div').show(300);
                resetCommiteeInput();

                $('#peopleName_departure').val(find.name);
                $('#committee_type').val(find.committee_type).change();
                if (find.committee_type === 'ผู้ทรงวุฒิ'){
                    $('#address').val(find.address);
                }

                if (find.committee_type === 'ผู้แทนหลัก' || find.committee_type === 'ผู้แทนสำรอง'){
                    if (find.committee_type === 'ผู้แทนสำรอง'){
                        $('#legate').val(find.legate).change();
                        $('#level').val(find.level).change();
                        $('#address').val(find.address);
                        $('#email').val(find.email);
                        $('#position').val(find.position);
                        $('#telephone').val(find.telephone);
                        $('#fax').val(find.fax);
                    }else{
                        $('#legate').val(find.legate).change();
                        $('#address').val(find.address);
                        $('#email').val(find.email);
                        $('#position').val(find.position);
                        $('#telephone').val(find.telephone);
                        $('#fax').val(find.fax);
                    }
                }

                if (find.committee_type === 'ฝ่ายเลขานุการ'){
                    $('#address').val(find.address);
                    $('#email').val(find.email);
                    $('#position').val(find.position);
                    $('#telephone').val(find.telephone);
                    $('#fax').val(find.fax);
                }

            });

            $('#committee_edit').on('click',function () {
                if (checkCommitteeInput('cache','edit')){
                    $('#committee_edit_div').hide(300);
                    $('#committee_save_div').show(300);
                }
            });

            $(document).on('click', '.allDepartmentEdit', function () {
                let this_edit = $(this).attr('data-value');
                let find = committeeInDepartment.find(value => value.token === this_edit);
                editDepartment = find;
                $('#department_detail').show(300);
                $('#managerBTN').attr('disabled',true);
                $('#committee_save_div').hide(300);
                $('#committee_edit_div').hide(300);
                $('#department_edit_div').show(300);
                $('#committee_cancle_div').hide(300);
                $('#department_cancle_div').show(300);
                resetCommiteeInput();
                $('#headDepartment').text('แก้ไขหน่วยงาน');
                $('#department').val(find.department).change();
                $('#peopleName_departure').val(find.name);
                $('#committee_type').val(find.committee_type).change();
                if (find.committee_type === 'ผู้ทรงวุฒิ'){
                    $('#address').val(find.address);
                }

                if (find.committee_type === 'ผู้แทนหลัก' || find.committee_type === 'ผู้แทนสำรอง'){
                    if (find.committee_type === 'ผู้แทนสำรอง'){
                        $('#legate').val(find.legate).change();
                        $('#level').val(find.level).change();
                        $('#address').val(find.address);
                        $('#email').val(find.email);
                        $('#position').val(find.position);
                        $('#telephone').val(find.telephone);
                        $('#fax').val(find.fax);
                    }else{
                        $('#legate').val(find.legate).change();
                        $('#address').val(find.address);
                        $('#email').val(find.email);
                        $('#position').val(find.position);
                        $('#telephone').val(find.telephone);
                        $('#fax').val(find.fax);
                    }
                }

                if (find.committee_type === 'ฝ่ายเลขานุการ'){
                    $('#address').val(find.address);
                    $('#email').val(find.email);
                    $('#position').val(find.position);
                    $('#telephone').val(find.telephone);
                    $('#fax').val(find.fax);
                }
            });

            $('#department_edit').on('click',function () {
                if (checkCommitteeInput('department','edit')){
                    $('#headDepartment').text('เพิ่มหน่วยงาน');
                    $('#div_committee_btn').hide(300);
                    $('#committee_table').hide(300);
                    $('#committee_table_tbody').empty();
                    $('#department').val("").change();
                    $('#committee_edit_div').hide(300);
                    $('#department_edit_div').hide(300);
                    $('#committee_save_div').show(300);
                    $('#department_cancle_div').hide(300);
                    $('#committee_cancle_div').show(300);
                    console.log($('#departmentInputDetail').val())
                }
            });

            $('#department_cancel').on('click',function () {
                committeeDepartmentCache = [];
                resetCommiteeInput();
                hideAllInput();
                $('#headDepartment').text('เพิ่มหน่วยงาน');
                $('#committee_cancle_div').show(300);
                $('#department_cancle_div').hide(300);
                $('#committee_edit_div').hide(300);
                $('#department_edit_div').hide(300);
                $('#div_committee_btn').hide(300);
                $('#committee_table').hide(300);
                $('#committee_save_div').show(300);
                $('#committee_table_tbody').empty();
                $('#department').val("").change();
            });

            ShowHideRemoveBtn();
            ShowHideForce();
        });


        function ShowHideRemoveBtn() { //ซ่อน-แสดงปุ่มลบ

            if ($('.other_attach_item').length > 1) {
                $('.attach-remove').show();
            } else {
                $('.attach-remove').hide();
            }
        }

        function ShowHideForce(){

            if($('#tis_force1').prop('checked')){//ทั่วไป
                $('label[for="issue_date"]').text('วันที่ประกาศใช้');
                $('.tis_force').hide();
            }else{//บังคับ
                $('label[for="issue_date"]').text('วันที่มีผลบังคับใช้');
                $('.tis_force').show();
            }

        }

        function resetOrder(){//รีเซตลำดับของตำแหน่ง

            $('#work-box').children().each(function(index, el) {
                $(el).find('input[type="radio"]').prop('name', 'status['+index+']');
                $(el).find('label[for*="positions"]').text((index+1)+'.ตำแหน่ง');
            });

        }

        function CropFile(){//เก็บข้อมูลภาพลงตัวแปร

            var croppied = $uploadCrop.croppie('get');

            $('#top').val(croppied.points[1]);
            $('#left').val(croppied.points[0]);
            $('#bottom').val(croppied.points[3]);
            $('#right').val(croppied.points[2]);
            $('#zoom').val(croppied.zoom);

            $uploadCrop.croppie('result', {

                type: 'canvas',

                size: 'viewport'

            }).then(function (resp) {

                $('#croppied').val(resp);

            });
        }

        function addToInput(person,toArr,type) {
            if (toArr === 'cache'){
                if (type === 'add'){
                    committeeDepartmentCache.push(person);
                }else if (type === 'edit'){
                    let index_find = committeeDepartmentCache.indexOf(editCommitteeCache);
                    committeeDepartmentCache.splice(index_find,1);
                    editCommitteeCache = null;
                    committeeDepartmentCache.push(person);
                }
                setToCommitteeTable();
            }
            if (toArr === 'department'){
                if (type === 'add'){
                    committeeInDepartment.push(person);
                }else if (type === 'edit'){
                    let index_find = committeeInDepartment.indexOf(editDepartment);
                    committeeInDepartment.splice(index_find,1);
                    editDepartment = null;
                    committeeInDepartment.push(person);
                }
                setToDepartmentTable();
            }

        }

        function setToCommitteeTable() {
            if (committeeDepartmentCache.length > 0){
                $('#div_committee_btn').show(300);
                $('#committee_table').show(300);
                $('#committee_table_tbody').empty();
                $.each(committeeDepartmentCache, function (n, val) {
                    $('#committee_table_tbody').append('<tr><td>'+val.name+'</td>\n' +
                        '                                            <td>'+val.committee_type+'</td>\n' +
                        '                                            <td>'+((val.telephone !== null) ? val.telephone : '-')+'</td>\n' +
                        '                                            <td>'+((val.email !== null) ? val.email : '-')+'</td>\n' +
                        '                                            <td class="text-center">\n' +
                        '                                                <button class="btn btn-primary btn-xs cacheEdit" data-value="'+val.token+'">\n' +
                        '                                                    <i class="fa fa-pencil-square-o"> </i>\n' +
                        '                                                </button>\n' +
                        '                                                <button class="btn btn-danger btn-xs cacheRemove" data-value="'+val.token+'">\n' +
                        '                                                    <i class="fa fa-remove"></i>\n' +
                        '                                                </button>\n' +
                        '                                            </td></tr>');
                });
            }else{
                $('#committee_table_tbody').empty();
                $('#committee_table').hide(300);
            }
        }

        function callDepartment() {
            $('#department_div').toggle(400);
        }

        function hideAllInput() {
            $('.secretary').hide(300);
            $('.main_Back').hide(300);
            $('.professional').hide(300);
        }

        function alertNotComplete() {
            alert('กรุณาใส่ข้อมูลให้ครบ!');
        }

        function checkInputNotNull(input,email) {
            if (email !== null){
                if (isEmail(email) == false){
                    alert('อีเมลไม่ถูกต้อง');
                    $('#email'.val(''))
                }
            }
            return input !== '' && input !== null && input !== undefined;
        }

        function setToDepartmentTable() {
            $('#departmentInputDetail').attr('value', JSON.stringify(committeeInDepartment));
            if (committeeInDepartment.length > 0){
                committeeInDepartment.sort(function(a, b) {
                    if ( a.department_name < b.department_name ){
                        return -1;
                    }
                    if ( a.department_name > b.department_name ){
                        return 1;
                    }
                    return 0;
                });
                $('#department_table').show(300);
                const alreadyPrint = [];
                $('#department_table_tbody').empty();
                $.each(committeeInDepartment, function (n, val) {
                    $('#department_table_tbody').append('<tr>\n' +
                        '                                        <td>'+((alreadyPrint.includes(val.department_name) ? ' ':val.department_name))+'</td>\n' +
                        '                                        <td>'+val.name+'</td>\n' +
                        '                                        <td>'+val.committee_type+'</td>\n' +
                        '                                        <td>'+((val.telephone !== null) ? val.telephone : '-')+'</td>\n' +
                        '                                        <td>'+((val.email !== null) ? val.email : '-')+'</td>\n' +
                        '                                        <td class="text-center">\n' +
                        '                                            <button class="btn btn-primary btn-xs allDepartmentEdit" data-value="'+val.token+'">\n' +
                        '                                                <i class="fa fa-pencil-square-o"> </i>\n' +
                        '                                            </button>\n' +
                        '                                            <button class="btn btn-danger btn-xs allDepartmentRemove" data-value="'+val.token+'">\n' +
                        '                                                <i class="fa fa-remove"></i>\n' +
                        '                                           </button>\n' +
                        '                                        </td>\n' +
                        '                                    </tr>');

                    if (!alreadyPrint.includes(val.department_name)){
                        alreadyPrint.push(val.department_name);
                    }
                });
            }else{
                $('#department_table_tbody').empty();
                $('#department_table').hide(300);
            }
        }

        function stringRandom() {
            return Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);
        }

        function showCommitteeInput(select_type) {
            if (select_type === "ผู้แทนสำรอง"){
                $('#committee_type_div').removeClass('col-md-12');
                $('#committee_type_div').addClass('col-md-6');
                $('#level_div').show(400);
            }else{
                $('#level_div').hide(400);
                $('#committee_type_div').removeClass('col-md-6');
                $('#committee_type_div').addClass('col-md-12');
            }

            if (select_type === 'ผู้แทนหลัก' || select_type === 'ผู้แทนสำรอง'){
                $('.professional').hide(300);
                $('.secretary').hide(300);
                $('.main_Back').show(300);
            }

            if (select_type === 'ผู้ทรงวุฒิ'){
                $('.secretary').hide(300);
                $('.main_Back').hide(300);
                $('.professional').show(300);
            }

            if (select_type === 'ฝ่ายเลขานุการ'){
                $('.main_Back').hide(300);
                $('.professional').hide(300);
                $('.secretary').show(300);
            }
        }

        function resetCommiteeInput() {
            $("#committee_type").val("").change();
            $("#legate").val("").change();
            $("#level").val("").change();
            $('#peopleName_departure').val('');
            $('#address').val('');
            $('#email').val('');
            $('#position').val('');
            $('#telephone').val('');
            $('#fax').val('');
        }

        function checkCommitteeInput(arr,type) {
            let department = $('#department').find('option:selected').val();
            let department_name = $('#department').find('option:selected').text();
            let name = $('#peopleName_departure').val();
            let legate = $('#legate').find('option:selected').val();
            let address = $('#address').val();
            let email = $('#email').val();
            let committee_type = $('#committee_type').find('option:selected').val();
            let position = $('#position').val();
            let telephone = $('#telephone').val();
            let fax = $('#fax').val();
            var personInDepartment = null;
            if (checkInputNotNull(committee_type,null)){
                if (committee_type === 'ผู้แทนหลัก' || committee_type === 'ผู้แทนสำรอง'){
                    if (checkInputNotNull(name,null) && checkInputNotNull(legate,null) && checkInputNotNull(address,null)
                        && checkInputNotNull(email,email) && checkInputNotNull(position,null) && checkInputNotNull(telephone,null)){
                        let level = null;
                        if (committee_type === 'ผู้แทนสำรอง'){
                            level = $('#level').find('option:selected').val();
                            if (level !== ''){
                                let checkLevelExist = {department:department,legate:legate,level:level};
                                let inCache = null;
                                if (editCommitteeCache !== null){ //หาตัวที่เลือกมา
                                    try {
                                        inCache = JSON.parse(JSON.stringify(editCommitteeCache));
                                    }catch (e) {
                                        inCache = null;
                                    }
                                }else if (editDepartment !== null){
                                    try {
                                        inCache = JSON.parse(JSON.stringify(editDepartment));
                                    }catch (e) {
                                        inCache = null
                                    }
                                }
                                if (inCache !== null){
                                    let index_find = getIndexToRemove(existDepartmentarr,inCache);
                                    if (index_find !== null && index_find !== false){
                                        existDepartmentarr.splice(index_find,1);
                                    }
                                }
                                let can = existInLevel(existDepartmentarr,checkLevelExist) === false;
                                if (can){
                                    personInDepartment = {
                                        department: department,
                                        department_name : department_name,
                                        name       : name,
                                        legate : legate,
                                        address: address,
                                        email: email,
                                        committee_type: committee_type,
                                        level: level,
                                        position: position,
                                        telephone: telephone,
                                        fax: fax,
                                        token: stringRandom()
                                    };
                                    existDepartmentarr.push(checkLevelExist);
                                }else {
                                    alert('ลำดับผู้แทนสำรองซ้ำ!');
                                    personInDepartment = null;
                                    if (inCache !== null){
                                        let returnBack = {department:inCache.department,legate:legate,level:inCache.level};
                                        existDepartmentarr.push(returnBack);
                                    }
                                }
                                console.log(existDepartmentarr);
                            }else{
                                alertNotComplete();
                                personInDepartment = null;
                            }
                        }else{
                            personInDepartment = {
                                department: department,
                                department_name : department_name,
                                name       : name,
                                legate : legate,
                                address: address,
                                email: email,
                                committee_type: committee_type,
                                level: level,
                                position: position,
                                telephone: telephone,
                                fax: fax,
                                token: stringRandom()
                            };
                        }
                    }else{
                        alertNotComplete();
                        personInDepartment = null;
                    }
                }

                if (committee_type === 'ผู้ทรงวุฒิ'){
                    if (checkInputNotNull(name,null) && checkInputNotNull(address,null)) {
                        personInDepartment = {
                            department: department,
                            department_name : department_name,
                            name       : name,
                            legate : null,
                            address: address,
                            email: null,
                            committee_type: committee_type,
                            level: null,
                            position: null,
                            telephone: null,
                            fax: null,
                            token: stringRandom()
                        };
                    }else{
                        alertNotComplete();
                        personInDepartment = null;
                    }
                }

                if (committee_type === 'ฝ่ายเลขานุการ'){
                    if (checkInputNotNull(name,null) && checkInputNotNull(address,null) && checkInputNotNull(email,email)
                        && checkInputNotNull(position,null) && checkInputNotNull(telephone,null)){
                        personInDepartment = {
                            department: department,
                            department_name : department_name,
                            name       : name,
                            legate : null,
                            address: address,
                            email: email,
                            committee_type: committee_type,
                            level: null,
                            position: position,
                            telephone: telephone,
                            fax: fax,
                            token: stringRandom()
                        };
                    }else{
                        alertNotComplete();
                        personInDepartment = null;
                    }

                }
            }else{
                alert('กรุณาเลือกประเภทคณะกรรมการ!')
            }

            if (personInDepartment !== null){
                addToInput(personInDepartment,arr,type);
                department = null;
                department_name = null;
                name = null;
                legate = null;
                address = null;
                email = null;
                committee_type = null;
                position = null;
                telephone = null;
                fax = null;
                resetCommiteeInput();
                hideAllInput();
                return true;
            }else{
                return false;
            }
        }

        function isEmail(email) {
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return regex.test(email);
        }

        function existInLevel(arr, valueOrObject) { // input an array , obj.value
            for (var i = 0; i < arr.length; i++) {
                if (arr[i].department === valueOrObject.department && arr[i].legate === valueOrObject.legate && arr[i].level === valueOrObject.level) {
                    return true;
                }
            }
            return false;
        }

        function getIndexToRemove(arr, valueOrObject) { // input an array , obj.value
            for (var i = 0; i < arr.length; i++) {
                if (arr[i].department === valueOrObject.department && arr[i].legate === valueOrObject.legate && arr[i].level === valueOrObject.level) {
                    return i;
                }
            }
            return false;
        }

        function resetOrdertable(){//รีเซตลำดับของตำแหน่ง

            $('#tbody-table_committee_lists').children().each(function(index, el) {
                $(el).find('td:first').text((index+1));
            });

            }

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }

    </script>

@endpush

