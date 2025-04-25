@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกผลการตรวจประเมิน (IB) {{ $assessment->id }}</h3>
                    @can('view-'.str_slug('saveassessmentib'))
                        <a class="btn btn-success pull-right" href="{{ url('/certify/save_assessment-ib') }}">
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

                    {!! Form::model($assessment, [
                        'method' => 'PATCH',
                        'url' => ['/certify/save_assessment-ib', $assessment->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form_assessment'
                    ]) !!}
                       <div id="box-readonly">
                          @include ('certify/ib.save_assessment_ib.form')
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
            let degree = '{{ ($assessment->degree >= 1)  ? 1 : 2 }}';
            if(degree == 1){
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