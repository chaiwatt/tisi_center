@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">Dashboard (สก.)</h3>
                        <a class="btn btn-success pull-right" href="{{ app('url')->previous()  }}">
                            <i class="icon-arrow-left-circle"></i> กลับ
                        </a>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-12">
                    @include('certify.dashboard.form')
          </div>
      </div>
  
    </div>
@endsection
