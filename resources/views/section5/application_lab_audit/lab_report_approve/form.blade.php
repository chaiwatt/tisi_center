@push('css')
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">

    <style>
        .custom_label1 {
            color: #000000 !important;
            font-weight: 500;
        }
        .custom_text1 {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            color: #000000 !important;
            font-weight: 500;
            padding-top: 7px;
            margin-bottom: 0;
            text-align: right;
        }
        .custom_label2 {
            color: #000000 !important;
            font-weight: 300;
        }
        .custom_text2 {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            color: #000000 !important;
            font-weight: 300;
            padding-top: 7px;
            margin-bottom: 0;
            text-align: right;
        }
        .custom_text_link {
            display: inline-block;
            max-width: 100%;
            margin-bottom: 5px;
            font-weight: 300;
            padding-top: 7px;
            margin-bottom: 0;
            text-align: right;
        }
        .div_dotted {
            border-top: none ;
            border-right: none ;
            border-bottom: 1px dotted;
            border-left: none ;
        }
    </style>
@endpush

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

<div class="clearfix"></div>

@include ('section5.application_lab_audit.panels.approve')

<div class="row">
    <center>
        <button class="btn btn-primary" type="submit">
            <i class="fa fa-paper-plane"></i> บันทึก
        </button>
        @can('view-'.str_slug('application-lab-audit'))
            <a class="btn btn-default" href="{{url('/section5/application_lab_audit')}}">
                <i class="fa fa-rotate-left"></i> ยกเลิก
            </a>
        @endcan
    </center>
</div>

@push('js')

    <!-- dataTables -->
    <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>

    <!-- icheck -->
    <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
    <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
    <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
    <script>
        jQuery(document).ready(function() { 
            
            //Disable ผลตรวจประเมิน
            $('#box-result').find('input, select, textarea').prop('disabled', true);
            $('#box-result').find('button').remove();
            $('#box-result').find('.show_tag_a').remove();

            //Disable บันทึกสรุปรายงาน
            $('#box-report').find('input, select, textarea').prop('disabled', true);
            $('#box-report').find('button').remove();
            $('#box-report').find('.show_tag_a').remove();
        });  
    </script>
@endpush
