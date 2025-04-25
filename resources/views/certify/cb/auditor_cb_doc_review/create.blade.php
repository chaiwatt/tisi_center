@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แต่งตั้งคณะผู้ตรวจประเมินเอกสาร (CB)</h3>
                    @can('view-'.str_slug('auditorcb'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
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
                    
                    {!! Form::open(['url' => '/certify/auditor_cb_doc_review/auditor_cb_doc_review_store', 'class' => 'form-horizontal', 'files' => true,'id'=>'form_auditor']) !!}
                              @include ('certify.cb.auditor_cb_doc_review.form')
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
