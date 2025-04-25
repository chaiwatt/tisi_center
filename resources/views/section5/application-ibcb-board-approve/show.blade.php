@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ผลเสนอพิจารณาและประกาศราชกิจจานุเบกษา IB/CB #{{ $applicationibcb->id }}</h3>
                        <a class="btn btn-success pull-right" href="{{ url('/section5/application-ibcb-board-approve') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ</a>

                    <div class="clearfix"></div>
                    <hr>

                    @if ($errors->any())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {!! Form::model($applicationibcb, [
                        'method' => 'PATCH',
                        'url' => ['/section5/application-ibcb-board-approve', $applicationibcb->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}


                    @include ('section5.application-ibcb-board-approve.form', ['submitButtonText' => 'Update'])

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">

        $(document).ready(function() {

            $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            $('#box-readonly').find('button').remove();
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();

        });

    </script>
@endpush
