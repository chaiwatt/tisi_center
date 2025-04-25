@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">ประวัติการกระทำความผิด #{{ $offender->id }}</h3>
                    @can('view-'.str_slug('law-cases-offender'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/offender') }}">
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

                    {!! Form::model($offender, [
                        'method' => 'PATCH',
                        'url' => ['/law/cases/offender', $offender->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'box-readonly'
                    ]) !!}
 
                    @include ('laws.cases.offender.form')

                    {!! Form::close() !!}

                            
                    <!-- Modal-ข้อมูลผู้กระทำความผิด -->
                    @include('laws.cases.offender.modals.infomation')

                    <!-- Modal-ข้อมูลผู้กระทำความผิด Log -->
                    @include('laws.cases.offender.modals.infomation-log')

                    <!-- Modal- ประวัติการกระทำความผิด -->
                    @include('laws.cases.offender.modals.case')

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>

        $(document).ready(function() {
            //Disable
            // $('#box-readonly').find('input, select, textarea').prop('disabled', true);
            // $('#box-readonly').find('button').remove();
            // $('#box-readonly').find('.show_tag_a').hide();
            // $('#box-readonly').find('.box_remove').remove();

            @if(\Session::has('infomation_success_message'))
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: '{{session()->get('infomation_success_message')}}',
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        });

    </script>
@endpush