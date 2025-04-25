@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">ปฏิทินวันหยุด #{{ $holiday->id }}</h3>
                    @can('view-'.str_slug('law-cases-forms'))
                        <a class="btn btn-default pull-right" href="{{ url('basic/holiday') }}">
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

                    {!! Form::model($holiday, [
                        'method' => 'PATCH',
                        'url' => ['basic/holiday', $holiday->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}

                     @include ('basic.holiday.form')

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
