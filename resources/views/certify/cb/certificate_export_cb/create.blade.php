@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ออกใบรับรอง (CB) </h3>
                    @can('view-'.str_slug('certificateexportcb'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/certificate-export-cb')}}">
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

                    {!! Form::open(['url' => '/certify/certificate-export-cb', 'class' => 'form-horizontal', 'files' => true,'id'=>"certificate_export_form", 'target'=>"_blank"]) !!}

                    @include ('certify/cb.certificate_export_cb.form')

                    {!! Form::close() !!}

                    
                    @include('certify/cb/certificate_export_cb/modal.add_attachment')
                    @include('certify/cb/certificate_export_cb/modal.edit_modle')
                </div>
            </div>
        </div>
    </div>
@endsection
