@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แก้ไขกำหนดมาตรฐาน #{{ $set_standard->id }}</h3>
                    @can('view-'.str_slug('set_standard'))
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous() }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    @can('view-'.str_slug('set_standard'))
                        @if ($set_standard->announce=='n')
                            <button class="btn btn-primary pull-right" id="standard_announcement" data-set_standard_id="{{ $set_standard->id }}" type="button" style="margin-right: 50px">
                                <i class="fa fa-rocket"></i> ผ่าน กมอ.
                            </button>
                        @else
                            <button class="btn btn-danger pull-right" id="cancel_announcement" data-set_standard_id="{{ $set_standard->id }}" type="button" style="margin-right: 50px">
                                <i class="fa fa-rocket"></i> ยกเลิกการผ่าน กมอ.
                            </button>
                        @endif
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

                    @include ('tis.set_standard.formEdit')

                </div>
            </div>
        </div>
    </div>
@endsection
