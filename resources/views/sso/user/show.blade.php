@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <!-- .row -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">ข้อมูลผู้ประกอบการ #{{ $user->id }}</h3>

                    @can('view-'.str_slug('user-sso'))
                        <a class="btn btn-success pull-right" href="{{ url('/sso/user-sso') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan

                    <div class="clearfix"></div>

                    {!! Form::model($user, [
                        'method' => 'PATCH',
                        'url' => ['/sso/user-sso', $user->getKey()],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'form-show'
                    ]) !!}

                        @include('sso.user.form')

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')

    <script type="text/javascript">

        //ปรับ style
        $(document).ready(function() {

            //ลบหัวข้อ
            $('#form-show').find('.box-title:first').next().remove();
            $('#form-show').find('.box-title:first').remove();

            //ลบปุ่มบันทึก
            $('#form-show').find('button[type="submit"]').closest('div').remove();

            $('#form-show').find('input, select').prop('disabled', true);
        });

    </script>

@endpush
