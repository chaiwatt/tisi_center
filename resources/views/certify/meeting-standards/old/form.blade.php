@push('css')
    <link href="{{asset('plugins/components/icheck/skins/all.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('plugins/components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('plugins/components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" />
    <link href="{{asset('plugins/components/bootstrap-datepicker-thai/css/datepicker.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/components/jasny-bootstrap/css/jasny-bootstrap.css') }}" rel="stylesheet">

<style type="text/css">
    .bootstrap-tagsinput {
        width: 100% !important;
    }
    .font-16{
        font-size:16px;
    }
    .font-14{
        font-size:10px;
    }

</style>
@endpush

<div class="col-md-12">
    <div class="white-box">
        
        <ul class="nav nav-tabs tabs ">
            <li class="active tab">
                <a href="#box-meet" id="tab-meet" data-toggle="tab"> <i class="fa fa-check-square-o" aria-hidden="true"> </i><span class="hidden-xs"> วาระการประชุม</span> </a>
            </li>
            <li class="tab">
                <a href="#box-meet-report" id="tab-meet-report" data-toggle="tab" aria-expanded="true"> <i class="fa fa-users"></i> <span class="hidden-xs">ผลการประชุม</span> </a>
            </li>
        </ul>


        <div class="tab-content">
            <div class="tab-pane active" id="box-meet">
                @include ('certify.meeting-standards.form-meet')          
            </div>

            <div class="tab-pane" id="box-meet-report">
                @include ('certify.meeting-standards.form-meet-report')
            </div>         
        </div>


    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">

    </div>
</div>

@push('js')
  <script src="{{ asset('plugins/components/icheck/icheck.min.js') }}"></script>
  <script src="{{ asset('plugins/components/icheck/icheck.init.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/bootstrap-datepicker-thai.js') }}"></script>
  <script src="{{ asset('plugins/components/bootstrap-datepicker-thai/js/locales/bootstrap-datepicker.th.js') }}"></script>
  <script src="{{ asset('js/function.js') }}"></script>
 
  <script>
    
    $(document).ready(function () {

        $('.date-range').datepicker({
            toggleActive: true,
            language:'th-th',
            format: 'dd/mm/yyyy',
        });

        $('#button-form-report').click(function(event) {
            RequestFormMeet();
        });

        $('#button-form-meet').click(function(event) {
            RequestFormMeetReport();
        });
   
    });



    function RequestFormMeetReport() {
        //จัดการข้อมูลในฟอร์ม
        $('#box-meet-report').find('input, select, textarea').attr('disabled', true);
        $('#box-meet-report').find('input, select, textarea').attr('required', false);
  
    }

    function RequestFormMeet() {
        //จัดการข้อมูลในฟอร์ม
        $('#box-meet').find('input, select, textarea').attr('disabled', true);           
        $('#box-meet').find('input, select, textarea').attr('required', false);      
    }

    function check_max_size_file() {
        var max_size = "{{ ini_get('upload_max_filesize') }}";
        var res = max_size.replace("M", "");
        $('.check_max_size_file').bind('change', function() {
            if( $(this).val() != ''){
            var size =   (this.files[0].size)/1024/1024 ; // หน่วย MB
              console.log(this.files[0]);
              if(size > res ){
                Swal.fire(
                        'ขนาดไฟล์เกินกว่า ' + res +' MB',
                        '',
                        'info'
                        )
                $(this).parent().parent().find('.fileinput-exists').click();
                $(this).val('');
                $(this).parent().parent().find('.custom-file-label').html('');
                  return false;
              }
            }
        });
    }

</script>
@endpush
