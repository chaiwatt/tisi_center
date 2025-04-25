{{-- work on Certify\CheckAssessmentController@showCertificateLabDetail --}}
@extends('layouts.master')
@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/summernote/summernote.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .table>tbody>tr>td ,label{
            line-height: 1.7;
            color: #5f5f5f;
        }
        /* เส้นขอบรอบตาราง */
.custom-bordered-table {
    border-collapse: separate;
    border: 0.5px solid #dee2e6; /* เส้นขอบรอบตาราง */
    border-spacing: 0; /* ไม่มีช่องว่างระหว่างเซลล์ */
}

/* เส้นขอบเฉพาะด้านซ้ายและขวาสำหรับ td และ th */
.custom-bordered-table td,
.custom-bordered-table th {
    border-left: 0.5px solid #dee2e6 !important; /* ขอบซ้าย */
    border-right: 0.5px solid #dee2e6 !important; /* ขอบขวา */
    border-top: none !important; /* ไม่มีขอบบน */
    border-bottom: none !important; /* ไม่มีขอบล่าง */
}

/* ยกเลิกเส้นขอบที่อาจซ้อนมาจาก Bootstrap */
.custom-bordered-table th,
.custom-bordered-table td {
    border-top: 0 !important;
    border-bottom: 0 !important;
}

/* เส้นขอบด้านล่างสุดของตาราง */
.custom-bordered-table tr:last-child td {
    border-bottom: none !important; /* ยกเลิกขอบล่างของแถวสุดท้าย */
}

    /* ปิดเอฟเฟกต์ hover */
    .table-no-hover tbody tr:hover {
        background-color: transparent !important;
    }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <div class="clearfix"></div>
                    <br>
                    {{-- {{$labCalScopeTransactionGroups}} --}}
                    @include ('certify.certiLab-show.show-page.form84')
                    @include ('certify.certiLab-show.show-page.form85')
                    <hr>
                    @include ('certify.certiLab-show.show-page.form86')
                    @include ('certify.certiLab-show.show-page.form87')
                    @include ('certify.certiLab-show.show-page.form88')
                    @include ('certify.certiLab-show.show-page.form89')

                    @if ($labCalRequest->count() !== 0)
                        @include ('certify.certiLab-show.show-page.form91')
                    @elseif($labTestRequest->count() !== 0)
                        @include ('certify.certiLab-show.show-page.form90')
                    @elseif($labCalRequest->count() == 0 && $labCalRequest->count() == 0)
                        @include ('certify.certiLab-show.show-page.form90')
                        @include ('certify.certiLab-show.show-page.form91')
                    @endif

                    @include ('certify.certiLab-show.show-page.form92')
                    @include ('certify.certiLab-show.show-page.form93')
                    @include ('certify.certiLab-show.show-page.form94')
                    @include ('certify.certiLab-show.show-page.form95')
                    @include ('certify.certiLab-show.show-page.form96')

                    

                    <div class="clearfix"></div>
                    @if(isset($previousUrl))
                        <a  href="{{ url("$previousUrl") }}"  class="btn btn-default btn-lg btn-block">
                            <i class="fa fa-rotate-left"></i>
                                <b>กลับ</b>
                        </a>
                    @else 
                       <a  href="{{ url("certify/check_certificate") }}"  class="btn btn-default btn-lg btn-block">
                        <i class="fa fa-rotate-left"></i>
                             <b>กลับ</b>
                     </a>
                    @endif
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')

    <script>
        let labCalRequest
        let labTestRequest
        let labRequestMain 
        let labRequestBranchs 
        let labRequestType = "test"

        $(document).ready(function () {

            // ตัวแปร labCalRequest และ labTestRequest ที่ได้รับค่าจาก PHP
            let labCalRequest = @json($labCalRequest ?? []);
            let labTestRequest = @json($labTestRequest ?? []);

            // ตรวจสอบความยาวของ labTestRequest
            console.log('Lab Test Request Length:', labTestRequest.length);

            // หาก labTestRequest ว่าง หรือไม่มีค่า ใช้ labCalRequest แทน
            if (labTestRequest.length > 0) {
                labRequestType = "test"
                console.log('LabTestRequest มีข้อมูล:', labTestRequest);
                labRequestMain = labTestRequest.filter(request => request.type === "1")[0];
                labRequestBranchs = labTestRequest.filter(request => request.type === "2");
            } else if (labCalRequest.length > 0) {
                labRequestType = "cal"
                console.log('LabCalRequest มีข้อมูล:', labCalRequest);
                labRequestMain = labCalRequest.filter(request => request.type === "1")[0];
                labRequestBranchs = labCalRequest.filter(request => request.type === "2");
            } else {
                labRequestType = "old_version"
            }

        });
    </script>

    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>

    <script src="{{ asset('plugins/components/summernote/summernote.js') }}"></script>
    <script src="{{ asset('plugins/components/summernote/summernote-ext-specialchars.js') }}"></script>

    {{-- <script>
        var labCalScopeTransactions;
        var branchLabAdresses;
        var certificateHistorys;
        var labCalScopeTransactionGroups;
        $(document).ready(function () {
            labCalScopeTransactionGroups = @json($labCalScopeTransactionGroups ?? null);
            labCalScopeTransactions = @json($labCalScopeTransactions ?? []);
            branchLabAdresses = @json($branchLabAdresses ?? []);
            // console.log(labCalScopeTransactions);
            
            $('.check-readonly').prop('disabled', true);
            $('.check-readonly').parent().removeClass('disabled');
            $('.check-readonly').parent().css({"background-color": "rgb(238, 238, 238);","border-radius":"50%"});
         
         });

    </script> --}}
    {{-- <script src="{{asset('assets/js/lab/cal/scope.js?v=1.0')}}"></script> --}}
    <script src="{{asset('assets/js/lab/labscope_manager.js?v=1.0')}}"></script>

    <script>
        $(document).ready(function () {
            $('.update_scope').remove();
        });
    </script>

    
@endpush
