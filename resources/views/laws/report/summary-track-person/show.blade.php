@extends('layouts.master')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title pull-left">รายละเอียด</h3>
                    @can('view-'.str_slug('law-report-summary-track-person'))
                        <a class="btn btn-default pull-right" href="{{ url('/law/report/summary-track-person') }}">
                            <i class="icon-arrow-left-circle" aria-hidden="true"></i> กลับ
                        </a>
                    @endcan
                    <div class="clearfix"></div>
                    <hr class="m-t-0">

                    @include ('laws.report.summary-track-person.form')

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
