@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">กำหนดมาตรฐาน {{ $set_standard->id }}</h3>
                    @can('view-'.str_slug('set_standard'))
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

                    @include ('tis.set_standard.form')
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function () {
            $('input,select,button,textarea').attr('disabled','disabled');
            setTimeout(function(){
              $('select.not_select2').attr('disabled','disabled');
              $('button[type="button"].attach-remove').hide();
              $('button[title="ลบ"].btn-light').hide();
            }, 3000);
            $('button.btn-primary').hide();
            $('a.btn-default').hide();
        });
    </script>
@endpush
