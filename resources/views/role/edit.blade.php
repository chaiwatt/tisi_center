@extends('layouts.master')

@push('css')
<link href="{{asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
@endpush


@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขสิทธิ์การใช้งาน</h3>

                    <a class="btn btn-success pull-right waves-effect waves-light" href="{{url('role-management')}}">
                        <i class="icon-arrow-left-circle"></i> กลับ
                    </a>

                    <div class="clearfix"></div>

                    <hr>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            {!! Form::open(['url' => ('role/edit/'.$role->id), 'method' => 'post', 'class'=>'form-horizontal' ]) !!}

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
    <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
    <script src="{{asset('plugins/components/toast-master/js/jquery.toast.js')}}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    {{--<script src="{{asset('js/toastr.js')}}"></script>--}}
    <script>
        @if(\Session::has('flash_message'))
            $.toast({
                heading: 'Success!',
                position: 'top-center',
                text: '{{session()->get('
                message ')}}',
                loaderBg: '#70b7d6',
                icon: 'success',
                hideAfter: 3000,
                stack: 6
            });
        @endif

        $(document).ready(function() {

            var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
            $('.js-switch').each(function() {
                new Switchery($(this)[0], $(this).data());
            });

            var count = 0;
            $('.amount_permission').each(function (index, rowId) {

                var txt = $(rowId).text();
                count += parseInt(txt);
            });

            console.log(count);

            //Select all View check boxes
            $('#all_view').change(function() {
                if ($(this).prop("checked")) {
                    $('.view').prop('checked', true);
                } else {
                    $('.view').prop('checked', false);
                }
            });

            //Select all Add check boxes
            $('#all_add').change(function() {
                if ($(this).prop("checked")) {
                    $('.add').prop('checked', true);
                } else {
                    $('.add').prop('checked', false);
                }
            });

            //Select all Edit check boxes
            $('#all_edit').change(function() {
                if ($(this).prop("checked")) {
                    $('.edit').prop('checked', true);
                } else {
                    $('.edit').prop('checked', false);
                }
            });

            //Select all Delete check boxes
            $('#all_delete').change(function() {
                if ($(this).prop("checked")) {
                    $('.delete').prop('checked', true);
                } else {
                    $('.delete').prop('checked', false);
                }
            });

            //Select all Delete check boxes
            $('#all_other').change(function() {
                if ($(this).prop("checked")) {
                    $('.other').prop('checked', true);
                } else {
                    $('.other').prop('checked', false);
                }
            });

            //Show Hide Menu
            $('.view-menu-detail').click(function(event) {

                var sign = $(this).children('i');
                var tr_row = $('tr[data-menu="'+$(this).attr('data-control')+'"]');

                if(tr_row.length == 0){//ถ้าไม่มีอาจจะเป็น submenu
                    tr_row = $('tr[data-submenu="'+$(this).attr('data-control')+'"]');
                }
                
                if($(sign).hasClass('fa-angle-double-down')){
                    $(sign).removeClass('fa-angle-double-down');
                    $(sign).addClass('fa-angle-double-up');
                    $(tr_row).show();
                }else{
                    $(sign).removeClass('fa-angle-double-up');
                    $(sign).addClass('fa-angle-double-down');
                    $(tr_row).hide();
                }

            });

            $('.view-menu-detail').click();
            $('.view-menu-detail').click();

        });
    </script>
@endpush
