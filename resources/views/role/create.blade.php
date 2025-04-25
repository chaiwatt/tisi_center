@extends('layouts.master')

@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

@endpush

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">สร้างกลุ่มผู้ใช้งาน</h3>

                    <a class="btn btn-success pull-right" href="{{url('role-management')}}">
                      <i class="icon-arrow-left-circle"></i> กลับ
                    </a>

                    <div class="clearfix"></div>
                    <hr>
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">

                            {!! Form::open(['url' => '/role/create', 'method' => 'post', 'class'=>'form-horizontal' ]) !!}

                                @include('role.form')

                            {!! Form::close() !!}

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>

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

        $(document).ready(function () {
            //Select all View check boxes
            $('#all_view').click(function () {
                if ($(this).attr("checked")) {
                    $('.view').click();
                } else {
                    $('.view').click();
                }
            });

            //Select all Add check boxes
            $('#all_add').click(function () {
                if ($(this).attr("checked")) {
                    $('.add').click();
                } else {
                    $('.add').click();
                }
            });

            //Select all Edit check boxes
            $('#all_edit').click(function () {
                if ($(this).attr("checked")) {
                    $('.edit').click();
                } else {
                    $('.edit').click();
                }
            });

            //Select all Delete check boxes
            $('#all_delete').click(function () {
                if ($(this).attr("checked")) {
                    $('.delete').click();
                } else {
                    $('.delete').click();
                }
            });

            //Select all Other check boxes
            $('#all_other').click(function () {
                if ($(this).attr("checked")) {
                    $('.other').click();
                } else {
                    $('.other').click();
                }
            });
        });
    </script>
@endpush
