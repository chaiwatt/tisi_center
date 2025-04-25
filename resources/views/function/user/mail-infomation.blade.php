@extends('layouts.master')

@push('css')

    <style>

    </style>

@endpush


@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบส่งเมล</h3>
                   
                    <a class="btn btn-success pull-right" href="{{url('/page/send-mails/user')}}">
                        <i class="fa fa-plus"></i> ส่งเมล
                    </a>
                  
                    <div class="clearfix"></div>
                    <hr>

                    <div class="table-responsive" style="margin-top: 20px">
                        <table class="table table-striped" >
                            <thead>
                                <tr >
                                    <th>No.</th>
                                    <th width="40%">เรื่อง</th>
                                    <th width="25%">ผู้ส่ง</th>
                                    <th width="10%">ประเภทการส่ง</th>
                                    {{-- <th width="15%">Email ผู้รับ</th> --}}
                                    <th width="10%">วันที่บันทึก</th>
                                    <th width="10%">เครื่องมือ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($data)
                                    @foreach ( $data as $key => $item )
                                        @php
                                            $list_type = ['1'=>'ส่งรหัสผ่าน','2'=>'ทั่วไป'];
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration or $item->getKey() }}</td>
                                            <td>{!! @$item->invite !!}</td>
                                            <td>{!! @$item->sender_name !!}</td>
                                            <td>{!! @$list_type[$item->send_type] !!}</td>
                                            {{-- <td>{!! @$item->emails !!}</td> --}}
                                            <td>{{ HP::DateThai($item->created_at) }}</td>
                                            <td>
                                                <a class="btn btn-info btn-xs" href="{{url('/page/send-mails/show/'.$item->id)}}">
                                                    ดูรายละเอียด
                                                </a>
                                            </td>
                                        <tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
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

            @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('flash_message')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
            @endif

        });


    </script>
