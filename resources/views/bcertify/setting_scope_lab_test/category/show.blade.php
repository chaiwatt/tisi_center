@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หมวดหมู่ #{{ $testBranchCategory->name }}</h3>
                    @can('view-'.str_slug('bcertify-scope-lab-test'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/setting_scope_lab_test/category/'.$testBranchCategory->testBranch->id) }}">
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
                                  <td>{{ $testBranchCategory->id }}</td>
                              </tr>
                              <tr>
                                <th> หมวดหมู่: </th>
                                <td> {{ $testBranchCategory->name }} </td>
                              </tr>
                              <tr>
                                <th> หมวดหมู่ Eng: </th>
                                <td> {{ $testBranchCategory->name_eng }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $testBranchCategory->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
