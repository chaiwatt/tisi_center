@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">แสดงความคิดเห็นต่อร่างกฎกระทรวง #{{ $note_std_draft->id }}</h3>
                    @can('view-'.str_slug('listen-std-draft'))
                        {{-- <a class="btn btn-success pull-right" href="{{ url('/tis/listen_std_draft') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a> --}}
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

                    {!! Form::model($note_std_draft, [
                        'method' => 'PATCH',
                        // 'url' => ['/tis/comment-standard-reviews', $note_std_draft->id],
                        'url' => ['/tis/listen_std_draft/save', $note_std_draft->id],
                        'class' => 'form-horizontal',
                        'files' => true
                    ]) !!}

                    @include ('tis.listen_std_draft.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
