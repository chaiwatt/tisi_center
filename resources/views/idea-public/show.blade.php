@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด {{ $ideapublic->id }}</h3>
                    @can('view-'.str_slug('idea-public'))
                        <a class="btn btn-success pull-right" href="{{ url('/idea-public') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                            <tr>
                                <th> ผลิตภัณฑ์</th>
                                <td> {{ $ideapublic->product }} </td>
                            </tr>
                            <tr>
                                <th> กลุ่มผลิตภัณฑ์/สาขา</th>
                                <td> {{ $ideapublic->productGroupName }} </td>
                            </tr>
                            <tr>
                                <th> รายละเอียด</th>
                                <td> {{ $ideapublic->description }} </td>
                            </tr>
                            <tr>
                                <th> มาตรฐานอ้างอิง</th>
                                <td> {{ $ideapublic->standards_ref }} </td>
                            </tr>
                            <tr>
                                <th style="vertical-align: top"> ข้อมูลเพิ่มเติม</th>
                                <td>
                                    @foreach ($attachs as $key => $attach)
                                        <div class="row" style="margin: 5px 0px">
                                            <div class="col-md-6">
                                                @if($attach->file_name!='' && HP::checkFileStorage($attach_path.$attach->file_name))
                                                    <a href="{{ HP::getFileStorage($attach_path.$attach->file_name) }}" target="_blank" class="view-attach btn btn-info btn-sm"><i class="fa fa-search"></i> {{ $attach->file_client_name }}</a>
                                                @endif
                                                <span>คำอธิบายเอกสาร : {{ $attach->file_note }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th> ชื่อ-สกุล ผู้ให้ข้อมูล</th>
                                <td> {{ $ideapublic->commentator }} </td>
                            </tr>
                            <tr>
                                <th> เบอร์โทร</th>
                                <td> {{ $ideapublic->tel }} </td>
                            </tr>
                            <tr>
                                <th> E-mail</th>
                                <td> {{ $ideapublic->email }} </td>
                            </tr>
                            <tr>
                                <th> หน่วยงาน</th>
                                <td> {{ $ideapublic->departmentName }} </td>
                            </tr>
                            <tr>
                                <th> วันที่บันทึก</th>
                                <td> {{ HP::DateTimeThai($ideapublic->created_at) }} </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
