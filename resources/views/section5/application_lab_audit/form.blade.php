@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{asset('plugins/components/jquery-datatables-editable/datatables.css')}}" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet">
    <style>
        .mb-1 {
            margin-bottom: 1rem;
        }
        .mb-2 {
            margin-bottom: 2rem;
        }
        .mb-3 {
            margin-bottom: 3rem;
        }
        .bootstrap-tagsinput > .label {
            line-height: 2.3;
        }
        .bootstrap-tagsinput {
            min-height: 42px;
            border-radius: 0;
            width: 100% !important;
        }
        .bootstrap-tagsinput input {
            padding: 6px 6px;
        }

        .form-body input[type="text"]:disabled {
            border-right:  medium none;
            border-top: medium none;
            border-left: medium none;
            border-bottom: 1px dotted;
            background-color: #FFFFFF;
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


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <div class="col-md-offset-4 col-md-4">
                <button class="btn btn-primary" type="submit" name="submit_type" value="1">
                    <i class="fa fa-paper-plane"></i> บันทึก
                </button>
                <button class="btn btn-success" type="submit" name="submit_type" value="2">
                    <i class="fa fa-clipboard" aria-hidden="true"></i> ฉบับร่าง
                </button>
                @can('view-'.str_slug('application-lab-audit'))
                    <a class="btn btn-default show_tag_a" href="{{url('/section5/application_lab_audit')}}">
                        <i class="fa fa-rotate-left"></i> ยกเลิก
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{asset('plugins/components/repeater/jquery.repeater.min.js')}}"></script>
  <!-- input calendar thai -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <!-- thai extension -->
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{asset('plugins/components/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{asset('plugins/components/jquery-datatables-editable/jquery.dataTables.js')}}"></script>
  <script src="{{asset('plugins/components/datatables/dataTables.bootstrap.js')}}"></script>
  <script src="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')}}"></script>
  <!-- input file -->
  <script src="{{ asset('js/jasny-bootstrap.js') }}"></script>
  <script>
    jQuery(document).ready(function() {

        $('.repeater-form-date').repeater({
            show: function () {
                $(this).slideDown();
            },
            hide: function (deleteElement) {
                if (confirm('คุณต้องการลบแถวนี้ ?')) {
                    $(this).slideUp(deleteElement);
                }
            }
        });

        $('.audit_result').change(function(){
            box_scope();
        });box_scope();

        $('.send_mail_status').on('ifChanged', function(event){
            send_mail_status();
        });
        send_mail_status();

        $(document).on('click', '.scope_checkbox', function () {
            var id   = $(this).val();
            var type = $(this).data('type');
            var tr   = $(this).closest('tr');

            if($(this).is(':checked',true)){
                tr.find('textarea.test_item_remark').val( type == 1?'เป็นไปตาม 17025':'เป็นไปตามภาคผนวก ก'  );
            }else{
                tr.find('textarea.test_item_remark').val('');
            }
        });

    });

    function box_scope(){
        if($('#audit_result').val() == 1){
            var box_scope = $('.box_scope');
            box_scope.show();
            box_scope.find('input').prop('disabled', false);
        }else{
            var box_scope = $('.box_scope');
            box_scope.hide();
            box_scope.find('input').prop('disabled', true);
        }
    }

    function send_mail_status(){
        let send_mail_status = $('.send_mail_status:checked').val();
        let box_noti_email = $('.box_noti_email');
        if(send_mail_status == 1){
            box_noti_email.find('.noti_email').prop('disabled', false);
            box_noti_email.find('.noti_email').prop('required', true);
            box_noti_email.show();
        }else{
            box_noti_email.find('.noti_email').prop('disabled', true);
            box_noti_email.find('.noti_email').prop('required', false);
            box_noti_email.hide();
        }
    }


  </script>
@endpush
