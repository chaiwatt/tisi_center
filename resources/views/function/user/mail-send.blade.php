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
                    <a class="btn btn-success pull-right" href="{{url('/page/send-mails/infomation')}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>
                    <div class="clearfix"></div>
                    <hr>

                    {!! Form::open(['url' => '/page/send-mails/user/save', 'class' => 'form-horizontal', 'files' => true]) !!}

                    @include ('function.user.form-mail')

                    {!! Form::close() !!}

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

@endpush