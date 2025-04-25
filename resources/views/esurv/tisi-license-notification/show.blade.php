@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แจ้งข้อมูลใบอนุญาต #{{ $license->id }}</h3>
                    @can('view-'.str_slug('other'))
                        <a class="btn btn-success pull-right" href="{{url("$previousUrl")}}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($license, [
                        'method' => 'PATCH',
                        'url' => ['/esurv/tisi_license_notification', $license->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}
                        <div id="box_readonly">
                       @include ('esurv.tisi-license-notification.form')
                       <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                        {!! Form::label('', 'เบอร์โทร:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('', !empty($license->user_updated->reg_phone) ?  $license->user_updated->reg_phone : null, ['class' => 'form-control','disabled'=>true]) !!}
                            {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div>
                      <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                        {!! Form::label('', 'E-mail:', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                            {!! Form::text('', !empty($license->user_updated->reg_email) ?  $license->user_updated->reg_email : null, ['class' => 'form-control','disabled'=>true]) !!}
                            {!! $errors->first('', '<p class="help-block">:message</p>') !!}
                        </div>
                      </div>
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
           // จัดการข้อมูลในกล่องคำขอ false
            $('#box_readonly').find('button[type="submit"]').remove();
            $('#box_readonly').find('.icon-close').parent().remove();
            $('#box_readonly').find('.fa-copy').parent().remove();
            $('#box_readonly').find('.list_attach').hide();
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('input').prop('disabled', true);
            $('#box_readonly').find('textarea').prop('disabled', true); 
             $('#box_readonly').find('select').prop('disabled', true);
             $('#box_readonly').find('.bootstrap-tagsinput').prop('disabled', true);
             $('#box_readonly').find('span.tag').children('span[data-role="remove"]').remove();
             $('#box_readonly').find('button').prop('disabled', true);
             $('#box_readonly').find('button').remove();
             $('#box_readonly').find('button').remove();
            $('body').on('click', '.attach-remove', function() {
                $(this).parent().parent().parent().find('input[type=hidden]').val('');
                $(this).parent().remove();
            });
        });
    </script>
     
@endpush