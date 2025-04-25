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
            <div class="white-box">
                <a class="btn btn-success pull-right" href="{{route('bcertify.auditor')}}">
                    <i class="icon-arrow-left-circle"></i> กลับ
                </a>
                <h3>รายละเอียดข้อมูลผู้ตรวจประเมิน</h3>
                <div class="clearfix"></div>
                <hr>
                <div class="col-md-12">
                    <div class="col-md-5 m-t-15">
                        <div class="col-md-4">
                            <p class="m-t-5 text-nowrap setTextColor text-right">เลขทะเบียนผู้ประเมิน:</p>
                        </div>
                        <div class="col-md-8">
                            <input type="text" readonly="" class="text-center form-control" value="{{$information->number_auditor}}">
                        </div>
                    </div>
                    <div class="col-md-7"></div>
                </div>
                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">ชื่อ - นามสกุล (TH):</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->title_th}}{{$information->fname_th}} {{$information->lname_th}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">ชื่อ - นามสกุล (EN):</p>
                        </div>
                        <div class="col-md-8">
                            <p style="text-transform: uppercase">{{$information->title_en}}{{$information->fname_en}} {{$information->lname_en}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">ที่อยู่:</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->address??'n/a'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">จังหวัด:</p>
                        </div>
                        <div class="col-md-8">
                            @php $provice = \App\Models\Basic\Province::where('PROVINCE_ID',$information->province_id)->first() @endphp
                            <p>{{$provice->PROVINCE_NAME??'n/a'}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">อำเภอ/เขต:</p>
                        </div>
                        <div class="col-md-8">
                            @php $amphur = \App\Models\Basic\Amphur::where('AMPHUR_ID',$information->amphur_id)->first() @endphp
                            <p>{{$amphur->AMPHUR_NAME??'n/a'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">ตำบล/แขวง:</p>
                        </div>
                        <div class="col-md-8">
                            @php $district = \App\Models\Basic\District::where('DISTRICT_ID',$information->district_id)->first() @endphp
                            <p>{{$district->DISTRICT_NAME??'n/a'}}</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">เบอร์โทรศัพท์:</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->tel??'n/a'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">E-mail:</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->email??'n/a'}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">หน่วยงาน:</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->department->title??'n/a'}}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">ตำแหน่ง:</p>
                        </div>
                        <div class="col-md-8">
                            <p>{{$information->position??'n/a'}}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 m-t-20">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <p class="setTextColor text-right">เจ้าหน้าที่ AB:</p>
                        </div>
                        <div class="col-md-8">
                            @if ($information->status_ab == 0)
                                <p>ไม่เป็น</p>
                            @else
                                <div class="col-md-4">
                                    <p>เป็น</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="setTextColor">กลุ่ม:</p>
                                </div>
                                <div class="col-md-4">
                                    @if ($information->group_id == 1)
                                        <p>CB</p>
                                    @elseif($information->group_id == 2)
                                        <p>IB</p>
                                    @elseif($information->group_id == 3)
                                        <p>Lab 1</p>
                                    @elseif($information->group_id == 4)
                                        <p>Lab 2</p>
                                    @elseif($information->group_id == 5)
                                        <p>Lab 3</p>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="white-box m-t-20">
                <div class="pull-right"><a href="{{route('bcertify.auditor.edit.education',['token'=>$information->token])}}" class="btn btn-outline-primary"><i class="mdi mdi-settings"></i> จัดการการศึกษา</a></div>
                <h3>รายละเอียดการศึกษา</h3>
                <div class="clearfix"></div>
                <hr>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" >
                            <thead>
                            <tr class="bg-primary text-center text-white" >
                                <th class="text-center">No.</th>
                                <th class="text-center">ปีที่สำเร็จ</th>
                                <th class="text-center">วุฒิการศึกษา</th>
                                <th class="text-center">สาขา</th>
                                <th class="text-center">ชื่อสถานศึกษา</th>
                                <th class="text-center">ประเทศ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $number_edu = 1 @endphp
                                @foreach($educations as $education)
                                    @php $contry = \Illuminate\Support\Facades\DB::table('tb_country')->select('*')->where('id',$education->country)->first() @endphp
                                    <tr>
                                        <td class="text-center">{{$number_edu}}.</td>
                                        <td class="text-center">{{$education->year}}</td>
                                        <td class="text-center">{{$education->level_education}}</td>
                                        <td class="text-center">{{$education->major_education}}</td>
                                        <td class="text-center">{{$education->school_name}}</td>
                                        <td class="text-center">{{$contry->title_en}}</td>
                                    </tr>
                                    @php $number_edu++ @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="white-box m-t-20">
                <div class="pull-right"><a href="{{route('bcertify.auditor.edit.training',['token'=>$information->token])}}" class="btn btn-outline-primary"><i class="mdi mdi-settings"></i> จัดการการฝึกอบรม</a></div>
                <h3>รายละเอียดการฝึกอบรม</h3>
                <hr>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" >
                            <thead>
                            <tr class="bg-primary text-center" >
                                <th class="text-center">No.</th>
                                <th class="text-center">วันที่อบรม</th>
                                <th class="text-center">ชื่อหลักสูตร</th>
                                <th class="text-center">หน่วยงาน</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $number_train = 1 @endphp
                            @foreach($trainings as $training)
                                <tr>
                                    <td class="text-center">{{$number_train}}.</td>
                                    <td class="text-center">{{\Carbon\Carbon::parse($training->start_training)->format('d M Y')}} - {{\Carbon\Carbon::parse($training->end_training)->format('d M Y')}}</td>
                                    <td class="text-center">{{$training->course_name}}</td>
                                    <td class="text-center">{{$training->department_name}}</td>
                                </tr>@php $number_train++ @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="white-box m-t-20">
                @php
                    $arr_type = array();
                    foreach ($expertises as $exper){
                        if (!in_array($exper->type_of_assessment,$arr_type)){
                            array_push($arr_type,$exper->type_of_assessment);
                        }
                    }
                @endphp
                <div class="pull-right"><a href="{{route('bcertify.auditor.edit.expertise',['token'=>$information->token])}}" class="btn btn-outline-primary"><i class="mdi mdi-settings"></i> จัดการความเชี่ยวชาญ</a></div>
                <h3>รายละเอียดความเชี่ยวชาญ</h3>
                <hr>
                <div class="col-md-12">
                    @foreach($arr_type as $check)
                        @if (1 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ข้อมูลความเชี่ยวชาญ (CB)</h4>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped" >
                                    <thead>
                                    <tr class="bg-primary text-center" >
                                        <th class="text-center">No.</th>
                                        <th class="text-center">มาตรฐาน</th>
                                        <th class="text-center">สาขา</th>
                                        <th class="text-center">ขอบข่าย</th>
                                        <th class="text-center">สถานะผู้ตรวจประเมิน</th>
                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $cb_count = 1 @endphp
                                    @foreach($expertises as $cb)
                                        @if ($cb->type_of_assessment == 1)
                                            <tr>
                                                <td class="text-center">{{$cb_count}}.</td>
                                                <td class="text-center">{{$cb->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $cb->branch_path::where('id',$cb->branch_id)->first();
                                                    $split_status = explode(',',$cb->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title}}</td>
                                                <td class="text-center">{{$cb->scope_name}}</td>
                                                <td class="text-center">{{implode(',',$arr_status)}}</td>
                                                <td class="text-center">{{$cb->specialized_expertise}}</td>
                                            </tr>
                                            @php $cb_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(2 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ข้อมูลความเชี่ยวชาญ (IB)</h4>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped" >
                                    <thead>
                                    <tr class="bg-primary text-center" >
                                    <tr class="bg-primary text-center" >
                                        <th class="text-center">No.</th>
                                        <th class="text-center">มาตรฐาน</th>
                                        <th class="text-center">ประเภทหน่วยตรวจ</th>
                                        <th class="text-center">หมวดหมู่การตรวจ</th>
                                        <th class="text-center">สาขา</th>
                                        <th class="text-center">สถานผู้ตรวจประเมิน</th>
                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    </tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $ib_count = 1 @endphp
                                    @foreach($expertises as $ib)
                                        @if ($ib->type_of_assessment == 2)
                                            <tr>
                                                <td class="text-center">{{$ib_count}}.</td>
                                                <td class="text-center">{{$ib->formula->title}}</td>
                                                <td class="text-center">{{$ib->type['title']}}</td>
                                                <td class="text-center">{{$ib->category['title']}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $ib->branch_path::where('id',$ib->branch_id)->first();
                                                    $split_status = explode(',',$ib->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);
                                                    }
                                                @endphp

                                                <td class="text-center">{{$find_branch->title}}</td>
                                                <td class="text-center">{{implode(',',$arr_status)}}</td>
                                                <td class="text-center">{{$ib->specialized_expertise}}</td>
                                            </tr>
                                            @php $ib_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif (3 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ความเชี่ยวชาญประเภทการตรวจ LAB สอบเทียบ</h4>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped" >
                                    <thead>
                                    <tr class="bg-primary text-center" >
                                        <th class="text-center">No.</th>
                                        <th class="text-center">มาตรฐาน</th>
                                        <th class="text-center">สาขา</th>
                                        <th class="text-center">รายการสอบเทียบ</th>
                                        <th class="text-center">สถานะผู้ตรวจประเมิน</th>
                                        <th class="text-center">ความเชี่ยวชาญเฉพาะด้าน</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $lab_exam_count = 1 @endphp
                                    @foreach($expertises as $labexam)
                                        @if ($labexam->type_of_assessment == 3)
                                            <tr>
                                                <td class="text-center">{{$lab_exam_count}}.</td>
                                                <td class="text-center">{{$labexam->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $labexam->branch_path::where('id',$labexam->branch_id)->first();
                                                    $find_list = \App\Models\Bcertify\CalibrationItem::where('id',$labexam->calibration_list)->where('state',1)->first();
                                                    $split_status = explode(',',$labexam->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title??'n/a'}}</td>
                                                <td class="text-center">{{$find_list->title??'n/a'}}</td>
                                                <td class="text-center">{{implode(',',$arr_status)}}</td>
                                                <td class="text-center">{{$labexam->specialized_expertise}}</td>
                                            </tr>
                                            @php $lab_exam_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif (4 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ความเชี่ยวชาญประเภทการตรวจ LAB ทดสอบ</h4>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $lab_test_count = 1 @endphp
                                    @foreach($expertises as $labtest)
                                        @if ($labtest->type_of_assessment == 4)
                                            <tr>
                                                <td class="text-center">{{$lab_test_count}}.</td>
                                                <td class="text-center">{{$labtest->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $labtest->branch_path::where('id',$labtest->branch_id)->first();
                                                    $find_product = \App\Models\Bcertify\ProductItem::where('id',$labtest->product)->where('state',1)->first();
                                                    $find_test_list = \App\Models\Bcertify\TestItem::where('id',$labtest->test_list)->where('state',1)->first();
                                                    $split_status = explode(',',$labtest->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title??'n/a'}}</td>
                                                <td class="text-center">{{$find_product->title??'n/a'}}</td>
                                                <td class="text-center">{{$find_test_list->title??'n/a'}}</td>
                                                <td class="text-center">{{implode(',',$arr_status)}}</td>
                                                <td class="text-center">{{$labtest->specialized_expertise}}</td>
                                            </tr>
                                            @php $lab_test_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach


                </div>
                <div class="clearfix"></div>
            </div>

            <div class="white-box m-t-20">
                <div class="pull-right"><a href="{{route('bcertify.auditor.edit.work',['token'=>$information->token])}}" class="btn btn-outline-primary"><i class="mdi mdi-settings"></i> จัดการประสบการณ์การทำงาน</a></div>
                <h3>รายละเอียดประสบการณ์การทำงาน</h3>
                <hr>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped" >
                            <thead>
                            <tr class="bg-primary text-center" >
                                <th class="text-center">No.</th>
                                <th class="text-center">ปีที่ทำงาน</th>
                                <th class="text-center">ตำแหน่ง</th>
                                <th class="text-center">หน่วยงาน</th>
                                <th class="text-center">บทบาทหน้าที่</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $number_work = 1 @endphp
                            @foreach($works as $work)
                                <tr>
                                    <td class="text-center">{{$number_work}}.</td>
                                    <td class="text-center">{{$work->year}}</td>
                                    <td class="text-center">{{$work->position}}</td>
                                    <td class="text-center">{{$work->department}}</td>
                                    <td class="text-center">{{$work->role}}</td>
                                </tr>
                                @php $number_work++ @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>



            <div class="white-box m-t-20">
                @php
                    $arr_type = array();
                    foreach ($assessments as $assess){
                        if (!in_array($assess->type_of_assessment,$arr_type)){
                            array_push($arr_type,$assess->type_of_assessment);
                        }
                    }
                @endphp
                <div class="pull-right"><a href="{{route('bcertify.auditor.edit.assessment',['token'=>$information->token])}}" class="btn btn-outline-primary"><i class="mdi mdi-settings"></i> จัดการประสบการณ์การตรวจประเมิน</a></div>
                <h3>รายละเอียดประสบการณ์การตรวจประเมิน</h3>
                <hr>
                <div class="col-md-12">
                    @foreach($arr_type as $check)
                        @if (1 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ประสบการณ์การตรวจประเมิน CB</h4>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $cb_count = 1 @endphp
                                    @foreach($assessments as $cb)
                                        @if ($cb->type_of_assessment == 1)
                                            <tr>
                                                <td class="text-center">{{$cb_count}}.</td>
                                                <td class="text-center">{{\Carbon\Carbon::parse($cb->start_date)->format('d M Y')}}</td>
                                                <td class="text-center">{{$cb->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $cb->branch_path::where('id',$cb->branch_id)->first();
                                                    $split_status = explode(',',$cb->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title}}</td>
                                                <td class="text-center">{{$cb->scope_name}}</td>
                                                <td class="text-center">{{$cb->role}}</td>
                                            </tr>
                                            @php $cb_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(2 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ประสบการณ์การตรวจประเมิน IB</h4>
                            <div class="clearfix"></div>
                            <div class="table-responsive">
                                <table class="table table-striped" >
                                    <thead>
                                    <tr class="bg-primary text-center" >
                                    <tr class="bg-primary text-center" >
                                        <th class="text-center">No.</th>
                                        <th class="text-center">วันที่ตรวจ</th>
                                        <th class="text-center">มาตรฐาน</th>
                                        <th class="text-center">ประเภทหน่วยตรวจ</th>
                                        <th class="text-center">หมวดหมู่การตรวจ</th>
                                        <th class="text-center">สาขา</th>
                                        <th class="text-center">บทบาทหน้าที่</th>
                                    </tr>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $ib_count = 1 @endphp
                                    @foreach($assessments as $ib)
                                        @if ($ib->type_of_assessment == 2)
                                            <tr>
                                                <td class="text-center">{{$ib_count}}.</td>
                                                <td class="text-center">{{\Carbon\Carbon::parse($ib->start_date)->format('d M Y')}}</td>
                                                <td class="text-center">{{$ib->formula->title}}</td>
                                                <td class="text-center">{{$ib->type['title']}}</td>
                                                <td class="text-center">{{$ib->category['title']}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $ib->branch_path::where('id',$ib->branch_id)->first();
                                                    $split_status = explode(',',$ib->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);
                                                    }
                                                @endphp

                                                <td class="text-center">{{$find_branch->title}}</td>
                                                <td class="text-center">{{$ib->role}}</td>
                                            </tr>
                                            @php $ib_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif (3 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ความเชี่ยวชาญประเภทการตรวจ LAB สอบเทียบ</h4>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $lab_exam_count = 1 @endphp
                                    @foreach($assessments as $labexam)
                                        @if ($labexam->type_of_assessment == 3)
                                            <tr>
                                                <td class="text-center">{{$lab_exam_count}}.</td>
                                                <td class="text-center">{{\Carbon\Carbon::parse($labexam->start_date)->format('d M Y')}}</td>
                                                <td class="text-center">{{$labexam->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $labexam->branch_path::where('id',$labexam->branch_id)->first();
                                                    $find_list = \App\Models\Bcertify\CalibrationItem::where('id',$labexam->calibration_list)->where('state',1)->first();
                                                    $split_status = explode(',',$labexam->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title??'n/a'}}</td>
                                                <td class="text-center">{{$find_list->title??'n/a'}}</td>
                                                <td class="text-center">{{$labexam->role}}</td>
                                            </tr>
                                            @php $lab_exam_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif (4 == $check)
                            <h4 class="col-md-12" style="margin-top: 15px; padding: 0px">ความเชี่ยวชาญประเภทการตรวจ LAB ทดสอบ</h4>
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
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $lab_test_count = 1 @endphp
                                    @foreach($assessments as $labtest)
                                        @if ($labtest->type_of_assessment == 4)
                                            <tr>
                                                <td class="text-center">{{$lab_test_count}}.</td>
                                                <td class="text-center">{{\Carbon\Carbon::parse($labtest->start_date)->format('d M Y')}}</td>
                                                <td class="text-center">{{$labtest->formula->title}}</td>
                                                @php
                                                    $arr_status = array();
                                                    $find_branch = $labtest->branch_path::where('id',$labtest->branch_id)->first();
                                                    $find_product = \App\Models\Bcertify\ProductItem::where('id',$labtest->product)->where('state',1)->first();
                                                    $find_test_list = \App\Models\Bcertify\TestItem::where('id',$labtest->test_list)->where('state',1)->first();
                                                    $split_status = explode(',',$labtest->auditor_status);
                                                    foreach ($split_status as $data){
                                                        $show = \App\Models\Bcertify\StatusAuditor::where('id',$data)->first();
                                                        array_push($arr_status,$show->title);

                                                    }
                                                @endphp
                                                <td class="text-center">{{$find_branch->title}}</td>
                                                <td class="text-center">{{$find_product->title}}</td>
                                                <td class="text-center">{{$find_test_list->title}}</td>
                                                <td class="text-center">{{$labtest->role}}</td>
                                            </tr>
                                            @php $lab_test_count++ @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach


                </div>
                <div class="clearfix"></div>
            </div>
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
@endpush
