@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">เพิ่ม หน่วยตรวจสอบ (LAB)</h3>
                    @can('view-'.str_slug('manage-lab'))
                        <a class="btn btn-success pull-right" href="{{url('/section5/labs')}}">
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

                    {!! Form::open(['url' => '/section5/labs', 'class' => 'form-horizontal', 'files' => true, 'id' => 'create_froms' ]) !!}

                    @include ('section5.manage-lab.add-new.form')   

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        jQuery(document).ready(function() {

            
        });
    </script>

@endpush