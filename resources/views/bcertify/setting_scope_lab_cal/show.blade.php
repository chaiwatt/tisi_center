@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ขอบข่าย Lab (สอบเทียบ) #{{ $calibrationBranch->title }}</h3>
                    @can('view-'.str_slug('bcertify-scope-lab-cal'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/setting_scope_lab_cal') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                              <tr>
                                  <th>ID</th>
                                  <td>{{ $calibrationBranch->id }}</td>
                              </tr>
                              <tr>
                                <th> สาขาการรับรอง: </th>
                                <td> {{ $calibrationBranch->title }} </td>
                              </tr>
                              <tr>
                                <th> สาขาการรับรอง Eng: </th>
                                <td> {{ $calibrationBranch->title_en }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $calibrationBranch->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
