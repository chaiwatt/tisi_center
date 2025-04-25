@extends('layouts.master')

@push('css')

<style>
  .item{
    margin: 5px 0px;
  }
</style>

@endpush

@section('content')
<div class="container-fluid">
    <!-- .row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title pull-left">ค้นหาข้อมูลโรงงาน กรมโรงงานอุตสาหกรรม (DIW)</h3>

                <a class="btn btn-success pull-right" href="{{ url()->previous() }}">
                    <i class="icon-arrow-left-circle"></i> กลับ
                </a>

                <div class="clearfix"></div>

                <hr>

                @if(Session::has('message'))
                    <p class="alert alert-danger">{{ Session::get('message') }}</p>
                    @php Session::forget('message'); @endphp
                @endif

                {!! Form::model($data, ['url' => '/ws/industry', 'method' => 'post', 'class' => 'form-horizontal']) !!}

                <div class="col-md-12">

                    <div class="form-group">
                        {!! Form::label('JuristicID', 'ค้นจากเลขทะเบียนโรงงาน:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-5">
                            {!! Form::text('JuristicID', null, ['class' => 'form-control', 'placeholder'=>'เลขทะเบียนโรงงาน 14 หลัก หรือ เลขทะเบียนโรงงาน(เดิม)', 'maxlength' => 20, 'required' => true]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('api_name', 'ค้นจากบริการ:', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-7">
                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('api_name', 1, true, ['id' => 'api_name1']) !!}
                                <label for="api_name1"> V1 (pid=4) ค้นได้เฉพาะเลขทะเบียนใหม่</label>
                            </div>

                            <div class="radio radio-danger pull-left m-l-5">
                                {!! Form::radio('api_name', 2, null, ['id' => 'api_name2']) !!}
                                <label for="api_name2"> V2 (srv=diwfac) ค้นได้ทั้งเลขทะเบียนใหม่และทะเบียนเดิม </label>
                            </div>

                            <div class="radio radio-success pull-left m-l-5">
                                {!! Form::radio('api_name', 3, null, ['id' => 'api_name3']) !!}
                                <label for="api_name3"> V3 (pid=9) ค้นได้เฉพาะเลขทะเบียนใหม่</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-5"></div>
                        <div class="col-md-4">
                            <button type="submit" class="btn waves-effect waves-light btn-info">
                                <i class="fa fa-search"></i> ค้นหา
                            </button>
                        </div>
                    </div>

                </div>

                <div class="form-group"></div>

                @if(array_key_exists("result", $data))

                    @php $juristic = $data['result']; @endphp

                    @if(!is_null($juristic) && $juristic->status=='success' && $juristic->result!='can not found any data')

                        @php 
                            $factory = $juristic->result[0]; 
                        @endphp

                        @if( in_array( $data['api_name'], [1,2] ) )
                            @include('ws.industry.pid')
                        @else
                            @include('ws.industry.pid-new') 
                        @endif


                    @elseif(!empty($juristic->result))
                        <div class="alert alert-warning"> {!! $juristic->result !!} </div>
                    @elseif(!empty($juristic->Result))
                        <div class="alert alert-warning"> {!! $juristic->Result !!} </div>
                    @else
                        <div class="alert alert-danger"> ไม่สามารถเชื่อมต่อบริการได้ในขณะนี้ </div>
                    @endif


                @endif

                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-map-show">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times</span>
                </button>
            </div>
            <div class="modal-body">
                <style>
                    .controls {
                         margin-top: 10px;
                         border: 1px solid transparent;
                         border-radius: 2px 0 0 2px;
                         box-sizing: border-box;
                         -moz-box-sizing: border-box;
                         height: 32px;
                         outline: none;
                         box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
                    }
                </style>

                <div id="map" style="height: 400px;"></div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger waves-effect text-left" data-dismiss="modal">ปิด</button>
            </div>
        </div>
    </div>
</div>

@endsection