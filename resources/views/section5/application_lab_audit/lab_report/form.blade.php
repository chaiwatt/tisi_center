@push('css')
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .inputgroup-custom {
            vertical-align: top; 
            padding: 6px 6px;
        }
        .inputgroup-btn {
            padding: 6px 6px; 
            vertical-align: top; 
            width: 0%;
        }
        .col-custom-1 {
            width: 40%;
            padding-left: 13px;
            padding-right: 5px;
        }
        .col-custom-2 {
            width: 50%;
            padding-left: 5px;
            padding-right: 5px;
        }
        .col-custom-3 {
            width: 10%;
            padding-left: 5px;
            padding-right: 5px;
        }
        .col-custom-4 {
            vertical-align: top; 
            padding-left: 5px;
            padding-right: 5px;
        }
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }
    </style>
@endpush

@php
    $application_report =  $applicationlabaudit->app_report;

    $file_attach_report = null;
    $file_attach_others = [];
    if( !is_null( $application_report ) ){
        $file_attach_report = App\AttachFile::where('section', 'file_attach_report')->where('ref_table', (new App\Models\Section5\ApplicationLabsReport)->getTable())->where('ref_id', $application_report->id )->first();
        $file_attach_others = App\AttachFile::where('section', 'file_attach_other')->where('ref_table', (new App\Models\Section5\ApplicationLabsReport)->getTable())->where('ref_id', $application_report->id )->get();
    }

@endphp

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">เลขที่คำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty( $applicationlabaudit->application_no )?$applicationlabaudit->application_no:null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่ยื่นคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlabaudit->application_date)?HP::DateThaiFull($applicationlabaudit->application_date):null !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">วันที่รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlabaudit->accept_date) ? HP::DateThaiFull($applicationlabaudit->accept_date) : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-2 col-md-offset-8 text-right">ผู้รับคำขอ :</div>
        <div class="col-md-2 div_dotted">
            <p>{!! !empty($applicationlabaudit->accept_by) && !is_null($applicationlabaudit->accepter) ? $applicationlabaudit->accepter->FullName : '-' !!}</p>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<br>

@include ('section5.application_lab_audit.panels.application')

<div class="clearfix"></div>

@include ('section5.application_lab_audit.panels.result')

<div class="clearfix"></div>

@include ('section5.application_lab_audit.panels.report')

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn btn-primary" type="submit">
                <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                @can('view-'.str_slug('application-lab-audit'))
                    <a class="btn btn-default" href="{{url('/section5/application_lab_audit')}}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('js')
    <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
    <!-- input calendar thai -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
    <!-- thai extension -->
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
    <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
    <!-- input file -->
    <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
        <!-- dataTables -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <!-- icheck -->
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script>
        jQuery(document).ready(function() { 
            
        $('.repeater-form-file').repeater({
                show: function () {
                    $(this).slideDown();
                },
                hide: function (deleteElement) {
                    if (confirm('คุณต้องการลบแถวนี้ ?')) {
                        $(this).slideUp(deleteElement);
                    }
                }
            });

            //Disable ผลตรวจประเมิน
            $('#box-result').find('input, select, textarea').prop('disabled', true);
            $('#box-result').find('button').remove();
            $('#box-result').find('.show_tag_a').remove();
        });

        
    </script>
@endpush
