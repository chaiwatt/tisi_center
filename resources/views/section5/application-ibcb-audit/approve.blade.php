@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">พิจารณาอนุมัติสรุปรายงานผลการตรวจประเมิน (IB/CB) #{{ $applicationibcb->application_no }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application-ibcb-audit') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ</a>

                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($applicationibcb, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application-ibcb-audit/approve-save', $applicationibcb->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('section5.application-ibcb-audit.form', ['hide_draft_btn' => true])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable ผลตรวจประเมิน
            $('#box-result').find('input, select, textarea').prop('disabled', true);
            $('#box-result').find('button').remove();
            $('#box-result').find('.show_tag_a').remove();
        });

    </script>
@endpush
