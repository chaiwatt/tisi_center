@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">หมวดหมู่ระบบงานหลัก</h3>
                    @can('view-'.str_slug('law-config-system-category'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/config/system-category') }}">
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

                    {!! Form::model($config_system_category, [
                        'method' => 'PATCH',
                        'url' => '',
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}

                    @include ('laws.config.system-category.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable
            $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();
        });

    </script>
@endpush
