{{-- AppointedCommitteeLtController --}}
@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">นัดหมายการประชุม check#</h3>
                    @can('view-'.str_slug('appointed-committee-lt'))
                        <a class="btn btn-success pull-right" href="{{url('/certify/meeting-standards')}}">
                            <i class="icon-arrow-left-circle"></i> กลับ
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
                        <form method="POST" 
                            action="{{ route('certify.meeting-standards.lt.store') }}" 
                            class="form-horizontal" 
                            enctype="multipart/form-data">

                            {{-- เพิ่ม CSRF Token สำหรับความปลอดภัย --}}
                            @csrf

                            @include ('certify.meeting-standards.lt.form')

                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection
