@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">สร้างใบเสร็จเงิน</h3>
                    @can('add-'.str_slug('accounting-receipt-info'))
                        <a class="btn btn-default pull-right" href="{{ url('/accounting/receipt-info')}}">
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

                    {!! Form::open(['url' => '/accounting/receipt-info', 'class' => 'form-horizontal', 'files' => true, 'id' => 'myForm']) !!}

                    @include ('accounting.receipt-info.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
