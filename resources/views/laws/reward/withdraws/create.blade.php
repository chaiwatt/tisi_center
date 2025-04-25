@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">เบิกเงินรางวัล</h3>
                    @can('view-'.str_slug('law-reward-withdraws'))
                        <a class="btn btn-default pull-right" href="{{url('/law/reward/withdraws')}}">
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

                    {!! Form::open(['url' => '/law/reward/withdraws', 'class' => 'form-horizontal', 'files' => true, 'id' => 'myForm']) !!}

                        @include ('laws.reward.withdraws.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
