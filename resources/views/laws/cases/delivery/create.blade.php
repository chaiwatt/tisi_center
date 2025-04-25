@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกจัดส่งหนังสือ (เพิ่ม)</h3>
                    @can('view-'.str_slug('law-cases-delivery'))
                        <a class="btn btn-default pull-right" href="{{url('/law/cases/delivery')}}">
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
  
                    @include('laws.cases.delivery.modals.file')

                    {!! Form::open(['url' => '/law/cases/delivery', 'class' => 'form-horizontal', 'files' => true]) !!}
                
                    @include ('laws.cases.delivery.form')
    
                    {!! Form::close() !!}


                </div>
            </div>
        </div>
    </div>
@endsection
