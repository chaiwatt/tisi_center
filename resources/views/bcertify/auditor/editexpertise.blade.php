@extends('layouts.master')

@push('css')

    <style>
        .setTextColor {
            color: black;
        }
        th{
            color: white;
        }
    </style>

@endpush

@section('content')
    <div class="container-fluid">
        <div class="class=col-sm-12">
            <form id="commentForm" action="{{route('bcertify.auditor.update.expertise')}}"
                  method="POST" enctype="multipart/form-data" class="form-horizontal">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="white-box">
                    <a class="btn btn-success pull-right" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <h3>แก้ไขข้อมูลประสบการณ์การทำงาน</h3>
                    <hr>
                    <div class="clearfix"></div>

                    <div class="col-md-6 m-t-15">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_type">ประเภทการตรวจประเมิน:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_type" id="expertise_type">
                                <option value="0">เลือกประเภทการตรวจประเมิน</option>
                                <option value="1">CB</option>
                                <option value="2">IB</option>
                                <option value="3">LAB สอบเทียบ</option>
                                <option value="4">LAB ทดสอบ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" >
                        <label class="col-md-3 control-label label-filter text-right" for="expertise_standard">มาตรฐาน:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="expertise_standard" name="expertise_standard" disabled>
                                <option selected value="0">เลือกมาตรฐาน</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_branch">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_branch">สาขา:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_branch" id="expertise_branch">
                                <option>เลือกสาขา</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_scope">
                        <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_scope">ขอบข่าย:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_scope" id="expertise_scope" disabled>
                                <option>เลือกขอบข่าย</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_inspection">
                        <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_inspection">ประเภทหน่วยตรวจ:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_inspection" id="expertise_inspection">
                                <option>เลือกประเภทหน่วยตรวจ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_category">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_category">หมวดหมู่การตรวจ:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_category" id="expertise_category">
                                <option>เลือกหมวดหมู่การตรวจ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_calibration">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_calibration">รายการสอบเทียบ:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_calibration" id="expertise_calibration" disabled>
                                <option>เลือกรายการสอบเทียบ</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_product">
                        <label class="col-md-3 control-label label-filter text-right text-nowrap" for="expertise_product">ผลิตภัณฑ์:</label>
                        <div class="col-md-9">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_product" id="expertise_product" disabled>
                                <option>เลือกผลิตภัณฑ์</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 m-t-15" style="display: none" id="view_expertise_test">
                        <label class="col-md-5 control-label label-filter text-right text-nowrap" for="expertise_test">รายการทดสอบ:</label>
                        <div class="col-md-7">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center"  name="expertise_test" id="expertise_test" disabled>
                                <option>เลือกรายการทดสอบ</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-7 m-t-15" >
                        <label class="col-md-4 control-label label-filter text-right" for="specialized_expertise">ความเชี่ยวชาญเฉพาะด้าน:</label>
                        <div class="col-md-8">
                            <textarea name="specialized_expertise" id="specialized_expertise" cols="30" rows="3" class="form-control form-control-lg"></textarea>
                        </div>
                    </div>
                    <div class="col-md-5 m-t-15" >
                        @php $data = \App\Models\Bcertify\StatusAuditor::where('state',1)->get() @endphp
                        <label class="col-md-4 control-label label-filter text-right text-nowrap" for="expertise_status">สถานะผู้ตรวจประเมิน:</label>
                        <div class="col-md-8">
                            <select class="form-control col-md-8 setBorder custom-select mr-sm-2 text-center " id="expertise_status" name="expertise_status" >
                                <option selected value="0">เลือกสถานะผู้ตรวจประเมิน</option>
                                @foreach($data as $show)
                                    <option value="{{$show->id}}">{{$show->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <p class="text-danger col-md-4 text-center" style="font-size: 10px">(กดอีกครั้งเพื่อลบ)</p>
                    </div>
                    <div class="col-md-8"></div>
                    <div class="col-md-4" id="total_status">
                    </div>

                    <div class="col-md-10"></div>
                    <div class="col-md-2" style="margin-top: 20px">
                        <div class="pull-right">
                            <button class="btn btn-success" type="button" id="add_expertise" disabled><i class="fa fa-plus"></i> เพิ่ม</button>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorExpertise">
                        <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                    </div>

                    <div class="clearfix"></div>
                    <hr>
                    {{--  Table ความเชี่ยวชาญ CB --}}
                    <div style="display: none;" id="viewCB">
                        <h3 class="col-md-12" style="margin-top: 15px; padding: 0px">ข้อมูลความเชี่ยวชาญ (CB)</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive" >
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">ขอบข่าย</th>
                                    <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                    <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_expertise_CB">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table ความเชี่ยวชาญ IB --}}
                    <div style="display: none;" id="viewIB">
                        <h3 style="margin-top: 15px">ข้อมูลความเชี่ยวชาญ (IB)</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">ประเภทหน่วยตรวจ</th>
                                    <th class="text-center">หมวดหมู่การตรวจ</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                    <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_expertise_IB">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table ความเชี่ยวชาญ LAB สอบเทียบ --}}
                    <div style="display: none;" id="viewLabExam">
                        <h3 style="margin-top: 15px">ความเชี่ยวชาญประเภทการตรวจ LAB สอบเทียบ</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">รายการสอบเทียบ</th>
                                    <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                    <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_expertise_lab">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{--  Table ความเชี่ยวชาญ LAB ทดสอบ --}}
                    <div style="display: none;" id="viewLabTest">
                        <h3 style="margin-top: 15px">ความเชี่ยวชาญประเภทการตรวจ LAB ทดสอบ</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">มาตรฐาน</th>
                                    <th class="text-center">สาขา</th>
                                    <th class="text-center">ผลิตภัณฑ์</th>
                                    <th class="text-center">รายการทดสอบ</th>
                                    <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                    <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="add_expertise_labTest">
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <input type="hidden" name="auditor" value="{{$auditor->id}}">
                    <input type="hidden" name="data_all_expertise" id="data_all_expertise">

                    <button class="pull-right btn btn-success" id="submit_form">บันทึก</button>

                    <div class="clearfix"></div>

                </div>
            </form>

        </div>

    </div>
@endsection


@push('js')

    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
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

        {{--  script ความเชี่ยวชาญ  --}}



        function clearFormExpertise() {
            $('#view_expertise_branch').hide();
            $('#view_expertise_scope').hide();
            $('#view_expertise_inspection').hide();
            $('#view_expertise_category').hide();
            $('#view_expertise_calibration').hide();
            $('#view_expertise_test').hide();
            $('#view_expertise_product').hide();
        }


        var checkEditStatus = 0;
        var branchEditNumber ;
        var standardEditNumber ;
        var typeEditNumber ;
        var catEditNumber ;
        var listCalibration ;
        var productExamNumber ;
        var listTestNumber ;
        var setScopeName = "";
        function expertiseApiStandard(select,_token) {
            $.ajax({
                url:"{{route('bcertify.api.standard')}}",
                method:"POST",
                data:{select:select,_token:_token},
                success:function (result){
                    let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                    $('#expertise_branch').empty();
                    $('#expertise_standard').empty();
                    $("#expertise_standard").prop('disabled', false);
                    // console.log(data);
                    $('#expertise_branch').append('<option value="0">- เลือกสาขา -</option>');
                    $('#expertise_standard').append('<option value="0">- มาตราฐาน -</option>');
                    $.each(data[0],function(index, value){
                        // console.log(value.title);
                        $('#expertise_branch').append('<option value='+value.id+' >'+value.title+'</option>');
                    });
                    $.each(data[1],function(index, value){
                        // console.log(value.title);
                        $('#expertise_standard').append('<option value='+value.id+' >'+value.title+'</option>');
                    });

                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();

                    if (checkEditStatus === 1){
                        $('#expertise_branch').val(branchEditNumber).change();
                        $('#expertise_standard').val(standardEditNumber).change();
                    }

                }
            })
        }

        var check_expertise_type = "";
        $('#expertise_type').on('change',function () {
            console.log($(this).val());
            const select = $(this).val();
            const _token = $('input[name="_token"]').val();

            console.log(select);
            console.log(_token);

            clearFormExpertise();
            $("#add_expertise").prop('disabled', false);
            expertiseApiStandard(select,_token);
            $('#view_expertise_branch').fadeIn();
            check_expertise_type = select;
            if (select === "1"){
                $('#view_expertise_scope').fadeIn();
            }
            else if (select === "2"){
                $('#view_expertise_inspection').fadeIn();
                $('#view_expertise_category').fadeIn();
                $.ajax({
                    url:"{{route('bcertify.api.inspection')}}",
                    method:"POST",
                    data:{select_branch:select,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_inspection").empty();
                        $("#expertise_category").empty();
                        $('#expertise_inspection').append('<option value="0">- เลือกประเภทหน่วยตรวจ -</option>');
                        $('#expertise_category').append('<option value="0">- เลือกหมวดหมู่การตรวจ -</option>');
                        $.each(data[0],function(index, value){
                            $('#expertise_inspection').append('<option value='+value.id+' >'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#expertise_category').append('<option value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_inspection').val(typeEditNumber).change();
                            $('#expertise_category').val(catEditNumber).change();
                            checkEditStatus = 0;

                        }


                    }
                })
            }
            else if (select === "3"){
                $('#view_expertise_calibration').fadeIn();
            }
            else if (select === "4"){
                $('#view_expertise_test').fadeIn();
                $('#view_expertise_product').fadeIn();
            }
        })



        $('#expertise_branch').on('change',function () {
            console.log($(this).val());
            const select_expertise_branch = $("#expertise_branch :selected").text();
            const _token = $('input[name="_token"]').val();
            if (check_expertise_type === "1"){
                $.ajax({
                    url:"{{route('bcertify.api.scope')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_scope").prop('disabled', false);
                        $("#expertise_scope").empty();
                        $('#expertise_scope').append('<option value="0">- เลือกขอบข่าย -</option>');
                        $.each(data,function(index, value){
                            $.each(value,function (index,newValue) {
                                $('#expertise_scope').append('<option value='+newValue.id+'>'+newValue.title+'</option>');
                            })
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_scope').append('<option value='+setScopeName+' >'+setScopeName+'</option>');
                            $('#expertise_scope').val(setScopeName).change();
                            checkEditStatus = 0;
                        }
                    }
                })
            }
            else if (check_expertise_type === "3"){
                $.ajax({
                    url:"{{route('bcertify.api.calibration')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_calibration").prop('disabled', false);
                        $("#expertise_calibration").empty();
                        $('#expertise_calibration').append('<option value="0">- เลือกรายการสอบเทียบ -</option>');
                        $.each(data,function(index, value){
                            $('#expertise_calibration').append('<option  value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_calibration').val(listCalibration).change();
                            checkEditStatus = 0;

                        }
                    }
                })
            }
            else if (check_expertise_type === "4"){
                $.ajax({
                    url:"{{route('bcertify.api.product')}}",
                    method:"POST",
                    data:{select_branch:select_expertise_branch,_token:_token},
                    success:function (result) {
                        let data = JSON.parse(JSON.parse(JSON.stringify(result)));
                        $("#expertise_product").prop('disabled', false);
                        $("#expertise_product").empty();
                        $('#expertise_product').append('<option value="0">เลือกผลิตภัณฑ์</option>');

                        $("#expertise_test").prop('disabled', false);
                        $("#expertise_test").empty();
                        $('#expertise_test').append('<option value="0">เลือกรายการทดสอบ</option>');

                        $.each(data[0],function(index, value){
                            $('#expertise_product').append('<option value='+value.id+' >'+value.title+'</option>');
                        });
                        $.each(data[1],function(index, value){
                            $('#expertise_test').append('<option value='+value.id+' >'+value.title+'</option>');
                        });

                        if (checkEditStatus === 1){
                            $('#expertise_product').val(productExamNumber).change();
                            $('#expertise_test').val(listTestNumber).change();
                            checkEditStatus = 0;

                        }

                    }
                })
            }
        })

        var keep_status = [];
        var keep_value_status_expertise = [];
        $('#expertise_status').on('change',function () {
            $('#total_status').empty();
            if (!keep_status.includes($("#expertise_status :selected").text()) && $(this).val() !== "0"){
                keep_status.push($("#expertise_status :selected").text());
                keep_value_status_expertise.push($(this).val());
                console.log(keep_status);
            }

            $.each(keep_status,function (index,value) {
                $('#total_status').append('<button class="col-md-6 bg-primary text-white text-nowrap text-center clickDelete" id='+value+' style="border: 1px solid blue; padding: 5px 15px ; border-radius: 20px; font-size: 11px ">'+value+'</button>');
            })
        })

        $(document).on('click', '.clickDelete', function () {
            var number_index = keep_status.indexOf($(this).attr('id'));
            keep_status.splice(number_index,1);
            keep_value_status_expertise.splice(number_index,1);
            console.log(number_index);
            console.log($(this).attr('id'));
            console.log(keep_status);
            $('#total_status').empty();
            $.each(keep_status,function (index,value) {
                $('#total_status').append('<button class="col-md-6 bg-primary text-white text-nowrap text-center clickDelete" id='+value+' style="border: 1px solid blue; padding: 5px 15px ; border-radius: 20px;  font-size: 11px">'+value+'</button>');
            });
            $('#expertise_status').val(0).change();
        })


        // --------------------------------------------------------------------------------------------------------
        var arr_expertise_CB = [];
        var arr_expertise_IB = [];
        var arr_expertise_LabExam = [];
        var arr_expertise_LabTest = [];

        function addToTableExpertiseCB(){
            $('#add_expertise_CB').empty();
            var count = 1;
            $.each(arr_expertise_CB,function (index,value) {
                $('#add_expertise_CB').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.scope_name+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditExpertiseCB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashExpertiseCB" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseIB(){
            $('#add_expertise_IB').empty();
            var count = 1;
            $.each(arr_expertise_IB,function (index,value) {
                $('#add_expertise_IB').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.cat+'</td>' +
                    '<td>'+value.typeCheck+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditExpertiseIB" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashExpertiseIB" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseLabExam(){
            $('#add_expertise_lab').empty();
            var count = 1;
            $.each(arr_expertise_LabExam,function (index,value) {
                $('#add_expertise_lab').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.listCalibation+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditExpertiseLabExam" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashExpertiseLabExam" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        function addToTableExpertiseLabTest(){
            $('#add_expertise_labTest').empty();
            var count = 1;
            $.each(arr_expertise_LabTest,function (index,value) {
                $('#add_expertise_labTest').append('<tr>' +
                    '<td class="text-center">'+count+'</td>' +
                    '<td>'+value.standard+'</td>' +
                    '<td>'+value.showBranch+'</td>' +
                    '<td>'+value.exProduct+'</td>' +
                    '<td>'+value.testList+'</td>' +
                    '<td>'+value.find_status+'</td>' +
                    '<td>'+value.specialized_expertise+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditExpertiseLabTest" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickTrashExpertiseLabTest" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count++
            })
        }
        $('#add_expertise').on('click',function () {
            const show_type = $('#expertise_type').val();
            const show_status = keep_status.join(",");
            const show_value_status_expertise = keep_value_status_expertise.join(',');
            const show_branch = $("#expertise_branch :selected").text();
            const show_branch_value = $("#expertise_branch").val();
            const show_standard = $("#expertise_standard :selected").text();
            const show_standard_value = $("#expertise_standard").val();
            const show_specialized = $('#specialized_expertise').val();
            const token =  Math.random().toString(36).substring(7);
            if (check_expertise_type === "1"){
                const show_scope = $("#expertise_scope :selected").text();
                const show_scope_value = $('#expertise_scope').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_scope !== "" && show_specialized !== "" && $('#expertise_status').val() !== "0" && $('#expertise_scope').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewCB').fadeIn();
                    var obj_expertise_CB = {auditor_status:show_value_status_expertise,show_scope_value:show_scope_value,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_type:show_type,standard:show_standard,showBranch:show_branch,scope_name:show_scope,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_CB.push(obj_expertise_CB);
                    console.log(arr_expertise_CB);
                    addToTableExpertiseCB();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_scope').val(0).change();
                    $('#expertise_status').val(0).change();
                }else {
                    $('#showErrorExpertise').fadeIn();
                }

            }
            else if (check_expertise_type === "2"){
                const show_inspection= $('#expertise_inspection :selected').text();
                const show_inspection_value = $('#expertise_inspection').val();
                const show_category= $('#expertise_category :selected').text();
                const show_category_value = $('#expertise_category').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_category !== "" && show_specialized !== "" && show_inspection !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewIB').fadeIn();
                    var obj_expertise_IB = {auditor_status:show_value_status_expertise,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_inspection_value:show_inspection_value,show_category_value:show_category_value,show_type:show_type,standard:show_standard,cat:show_category,typeCheck:show_inspection,showBranch:show_branch,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_IB.push(obj_expertise_IB);
                    addToTableExpertiseIB();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_inspection').val(0).change();
                    $('#expertise_category').val(0).change();
                    $('#expertise_status').val(0).change();
                }else {
                    $('#showErrorExpertise').fadeIn();
                }

            }
            else if (check_expertise_type === "3"){
                const show_calibration = $('#expertise_calibration :selected').text();
                const show_calibration_value = $('#expertise_calibration').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_specialized !== "" && show_calibration !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewLabExam').fadeIn();
                    var obj_expertise_LabExam = {auditor_status:show_value_status_expertise,show_calibration_value:show_calibration_value,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_type:show_type,standard:show_standard,showBranch:show_branch,listCalibation:show_calibration,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_LabExam.push(obj_expertise_LabExam);
                    addToTableExpertiseLabExam();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_calibration').val(0).change();
                    $('#expertise_status').val(0).change();
                }else {
                    $('#showErrorExpertise').fadeIn();
                }
            }
            else if (check_expertise_type === "4"){
                const show_product = $('#expertise_product :selected').text();
                const show_product_value = $('#expertise_product').val();
                const show_test = $('#expertise_test :selected').text();
                const show_test_value = $('#expertise_test').val();
                if (show_type !== "" && show_standard !== "" && show_branch !== "" && show_specialized !== "" && show_product !== "" && show_test !== "" && $('#expertise_status').val() !== "0") {
                    $('#showErrorExpertise').fadeOut();
                    $('#viewLabTest').fadeIn();
                    var obj_expertise_LabTest = {auditor_status:show_value_status_expertise,show_branch_value:show_branch_value,show_standard_value:show_standard_value,show_product_value:show_product_value,show_test_value:show_test_value,show_type:show_type,standard:show_standard,showBranch:show_branch,exProduct:show_product,testList:show_test,find_status:show_status,specialized_expertise:show_specialized,token:token};
                    arr_expertise_LabTest.push(obj_expertise_LabTest);
                    addToTableExpertiseLabTest();
                    $('#expertise_type').val(0).change();
                    $('#expertise_branch').val(0).change();
                    $('#expertise_standard').val(0).change();
                    $('#expertise_product').val(0).change();
                    $('#expertise_test').val(0).change();
                    $('#expertise_status').val(0).change();
                }
                else {
                    $('#showErrorExpertise').fadeIn();
                }
            }

            $('#total_status').empty();
            keep_status = [];
            $('#specialized_expertise').val("");
        })


        // click delete expertise CB
        $(document).on('click','.clickTrashExpertiseCB',function () {
            console.log(arr_expertise_CB);
            let this_remove_expertise_cb = $(this).attr('id');
            let find_expertise_cb = arr_expertise_CB.find(value => value.token === this_remove_expertise_cb);
            var index_expertise_cb = arr_expertise_CB.indexOf(find_expertise_cb);
            arr_expertise_CB.splice(index_expertise_cb,1);
            addToTableExpertiseCB();
            if (arr_expertise_CB.length === 0){
                $('#viewCB').fadeOut();
            }
        })
        // click edit expertise CB
        $(document).on('click','.clickEditExpertiseCB',function () {
            console.log(arr_expertise_CB);
            let this_remove_expertise_cb = $(this).attr('id');
            let find_expertise_cb = arr_expertise_CB.find(value => value.token === this_remove_expertise_cb);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_cb['auditor_status'].split(",");
            branchEditNumber = find_expertise_cb['show_branch_value'];
            standardEditNumber = find_expertise_cb['show_standard_value'];
            setScopeName = find_expertise_cb['scope_name'];
            $('#expertise_type').val(1).change();
            $('#specialized_expertise').val(find_expertise_cb['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            var index_expertise_cb = arr_expertise_CB.indexOf(find_expertise_cb);
            arr_expertise_CB.splice(index_expertise_cb,1);
            addToTableExpertiseCB();
            if (arr_expertise_CB.length === 0){
                $('#viewCB').fadeOut();
            }
        })

        // click delete expertise IB
        $(document).on('click','.clickTrashExpertiseIB',function () {
            console.log(arr_expertise_IB);
            let this_remove_expertise_ib = $(this).attr('id');
            let find_expertise_ib = arr_expertise_IB.find(value => value.token === this_remove_expertise_ib);
            var index_expertise_ib = arr_expertise_IB.indexOf(find_expertise_ib);
            arr_expertise_IB.splice(index_expertise_ib,1);
            addToTableExpertiseIB();
            if (arr_expertise_IB.length === 0){
                $('#viewIB').fadeOut();
            }
        })
        // click Edit expertise IB
        $(document).on('click','.clickEditExpertiseIB',function () {
            console.log(arr_expertise_IB);
            let this_remove_expertise_ib = $(this).attr('id');
            let find_expertise_ib = arr_expertise_IB.find(value => value.token === this_remove_expertise_ib);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_ib['auditor_status'].split(",");
            branchEditNumber = find_expertise_ib['show_branch_value'];
            standardEditNumber = find_expertise_ib['show_standard_value'];
            typeEditNumber = find_expertise_ib['show_inspection_value'];
            catEditNumber = find_expertise_ib['show_category_value'];
            $('#expertise_type').val(2).change();
            $('#specialized_expertise').val(find_expertise_ib['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            var index_expertise_ib = arr_expertise_IB.indexOf(find_expertise_ib);
            arr_expertise_IB.splice(index_expertise_ib,1);
            addToTableExpertiseIB();
            if (arr_expertise_IB.length === 0){
                $('#viewIB').fadeOut();
            }
        });

        // click delete expertise Lab Exam
        $(document).on('click','.clickTrashExpertiseLabExam',function () {
            console.log(arr_expertise_LabExam);
            let this_remove_expertise_lab_exam = $(this).attr('id');
            let find_expertise_lab_exam = arr_expertise_LabExam.find(value => value.token === this_remove_expertise_lab_exam);
            var index_expertise_lab_exam = arr_expertise_LabExam.indexOf(find_expertise_lab_exam);
            arr_expertise_LabExam.splice(index_expertise_lab_exam,1);
            addToTableExpertiseLabExam();
            if (arr_expertise_LabExam.length === 0){
                $('#viewLabExam').fadeOut();
            }
        })
        // click Edit expertise Lab Exam
        $(document).on('click','.clickEditExpertiseLabExam',function () {
            console.log(arr_expertise_LabExam);
            let this_remove_expertise_lab_exam = $(this).attr('id');
            let find_expertise_lab_exam = arr_expertise_LabExam.find(value => value.token === this_remove_expertise_lab_exam);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_lab_exam['auditor_status'].split(",");
            branchEditNumber = find_expertise_lab_exam['show_branch_value'];
            standardEditNumber = find_expertise_lab_exam['show_standard_value'];
            listCalibration = find_expertise_lab_exam['show_calibration_value'];
            $('#expertise_type').val(3).change();
            $('#specialized_expertise').val(find_expertise_lab_exam['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            var index_expertise_lab_exam = arr_expertise_LabExam.indexOf(find_expertise_lab_exam);
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            arr_expertise_LabExam.splice(index_expertise_lab_exam,1);
            addToTableExpertiseLabExam();
            if (arr_expertise_LabExam.length === 0){
                $('#viewLabExam').fadeOut();
            }
        })


        // click delete expertise Lab Test
        $(document).on('click','.clickTrashExpertiseLabTest',function () {
            console.log(arr_expertise_LabTest);
            let this_remove_expertise_lab_test = $(this).attr('id');
            let find_expertise_lab_test = arr_expertise_LabTest.find(value => value.token === this_remove_expertise_lab_test);
            var index_expertise_lab_test = arr_expertise_LabTest.indexOf(find_expertise_lab_test);
            arr_expertise_LabTest.splice(index_expertise_lab_test,1);
            addToTableExpertiseLabTest();
            if (arr_expertise_LabTest.length === 0){
                $('#viewLabTest').fadeOut();
            }
        })


        // click Edit expertise Lab Test
        $(document).on('click','.clickEditExpertiseLabTest',function () {
            console.log(arr_expertise_LabTest);
            let this_remove_expertise_lab_test = $(this).attr('id');
            let find_expertise_lab_test = arr_expertise_LabTest.find(value => value.token === this_remove_expertise_lab_test);
            checkEditStatus = 1;
            var statusExpertise = find_expertise_lab_test['auditor_status'].split(",");
            branchEditNumber = find_expertise_lab_test['show_branch_value'];
            standardEditNumber = find_expertise_lab_test['show_standard_value'];
            productExamNumber = find_expertise_lab_test['show_product_value'];
            listTestNumber = find_expertise_lab_test['show_test_value'];
            $('#expertise_type').val(4).change();
            $('#specialized_expertise').val(find_expertise_lab_test['specialized_expertise']);
            $('#total_status').empty();
            keep_status = [];
            keep_value_status_expertise = [];
            var index_expertise_lab_test = arr_expertise_LabTest.indexOf(find_expertise_lab_test);
            $.each(statusExpertise,function (index,value) {
                $("#expertise_status").val(value).change();
            });
            arr_expertise_LabTest.splice(index_expertise_lab_test,1);
            addToTableExpertiseLabTest();
            if (arr_expertise_LabTest.length === 0){
                $('#viewLabTest').fadeOut();
            }
        });


        $(document).ready(function () {
            // ดึง ความเชี่ยวชาญ
            var expertise_arr = {!! $expertise !!};
            console.log(expertise_arr);
            $.each(expertise_arr,function (index,data) {
                if (data.type_of_assessment === 1) {
                    arr_expertise_CB.push(expertise_arr[index]);
                }
                else if (data.type_of_assessment === 2) {
                    arr_expertise_IB.push(expertise_arr[index]);
                }
                else if (data.type_of_assessment === 3) {
                    arr_expertise_LabExam.push(expertise_arr[index]);
                }
                else if (data.type_of_assessment === 4) {
                    arr_expertise_LabTest.push(expertise_arr[index]);
                }
            })
            if (arr_expertise_CB.length !== 0){
                $('#viewCB').fadeIn();
                addToTableExpertiseCB();
            }
            if (arr_expertise_IB.length !== 0){
                $('#viewIB').fadeIn();
                addToTableExpertiseIB();
            }
            if (arr_expertise_LabExam.length !== 0){
                $('#viewLabExam').fadeIn();
                addToTableExpertiseLabExam();
            }
            if (arr_expertise_LabTest.length !== 0){
                $('#viewLabTest').fadeIn();
                addToTableExpertiseLabTest();
            }

            $('#submit_form').on('click',function () {
                var arr_all_expertise = [];
                arr_all_expertise.push(arr_expertise_CB,arr_expertise_IB,arr_expertise_LabExam,arr_expertise_LabTest);
                $('#data_all_expertise').val(JSON.stringify(arr_all_expertise));
                $('#commentForm').submit();
            })

        })
    </script>
@endpush
