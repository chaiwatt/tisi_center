@extends('layouts.master')
@push('css')
    <style>
        .customerPage {
            background-color: white;
            padding: 10px 0px;
            border-radius: 10px;
            border: 1px solid #dcdbd8;
            transition: 0.3s;
            /*height: 140px;*/
            vertical-align: middle;
            /*max-height: 140px;*/
        }

        .customerPage:hover {
            background-color: #f5f5f5;
        }

        .gridBlue{
            border-top: 2px solid rgba(9, 132, 227, 0.5);
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="white-box">
            <h3 class="box-title pull-left">รายละเอียดข้อมูลคณะกรรมการเฉพาะด้าน {{ $committeeSpecial->id }}</h3>
            @can('view-'.str_slug('department'))
                <a class="btn btn-success pull-right" href="{{ url('committee') }}">
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                </a>
            @endcan
            <div class="clearfix"></div>
            <div class="table-responsive m-t-10">
                <table class="table table">
                    <tbody>
                    <tr>
                        <th class="col-xs-3"> เรื่อง</th>
                        <td>{{ $committeeSpecial->committee_group ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3"> ชื่อคณะกรรมการ</th>
                        <td>{{ $committeeSpecial->faculty ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3"> คณะที่</th>
                        <td>{{ $committeeSpecial->faculty_no ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3"> ชื่อคณะกรรมการ</th>
                        <td>{{ $committeeSpecial->committee_group ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="col-xs-3">  กลุ่มผลิตภัณฑ์/สาขา</th>
                        <td> {{  !empty($committeeSpecial->product_group_to->title) ? $committeeSpecial->product_group_to->title : '-' }} </td>
                    </tr>
                    <tr>
                        <th> เลขที่คำสั่งแต่งตั้ง</th>
                        <td> {{ $committeeSpecial->appoint_number ?? '-' }} </td>
                    </tr>
                    <tr>
                        <th> วันที่มีคำสั่ง</th>
                        <td> {{ \Carbon\Carbon::parse($committeeSpecial->appoint_date)->format('d/m/Y') ?? '-' }} </td>
                    </tr>
                    <tr>
                        <th> หมายเหตุ</th>
                        <td> {{ $committeeSpecial->message ?? '-' }} </td>
                    </tr>
                    <tr>
                        <th> หนังสือแต่งตั้งคณะกรรมการ </th>
                        <td>
                            @if ($committeeSpecial->authorize_file)
                                <a href="{{ url('committee/authorize/file/'.$committeeSpecial->authorize_file) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" style="font-size:38px; color:red" aria-hidden="true"></i>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th> วันที่บันทึก</th>
                        <td> {{ \Carbon\Carbon::parse($committeeSpecial->created_at)->format('d/m/Y') }} </td>
                    </tr>
                    <tr>
                        <th> ผู้บันทึก</th>
                        <td> {{ $committeeSpecial->user_FullName() }} </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            @php
                $files = \Illuminate\Support\Facades\DB::table('appointment_files')->select('file_path','token','created_at')->where('committee_special_id',$committeeSpecial->id)->get();
            @endphp
            @if ($files->count() > 0)
                <hr>
                <div id="appoint_files_table">
                    <h3 class="m-b-10">ไฟล์แนบอื่นๆ</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary">
                            <tr>
                                <th class="text-white text-center">#</th>
                                <th class="text-white text-center">ชื่อไฟล์</th>
                                <th class="text-white text-center">บันทึกวันที่</th>
                                <th class="text-white text-center">ดาวน์โหลด</th>
                            </tr>
                            </thead>
                            <tbody id="appoint_files_body">
                            @foreach ($files as $file)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td>{{$file->file_path}}</td>
                                    <td class="text-center">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ url('committee/appointment/files/'.$file->file_path) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o" style="font-size:25px; color:red" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            <hr>
            @endif

            @if ($committeeSpecial->in_department->count() > 0)
                <div id="department_table">
                    <h3 class="m-b-15">รายชื่อคณะกรรมการเฉพาะด้าน</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-primary">
                            <tr class="text-nowrap">
                                <th class="text-white text-center">หน่วยงาน</th>
                                <th class="text-white text-center">ชื่อ-สกุล</th>
                                <th class="text-white text-center">ประเภท</th>
                                <th class="text-white text-center">กลุ่มผู้แทน</th>
                                <th class="text-white text-center">ตำแหน่ง</th>
                                <th class="text-white text-center">ที่อยู่</th>
                                <th class="text-white text-center">โทรศัพท์</th>
                                <th class="text-white text-center">โทรสาร</th>
                                <th class="text-white text-center">อีเมลล์</th>
                            </tr>
                            </thead>
                            <tbody id="department_table_tbody">
                            @php
                                $last = null;
                            @endphp
                            @foreach ($committeeSpecial->in_department()->orderBy('department_id','asc')->get() as $department)
                                <tr class="{{$department->get_department()->title != $last && $loop->iteration != 1 ?  'gridBlue':null}}">
                                    <td>
                                        @if ($department->get_department()->title)
                                            {{$department->get_department()->title != $last ? $department->get_department()->title:null}}
                                            @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{$department->name}}</td>
                                    <td class="text-center">{{$department->get_committee_type() == 'ผู้แทนสำรอง' ? $department->get_committee_type().' ลำดับ '.$department->level:$department->get_committee_type()}}</td>
                                    <td>{{$department->represent_group ?? '-'}}</td>
                                    <td>{{$department->position ?? '-'}}</td>
                                    <td>{{$department->address ?? '-'}}</td>
                                    <td>{{$department->tel ?? '-'}}</td>
                                    <td>{{$department->fax ?? '-'}}</td>
                                    <td>{{$department->email ?? '-'}}</td>
                                </tr>
                                @php
                                    $last = $department->get_department()->title;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

