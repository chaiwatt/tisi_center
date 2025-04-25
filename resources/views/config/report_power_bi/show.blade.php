@extends('layouts.master')

@section('content')

    <style type="text/css" id="css-after-load">

    </style>
    <div id="tmp-after-load" class="hide">

    </div>

    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด ตั้งค่า รายงาน Power BI {{ $item->id }}</h3>
                    @can('view-'.str_slug('SsoUrl'))
                        <a class="btn btn-success pull-right" href="{{ url('/config/report-power-bi') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table">
                            <tbody>
                                <tr>
                                    <th class="col-md-4">ID</th>
                                    <td class="col-md-8">{{ $item->id }}</td>
                                </tr>
                                <tr>
                                    <th> ชื่อรายงาน </th>
                                    <td> {{ $item->title }} </td>
                                </tr>
                                <tr>
                                    <th> กลุ่มรายงาน </th>
                                    <td> {!! !is_null($item->group) ? $item->group->title : '<i class="text-muted">ไม่มีกลุ่ม</i>' !!} </td>
                                </tr>
                                <tr>
                                    <th> URL </th>
                                    <td style="word-wrap: anywhere;"> {{ $item->url }} </td>
                                </tr>
                                <tr>
                                    <th> สถานะ </th>
                                    <td> {!! $item->state=='1'?'<span class="label label-success">เปิดใช้งาน</span>':'<span class="label label-danger">ปิดใช้งาน</span>' !!} </td>
                                </tr>
                                <tr>
                                    <th> ผู้สร้าง </th>
                                    <td> {{ $item->createdName }} </td>
                                </tr>
                                <tr>
                                    <th> วันเวลาที่สร้าง </th>
                                    <td> {{ HP::DateTimeThai($item->created_at) }} </td>
                                </tr>
                                <tr>
                                    <th> ผู้แก้ไข </th>
                                    <td> {{ $item->updatedName }} </td>
                                </tr>
                                <tr>
                                    <th> วันเวลาที่แก้ไข </th>
                                    <td> {{ HP::DateTimeThai($item->updated_at) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-top"> กลุ่มผู้ใช้งาน: </th>
                                    <td>
                                        @php
                                            $roles = App\Role::where('label', 'staff')->get();
                                            $role_checkeds = isset($item) ? $item->roles->pluck('role_id')->toArray() : [];
                                        @endphp

                                        <div>
                                            {!! $item->role_all=='1' ? '<i class="fa fa-check-square text-success"></i>' : '&nbsp;' !!}
                                            <label><b>กลุ่มผู้ใช้งานทั้งหมด (รวมที่จะสร้างใหม่ด้วย)</b></label>
                                        </div>

                                        @foreach ($roles as $role)
                                            <div>
                                                {!! in_array($role->id, $role_checkeds) || $item->role_all=='1' ? '<i class="fa fa-check-square text-success"></i>' : '&nbsp;&nbsp;' !!}
                                                <label>{{ $role->name }}</label>
                                            </div>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
<script>
    $(document).ready(function () {
        //สร้าง box div เพื่อดึงค่าสีตาม class css และเอาไปสร้างเป็น css ชุดใหม่ใน css-after-load
        var css_colors = Array();
        $('.bg-color').each(function(index, el) {
            $('#tmp-after-load').append('<div class="'+$(el).data('color')+'"></div>');
            var color = '.' + $(el).data('color') + '{';
                color += ' color: ' + $('#tmp-after-load').find('.'+$(el).data('color')).css('background-color') + ' !important;';
                color += ' background-color: transparent !important;';
                color += '}';
            css_colors.push(color);
        });
        $('#css-after-load').html(css_colors.join(' '));
    });
</script>
@endpush
