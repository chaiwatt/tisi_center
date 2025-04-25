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
            <form id="commentForm" action="{{ route('bcertify.auditor.update.education') }}"
                  method="POST" enctype="multipart/form-data" class="form-horizontal">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="white-box">
                    <a class="btn btn-success pull-right" href="{{route('bcertify.auditor.show',['token'=>$auditor->token])}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <h3>แก้ไขข้อมูลการศึกษา</h3>
                    <hr>
                    <div class="clearfix"></div>
                    <div class="col-md-12" style="padding: 25px 10px ; margin-bottom: 20px">
                        <div class="col-md-4">
                            <label class="col-md-4 control-label label-filter" for="year">ปีที่สำเร็จ</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control text-center" id="year" name="year" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="col-md-4 control-label label-filter" for="education">วุฒิการศึกษา</label>
                            <div class="col-md-8">
                                <select class="form-control col-md-9 setBorder custom-select mr-sm-2 text-center" id="education" name="education" >
                                    <option selected value="0">- เลือกวุฒิการศึกษา -</option>
                                    <option value="1">ป.ตรี</option>
                                    <option value="2">ป.โท</option>
                                    <option value="3">ป.เอก</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="col-md-4 control-label label-filter" for="major">สาขา</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control text-center" id="major" name="major" >
                            </div>
                        </div>


                        <div class="col-md-5" style="margin-top: 20px">
                            <label class="col-md-4 control-label label-filter test-left" for="school">ชื่อสถานศึกษา</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control text-center" id="school" name="school" >
                            </div>
                        </div>

                        @php $contry = \Illuminate\Support\Facades\DB::table('tb_country')->select('*')->get() @endphp
                        <div class="col-md-4" style="margin-top: 20px">
                            <label class="col-md-4 control-label label-filter" for="country">ประเทศ</label>
                            <div class="col-md-8">
                                <select name="country" id="country" class="form-control">
                                    <option selected value="-1">- เลือกประเทศ -</option>
                                    @foreach($contry as $show)
                                        <option value="{{ $show->id }}" >{{ $show->title }}-{{ $show->title_en }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3" style="margin-top: 20px">
                            <div class="pull-right">
                                <button class="btn btn-success" type="button" id="addItemInformation"><i class="fa fa-plus"></i> เพิ่ม</button>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 20px ; display: none" id="showErrorEducation">
                            <p class="text-danger text-center">** กรุณากรอกข้อมูลให้ครบถ้วน **</p>
                        </div>

                    </div>

                    <hr>
                    <h3 style="margin-top: 15px">ประวัติการศึกษา</h3>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table class="table table-striped" >
                            <thead>
                            <tr class="bg-primary text-center" >
                                <th class="text-center">No.</th>
                                <th class="text-center">ปีที่สำเร็จ</th>
                                <th class="text-center">วุฒิการศึกษา</th>
                                <th class="text-center">สาขา</th>
                                <th class="text-center">ชื่อสถานศึกษา</th>
                                <th class="text-center">ประเทศ</th>
                                <th class="text-center">เครื่องมือ</th>
                            </tr>
                            </thead>
                            <tbody id="information">

                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>

                    <input type="hidden" name="auditor" value="{{$auditor->id}}">
                    <input type="hidden" name="education_history" id="education_history">

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
        $('#education').on('change',function () {
            education = $("#education :selected").text();
        });

        $('#addItemInformation').on('click', function () {
            const year = $('#year').val();
            const major = $('#major').val();
            const school = $('#school').val();
            const country = $('#country').val();
            const contry_show = $('#country :selected').text();
            const token = Math.random().toString(36).substring(7);
            console.log(education);
            if (year !== "" && major !== "" && school !== "" && country !== "") {
                $('#showErrorEducation').fadeOut();
                var obj_education = {contry_show:contry_show,year:year,level_education:education,major_education:major,school_name:school,country:country,token:token};
                arr_education.push(obj_education);
                getToTableEdu();
                $('#education').val(0).change();
                $('#year').val("");
                $('#major').val("");
                $('#education').val("").change();
                $('#school').val("");
                $('#country').val("-1").change();
            }
            else {
                $('#showErrorEducation').fadeIn();
            }
        })

        $(document).on('click','.clickEducation',function () {
            console.log($(this).attr('id'));
            let this_remove_edu = $(this).attr('id');
            let find_edu = arr_education.find(value => value.token === this_remove_edu);
            var index_edu = arr_education.indexOf(find_edu);
            arr_education.splice(index_edu,1);
            getToTableEdu();
            $('#year').val("");
            $('#major').val("");
            $('#education').val("").change();
            $('#school').val("");
            $('#country').val("");

        })

        $(document).on('click','.clickEditEducation',function () {
            console.log(arr_education);
            let this_remove_edu = $(this).attr('id');
            let find_edu = arr_education.find(value => value.token === this_remove_edu);
            var valueEducation ;
            if (find_edu['level_education'] === "ป.ตรี"){
                valueEducation = 1;
            }
            else if (find_edu['level_education'] === "ป.โท"){
                valueEducation = 2;
            }
            else if (find_edu['level_education'] === "ป.เอก"){
                valueEducation = 3;
            }
            $('#year').val(find_edu['year']);
            $('#major').val(find_edu['major_education']);
            $('#school').val(find_edu['school_name']);
            $('#country').val(find_edu['country']).change();
            $('#education').val(valueEducation).change();
            var index_edu = arr_education.indexOf(find_edu);
            arr_education.splice(index_edu,1);
            getToTableEdu();

        })

        function getToTableEdu(){
            var count_edu = 1;
            $('#information').empty();
            $.each(arr_education,function (index,value) {
                $('#information').append('<tr>' +
                    '<td class="text-center">'+count_edu+'.</td>' +
                    '<td class="text-center">'+value.year+'</td>' +
                    '<td class="text-center">'+value.level_education+'</td>' +
                    '<td>'+value.major_education+'</td>' +
                    '<td>'+value.school_name+'</td>' +
                    '<td class="text-center">'+value.contry_show+'</td>' +
                    '<td class="text-center">' +
                        '<button class="btn btn-primary btn-xs clickEditEducation" type="button" id='+value.token+'><i class="fa fa-pencil-square-o " aria-hidden="true" data-toggle="tooltip" title="Edit"> </i></button>' +
                        '<button class="btn btn-danger btn-xs clickEducation" type="button" id='+value.token+'><i class="fa fa-trash-o " aria-hidden="true" data-toggle="tooltip" title="Delete"> </i></button>' +
                    '</td>' +
                    '</tr>');
                count_edu++;

            })
        }


        $(document).ready(function () {
            // ดึง Education มาแก้ไข
            arr_education = {!! $educations !!};
            getToTableEdu();

            $('#submit_form').on('click',function () {
                $('#education_history').val(JSON.stringify(arr_education));
                $('#commentForm').submit();
            })

        })
    </script>
@endpush
