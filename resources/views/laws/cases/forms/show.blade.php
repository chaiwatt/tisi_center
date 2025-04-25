@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left ">แจ้งงานคดีผลิตภัณฑ์อุตสาหกรรม #{{ $lawcasesform->id }}</h3>
                    @can('view-'.str_slug('law-cases-forms'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/cases/forms') }}">
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

                    @include ('laws.cases.forms.modals.modal-infomation')

                    {!! Form::model($lawcasesform, [
                        'method' => 'PATCH',
                        'url' => ['/law/cases/forms', $lawcasesform->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'Casesform'
                    ]) !!}

                    @include ('laws.cases.forms.form')

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
            $('#Casesform').find('input, select, textarea').prop('disabled', true);
            $('#Casesform').find('button').remove();
            $('#Casesform').find('.show_tag_a').hide();
            $('#Casesform').find('.box_remove').remove();
            $('#Casesform').find('button').remove();
  
        });
        
    </script>
@endpush
