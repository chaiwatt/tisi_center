@push('css')
    <style>

    
    </style>
@endpush


@php
    $lawcase = $case;
@endphp
@include('laws.cases.request-form.cases') 


@push('js')
    <script>
        $(document).ready(function() {



        });
    </script>
@endpush
