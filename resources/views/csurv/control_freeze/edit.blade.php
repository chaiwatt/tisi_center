@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกการยึด อายัดผลิตภัณฑ์อุตสาหกรรม#{{ $data->id }}</h3>
        
                        <a class="btn btn-success pull-right" href="{{ url('/csurv/control_freeze') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>

                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($data, [
                        'method' => 'PATCH',
                        'url' => ['/csurv/control_freeze', $data->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}
                        <div id="box-readonly">
                       @include ('csurv/control_freeze.form')
                       </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
@push('js') 
    <script>
        jQuery(document).ready(function() {

            $('#box-readonly').find('.list_select').prop('disabled', true);

        });
    </script>
     
@endpush