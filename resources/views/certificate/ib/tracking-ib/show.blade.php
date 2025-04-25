@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบตรวจสอบคำขอหน่วยรับรอง {{ $certi_ib->app_no ?? null }} </h3>
                    @can('view-'.str_slug('checkcertificatecb'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr>
                    {!! Form::model($certi_ib, [
                        'id'=>'app_certi_form',
                        'class' => 'form-horizontal' 
                    ]) !!}
                    
                      <div id="box-readonly">
                         @include ('certificate/ib/tracking-ib.form')
                      </div>
                   
                        <div class="row form-group">
                            <a  href="{{ app('url')->previous()  }}">
                                <div class="alert alert-dark text-center" role="alert">
                                    <b>กลับ</b>
                                </div>
                            </a> 
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
              $('#box-readonly').find('button[type="submit"]').remove();
              $('#box-readonly').find('.icon-close').parent().remove();
              $('#box-readonly').find('.fa-copy').parent().remove();
              $('#box-readonly').find('.hide_attach').hide();
              $('#box-readonly').find('input').prop('disabled', true);
              $('#box-readonly').find('input').prop('disabled', true);
              $('#box-readonly').find('textarea').prop('disabled', true); 
              $('#box-readonly').find('select').prop('disabled', true);
              $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
              $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
        });
    </script>
     
@endpush
