@push('css')
    <link href="{{ asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/switchery/dist/switchery.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 70px;
            border-radius: 0;
            width: 100% !important;
            -webkit-border-radius: 7px;
            -moz-border-radius: 7px;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }
        .note-editor.note-frame {
            border-radius: 4px !important;
        }

    </style>
@endpush


@if( !isset( $lawlistministry->id ) )
    <div class="row">
        @include('laws.listen.ministry.form.make-ministry')
    </div> 
@else
    <div class="row">
        @include('laws.listen.ministry.form.edit-ministry')
    </div> 
@endif


@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script src="{{asset('plugins/components/switchery/dist/switchery.min.js')}}"></script>
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

  <script>
     $(document).ready(function() {
        //ปฎิทิน
        $('.mydatepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            language:'th-th',
            format: 'dd/mm/yyyy'
        });

    });

    function checkNone(value) {
        return value !== '' && value !== null && value !== undefined && value !== NaN;
    }
  </script>
@endpush