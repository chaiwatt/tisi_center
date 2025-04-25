@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขPromoteTrader #{{ $promotetrader->id }}</h3>
                    @can('view-'.str_slug('PromoteTrader'))
                        <a class="btn btn-success pull-right" href="{{ url('/admin/basic/promote-trader') }}">
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

                    {!! Form::model($promotetrader, [
                        'method' => 'PATCH',
                        'url' => ['/admin/basic/promote-trader', $promotetrader->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('admin/basic.promote-trader.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
