@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม#{{ $data->id }}</h3>
        
                        <a class="btn btn-success pull-right" href="{{ url('/csurv/control_freeze') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>

                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($data, [
                        'method' => 'PATCH',
                        'url' => ['/csurv/control_freeze', $data->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}
                        <div id="box-readonly">
                       @include ('csurv/control_freeze.form')
                       </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js') 
    <script>
        jQuery(document).ready(function() {

            $('#box-readonly').find('.data_hide').hide();
           // จัดการข้อมูลในกล่องคำขอ false
           $('#box-readonly').find('button[type="submit"]').remove();
            $('#box-readonly').find('.icon-close').parent().remove();
            $('#box-readonly').find('.fa-copy').parent().remove();
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true);
             $('#box-readonly').find('select').prop('disabled', true);
             $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
             $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
             $('#box-readonly').find('button').prop('disabled', true);
             $('#box-readonly').find('button').remove();

            $('body').on('click', '.attach-remove', function() {
                $(this).parent().parent().parent().find('input[type=hidden]').val('');
                $(this).parent().remove();
            });
        });
    </script>
     
@endpush