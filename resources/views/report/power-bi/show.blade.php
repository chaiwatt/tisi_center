@extends('layouts.master')

@push('css')

@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <h3 class="box-title pull-left">{{ $item->title }}</h3>

                    <div class="clearfix"></div>
                    <hr class="m-t-0">

                    <iframe title="{{ $item->title }}" src="{{ $item->url }}" class="col-md-12 col-sm-12" height="800" frameborder="0" allowFullScreen="true"></iframe>
                    <div class="clearfix"></div>

                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
    <script>

    </script>
@endpush
