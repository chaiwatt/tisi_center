@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">บันทึกจัดส่งหนังสือ (แก้ไข)</h3>
                    @can('view-'.str_slug('law-cases-delivery'))
                        <a class="btn btn-success pull-right" href="{{ url('/law/cases/delivery') }}">
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

                    @include('laws.cases.delivery.modals.file')
   
                    {!! Form::model($lawcasesdelivery, [
                        'method' => 'PATCH',
                        'url'    => ['/law/cases/delivery', $lawcasesdelivery->id],
                        'class'  => 'form-horizontal',
                        'files'  => true,
                        'id'     => 'box-readonly'
                    ]) !!}

                    @include ('laws.cases.delivery.form')

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
            $('#box-readonly').find('.repeater-form-file').remove();

        });

    </script>
@endpush