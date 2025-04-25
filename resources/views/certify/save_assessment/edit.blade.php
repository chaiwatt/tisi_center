@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ระบบบันทึกผลการตรวจประเมิน</h3>
                    @can('view-'.str_slug('auditor'))
                    <a class="btn btn-success pull-right" href="{{ route('save_assessment.index', ['app' => $app ? $app->id : '']) }}">
                        <i class="icon-arrow-left-circle"></i> กลับ
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
                    {{-- {!! Form::model($notice, [
                        'method' => 'put',
                        'url' => route('save_assessment.update', ['notice'=> $notice, 'app' => @$notice->applicant->id ? : '']),
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!} --}}
                    {!! Form::open(['url' => route('save_assessment.update', ['notice'=> $notice, 'app' => @$notice->applicant->id ? : '']), 'class' => 'form-horizontal', 'method' => 'put', 'files' => true, 'id' => 'form_assessment']) !!}
                        <div id="box-readonly">
                            @include ('certify.save_assessment.form')
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
            let status = '{{ !empty($notice->applicant->status)  ? $notice->applicant->status : null }}';
            if(status == 19){
                $('#box-readonly').find('button[type="submit"]').remove();
                $('#box-readonly').find('.icon-close').parent().remove();
                $('#box-readonly').find('.fa-copy').parent().remove();
                $('#box-readonly').find('.div_hide').hide();
                $('#box-readonly').find('input').prop('disabled', true);
                $('#box-readonly').find('input').prop('disabled', true);
                $('#box-readonly').find('textarea').prop('disabled', true); 
                $('#box-readonly').find('select').prop('disabled', true);
                $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
                $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
                $('#box-readonly').find('button').prop('disabled', true);

            }

   
        });
    </script>
     
@endpush