@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ออกใบรับรอง (IB)</h3>
                    @can('view-'.str_slug('certificateexportib'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/certificate-export-ib')}}">
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

                    {!! Form::open(['url' => '/certify/certificate-export-ib', 
                                    'class' => 'form-horizontal',
                                    'files' => true,
                                    'id'=>'certificate_export_form',
                                    'target'=>"_blank"]) !!}

                    @include ('certify/ib.certificate_export_ib.form')

                    {!! Form::close() !!}

                    @include('certify/ib/certificate_export_ib/modal.add_attachment')
                    @include('certify/ib/certificate_export_ib/modal.edit_modle')
                </div>
            </div>
        </div>
    </div>
@endsection
