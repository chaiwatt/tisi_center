@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดหมวดหมู่ผลิตภัณฑ์ {{ $product_category->id }}</h3>
                    @can('view-'.str_slug('product_category'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/product_category') }}">
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
                                  <td>{{ $product_category->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อหมวดหมู่ผลิตภัณฑ์ (TH): </th>
                                <td> {{ $product_category->title }} </td>
                              </tr>
                              <tr>
                                <th> ชื่อหมวดหมู่ผลิตภัณฑ์ (EN): </th>
                                <td> {{ $product_category->title_en }} </td>
                              </tr>
                              <tr>
                                <th> มาตรฐาน: </th>
                                <td> {{ $product_category->formula->title }} </td>
                              </tr>
                              <tr>
                                <th> สาขาทดสอบ: </th>
                                <td> {{ $product_category->test_branch->title }} </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $product_category->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $product_category->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($product_category->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $product_category->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($product_category->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
