@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่มกำหนดมาตรฐาน</h3>
                    @can('view-'.str_slug('set_standard'))
                        <a class="btn btn-success pull-right" href="{{url('/tis/set_standard')}}">
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

                 

                    @include ('tis.set_standard.form')

                 

                </div>
            </div>
        </div>
    </div>
@endsection
