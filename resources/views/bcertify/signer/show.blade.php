@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดผู้ลงนาม {{ $signer->id }}</h3>
                    @can('view-'.str_slug('signer'))
                        <a class="btn btn-success pull-right" href="{{ url('/bcertify/signer') }}">
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
                                  <td>{{ $signer->id }}</td>
                              </tr>
                              <tr>
                                <th> ชื่อ-สกุล ผู้ลงนาม: </th>
                                <td> {{ $signer->title }} </td>
                              </tr>
                              <tr>
                                <th> ตำแหน่ง: </th>
                                <td> {{ $signer->position1 }} </td>
                              </tr>
                              <tr>
                                <th>  </th>
                                <td> {{ $signer->position2 }} </td>
                              </tr>
                              <tr>
                                <th>  </th>
                                <td> {{ $signer->position3 }} </td>
                              </tr>
                              <tr>
                                <th class="text-top"> ไฟล์ลายเซ็น: </th>
                                <td class="col-md-8">
                                  @if (HP::checkFileStorage($attach_path.$signer->signature_img))
                                      <a href="{{ HP::getFileStorage($attach_path.$signer->signature_img) }}" target="_blank">
                                        <img class="img-responsive img-thumbnail" src="{{ HP::getFileStorage($attach_path.$signer->signature_img) }}" width="60%" />
                                      </a>
                                  @endif
                                </td>
                              </tr>
                              <tr>
                                <th> สถานะ </th>
                                <td> {!! $signer->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                              </tr>
                              <tr>
                                <th> ผู้สร้าง </th>
                                <td> {{ $signer->createdName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่สร้าง </th>
                                <td> {{ HP::DateTimeThai($signer->created_at) }} </td>
                              </tr>
                              <tr>
                                <th> ผู้แก้ไข </th>
                                <td> {{ $signer->updatedName }} </td>
                              </tr>
                              <tr>
                                <th> วันเวลาที่แก้ไข </th>
                                <td> {{ HP::DateTimeThai($signer->updated_at) }} </td>
                              </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
