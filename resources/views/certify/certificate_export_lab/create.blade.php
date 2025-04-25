@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ออกใบรับรอง (LAB) </h3>
                    @can('view-'.str_slug('certificateexportlab'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/certificate-export-lab')}}">
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

                    {!! Form::open(['url' => '/certify/certificate-export-lab', 'class' => 'form-horizontal', 'files' => true,'id' =>'certificate_export_form','target'=>"_blank"]) !!}
                    @include('certify.certificate_export_lab.form')

                    {!! Form::close() !!}
                 
                    @include('certify/certificate_export_lab/modal.add_attachment')

                    @include('certify/certificate_export_lab/modal.edit_modle')

                </div>
            </div>
        </div>
    </div>
@endsection
