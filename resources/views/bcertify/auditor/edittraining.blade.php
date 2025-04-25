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
            <form id="commentForm" action="{{ route('bcertify.auditor.update.training') }}"
                  method="POST" enctype="multipart/form-data" class="form-horizontal">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="white-box">
                    <a class="btn btn-success pull-right" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <h3>แก้ไขการฝึกอบรม</h3>
                    <hr>
                    <div class="clearfix"></div>
                    <h2 class="hidden">&nbsp;</h2>
                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                        <div class="col-md-7">
                            <label class="col-md-3 control-label label-filter text-right" for="subject">ชื่อหลักสูตร:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control text-center" id="subject" name="subject" >
                            </div>
                        </div>

                        <div class="col-md-5">
                            <label class="col-md-3 control-label label-filter" for="institution">หน่วยงาน:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control text-center" id="institution" name="institution" >
                            </div>
                        </div>

                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                            {!! Form::label('start_date', 'วันที่เริ่มอบรม:', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('start_date', null, ['class' => 'form-control mydatepicker']) !!}
                                {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-5 m-t-15 {{ $errors->has('birth_date') ? 'has-error' : ''}}">
                            {!! Form::label('end_date', 'วันที่สิ้นสุด:', ['class' => 'col-md-4 control-label']) !!}
                            <div class="col-md-8">
                                {!! Form::text('end_date', null, ['class' => 'form-control mydatepicker']) !!}
                                {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="col-md-2" style="margin-top: 20px">
                            <div class="pull-right">
                                <button class="btn btn-success" type="button" id="add_history"><i class="fa fa-plus"></i> เพิ่ม</button>
                            </div>
                        </div>

                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorTraining">
                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                        </div>

                    </div>

                    <hr>
                    <h3 style="margin-top: 15px">ประวัติการฝึกอบรม</h3>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" >
                            <thead>
                            <tr class="bg-primary text-center" >
                                <th class="text-center">No.</th>
                                <th class="text-center">วันที่อบรม</th>
                                <th class="text-center">ชื่อหลักสูตร</th>
                                <th class="text-center">หน่วยงาน</th>
                                <th class="text-center"></th>
                            </tr>
                            </thead>
                            <tbody id="history">
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>

                    <input type="hidden" name="auditor" value="{{$auditor->id}}">
                    <input type="hidden" name="training" id="training">

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
        // สำหรับหน้า การฝึกอบรม
        $('#add_history').on('click',function () {
            const name_history = $('#subject').val();
            const start_date = $('#start_date').val();
            const end_date = $('#end_date').val();
            const institution = $('#institution').val();
            const token = Math.random().toString(36).substring(7);
            if (name_history !== "" && start_date !== "" && institution !== "") {
                $('#showErrorTraining').fadeOut();
                var obj_history = {start_training:start_date,end_training:end_date,course_name:name_history,department_name:institution,token:token};
                arr_history.push(obj_history);
                getToTableHistory();
                $('#subject').val("");
                $('#start_date').val("");
                $('#end_date').val("");
                $('#institution').val("");
            }
            else {
                $('#showErrorTraining').fadeIn();
            }

        });

        $(document).on('click','.clickHistory',function () {
            let this_remove_his = $(this).attr('id');
            let find_his = arr_history.find(value => value.token === this_remove_his);
            var index_his = arr_history.indexOf(find_his);
            arr_history.splice(index_his,1);
            getToTableHistory();
            $('#subject').val("");
            $('#start_date').val("");
            $('#end_date').val("");
            $('#institution').val("");
        })

        $(document).on('click','.clickEditTraining',function () {
            console.log(arr_history);
            let this_remove_his = $(this).attr('id');
            let find_his = arr_history.find(value => value.token === this_remove_his);
            var index_his = arr_history.indexOf(find_his);
            arr_history.splice(index_his,1);
            getToTableHistory();
            $('#subject').val(find_his['course_name']);
            $('#start_date').val(find_his['start_training']);
            $('#end_date').val(find_his['end_training']);
            $('#institution').val(find_his['department_name']);
        })

        function getToTableHistory(){
            var arr_mount = ['ม.ค.','ก.พ.','มี.ค.','เม.ษ.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'];
            var arr_number_mount = ['01','02','03','04','05','06','07','08','09','10','11','12'];
            var count_his = 1;
            $('#history').empty();
            $.each(arr_history,function (index,value) {
                const split_first_date = value.start_training.split("/");
                const find_number_first_mount = arr_number_mount.find(element => element === split_first_date[1]);
                const index_first_mount = arr_number_mount.indexOf(find_number_first_mount);
                const show_first_year = parseInt(split_first_date[2])+543;

                const split_second_date = value.end_training.split("/");
                const find_number_second_mount = arr_number_mount.find(element => element === split_second_date[1]);
                const index_second_mount = arr_number_mount.indexOf(find_number_second_mount);
                const show_second_year = parseInt(split_second_date[2])+543;

                // console.log(arr_mount[index_first_mount]);
                $('#history').append('<tr>' +
                    '<td class="text-center">'+count_his+'.</td>' +
                    '<td class="text-center">'+split_first_date[0]+" "+arr_mount[index_first_mount]+" "+show_first_year+" - "+
                    split_second_date[0]+" "+arr_mount[index_second_mount]+" "+show_second_year+'</td>' +
                    '<td>'+value.course_name+'</td>' +
                    '<td>'+value.department_name+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditTraining" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickHistory" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');

                count_his++;

            });
        }


        $(document).ready(function () {
            // ดึงการฝึกอบรม
            arr_history = {!! $trainings !!};
            getToTableHistory();


            $('#submit_form').on('click',function () {
                $('#training').val(JSON.stringify(arr_history));
                $('#commentForm').submit();
            })
        })
    </script>
@endpush
