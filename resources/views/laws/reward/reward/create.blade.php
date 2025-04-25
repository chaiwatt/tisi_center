@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left text-primary">สัดส่วนผู้มีสิทธิ์ได้รับเงิน</h3>
                    @can('view-'.str_slug('law-reward-reward'))
                        <a class="btn btn-default pull-right" href="{{url('/law/reward/reward')}}">
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

                    {!! Form::open(['url' => '/law/reward/reward', 'class' => 'form-horizontal', 'files' => true, 'id' => 'myForm']) !!}

                        @include ('laws.reward.reward.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
