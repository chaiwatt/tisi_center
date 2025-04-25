@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ตรวจประเมินหน่วยตรวจสอบผลิตภัณฑ์อุตสาหกรรม (LAB) #{{ $applicationlabaudit->id }}</h3>
                    @can('view-'.str_slug('application-lab-audit'))
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application_lab_audit') }}">
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

                    {!! Form::model($applicationlabaudit, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application_lab_audit', $applicationlabaudit->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}

                    @include ('section5.application_lab_audit.form')

                    <div class="clearfix"></div>

                    @include ('section5.application_lab_audit.panels.report')

                    <div class="clearfix"></div>

                    @include ('section5.application_lab_audit.panels.approve')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable
            $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();

            //Disable ผลตรวจประเมิน
            $('#box-result').find('input, select, textarea').prop('disabled', true);
            $('#box-result').find('button').remove();
            $('#box-result').find('.show_tag_a').remove();

            //Disable บันทึกสรุปรายงาน
            $('#box-report').find('input, select, textarea').prop('disabled', true);
            $('#box-report').find('button').remove();
            $('#box-report').find('.show_tag_a').remove();

            $('#box-report_approve').find('input, select, textarea').prop('disabled', true);
            $('#box-report_approve').find('button').remove();
            $('#box-report_approve').find('.show_tag_a').remove();
        });

    </script>
@endpush
