@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกการดำเนินงานคดี</h3>
                    @can('view-'.str_slug('law-cases-operations'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/operations') }}">
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
                    
                    {!! Form::model($result, [
                        'method' => 'PATCH',
                        'url' => ['/law/cases/operations', $result->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form-operations' 
                    ]) !!}

                     @include ('laws.cases.operations.form')
               
                    {!! Form::close() !!} 

                </div>
            </div>
        </div>
    </div>
@endsection
