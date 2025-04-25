@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกำหนดสัดส่วนเงินรางวัลสินบน</h3>
                    @can('view-'.str_slug('law-config-reward'))
                        <a class="btn btn-success pull-right" href="{{ url('/law/config/reward') }}">
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

                    {!! Form::model($config_reward, [
                        'method' => 'PATCH',
                        'url' => ['/law/config/reward', $config_reward->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('laws.config.reward.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
