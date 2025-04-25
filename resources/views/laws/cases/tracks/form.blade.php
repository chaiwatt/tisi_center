@push('css')
<link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
<style>
    .div_dotted {
        border-bottom: 1px dotted #000;
        padding: 0 0 5px 0;
        cursor: not-allowed;
    }

    .input_dotted {
        border: none;
        border-bottom: 1px dotted #000;
        cursor: not-allowed;
    }

    legend {
        margin-bottom: 0px;
    }

    table {
        border: 1px solid #000;
    }

    th {
        background-color: #D6DCE5;
        color: #000;
        border: 1px solid #000;
    }

    tfoot {
        background-color: #F2F2F2;
        color: #000;
    }

    text-left {
        text-align: left !important;
    }
    .div-show{
        display: block;
    }
    .div-hide{
        display: none;
    }
    .input_dotted[disabled] {
        background-color: #ffffff;
        opacity: 1;
    }
    .alert-secondary {
        color: #fdfdfd !important;
        background-color: #bebebe;
        border-color: #c1c1c1;
    }
 
</style>
@endpush

<div class="row">
    <div class="col-md-8">
        <fieldset class="white-box">
            <legend class="legend"><h5>ข้อมูลผู้กระทำความผิด</h5></legend>
            <!-- ข้อมูลผู้กระทำความผิด -->
            @include('laws.cases.tracks.tabs.offend')

        </fieldset>
    </div>
    <div class="col-md-4">
        <!-- สถานะ -->
        @include('laws.cases.tracks.tabs.status')
    </div>
</div>

@php
    $law_result     = $cases->law_cases_result_to;
    $license_result = $cases->license_result;
    $product_result = $cases->product_result;
@endphp

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>การดำเนินการผู้ประกอบการ</h5></legend>

            @if(!empty($law_result))
                @include('laws.cases.tracks.tabs.person')
            @else
                <div class="alert alert-bg-secondary text-center font-20">
                    รอดำเนินการ
                </div>
            @endif
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>การดำเนินการกับใบอนุญาต</h5></legend>
            @if( !empty($law_result) && in_array(  $law_result->license, [1]) && !empty($license_result) )
                @include('laws.cases.tracks.tabs.license')
            @elseif( !empty($law_result) && in_array(  $law_result->license, [1]) && empty($license_result) )
                <div class="alert alert-bg-secondary text-center font-20">
                    รอดำเนินการ
                </div>
            @else
                <div class="alert alert-bg-secondary text-center font-20">
                    ไม่ต้องดำเนินการ
                </div>
            @endif
        </fieldset>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <fieldset class="white-box">
            <legend class="legend"><h5>การดำเนินการกับผลิตภัณฑ์</h5></legend>
            @if( !empty($law_result) && in_array(  $law_result->product, [1]) && !empty($product_result) )
                @include('laws.cases.tracks.tabs.product')
            @elseif( !empty($law_result) && in_array(  $law_result->product, [1]) && empty($product_result) )
                <div class="alert alert-bg-secondary text-center font-20">
                    รอดำเนินการ
                </div>
            @else
                <div class="alert alert-bg-secondary text-center font-20">
                    ไม่ต้องดำเนินการ
                </div>
            @endif
        </fieldset>
    </div>
</div>

@push('js')
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-typeahead/bootstrap3-typeahead.min.js') }}"></script>
    <script src="{{asset('js/jasny-bootstrap.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
    <script>
        $(document).ready(function() {

            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%","cursor":"not-allowed"});

            status_result();

        });

        function checkNone(value) {
            return value !== '' && value !== null && value !== undefined;
        }
        function status_result(){
            var row = $("input[name=status_result]:checked").val(); 
                if(row == "1"){ 
                    $('.div_pause').hide(); 
                    $('.div_revoke').hide(); 
                    $('#date_pause_start, #date_pause_end, #date_revoke, #basic_revoke_type_id').prop('required', false);
                } else   if(row == "2"){ 
                    $('.div_pause').show(200); 
                    $('.div_revoke').hide(); 
                    $('#date_pause_start, #date_pause_end').prop('required', true);
                    $('#date_revoke, #basic_revoke_type_id').prop('required', false);
               } else   if(row == "3"){ 
                    $('.div_pause').hide(); 
                    $('.div_revoke').show(200); 
                    $('#date_pause_start, #date_pause_end').prop('required', false);
                    $('#date_revoke, #basic_revoke_type_id').prop('required',true );
                }
            }

    </script>
@endpush
