@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">จัดการข้อมูลห้องสมุด</h3>
                    @can('view-'.str_slug('law-book-manage'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/book/manage') }}">
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

                    {!! Form::model($book_manage, [
                        'method' => 'PATCH',
                        'url' => ['/law/book/manage', $book_manage->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}

                    @include ('laws.books.manage.form')

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
            $('#box-readonly').find('input, select').prop('disabled', true);
            $('#box-readonly').find('.summernote').summernote("disable");
            $('#box-readonly').find('button').prop('disabled', true);
            $('#box-readonly').find('.show_tag_a').hide();
            $('#box-readonly').find('.box_remove').remove();
        });

    </script>
@endpush
