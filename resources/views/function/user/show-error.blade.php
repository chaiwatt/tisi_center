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
                    <h3 class="box-title pull-left">ระบบส่งเมลรายละเอียด</h3>
                  
                        <a class="btn btn-success pull-right" href="{{url('/page/send-mails/user')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                 
                    <div class="clearfix"></div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-borderless" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">ชื่อ - สกุล</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Username</th>
                                    <th class="text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($list)
                                    @php
                                        $i = 0;
                                    @endphp
                                    @foreach ( $list as $item )
                                        @php
                                            $i++;
                                        @endphp
                                        <tr>
                                            <td>{!! $i !!}</td>
                                            <td>{!! isset($item->trader_operater_name)?$item->trader_operater_name:null !!}</td>
                                            <td>{!! isset($item->agent_email)?$item->agent_email:null !!}</td>
                                            <td>{!! isset($item->trader_username)?$item->trader_username:null !!}</td>
                                            <td>{!! isset($item->status)?$item->status:null !!}</td>
                                        </tr>
                                        
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

    <script>
        $(document).ready(function () {



        });


    </script>

@endpush