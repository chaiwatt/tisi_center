@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">บันทึกผลตรวจสอบเอกสาร</h3>
                    @can('view-'.str_slug('law-cases-result'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/results') }}">
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

                    {!! Form::model($case, [
                        'method' => 'POST',
                        'url' => ['/law/cases/results/document', $case->id],
                        'class' => 'form-horizontal form-document',
                        'files' => true
                    ]) !!}
                        @include('laws/cases.result.form.document')
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection


