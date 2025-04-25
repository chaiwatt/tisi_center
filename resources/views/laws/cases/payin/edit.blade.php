@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">ใบแจ้งชำระเงิน (Pay-in)</h3>
                    @can('view-'.str_slug('law-cases-payin'))
                         <a class="btn btn-default pull-right" href="{{url('/law/cases/payin')}}">
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

                    {!! Form::model($cases, [
                        'method' => 'POST',
                        'url' => ['/law/cases/payin', $cases->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' =>'pay_in_form'
                    ]) !!}

                            @include ('laws.cases.payin.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
