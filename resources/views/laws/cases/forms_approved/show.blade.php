@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">ดูรายละเอียดพิจารณางานคดี  {{ $lawcase->ref_no ?? '' }} </h3>
                    @can('view-'.str_slug('law-cases-forms-approved'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/forms_approved') }}">
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

               

                    {!! Form::model($lawcase, [
                        'method' => 'PATCH',
                        'url' => ['/law/cases/forms_approved', $lawcase->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'Casesform'
                    ]) !!}

                    @include('laws.cases.forms_approved.form.form')

                    {!! Form::close() !!}

                    <div class="clearfix"></div>
                    {{-- @include('laws.cases.forms_approved.modals.modal-form-status') --}}
                    @include('laws.cases.forms_approved.modals.modal-status')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        
        $(document).ready(function() {
            //Disable
            $('#Casesform').find('input, select, textarea').prop('disabled', true);
        
            $('#Casesform').find('.show_tag_a').hide();
            $('#Casesform').find('.box_remove').remove();
            
  
        });
        
    </script>
@endpush
