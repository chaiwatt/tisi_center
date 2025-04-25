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
            <form id="commentForm" action="{{ route('bcertify.auditor.update.work') }}"
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
                        <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                            <div class="col-md-2">
                                <label class="col-md-3 control-label label-filter text-right" for="experience_year">ปี:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control text-center" id="experience_year" name="experience_year" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-3 control-label label-filter text-right" for="experience_position">ตำแหน่ง:</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control text-center" id="experience_position" name="experience_position" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="col-md-4 control-label label-filter text-right text-nowrap" for="experience_department">หน่วยงาน:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control text-center" id="experience_department" name="experience_department" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="col-md-4 control-label label-filter text-right text-nowrap" for="experience_character">บทบาทหน้าที่:</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control text-center" id="experience_character" name="experience_character" >
                                </div>
                            </div>
                            <div class="col-md-10"></div>
                            <div class="col-md-2" style="margin-top: 20px">
                                <div class="pull-right">
                                    <button class="btn btn-success" type="button" id="add_experience" ><i class="fa fa-plus"></i> เพิ่ม</button>
                                </div>
                            </div>
                            <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorWork">
                                <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                            </div>

                        </div>

                        <hr>
                        <h3 style="margin-top: 15px">ประสบการณ์การทำงาน</h3>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-striped" >
                                <thead>
                                <tr class="bg-primary text-center" >
                                    <th class="text-center">No.</th>
                                    <th class="text-center">ปีที่ทำงาน</th>
                                    <th class="text-center">ตำแหน่ง</th>
                                    <th class="text-center">หน่วยงาน</th>
                                    <th class="text-center">บทบาทหน้าที่</th>
                                    <th class="text-center"></th>
                                </tr>
                                </thead>
                                <tbody id="experience">
                                </tbody>
                            </table>
                        </div>
                        <div class="clearfix"></div>

                        <input type="hidden" name="auditor" value="{{$auditor->id}}">
                        <input type="hidden" name="work" id="work">

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
        $('#add_experience').on('click',function () {
            const experience_year = $('#experience_year').val();
            const experience_position = $('#experience_position').val();
            const experience_department = $('#experience_department').val();
            const experience_character = $('#experience_character').val();
            const token = Math.random().toString(36).substring(7);

            if (experience_year !== "" && experience_position !== "" && experience_department !== "" && experience_character !== ""){
                $('#showErrorWork').fadeOut();
                var obj_experience = {year:experience_year,position:experience_position,department:experience_department,role:experience_character,token:token};
                arr_experience.push(obj_experience);
                getToTableExperience();
                $('#experience_year').val("");
                $('#experience_position').val("");
                $('#experience_department').val("");
                $('#experience_character').val("");
            }
            else {
                $('#showErrorWork').fadeIn();
            }

        })

        $(document).on('click','.clickEx',function () {
            let this_remove_ex = $(this).attr('id');
            let find_ex = arr_experience.find(value => value.token === this_remove_ex);
            var index_ex = arr_experience.indexOf(find_ex);
            arr_experience.splice(index_ex,1);
            getToTableExperience();
            $('#experience_year').val("");
            $('#experience_position').val("");
            $('#experience_department').val("");
            $('#experience_character').val("");
        })


        $(document).on('click','.clickEditWork',function () {
            console.log(arr_experience);
            let this_remove_ex = $(this).attr('id');
            let find_ex = arr_experience.find(value => value.token === this_remove_ex);
            var index_ex = arr_experience.indexOf(find_ex);
            arr_experience.splice(index_ex,1);
            getToTableExperience();
            $('#experience_year').val(find_ex['year']);
            $('#experience_position').val(find_ex['position']);
            $('#experience_department').val(find_ex['department']);
            $('#experience_character').val(find_ex['role']);
        })

        function getToTableExperience() {
            var count_ex = 1;
            $('#experience').empty()
            $.each(arr_experience,function (index,value) {
                $('#experience').append('<tr>' +
                    '<td class="text-center">'+count_ex+'.</td>' +
                    '<td class="text-center">'+value.year+'</td>' +
                    '<td>'+value.position+'</td>' +
                    '<td>'+value.department+'</td>' +
                    '<td>'+value.role+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditWork" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickEx" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');

                count_ex++;

            })

        }

        $(document).ready(function () {

            // ดึงประสบการณ์
            arr_experience = {!! $works !!};
            getToTableExperience();

            $('#submit_form').on('click',function () {
                $('#work').val(JSON.stringify(arr_experience));
                $('#commentForm').submit();
            })
        })
    </script>
@endpush
