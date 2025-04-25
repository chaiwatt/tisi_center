@extends('layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="white-box">
                <h3 class="box-title pull-left">ตรวจสอบคำขอรับการแต่งตั้งเป็นหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (IB) #{{ $application_inspection_unit->id }}</h3>
                    <a class="btn btn-success pull-right" href="{{ url('/section5/accept-inspection-unit') }}">
                        <i class="icon-arrow-left-circle" aria-hidden="true"></i>กลับ</a>

                <div class="clearfix"></div>
                <hr>

                {!! Form::model($application_inspection_unit, [
                    'method' => 'PATCH',
                    'url' => ['/section5/accept-inspection-unit/', $application_inspection_unit->id],
                    'class' => 'form-horizontal',
                    'files' => true
                ]) !!}
                <div id="box-readonly">
                    @include ('section5.accept-inspection-unit.form', ['submitButtonText' => 'Update'])
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
            $('#box-readonly').find('input').prop('disabled', true);
            $('#box-readonly').find('textarea').prop('disabled', true);
            $('#box-readonly').find('select').prop('disabled', true);
            $('#box-readonly').find('.bootstrap-tagsinput').prop('disabled', true);
            $('#box-readonly').find('span.tag').children('span[data-role="remove"]').remove();
            $('#box-readonly').find('button').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.btn-remove-file').parent().remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.input_show_file').hide();
            
        });
    </script>

@endpush