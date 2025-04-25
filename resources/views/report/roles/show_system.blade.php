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
                    <h3 class="box-title pull-left">รายงานการกำหนดสิทธิ์การใช้งานของแต่ละกลุ่มบทบาท # {{ $roles->id }}</h3>
                    @can('view-'.str_slug('report-roles'))
                        <div class="pull-right">
                            <a class="btn btn-success" href="{{ url('/report/roles/export_role?role_id='.$roles->id) }}">
                                Export
                            </a>
                            <a class="btn btn-info" href="{{ url('/report/roles/') }}">
                                <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                            </a>
                        </div>
                    @endcan

                    <div class="clearfix"></div>
                    <hr>

                    <div class="row">
                        <div class="col-sm-12">
                            <center>
                                <h2 class="text-dark">กลุ่มบทบาท : {!! $roles->name !!}</h2>
                                <h4 class="text-dark">ส่วนการควบคุม : {!!  !empty($roles->label)?($roles->label=='staff'?'เจ้าหน้าที่':'ผู้ประกอบการ'):'-' !!}</h4>
                            </center>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-sm-12">

                            @php
                                $arr_permissions = App\Permission::all()->pluck('id', 'name')->toArray();

                                $role_permissions = $roles->permissions()->pluck('id')->toArray();

                                $permissions_role = [];
                                foreach ( $roles->permissions->pluck('name','name')->toArray() as  $permissions) {

                                    $message = $permissions;
                                    $message = str_replace("add-", "", $message);
                                    $message = str_replace("view-", "", $message);
                                    $message = str_replace("edit-", "", $message);
                                    $message = str_replace("delete-", "", $message);
                                    $message = str_replace("other-", "", $message);

                                    $message = str_replace("assign_work-", "", $message);
                                    $message = str_replace("poko_approve-", "", $message);
                                    $message = str_replace("poao_approve-", "", $message);
                                    $message = str_replace("view_all-", "", $message);

                                    $permissions_role[  $message ] =  $message;

                                }
                       
                                if( $roles->label=='staff' ){
                                    $ListMenu = HP::MenuSidebar(false); 
                                }else{
                                    $ListMenu = HP::MenuTraderSidebar(); 
                                }

                            @endphp

                            <table class="table table-bordered" id="myTable">
                                <tbody>
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach (  $ListMenu  as $Menu )
                                        @if( HP::CheckRoleMenuItem( $Menu->items , $permissions_role ) )
                                            
                                            <tr class="info">
                                                <th colspan="9"><b>{!! $Menu->_comment !!}</b></th>
                                            </tr>
                                            <tr>
                                                <th rowspan="2" width="30%" class="text-center">ระบบงาน</th>
                                                <th colspan="9" class="text-center">สิทธิ์การใช้งาน</th>
                                            </tr>
                                            <tr>
                                                <th width="8%" class="text-center">ดู</th>
                                                <th width="8%" class="text-center">เพิ่ม</th>
                                                <th width="8%" class="text-center">แก้ไข</th>
                                                <th width="8%" class="text-center">ลบ</th>
                                                <th width="8%" class="text-center">มอบหมาย</th>
                                                <th width="8%" class="text-center">ผก.อนุมัติ</th>
                                                <th width="8%" class="text-center">ผอ.อนุมัติ</th>
                                                <th width="8%" class="text-center">ดูได้ทุกรายการ</th>
                                            </tr>
   
                                            @isset( $Menu->items )

                                                @foreach (  $Menu->items  as $Item )

                                                    @if( isset($Item->sub_menus) )

                                                        @if( HP::CheckRoleMenuItem( $Item->sub_menus , $permissions_role ) )

                                                            <tr>
                                                                <td colspan="9"><b>{!! $Item->display !!}</b></td>
                                                            </tr>

                                                            @foreach ( $Item->sub_menus as $sub_menus )
                                                                @if(property_exists($sub_menus, 'title') && HP::CheckRoleMenuItem( $sub_menus , $permissions_role ))
                                                                    @php
                                                                        $permissions =	HP::permissionList( $sub_menus->title , $arr_permissions   );
                                                                        $i++;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>
                                                                            {!! $i !!}. {!! $sub_menus->display !!}
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['view'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['add'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>  
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['edit'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['delete'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['assign_work'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['poko_approve'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['poao_approve'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                        <td  class="text-center">
                                                                            @if(in_array($permissions['view_all'], $role_permissions))
                                                                                <div class="icheckbox_flat-blue checked"></div>
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                    
                                                            @endforeach

                                                        @endif

                                                    @else
                                                        @if(property_exists($Item, 'title') &&  HP::CheckRoleMenuItem( $Item , $permissions_role ) )
                                                            @php
                                                                $permissions =	HP::permissionList( $Item->title , $arr_permissions   );
                                                                $i++;
                                                            @endphp
                                                            <tr>
                                                                <td width="30%">
                                                                    {!! $i !!}. {!! $Item->display !!}
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['view'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['add'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>  
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['edit'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['delete'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['assign_work'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['poko_approve'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['poao_approve'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                                <td  class="text-center">
                                                                    @if(in_array($permissions['view_all'], $role_permissions))
                                                                        <div class="icheckbox_flat-blue checked"></div>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endif

                                                    @endif

                                                @endforeach


                                            @endisset

                                        @endif
    
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