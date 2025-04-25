@extends('layouts.master')

@push('css')

    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .setTextColor {
            color: black;
        }
        th{
            color: white;
        }

        .datepicker-switch{
            color: black;
        }

        .prev {
            color: black;
        }
        .next {
            color: black;
        }

        .dow{
            color: black;
        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="class=col-sm-12">
            <form id="commentForm" action="{{route('bcertify.auditor.update.assessment')}}"
                  method="POST" enctype="multipart/form-data" class="form-horizontal">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="white-box">
                    <a class="btn btn-success pull-right" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <h3>แก้ไขประสบการณ์การตรวจประเมิน</h3>
                    <hr>
                    <div class="clearfix"></div>

                    <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                        {!! Form::label('start_check_date', 'วันที่ตรวจประเมิน:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('start_check_date', null, ['class' => 'form-control mydatepicker']) !!}
                            {!! $errors->first('start_check_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>

                    <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                        {!! Form::label('end_check_date', 'ถึง:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-8">
                            {!! Form::text('end_check_date', null, ['class' => 'form-control mydatepicker']) !!}
                            {!! $errors->first('end_check_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="type_of_check">ประเภทการตรวจประเมิน:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center type_of_check"  name="type_of_check" >
                                <option selected value="0">เลือกประเภทการตรวจประเมิน</option>
                                <option value="1">CB</option>
                                <option value="2">IB</option>
                                <option value="3">LAB สอบเทียบ</option>
                                <option value="4">LAB ทดสอบ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" >
                        <label class="col-md-3 control-label label-filter text-right" for="check_standard">มาตรฐาน:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_standard" name="check_standard" disabled>
                                <option selected>เลือกมาตรฐาน</option>
                            </select>
                        </div>
                    </div>

                    {{-- hidden : show when choose ประเภทการตรวจประเมิน --}}
                    <div class="col-md-6 m-t-15" style="display: none;" id="branch">
                        <label class="col-md-5 control-label label-filter text-right" for="check_branch">สาขา:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_branch" name="check_branch" >
                                <option selected>เลือกสาขา</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="scope">
                        <label class="col-md-3 control-label label-filter text-right" for="check_scope">ขอบข่าย:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_scope" name="check_scope" disabled>
                                <option selected>เลือกขอบข่าย</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="calibration">
                        <label class="col-md-4 control-label label-filter text-right" for="check_calibration">รายการสอบเทียบ:</label>
                        <div class="col-md-8">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_calibration" name="check_calibration" disabled>
                                <option selected>เลือกรายการสอบเทียบ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="inspection">
                        <label class="col-md-4 control-label label-filter text-right" for="check_inspection">ประเภทหน่วยตรวจ:</label>
                        <div class="col-md-8">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_inspection" name="check_inspection" >
                                <option selected>เลือกประเภทหน่วยตรวจ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="category">
                        <label class="col-md-5 control-label label-filter text-right" for="check_category">หมวดหมู่การตรวจ:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_category" name="check_category" >
                                <option selected>เลือกหมวดหมู่การตรวจ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="product">
                        <label class="col-md-3 control-label label-filter text-right" for="check_product">ผลิตภัณฑ์:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_product" name="check_product"  disabled>
                                <option selected>เลือกผลิตภัณฑ์</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none;" id="test">
                        <label class="col-md-5 control-label label-filter text-right" for="check_test">รายการทดสอบ:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_test" name="check_test" disabled>
                                <option selected>เลือกรายการทดสอบ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-7 m-t-15" >
                        <label class="col-md-3 control-label label-filter text-right" for="check_role">บทบาทหน้าที่:</label>
                        <div class="col-md-9">
                            <textarea name="check_role" id="check_role" cols="30" rows="3" class="form-control form-control-lg"></textarea>
                        </div>
                    </div>
                    <div class="col-md-5 m-t-15" >
                        @php $kind = \App\Models\Bcertify\StatusAuditor::where('kind',1)->where('state',1)->get() @endphp
                        <label class="col-md-4 control-label label-filter text-right" for="check_status">สถานะผู้ประเมิน:</label>
                        <div class="col-md-8">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="check_status" name="check_status" >
                                <option selected value="0">เลือกสถานะผู้ประเมิน</option>
                                @foreach($kind as $show)
                                    <option value="{{$show->id}}" >{{$show->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-10"></div>
                    <div class="col-md-2" style="margin-top: 20px">
                        <div class="pull-right">
                            <button class="btn btn-success" type="button" id="add_check" disabled><i class="fa fa-plus"></i> เพิ่ม</button>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorAssessment">
                        <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                    </div>


                    <div class="clearfix"></div>

                    <hr>
                    {{--  Table CB --}}
                    <div id="tableCheckCB" style="display: none;">
                        <h3 class="col-md-12" style="margin-top: 15px; padding: 0px">ประสบการณ์การตรวจประเมิน CB</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">วันที่ตรวจ</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">ขอบข่าย</th>
                                    <th class="text-center">บทบาทหน้าที่</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_cb">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table IB --}}
                    <div id="tableCheckIB" style="display: none;">
                        <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน IB</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">วันที่ตรวจ</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">ประเภทหน่วยตรวจ</th>
                                    <th class="text-center">หมวดหมู่การตรวจ</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">บทบาทหน้าที่</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_ib">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table LAB สอบเทียบ --}}
                    <div id="tableCheckLabExam" style="display: none;">
                        <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน LAB สอบเทียบ</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">วันที่ตรวจ</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">รายการสอบเทียบ</th>
                                    <th class="text-center">บทบาทหน้าที่</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_labcalibration">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table LAB ทดสอบ --}}
                    <div id="tableCheckLabTest" style="display: none;">
                        <h3 style="margin-top: 15px">ประสบการณ์การตรวจประเมิน LAB ทดสอบ</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">วันที่ตรวจ</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">ผลิตภัณฑ์</th>
                                    <th class="text-center">รายการทดสอบ</th>
                                    <th class="text-center">บทบาทหน้าที่</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_labtest">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input type="hidden" name="auditor" value="{{$auditor->id}}">
                    <input type="hidden" name="data_all_check" id="data_all_check">

                    <button class="pull-right btn btn-success" id="submit_form">บันทึก</button>

                    <div class="clearfix"></div>

                </div>


            </form>
        </div>

    </div>
@endsection


@push('js')

    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>


    <script>
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy',
            orientation: 'bottom',
        });
    </script>


    <script>
        @if(\Session::has('success_message'))
        $.toast({
            heading: 'Success!',
            position: 'top-center',
            text: '{{session()->get('success_message')}}',
            loaderBg: '#70b7d6',
            icon: 'success',
            hideAfter: 3000,
            stack: 6
        });
        @endif
    </script>


    <script>
        {{--  script ประสบการณ์การตรวจประเมินทั้งหมด  --}}
        function clearForm(){
            $('#branch').hide();
            $('#scope').hide();
            $('#calibration').hide();
            $('#inspection').hide();
            $('#category').hide();
            $('#product').hide();
            $('#test').hide();
        }


        var checkApiEditStatus = 0;
        var branchCheckEditNumber;
        var standardCheckEditNumber;
        var typeCheckEditNumber;
        var categoryCheckEditNumber;
        var calibrationCheckEditNumber;
        var productCheckEditNumber;
        var testCheckEditNumber;

        var setScopeName = "";
        function apiStandart(select,_token){
            $.ajax({
                url:"{{route('bcertify.api.standard')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result) {
                    $('#check_standard').empty();
                    $("#check_standard").prop('disabled', false);
                    $('#check_branch').empty();
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    // console.log(data)
                    if (data.length > 1) {
                        for (let i = 0; i <data.length ; i++) {
                            if (i === 0){
                                // console.log(data[0]);
                                $('#check_branch').append('<option value="0">- เลือกสาขา -</option>');
                                $('#check_standard').append('<option value="0">- มาตราฐาน -</option>');
                                $.each(data[0],function(index, value){
                                    // console.log(value.title);
                                    $('#check_branch').append('<option value='+value.id+' >'+value.title+'</option>');
                                });

                            }else {
                                $.each(data[1],function(index, value){
                                    $('#check_standard').append('<option  value='+value.id+' >'+value.title+'</option>');

                                });
                            }
                        }
                    }

                    if (checkApiEditStatus === 1){
                        $('#check_branch').val(branchCheckEditNumber).change();
                        $('#check_standard').val(standardCheckEditNumber).change();
                    }


                }
            });
        }

        let type = "";
        $('.type_of_check').on('change',function () {
            if ($(this).val() !== "") {
                const select = $(this).val();
                const _token = $('input[name="_token"]').val();
                $("#add_check").prop('disabled', false);
                clearForm();
                apiStandart(select,_token);
                $('#branch').fadeIn();
                if (select === "1") {
                    $('#scope').fadeIn();
                }
                else if (select === "2") {
                    $('#inspection').fadeIn();
                    $('#category').fadeIn();
                    $.ajax({
                        url:"{{route('bcertify.api.inspection')}}",
                        method:"POST",
                        data:{select_branch:select,_token:_token},
                        success:function (result) {
                            let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                            $("#check_inspection").empty();
                            $("#check_category").empty();
                            $('#check_inspection').append('<option value="0">- เลือกประเภทหน่วยตรวจ -</option>');
                            $('#check_category').append('<option value="0">- เลือกหมวดหมู่การตรวจ -</option>');
                            $.each(data[0],function(index, value){
                                $('#check_inspection').append('<option  value='+value.id+' >'+value.title+'</option>');
                            });
                            $.each(data[1],function(index, value){
                                $('#check_category').append('<option value='+value.id+' >'+value.title+'</option>');
                            });

                            if (checkApiEditStatus === 1){
                                $('#check_inspection').val(typeCheckEditNumber).change();
                                $('#check_category').val(categoryCheckEditNumber).change();
                                checkApiEditStatus = 0;
                            }

                        }
                    })
                }
                else if (select === "3") {
                    $('#calibration').fadeIn();
                }

                else if (select === "4") {
                    $('#product').fadeIn();
                    $('#test').fadeIn();
                }

                else {
                }
                type = select;
            }
        });



        $('#check_branch').on('change',function () {
            const select_branch = $('#check_branch :selected').text();
            const _token = $('input[name="_token"]').val();
            if (type === "1"){
                $.ajax({
                    url:"{{route('bcertify.api.scope')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_scope").prop('disabled', false);
                        $("#check_scope").empty();
                        $('#check_scope').append('<option value="0">- เลือกสาขา -</option>');
                        $.each(data,function(index, value){
                            $.each(value,function (index,newValue) {
                                $('#check_scope').append('<option>'+newValue.title+'</option>');
                            })
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_scope').append('<option value='+setScopeName+' >'+setScopeName+'</option>');
                            $('#check_scope').val(setScopeName).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }

            else if (type === "3"){
                $.ajax({
                    url:"{{route('bcertify.api.calibration')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_calibration").prop('disabled', false);
                        $("#check_calibration").empty();
                        $('#check_calibration').append('<option value="0">- เลือกรายการสอบเทียบ -</option>');
                        $.each(data,function(index, value){
                            $('#check_calibration').append('<option value='+value.id+'>'+value.title+'</option>');
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_calibration').val(calibrationCheckEditNumber).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }
            else if (type === "4"){
                $.ajax({
                    url:"{{route('bcertify.api.product')}}",
                    method:"POST",
                    data:{select_branch:select_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#check_product").prop('disabled', false);
                        $("#check_product").empty();
                        $('#check_product').append('<option value="0">เลือกผลิตภัณฑ์</option>');

                        $("#check_test").prop('disabled', false);
                        $("#check_test").empty();
                        $('#check_test').append('<option value="0">เลือกรายการทดสอบ</option>');

                        $.each(data[0],function(index, value){
                            $('#check_product').append('<option value='+value.id+'>'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#check_test').append('<option value='+value.id+'>'+value.title+'</option>');
                        });

                        if (checkApiEditStatus === 1){
                            $('#check_product').val(productCheckEditNumber).change();
                            $('#check_test').val(testCheckEditNumber).change();
                            checkApiEditStatus = 0;
                        }
                    }
                })

            }
            else {
                console.log("testttt")
            }

            // console.log(select_branch);
        });


        // ############################################################################################################
        var arr_check_cb = [];
        var arr_check_ib = [];
        var arr_check_labexam = [];
        var arr_check_labtest = [];


        function addCheckToTableCB(){
            $('#add_cb').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            console.log(arr_check_cb);
            $.each(arr_check_cb,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_cb').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.scope_name+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditCheckCB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashCheckCB" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++;
            })

        }
        function addCheckToTableIB(){
            $('#add_ib').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            console.log(arr_check_ib);
            $.each(arr_check_ib,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_ib').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.typeCheck+'</td>' +
                    '<td>'+value.cat+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditCheckIB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashCheckIB" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++;
            })

        }
        function addCheckToTableLabExam(){
            $('#add_labcalibration').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            $.each(arr_check_labexam,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_labcalibration').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.listCalibation+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditCheckLabExam" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashCheckLabExam" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');

                count++;
            })

        }
        function addCheckToTableLabTest(){
            $('#add_labtest').empty();
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count = 1;
            $.each(arr_check_labtest,function (index,value) {

                const split_first_date = value.first_date.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                $('#add_labtest').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.exProduct+'</td>' +
                    '<td>'+value.testList+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditCheckLabTest" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashCheckLabTest" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');


                count++;
            })

        }
        $('#add_check').on('click' ,function () {
            const branch = $('#check_branch :selected').text();
            const branch_value = $('#check_branch').val();
            const standard = $('#check_standard :selected').text();
            const standard_value = $('#check_standard').val();
            const start_date = $('#start_check_date').val();
            const end_date = $('#end_check_date').val();
            const role = $('#check_role').val();
            const token = Math.random().toString(36).substring(7);
            const status = $('#check_status').val();
            const show_type = $('#type_of_check').val();
            if (type === "1"){
                const scope = $('#check_scope').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && scope !== "" && $('#check_status').val() !== "0" && $('#check_scope').val() !== "0") {
                    $('#showErrorAssessment').fadeOut();
                    var obj_check_cb = {branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,scope_name:scope,role:role,showStaus:status,token:token};
                    $('#tableCheckCB').fadeIn();
                    arr_check_cb.push(obj_check_cb);
                    addCheckToTableCB();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_scope').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn();
                }
            }
            else if (type === "2"){
                const type_inspection = $('#check_inspection :selected').text();
                const type_inspection_value = $('#check_inspection').val();
                const category = $('#check_category :selected').text();
                const category_value = $('#check_category').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && type_inspection !== "" && category !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut();
                    var obj_check_ib = {category_value:category_value,type_inspection_value:type_inspection_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,typeCheck:type_inspection,cat:category,showBranch:branch,role:role,showStaus:status,token:token};
                    $('#tableCheckIB').fadeIn();
                    arr_check_ib.push(obj_check_ib);
                    addCheckToTableIB();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_inspection').val(0).change();
                    $('#check_category').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn();
                }
            }
            else if (type === "3"){
                const calibration = $('#check_calibration :selected').text();
                const calibration_value = $('#check_calibration').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && calibration !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut();
                    var obj_check_labExam = {calibration_value:calibration_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,listCalibation:calibration,role:role,showStaus:status,token:token};
                    $('#tableCheckLabExam').fadeIn();
                    arr_check_labexam.push(obj_check_labExam);
                    addCheckToTableLabExam();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#calibration').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn();
                }

            }
            else if (type === "4"){
                const product = $('#check_product :selected').text();
                const product_value = $('#check_product').val();
                const lab_test = $('#check_test :selected').text();
                const lab_test_value = $('#check_test').val();
                if (show_type !== "" && standard !== "" && branch !== "" && start_date !== "" && end_date !== "" && product !== "" && lab_test !== "" && $('#check_status').val() !== "0") {
                    $('#showErrorAssessment').fadeOut();
                    var obj_check_labTest = {lab_test_value:lab_test_value,product_value:product_value,branch_value:branch_value,standard_value:standard_value,checkType:type,first_date:start_date,second_date:end_date,standard:standard,showBranch:branch,exProduct:product,testList:lab_test,role:role,showStaus:status,token:token};
                    $('#tableCheckLabTest').fadeIn();
                    arr_check_labtest.push(obj_check_labTest);
                    addCheckToTableLabTest();
                    $('#check_branch').val(0).change();
                    $('#check_standard').val(0).change();
                    $('#check_status').val(0).change();
                    $('.type_of_check').val(0).change();
                    $('#check_product').val(0).change();
                    $('#check_test').val(0).change();
                }
                else {
                    $('#showErrorAssessment').fadeIn();
                }


            }
            // $("#add_check").prop('disabled', true);
            $('#check_role').val("");
            $('#start_check_date').val("");
            $('#end_check_date').val("");
        });

        // click delete check CB
        $(document).on('click','.clickTrashCheckCB',function () {
            console.log(arr_check_cb);
            let this_remove_check_cb = $(this).attr('id');
            let find_check_cb = arr_check_cb.find(value => value.token === this_remove_check_cb);
            var index_check_cb = arr_check_cb.indexOf(find_check_cb);
            arr_check_cb.splice(index_check_cb,1);
            addCheckToTableCB();
            if (arr_check_cb.length === 0){
                $('#tableCheckCB').fadeOut();
            }
        })

        // click edit check CB
        $(document).on('click','.clickEditCheckCB',function () {
            console.log(arr_check_cb);
            let this_remove_check_cb = $(this).attr('id');
            let find_check_cb = arr_check_cb.find(value => value.token === this_remove_check_cb);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_cb['branch_value'];
            standardCheckEditNumber = find_check_cb['standard_value'];
            setScopeName = find_check_cb['scope_name'];
            $('.type_of_check').val(1).change();
            $('#start_check_date').val(find_check_cb['first_date']);
            $('#end_check_date').val(find_check_cb['second_date']);
            $('#check_role').val(find_check_cb['role']);
            $('#check_status').val(find_check_cb['showStaus']).change();
            var index_check_cb = arr_check_cb.indexOf(find_check_cb);
            arr_check_cb.splice(index_check_cb,1);
            addCheckToTableCB();
            if (arr_check_cb.length === 0){
                $('#tableCheckCB').fadeOut();
            }
        });
        // click delete check IB
        $(document).on('click','.clickTrashCheckIB',function () {
            console.log(arr_check_ib);
            let this_remove_check_ib = $(this).attr('id');
            let find_check_ib = arr_check_ib.find(value => value.token === this_remove_check_ib);
            var index_check_ib = arr_check_ib.indexOf(find_check_ib);
            arr_check_ib.splice(index_check_ib,1);
            addCheckToTableIB();
            if (arr_check_ib.length === 0){
                $('#tableCheckIB').fadeOut();
            }
        });

        // click edit check IB
        $(document).on('click','.clickEditCheckIB',function () {
            console.log(arr_check_ib);
            let this_remove_check_ib = $(this).attr('id');
            let find_check_ib = arr_check_ib.find(value => value.token === this_remove_check_ib);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_ib['branch_value'];
            standardCheckEditNumber = find_check_ib['standard_value'];

            typeCheckEditNumber = find_check_ib['type_inspection_value'];
            categoryCheckEditNumber = find_check_ib['category_value'];

            $('.type_of_check').val(2).change();
            $('#start_check_date').val(find_check_ib['first_date']);
            $('#end_check_date').val(find_check_ib['second_date']);
            $('#check_role').val(find_check_ib['role']);
            $('#check_status').val(find_check_ib['showStaus']).change();
            var index_check_ib = arr_check_ib.indexOf(find_check_ib);
            arr_check_ib.splice(index_check_ib,1);
            addCheckToTableIB();
            if (arr_check_ib.length === 0){
                $('#tableCheckIB').fadeOut();
            }
        });
        // click delete check Lab Exam
        $(document).on('click','.clickTrashCheckLabExam',function () {
            console.log(arr_check_labexam);
            let this_remove_check_lab_exam = $(this).attr('id');
            let find_check_lab_exam = arr_check_labexam.find(value => value.token === this_remove_check_lab_exam);
            var index_check_lab_exam = arr_check_labexam.indexOf(find_check_lab_exam);
            arr_check_labexam.splice(index_check_lab_exam,1);
            addCheckToTableLabExam();
            if (arr_check_labexam.length === 0){
                $('#tableCheckLabExam').fadeOut();
            }
        });
        // click edit check Lab Exam
        $(document).on('click','.clickEditCheckLabExam',function () {
            console.log(arr_check_labexam);
            let this_remove_check_lab_exam = $(this).attr('id');
            let find_check_lab_exam = arr_check_labexam.find(value => value.token === this_remove_check_lab_exam);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_lab_exam['branch_value'];
            standardCheckEditNumber = find_check_lab_exam['standard_value'];
            calibrationCheckEditNumber = find_check_lab_exam['calibration_value'];
            $('.type_of_check').val(3).change();
            $('#start_check_date').val(find_check_lab_exam['first_date']);
            $('#end_check_date').val(find_check_lab_exam['second_date']);
            $('#check_role').val(find_check_lab_exam['role']);
            $('#check_status').val(find_check_lab_exam['showStaus']).change();
            var index_check_lab_exam = arr_check_labexam.indexOf(find_check_lab_exam);
            arr_check_labexam.splice(index_check_lab_exam,1);
            addCheckToTableLabExam();
            if (arr_check_labexam.length === 0){
                $('#tableCheckLabExam').fadeOut();
            }
        });
        // click delete check Lab Test
        $(document).on('click','.clickTrashCheckLabTest',function () {
            console.log(arr_check_labtest);
            let this_remove_check_lab_test = $(this).attr('id');
            let find_check_lab_test = arr_check_labtest.find(value => value.token === this_remove_check_lab_test);
            var index_check_lab_test = arr_check_labtest.indexOf(find_check_lab_test);
            arr_check_labtest.splice(index_check_lab_test,1);
            addCheckToTableLabTest();
            if (arr_check_labtest.length === 0){
                $('#tableCheckLabTest').fadeOut();
            }
        });
        // click edit check Lab Test
        $(document).on('click','.clickEditCheckLabTest',function () {
            console.log(arr_check_labtest);
            let this_remove_check_lab_test = $(this).attr('id');
            let find_check_lab_test = arr_check_labtest.find(value => value.token === this_remove_check_lab_test);
            checkApiEditStatus = 1;
            branchCheckEditNumber = find_check_lab_test['branch_value'];
            standardCheckEditNumber = find_check_lab_test['standard_value'];
            productCheckEditNumber = find_check_lab_test['product_value'];
            testCheckEditNumber = find_check_lab_test['lab_test_value'];
            $('.type_of_check').val(4).change();
            $('#start_check_date').val(find_check_lab_test['first_date']);
            $('#end_check_date').val(find_check_lab_test['second_date']);
            $('#check_role').val(find_check_lab_test['role']);
            $('#check_status').val(find_check_lab_test['showStaus']).change();
            var index_check_lab_test = arr_check_labtest.indexOf(find_check_lab_test);
            arr_check_labtest.splice(index_check_lab_test,1);
            addCheckToTableLabTest();
            if (arr_check_labtest.length === 0){
                $('#tableCheckLabTest').fadeOut();
            }
        });


        $(document).ready(function () {
            // ดึง ประสบการณ์ตรวจประเมิน
            var check_arr = {!! $assessments !!};
            console.log(check_arr);
            $.each(check_arr,function (index,data) {
                if (data.type_of_assessment === 1) {
                    arr_check_cb.push(check_arr[index]);
                }
                else if (data.type_of_assessment === 2) {
                    arr_check_ib.push(check_arr[index]);
                }
                else if (data.type_of_assessment === 3) {
                    arr_check_labexam.push(check_arr[index]);
                }
                else if (data.type_of_assessment === 4) {
                    arr_check_labtest.push(check_arr[index]);
                }
            })

            if (arr_check_cb.length !== 0){
                $('#tableCheckCB').fadeIn();
                addCheckToTableCB();
            }
            if (arr_check_ib.length !== 0){
                $('#tableCheckIB').fadeIn();
                addCheckToTableIB();
            }
            if (arr_check_labexam.length !== 0){
                $('#tableCheckLabExam').fadeIn();
                addCheckToTableLabExam();
            }
            if (arr_check_labtest.length !== 0){
                $('#tableCheckLabTest').fadeIn();
                addCheckToTableLabTest();
            }




            $('#submit_form').on('click',function () {
                var arr_all_check = [];
                arr_all_check.push(arr_check_cb,arr_check_ib,arr_check_labexam,arr_check_labtest);
                $('#data_all_check').val(JSON.stringify(arr_all_check));
                $('#commentForm').submit();
            })
        })

    </script>
@endpush
