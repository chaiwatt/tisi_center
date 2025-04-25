@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่ม ผู้ตรวจ/ผู้ประเมิน (IB)</h3>
                    @can('view-'.str_slug('manage-inspector'))
                        <a class="btn btn-success pull-right" href="{{url('/section5/inspectors')}}">
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

                    {!! Form::open(['url' => '/section5/inspectors', 'class' => 'form-horizontal', 'files' => true, 'id' => 'create_froms' ]) !!}

                    @include ('section5.manage-inspectors.add-new.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
