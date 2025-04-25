@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด ตั้งค่า URL SSO {{ $ssourl->id }}</h3>
                    @can('view-'.str_slug('SsoUrl'))
                        <a class="btn btn-success pull-right" href="{{ url('/config/sso-url') }}">
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
                                    <td>{{ $ssourl->id }}</td>
                                </tr>
                                <tr>
                                    <th> ชื่อรายงาน </th>
                                    <td> {{ $ssourl->title }} </td>
                                </tr>
                                <tr>
                                    <th> app_name </th>
                                    <td> {{ $ssourl->app_name }} </td>
                                </tr>
                                <tr>
                                    <th> รายละเอียด </th>
                                    <td> {{ $ssourl->details }} </td>
                                </tr>
                                <tr>
                                    <th> URL </th>
                                    <td> {{ $ssourl->urls }} </td>
                                </tr>
                                <tr>
                                    <th> กลุ่ม URL </th>
                                    <td> {!! !is_null($ssourl->group) ? $ssourl->group->title : '<i class="text-muted">ไม่มีกลุ่ม</i>' !!} </td>
                                </tr>
                                <tr>
                                    <th> ไอคอน </th>
                                    <td> {{ $ssourl->icons }} </td>
                                </tr>
                                <tr>
                                    <th> สี </th>
                                    <td> {{ $ssourl->colors }} </td>
                                </tr>
                                <tr>
                                    <th> วิธีไป URL ปลายทาง </th>
                                    <td> {{ App\Models\Config\SettingSystem::transfer_methods()[$ssourl->transfer_method] }} </td>
                                </tr>
                                <tr>
                                    <th> สถานะ </th>
                                    <td> {!! $ssourl->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                                </tr>
                                <tr>
                                    <th> ไม่ให้สาขาใช้งาน </th>
                                    <td> {!! $ssourl->branch_block=='1'?'<span class="label label-danger">ใช่ (ไม่ให้ใช้งาน)</span>':'<span class="label label-success">ไม่ (ให้ใช้งาน)</span>' !!} </td>
                                </tr>
                                <tr>
                                    <th> ผู้สร้าง </th>
                                    <td> {{ $ssourl->createdName }} </td>
                                </tr>
                                <tr>
                                    <th> วันเวลาที่สร้าง </th>
                                    <td> {{ HP::DateTimeThai($ssourl->created_at) }} </td>
                                </tr>
                                <tr>
                                    <th> ผู้แก้ไข </th>
                                    <td> {{ $ssourl->updatedName }} </td>
                                </tr>
                                <tr>
                                    <th> วันเวลาที่แก้ไข </th>
                                    <td> {{ HP::DateTimeThai($ssourl->updated_at) }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
