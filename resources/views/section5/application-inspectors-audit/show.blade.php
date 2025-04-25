@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียดผลตรวจประเมินผู้ตรวจ และผู้ประเมิน #{{ $application_inspectors->id }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application-inspectors-audit') }}">
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

                    {!! Form::model($application_inspectors, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application-inspectors-audit/approve-save', $application_inspectors->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                        @include ('section5.application-inspectors-audit.form', ['submitButtonText' => 'Update'])

                    {!! Form::close() !!}

                    <div class="row text-center">
                        <a class="btn btn-default" href="{{ url('/section5/application-inspectors-audit') }}">
                            <i class="fa fa-rotate-left" aria-hidden="true"></i> กลับ
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        $(document).ready(function() {
            $('#box-approve').find('input, textarea, select').prop('disabled', true);
            $('#box-approve').find('button[type="submit"], a.btn').remove();
        });
    </script>

@endpush
