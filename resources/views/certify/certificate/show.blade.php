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
            <h3 class="box-title pull-left">ข้อมูลใบรับรองระบบงาน {{ $certificate->id }}</h3>
            @can('view-'.str_slug('department'))
                <a class="btn btn-danger pull-right" href="{{ url('certificate') }}">
                    <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                </a>
            @endcan
            <div class="clearfix"></div>
            <div class="table-responsive m-t-10">
                <table class="table table">
                    <tbody>
                    <tr>
                        <th class="col-xs-3"> เลขที่ใบคำขอ </th>
                        <td>{{ $certificate->request_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th> ประเภทการตรวจ </th>
                        <td> {{$certificate->assessment_type() ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> ชื่อหน่วยตรวจ/หน่วยรับรอง/ห้องปฏิบัติการ </th>
                        <td> {{$certificate->unit_name ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> สถานภาพห้องปฏิบัติการ </th>
                        <td>
                            <?php $status = $certificate->getLabStatus(); ?>
                            @if ($status)
                                @foreach ($status as $tus)
                                        @if ($loop->iteration < sizeof($status))
                                            <span>{{$tus.' ,'}}&ensp;</span>
                                        @else
                                            <span>{{$tus}} </span>
                                        @endif
                                @endforeach
                                @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th> เลขที่ใบรับรอง </th>
                        <td> {{$certificate->certificate_file_number ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> หมายเลขการรับรอง </th>
                        <td> {{$certificate->certificate_number ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> เลขมาตรฐาน </th>
                        <td> {{$certificate->get_formulaTH_EN() ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> สาขา </th>
                        <td>
                            <?php $branches = $certificate->get_branch()?>
                            @if ($branches)
                                @foreach($branches as $branch)
                                    @if ($loop->iteration < sizeof($branches))
                                            <span>{{$branch->title.' ('.$branch->title_en.')'.' ,'}}&ensp;</span>
                                        @else
                                            <span>{{$branch->title.' ('.$branch->title_en.')'}} </span>
                                    @endif
                                @endforeach
                                @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th> วันที่ออกใบรับรอง </th>
                        <td> {{\Carbon\Carbon::parse($certificate->certified_date)->format('d/m/Y') ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> วันที่ใบรับรองหมดอายุ </th>
                        <td> {{\Carbon\Carbon::parse($certificate->certified_exp)->format('d/m/Y') ?? '-'}} </td>
                    </tr>
                    <tr>
                        <th> เอกสารใบรับรอง </th>
                        <td>
                            @if ($certificate->certificate_file)
                                <a href="{{ url('certificate/files/'.$certificate->certificate_file) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o" style="font-size:38px; color:red" aria-hidden="true"></i>
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th> ข้อมูลใบรับรอง </th>
                        <td> {{$certificate->get_certificateOption()}} </td>
                    </tr>
                    <tr>
                        <th> ผู้บันทึก </th>
                        <td> {{$certificate->user_FullName()}} </td>
                    </tr>
                    <tr>
                        <th> สถานะ </th>
                        <td>
                            @if($certificate->state=='1')
                                เปิด &nbsp;<i class="fa fa-check-circle fa-lg text-success"></i>
                            @else
                                ปิด &nbsp;<i class="fa fa-times-circle fa-lg text-danger"></i>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            @if ($other_files->count() > 0)
                <hr>
                <div id="appoint_files_table">
                    <h3 class="m-b-10">ไฟล์แนบใบอื่นๆ</h3>
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
                            @foreach ($other_files as $file)
                                <tr>
                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center">{{$file->file_path}}</td>
                                    <td class="text-center">{{\Carbon\Carbon::parse($file->created_at)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{url('certificate/files/others/'.$file->file_path)}}" target="_blank">
                                            <i class="fa fa-file-pdf-o" style="font-size:25px; color:red" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection

