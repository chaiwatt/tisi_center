@extends('layouts.master')

    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@push('css')
    <style>

    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท (Elicense) # {{ $usergroup->id }}</h3>
                    @can('view-'.str_slug('report-roles'))
                        <div class="pull-right">
                            <a class="btn btn-success" href="{{ url('/report/elicense-roles/export_role?role_id='.$usergroup->id) }}">
                                Export
                            </a>
                            <a class="btn btn-info" href="{{ url('/report/elicense-roles/') }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                            </a>
                        </div>
                    @endcan

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <center>
                                <h2 class="text-dark">กลุ่มบทบาท : {!! $usergroup->title !!}</h2>
                            </center>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-sm-12">

                            @php
                                $ListMenu         = $usergroup->racl_view()
                                                                ->whereHas('racl_permission_list', function($query) use($usergroup){
                                                                    $query->where('group_id',$usergroup->id)
                                                                            ->where(function($query){
                                                                                $query->where('stateaccess', 1)
                                                                                        ->Orwhere('stateadd', 1)
                                                                                        ->Orwhere('stateedit', 1)
                                                                                        ->Orwhere('statedelete', 1)
                                                                                        ->Orwhere('stateeditown', 1)
                                                                                        ->Orwhere('stateprint', 1)
                                                                                        ->Orwhere('stateexcel', 1)
                                                                                        ->Orwhere('statecopy', 1)
                                                                                        ->Orwhere('stateapply', 1)
                                                                                        ->Orwhere('statecheckout', 1);
                                                                            });
                                                                })
                                                                ->get()
                                                                ->groupby('ref_id') ;
                                $component        = App\Models\Elicense\Racl\RaclComponent::pluck('title', 'id')->toArray();
                                $permissions_list = $usergroup->racl_permission()
                                                                ->where(function($query){
                                                                    $query->where('stateaccess', 1)
                                                                            ->Orwhere('stateadd', 1)
                                                                            ->Orwhere('stateedit', 1)
                                                                            ->Orwhere('statedelete', 1)
                                                                            ->Orwhere('stateeditown', 1)
                                                                            ->Orwhere('stateprint', 1)
                                                                            ->Orwhere('stateexcel', 1)
                                                                            ->Orwhere('statecopy', 1)
                                                                            ->Orwhere('stateapply', 1)
                                                                            ->Orwhere('statecheckout', 1);
                                                                })
                                                                ->get()
                            @endphp

                            <table class="table table-bordered" id="myTable">
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp

                                    @foreach (  $ListMenu  as $key_com => $Menu )

                                        <tr class="info">
                                            <th colspan="11"><b>{!! array_key_exists( $key_com, $component )?$component[  $key_com ]:null !!}</b></th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" width="20%" class="text-center">ระบบงาน</th>
                                            <th colspan="10" class="text-center">สิทธิ์การใช้งาน</th>
                                        </tr>
                                        <tr>
                                            <th width="8%" class="text-center">ใช้งาน</th>
                                            <th width="8%" class="text-center">เพิ่ม</th>
                                            <th width="8%" class="text-center">แก้ไข</th>
                                            <th width="8%" class="text-center">ลบ</th>
                                            <th width="8%" class="text-center">มอบหมายงาน</th>
                                            <th width="8%" class="text-center">พิมพ์</th>
                                            <th width="8%" class="text-center">Excel</th>
                                            <th width="8%" class="text-center">คัดลอก</th>
                                            <th width="8%" class="text-center">ดูรายละเอียด</th>
                                            <th width="8%" class="text-center">ปลดล็อค</th>
                                        </tr>

                                        @foreach ( $Menu as $Item )
                                            @php
                                                $permissions = $permissions_list->where('view_id', $Item->id )->first();
                                                $i++;
                                            @endphp
                                            <tr>
                                                <td>
                                                    {!! $i !!}. {!! $Item->name !!}
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateaccess, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateadd, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateedit, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->statedelete, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateeditown, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateprint, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateexcel, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->statecopy, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->stateapply, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                                <td  class="text-center">
                                                    @if( !empty($permissions)  && in_array($permissions->statecheckout, [1] ))
                                                        <div class="icheckbox_flat-blue checked"></div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endforeach


                                </tbody>
                            </table>

                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

@endpush