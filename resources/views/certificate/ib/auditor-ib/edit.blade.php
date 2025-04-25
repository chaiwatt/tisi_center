@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แต่งตั้งคณะผู้ตรวจประเมิน (IB) #{{ $auditor->id }}</h3>
                    @can('view-'.str_slug('auditorib'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
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

                    {!! Form::model($auditor, [
                        'method' => 'PATCH',
                        'url' => ['/certificate/auditor-ibs', $auditor->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id'=>'form_auditor'
                    ]) !!}
                      <div id="box-readonly">
                              @include ('certificate/ib/auditor-ib.form')
                      </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
