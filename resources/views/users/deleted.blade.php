@extends('layouts.master')

@push('css')
    
@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ผู้ใช้งานที่ถูกลบ</h3>
                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="myTable" class="table table-striped">
                                    <thead>
                                      <tr>
                                          <th>ลำดับ</th>
                                          <th>ชื่อ-สกุล</th>
                                          <th>สิทธิ์การใช้งาน</th>
                                          <th class="text-center">กู้คืน</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $key=>$user)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{ $user->reg_fname }} {{ $user->reg_lname }}</td>
                                            <td>
                                              @php
                                                $roles = $user->roles()->pluck('name')->implode(', ')
                                              @endphp

                                              @if($roles!='')
                                                {{ $roles }}
                                              @else
                                                <i class="text-danger">ไม่มีกลุ่ม</i>
                                              @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{url('user/restore/'.$user->getKey())}}" class="btn btn-info btn-sm">
                                                  <i class="icon-refresh"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click','.delete',function (e) {
                if(confirm('Are you sure want to delete?'))
                {

                }
                else
                {
                    return false;
                }
            });

            @if(\Session::has('message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif
        });

    </script>

@endpush
